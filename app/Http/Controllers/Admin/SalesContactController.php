<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesContact;
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
        $contacts = SalesContact::ordered()->get();

        $texts = $this->textValues();

        return view('admin.sales.index', compact('contacts', 'texts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] = $data['sort_order'] ?? ((int) SalesContact::max('sort_order') + 1);

        SalesContact::create($data);

        return back()->with('status', 'Kontak sales ditambahkan.');
    }

    public function update(Request $request, SalesContact $salesContact)
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] = $data['sort_order'] ?? $salesContact->sort_order;

        $salesContact->update($data);

        return back()->with('status', 'Kontak sales diperbarui.');
    }

    public function toggle(SalesContact $salesContact)
    {
        $salesContact->update(['is_active' => ! $salesContact->is_active]);

        return back()->with('status', 'Status kontak diubah.');
    }

    public function destroy(SalesContact $salesContact)
    {
        $salesContact->delete();

        return back()->with('status', 'Kontak sales dihapus.');
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

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-()]{8,30}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}