<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlantRequest;
use App\Models\Plant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PlantController extends Controller
{
    /** Daftar lokasi + data untuk form ubah di sisi kanan. */
    public function index()
    {
        $plants = Plant::orderBy('sort_order')->orderBy('id')->get();

        $editData = $plants
            ->mapWithKeys(fn (Plant $p) => [$p->id => $p->toEditArray()])
            ->all();

        $nextOrder = (int) Plant::max('sort_order') + 1;

        return view('admin.plants.index', compact('plants', 'editData', 'nextOrder'));
    }

    public function store(PlantRequest $request): RedirectResponse
    {
        Plant::create($this->payload($request));

        return redirect()->route('admin.plants.index')
            ->with('success', 'Lokasi tersimpan. Website sudah diperbarui.');
    }

    public function update(PlantRequest $request, Plant $plant): RedirectResponse
    {
        $plant->update($this->payload($request, $plant));

        return redirect()->route('admin.plants.index')
            ->with('success', 'Perubahan lokasi tersimpan. Website sudah diperbarui.');
    }

    public function destroy(Plant $plant): RedirectResponse
    {
        $plant->delete();

        return redirect()->route('admin.plants.index')->with('success', 'Lokasi dihapus.');
    }

    /** Saklar Tayang / Sembunyikan di daftar (dipanggil lewat fetch, tanpa reload). */
    public function toggle(Plant $plant): JsonResponse
    {
        $plant->update(['is_active' => ! $plant->is_active]);

        return response()->json(['is_active' => $plant->is_active]);
    }

    private function payload(PlantRequest $request, ?Plant $plant = null): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? ($plant?->sort_order ?? ((int) Plant::max('sort_order') + 1));

        return $data;
    }
}
