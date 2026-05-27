<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(15);
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