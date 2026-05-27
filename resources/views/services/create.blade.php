@extends('layouts.app')

@section('content')
<h1 class="mb-4">Новая услуга</h1>

<form action="{{ route('admin.services.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Цена</label>
                <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', 0) }}">
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-3">Создать</button>
</form>
@endsection