<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use App\Models\Setting;
use Illuminate\Http\Request;

class SalesContactController extends Controller
{
    /**
     * Teks halaman Kontak yang terjemahannya diisi manual dari admin (tab ID | EN | 中文).
     * key setting => [key terjemahan bawaan di lang/{id,en,zh}/site.php, batas karakter]
     * Disimpan sebagai JSON {"id":..,"en":..,"zh":..} lewat Setting::put(), dibaca lewat Setting::tr().
     */
    public const TEXTS = [
        'contact_title'         => ['lang' => 'contact_response_title', 'max' => 120],
        'contact_lead'          => ['lang' => 'contact_intro_lead',     'max' => 200],
        'contact_intro'         => ['lang' => 'contact_intro_rest',     'max' => 400],
        'contact_sales_heading' => ['lang' => 'sales_wa',               'max' => 120],
        'contact_wa_greeting'   => ['lang' => 'wa_greeting',            'max' => 120],
    ];

    public function index()
    {
        $hq = Plant::headquarters();
        $texts = $this->textValues();

        return view('admin.sales.index', compact('hq', 'texts'));
    }

    /**
     * Simpan nomor (+ label), email, dan jam kerja Kantor Pusat.
     * Ini baris Plant HQ yang sama dengan menu Lokasi Plant, jadi kedua sisi selalu konsisten
     * dan nomor yang sama tampil di peta, halaman /kontak, dan popup lokasi.
     */
    public function updateHq(Request $request)
    {
        $hq = Plant::headquarters();

        if (! $hq) {
            return back()->withErrors(['hq' => 'Belum ada Plant yang ditandai HQ. Centang "Kantor pusat (HQ)" di menu Lokasi Plant dulu.']);
        }

        $rawPhones = (array) $request->input('phones', []);
        $rawNames  = (array) $request->input('phone_names', []);
        $phones = $names = [];
        foreach ($rawPhones as $i => $v) {
            $v = trim((string) $v);
            if ($v === '') { continue; }
            $phones[] = $v;
            $names[]  = trim((string) ($rawNames[$i] ?? ''));
        }
        $request->merge(['phones' => $phones, 'phone_names' => $names]);

        $data = $request->validate([
            'phones'           => ['nullable', 'array', 'max:10'],
            'phones.*'         => ['string', 'regex:/^[0-9+\s\-()]{6,50}$/'],
            'phone_names'      => ['nullable', 'array', 'max:10'],
            'phone_names.*'    => ['nullable', 'string', 'max:100'],
            'email'            => ['nullable', 'email', 'max:150'],
            'working_hours'    => ['nullable', 'string', 'max:255'],
            'working_hours_en' => ['nullable', 'string', 'max:255'],
            'working_hours_zh' => ['nullable', 'string', 'max:255'],
        ], [
            'phones.*.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ]);

        $data['phones']      = array_values($data['phones'] ?? []);
        $data['phone_names'] = array_values($data['phone_names'] ?? []);
        $data['phone']       = $data['phones'][0] ?? null; // kolom lama, sama seperti PlantController

        $hq->update($data);

        return back()->with('status', 'Kontak disimpan. Perubahan ini juga tampil di menu Lokasi Plant.');
    }

    /** Simpan teks halaman Kontak (ID, EN, 中文). */
    public function updateTexts(Request $request)
    {
        $rules = [];
        foreach (self::TEXTS as $key => $meta) {
            $rules[$key] = ['nullable', 'array'];
            $rules[$key.'.*'] = ['nullable', 'string', 'max:'.$meta['max']];
        }
        $request->validate($rules);

        $codes = array_keys(config('site.locales'));

        foreach (self::TEXTS as $key => $meta) {
            $values = [];
            foreach ($codes as $code) {
                $values[$code] = trim((string) data_get($request->input($key, []), $code, ''));
            }
            // Semua kosong -> simpan kosong, website kembali memakai teks bawaan di lang/*/site.php
            Setting::put($key, implode('', $values) === '' ? '' : json_encode($values, JSON_UNESCAPED_UNICODE));
        }

        return back()->with('status', 'Teks halaman Kontak disimpan.');
    }

    /** Nilai awal form: isian tersimpan, atau terjemahan bawaan per bahasa bila belum pernah disimpan. */
    protected function textValues(): array
    {
        $codes = array_keys(config('site.locales'));
        $out = [];

        foreach (self::TEXTS as $key => $meta) {
            $saved = json_decode((string) Setting::get($key, ''), true);

            foreach ($codes as $code) {
                $out[$key][$code] = is_array($saved)
                    ? (string) ($saved[$code] ?? '')
                    : trans('site.'.$meta['lang'], [], $code);
            }
        }

        return $out;
    }
}
