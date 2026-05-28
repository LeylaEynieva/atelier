@extends('layouts.app')

@section('page-title', 'Управление услугами')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Услуги</h1>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Новая услуга</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.services.index') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Поиск</label>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="По названию или описанию"
                    value="{{ request('search') }}"
                >
            </div>
            <div class="col-md-2">
                <label class="form-label">Цена от</label>
                <input
                    type="number"
                    name="price_from"
                    class="form-control"
                    placeholder="0"
                    value="{{ request('price_from') }}"
                    step="0.01"
                    min="0"
                >
            </div>
            <div class="col-md-2">
                <label class="form-label">Цена до</label>
                <input
                    type="number"
                    name="price_to"
                    class="form-control"
                    placeholder="—"
                    value="{{ request('price_to') }}"
                    step="0.01"
                    min="0"
                >
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Сбросить</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ Str::limit($service->description, 50) }}</td>
                    <td>{{ number_format($service->price, 2) }} ₽</td>
                    <td>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Услуги не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $services->links() }}
    </div>
</div>
@endsection