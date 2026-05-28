<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\MaterialStoreRequest;
use App\Http\Requests\MaterialUpdateRequest;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($request->filled('price_from')) {
            $query->where('price_per_unit', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $query->where('price_per_unit', '<=', $request->price_to);
        }

        if ($request->filled('stock_from')) {
            $query->where('stock_quantity', '>=', $request->stock_from);
        }

        if ($request->filled('stock_to')) {
            $query->where('stock_quantity', '<=', $request->stock_to);
        }

        $materials = $query->paginate(15)->withQueryString();

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(MaterialStoreRequest $request)
    {
        Material::create($request->validated());
        return redirect()->route('admin.materials.index')->with('success', 'Материал добавлен.');
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(MaterialUpdateRequest $request, Material $material)
    {
        $material->update($request->validated());
        return redirect()->route('admin.materials.index')->with('success', 'Материал обновлён.');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('admin.materials.index')->with('success', 'Материал удалён.');
    }
}