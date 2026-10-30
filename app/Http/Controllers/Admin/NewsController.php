<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /** Daftar berita + data untuk form ubah di sisi kanan. */
    public function index()
    {
        $news = News::orderByDesc('published_at')->orderByDesc('id')->paginate(15);

        $editData = $news->getCollection()
            ->mapWithKeys(fn (News $n) => [$n->id => $n->toEditArray()])
            ->all();

        return view('admin.news.index', compact('news', 'editData'));
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        $data = $this->payload($request);
        $data['slug'] = $this->uniqueSlug($data['title']['id']);
        $data['created_by'] = $request->user()?->id;

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita tersimpan. Website sudah diperbarui.');
    }

    public function update(NewsRequest $request, News $news): RedirectResponse
    {
        $news->update($this->payload($request, $news)); // slug tidak diubah agar link lama tetap jalan

        return redirect()->route('admin.news.index')
            ->with('success', 'Perubahan berita tersimpan. Website sudah diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->deleteFile($news->thumbnail_path);
        foreach ($news->blocks ?? [] as $b) {
            if (($b['type'] ?? '') === 'i') {
                $this->deleteFile($b['image'] ?? null);
            }
        }
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita dihapus.');
    }

    /** Saklar Tayang / Sembunyikan di daftar (dipanggil lewat fetch, tanpa reload). */
    public function toggle(News $news): JsonResponse
    {
        $news->update(['is_active' => ! $news->is_active]);

        return response()->json(['is_active' => $news->is_active]);
    }

    /** Ganti thumbnail langsung dari kotak foto di baris daftar. */
    public function thumbnail(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ], [], ['thumbnail' => 'Thumbnail']);

        $this->deleteFile($news->thumbnail_path);
        $news->update(['thumbnail_path' => $this->storeThumbnail($request->file('thumbnail'))]);

                return back()->with('success', 'Thumbnail diperbarui. Website sudah diperbarui.');
    }

    // ------------------------------------------------------------------ helper

    /** Susun data siap simpan dari form (judul, thumbnail, dan isi artikel). */
    private function payload(NewsRequest $request, ?News $news = null): array
    {
        $data = [
            'title'        => $this->langs($request->input('title', [])),
            'category'     => $request->input('category'),
            'published_at' => $request->input('published_at'),
            'is_active'    => $request->boolean('is_active'),
        ];

        // thumbnail
        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($news?->thumbnail_path);
            $data['thumbnail_path'] = $this->storeThumbnail($request->file('thumbnail'));
        } elseif ($news && $request->boolean('remove_thumbnail')) {
            $this->deleteFile($news->thumbnail_path);
            $data['thumbnail_path'] = null;
        }

        // isi artikel (urutan blok mengikuti urutan di form)
        $oldImages = collect($news?->blocks ?? [])->where('type', 'i')->pluck('image')->filter()->all();
        $blocks = [];

        foreach ($request->input('blocks', []) as $i => $b) {
            if (($b['type'] ?? 'p') === 'i') {
                $path = $b['existing_image'] ?? null;
                if ($path && ! in_array($path, $oldImages, true)) {
                    $path = null; // hanya terima gambar yang memang milik berita ini
                }
                if ($request->hasFile("blocks.$i.image")) {
                    $path = $request->file("blocks.$i.image")->store('news/blocks', 'public');
                }
                if (! $path) {
                    continue; // blok gambar tanpa gambar dilewati
                }
                $blocks[] = ['type' => 'i', 'image' => $path, 'caption' => $this->langs($b['caption'] ?? [])];
            } else {
                $text = $this->langs($b['text'] ?? []);
                if (! array_filter($text)) {
                    continue; // paragraf kosong dilewati
                }
                $blocks[] = ['type' => 'p', 'text' => $text];
            }
        }

        // hapus file gambar artikel yang sudah tidak dipakai
        $used = collect($blocks)->where('type', 'i')->pluck('image')->all();
        foreach (array_diff($oldImages, $used) as $unused) {
            $this->deleteFile($unused);
        }

        $data['blocks'] = $blocks;

        return $data;
    }

    private function langs(array $v): array
    {
        return [
            'id' => trim((string) ($v['id'] ?? '')),
            'en' => trim((string) ($v['en'] ?? '')),
            'zh' => trim((string) ($v['zh'] ?? '')),
        ];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $n = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    /** File bawaan di public/images tidak ikut dihapus. */
    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }

 /** Crop tengah + resize ke 880x400 px (rasio 11:5, sama dengan kartu di website). */
    private function storeThumbnail(UploadedFile $file): string
    {
        try {
            @ini_set('memory_limit', '512M');

            $w = 880;
            $h = 400;
            $real = $file->getRealPath();

            $src = match ($file->getMimeType()) {
                'image/jpeg' => imagecreatefromjpeg($real),
                'image/png'  => imagecreatefrompng($real),
                'image/webp' => imagecreatefromwebp($real),
                default      => false,
            };
            if (! $src) {
                throw new \RuntimeException('Gagal membaca gambar.');
            }

            if ($file->getMimeType() === 'image/jpeg' && function_exists('exif_read_data')) {
                $orient = @exif_read_data($real)['Orientation'] ?? 1;
                $src = match ($orient) {
                    3 => imagerotate($src, 180, 0),
                    6 => imagerotate($src, -90, 0),
                    8 => imagerotate($src, 90, 0),
                    default => $src,
                };
            }

            $sw = imagesx($src);
            $sh = imagesy($src);
            $scale = max($w / $sw, $h / $sh);
            $cw = (int) round($w / $scale);
            $ch = (int) round($h / $scale);
            $sx = (int) max(0, ($sw - $cw) / 2);
            $sy = (int) max(0, ($sh - $ch) / 2);

            $dst = imagecreatetruecolor($w, $h);
            imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            imagecopyresampled($dst, $src, 0, 0, $sx, $sy, $w, $h, $cw, $ch);

            ob_start();
            imagejpeg($dst, null, 85);
            $binary = ob_get_clean();

            $path = 'news/' . Str::random(40) . '.jpg';

            if (! $binary || ! Storage::disk('public')->put($path, $binary)) {
                throw new \RuntimeException('Gagal menulis thumbnail ke storage.');
            }

            return $path;

            return $path;
        } catch (\Throwable $e) {
            report($e);

            // cadangan: simpan file asli, tampilan tetap dirapikan oleh CSS (object-fit: cover)
            return $file->store('news', 'public');
        }
    }
}
