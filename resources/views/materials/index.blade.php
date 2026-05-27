@extends('layouts.app')

@section('page-title', 'Управление материалами')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Материалы</h1>
    <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">+ Новый материал</a>
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
        {{ $materials->links() }}
    </div>
</div>
@endsection