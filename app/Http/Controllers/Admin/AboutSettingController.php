<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Pengaturan halaman "Tentang Kami" (semua teks 3 bahasa + foto direktur + statistik + filosofi + sejarah). */
class AboutSettingController extends Controller
{
    private const LOCALES = ['id', 'en', 'zh'];

    public function edit()
    {
        return view('admin.about.edit', ['about' => AboutPage::current()]);
    }

    public function update(Request $request)
    {
        $rules = array_merge(
            $this->mlRules('hero_badge', 150),
            $this->mlRules('hero_title', 200),
            $this->mlRules('hero_accent', 200),
            $this->mlRules('hero_lead', 600),

            ['stats' => 'nullable|array|max:8', 'stats.*.since' => 'nullable|integer|between:1800,2100'],
            $this->mlRules('stats.*.value', 40),
            $this->mlRules('stats.*.label', 80),

            [
                'director_name'        => 'nullable|string|max:150',
                'director_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'remove_director_photo' => 'nullable|boolean',
            ],
            $this->mlRules('director_title', 120),
            $this->mlRules('overview_title', 200),
            $this->mlRules('overview_body', 6000),

            $this->mlRules('vision_heading', 120),
            $this->mlRules('vision', 1000),
            $this->mlRules('mission_heading', 120),
            $this->mlRules('mission', 2000),

            $this->mlRules('philosophy_title', 150),
            $this->mlRules('philosophy_sub', 300),
            ['philosophy' => 'nullable|array|max:8'],
            $this->mlRules('philosophy.*', 300),

            $this->mlRules('history_title', 150),
            $this->mlRules('history_sub', 300),
            [
                'history'        => 'nullable|array|max:30',
                'history.*.from' => 'nullable|integer|between:1800,2100',
                'history.*.to'   => 'nullable|integer|between:1800,2100|gte:history.*.from',
            ],
            $this->mlRules('history.*.text', 1500),
        );

        $request->validate($rules, [
            'director_photo.image'    => 'Foto direktur harus berupa gambar (JPG, PNG, atau WEBP).',
            'director_photo.max'      => 'Ukuran foto direktur maksimal 4 MB.',
            'director_photo.uploaded' => 'Foto gagal diunggah. Cek batas upload PHP (upload_max_filesize / post_max_size).',
            'history.*.to.gte'        => 'Tahun akhir pada Sejarah tidak boleh lebih kecil dari tahun awal.',
        ]);

        // Statistik: baris kosong dibuang
        $stats = [];
        foreach ((array) $request->input('stats', []) as $row) {
            $item = [
                'value' => $this->ml($row['value'] ?? []),
                'since' => ($row['since'] ?? '') !== '' && $row['since'] !== null ? (int) $row['since'] : null,
                'label' => $this->ml($row['label'] ?? []),
            ];
            if ($item['since'] === null && $this->isEmpty($item['value']) && $this->isEmpty($item['label'])) {
                continue;
            }
            $stats[] = $item;
        }

        // Filosofi
        $philosophy = [];
        foreach ((array) $request->input('philosophy', []) as $row) {
            $item = $this->ml($row);
            if (! $this->isEmpty($item)) {
                $philosophy[] = $item;
            }
        }

        // Sejarah: tahun awal wajib untuk tampil; tahun akhir opsional (kosong = satu tahun saja)
        $history = [];
        foreach ((array) $request->input('history', []) as $row) {
            $from = ($row['from'] ?? '') !== '' && $row['from'] !== null ? (int) $row['from'] : null;
            $to = ($row['to'] ?? '') !== '' && $row['to'] !== null ? (int) $row['to'] : null;
            $text = $this->ml($row['text'] ?? []);
            if ($from === null && $this->isEmpty($text)) {
                continue;
            }
            $history[] = ['from' => $from, 'to' => ($to !== null && $to !== $from) ? $to : null, 'text' => $text];
        }

        $content = [
            'hero_badge'  => $this->ml($request->input('hero_badge')),
            'hero_title'  => $this->ml($request->input('hero_title')),
            'hero_accent' => $this->ml($request->input('hero_accent')),
            'hero_lead'   => $this->ml($request->input('hero_lead')),
            'stats'       => $stats,

            'director_name'  => trim((string) $request->input('director_name', '')),
            'director_title' => $this->ml($request->input('director_title')),
            'overview_title' => $this->ml($request->input('overview_title')),
            'overview_body'  => $this->ml($request->input('overview_body')),

            'vision_heading'  => $this->ml($request->input('vision_heading')),
            'vision'          => $this->ml($request->input('vision')),
            'mission_heading' => $this->ml($request->input('mission_heading')),
            'mission'         => $this->ml($request->input('mission')),

            'philosophy_title' => $this->ml($request->input('philosophy_title')),
            'philosophy_sub'   => $this->ml($request->input('philosophy_sub')),
            'philosophy'       => $philosophy,

            'history_title' => $this->ml($request->input('history_title')),
            'history_sub'   => $this->ml($request->input('history_sub')),
            'history'       => $history,
        ];

        $about = AboutPage::query()->first() ?? new AboutPage();

        if ($request->hasFile('director_photo')) {
            if ($about->director_photo) {
                Storage::disk('public')->delete($about->director_photo);
            }
            $about->director_photo = $request->file('director_photo')->store('about', 'public');
        } elseif ($request->boolean('remove_director_photo') && $about->director_photo) {
            Storage::disk('public')->delete($about->director_photo);
            $about->director_photo = null;
        }

        $about->content = $content;
        $about->save();

        return redirect()->route('admin.about.edit')->with('success', 'Halaman Tentang Kami disimpan.');
    }

    /** Aturan validasi untuk satu kolom 3 bahasa: key.id, key.en, key.zh */
    private function mlRules(string $key, int $max): array
    {
        $rules = [];
        foreach (self::LOCALES as $l) {
            $rules[$key.'.'.$l] = 'nullable|string|max:'.$max;
        }

        return $rules;
    }

    /** Rapikan nilai 3 bahasa jadi ['id'=>..,'en'=>..,'zh'=>..] */
    private function ml($v): array
    {
        $out = [];
        foreach (self::LOCALES as $l) {
            $out[$l] = trim((string) (is_array($v) ? ($v[$l] ?? '') : ''));
        }

        return $out;
    }

    private function isEmpty(array $ml): bool
    {
        return implode('', $ml) === '';
    }
}
