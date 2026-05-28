@extends('layouts.app')

@section('page-title', 'Управление материалами')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Материалы</h1>
    <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">+ Новый материал</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.materials.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Поиск</label>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Название материала"
                    value="{{ request('search') }}"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Ед. изм.</label>
                <select name="unit" class="form-select">
                    <option value="">Все</option>
                    <option value="шт" {{ request('unit') == 'шт' ? 'selected' : '' }}>шт</option>
                    <option value="метр" {{ request('unit') == 'метр' ? 'selected' : '' }}>метр</option>
                    <option value="катушка" {{ request('unit') == 'катушка' ? 'selected' : '' }}>катушка</option>
                    <option value="комплект" {{ request('unit') == 'комплект' ? 'selected' : '' }}>комплект</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Цена от</label>
                <input
                    type="number"
                    name="price_from"
                    class="form-control"
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
                    value="{{ request('price_to') }}"
                    step="0.01"
                    min="0"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Остаток от</label>
                <input
                    type="number"
                    name="stock_from"
                    class="form-control"
                    value="{{ request('stock_from') }}"
                    min="0"
                >
            </div>

            <div class="col-md-2">
                <label class="form-label">Остаток до</label>
                <input
                    type="number"
                    name="stock_to"
                    class="form-control"
                    value="{{ request('stock_to') }}"
                    min="0"
                >
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ route('admin.materials.index') }}" class="btn btn-outline">Сбросить</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Ед. изм.</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                        <th style="white-space: nowrap;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->unit }}</td>
                        <td>{{ number_format($material->price_per_unit, 2) }} ₽</td>
                        <td>{{ $material->stock_quantity }} {{ $material->unit }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-warning" style="padding: 2px 8px; font-size: 11px; width: 100px; display: inline-block; text-align: center; margin-right: 5px;">Редактировать</a>
                            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 2px 8px; font-size: 11px; width: 100px; display: inline-block; text-align: center;" onclick="return confirm('Удалить материал?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Материалы не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-body">
        {{ $materials->appends(request()->query())->links() }}
    </div>
</div>
@endsection