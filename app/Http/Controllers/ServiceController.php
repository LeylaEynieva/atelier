<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Поиск по названию и описанию
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Цена от
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }

        // Цена до
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        $services = $query->latest()->paginate(15)->withQueryString();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(ServiceStoreRequest $request)
    {
        Service::create($request->validated());
        return redirect()->route('admin.services.index')->with('success', 'Услуга добавлена.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        $service->update($request->validated());
        return redirect()->route('admin.services.index')->with('success', 'Услуга обновлена.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Услуга удалена.');
    }
}