<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /** Kolom teks biasa: key => aturan validasi */
    private const PLAIN = [
        'site_name' => 'nullable|string|max:120',
        'address' => 'nullable|string|max:500',
        'phone' => 'nullable|string|max:120',
        'email_marketing' => 'nullable|email|max:150',
        'working_hours' => 'nullable|string|max:150',
        'youtube_video_id' => ['nullable', 'string', 'max:500'],
        'ig_posts' => 'nullable|string|max:20',
        'ig_followers' => 'nullable|string|max:20',
        'ig_following' => 'nullable|string|max:20',
        'social_instagram' => 'nullable|url|max:300',
        'social_facebook' => 'nullable|url|max:300',
        'social_youtube' => 'nullable|url|max:300',
        'social_tiktok' => 'nullable|url|max:300',
        'social_linkedin' => 'nullable|url|max:300',
        'meta_keywords' => 'nullable|string|max:300',
    ];

    public function index()
    {
        $values = Setting::allCached();
        $meta = json_decode($values['meta_description'] ?? '', true);

        return view('admin.settings.index', [
            'v' => $values,
            'metaDescription' => is_array($meta) ? $meta : ['id' => (string) ($values['meta_description'] ?? ''), 'en' => '', 'zh' => ''],
        ]);
    }

    private function extractYoutubeId(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $path = trim(parse_url($url, PHP_URL_PATH) ?? '/', '/');

        if (!in_array($host, [
            'youtu.be',
            'www.youtu.be',
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'music.youtube.com',
        ], true)) {
            return null;
        }

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $id = explode('/', $path)[0] ?? '';
        } else {
            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);

            if (str_starts_with($path, 'watch')) {
                $id = $query['v'] ?? '';
            } elseif (preg_match('#^(embed|shorts|live)/([A-Za-z0-9_-]{11})$#', $path, $matches)) {
                $id = $matches[2];
            } else {
                $id = '';
            }
        }

        return preg_match('/^[A-Za-z0-9_-]{11}$/', $id) ? $id : null;
    }

    public function update(Request $request)
    {
        $rules = self::PLAIN + [
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'meta_description' => 'nullable|array',
            'meta_description.*' => 'nullable|string|max:300',
        ];
        $request->validate($rules);

        $youtubeUrl = trim((string) $request->input('youtube_video_id'));

        if ($youtubeUrl !== '') {
            $youtubeId = $this->extractYoutubeId($youtubeUrl);

            if (!$youtubeId) {
                return back()
                    ->withErrors([
                        'youtube_video_id' => 'Link YouTube tidak valid. Periksa kembali link video.',
                    ])
                    ->withInput();
            }

            $request->merge([
                'youtube_video_id' => $youtubeId,
            ]);
        }

        foreach (array_keys(self::PLAIN) as $key) {
            Setting::put($key, trim((string) $request->input($key, '')));
        }

        $meta = [];
        foreach (array_keys(config('site.locales')) as $code) {
            $meta[$code] = trim((string) data_get($request->input('meta_description', []), $code, ''));
        }
        Setting::put('meta_description', json_encode($meta, JSON_UNESCAPED_UNICODE));

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('logo', $request->file('logo')->store('branding', 'public'));
        } elseif ($request->boolean('remove_logo')) {
            $old = Setting::get('logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('logo', null);
        }

        return back()->with('success', 'Pengaturan tersimpan.');
    }
}
