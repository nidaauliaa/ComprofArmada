<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesContact;
use Illuminate\Http\Request;

class SalesContactController extends Controller
{
    public function index()
    {
        $contacts = SalesContact::ordered()->get();

        return view('admin.sales.index', compact('contacts'));
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

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:100'],
            'phone'      => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-()]{8,30}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}