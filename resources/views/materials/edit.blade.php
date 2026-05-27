@extends('layouts.app')

@section('content')
<h1 class="mb-4">Редактировать материал</h1>

<form action="{{ route('admin.materials.update', $material) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $material->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Единица измерения</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $material->unit) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Цена за единицу</label>
                <input type="number" step="0.01" min="0" name="price_per_unit" class="form-control" value="{{ old('price_per_unit', $material->price_per_unit) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Остаток</label>
                <input type="number" min="0" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $material->stock_quantity) }}" required>
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-3">Сохранить</button>
</form>
@endsection