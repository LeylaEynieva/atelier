<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\MaterialStoreRequest;
use App\Http\Requests\MaterialUpdateRequest;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::paginate(15);
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