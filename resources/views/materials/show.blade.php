@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Материал #{{ $material->id }}</h1>
    <div>
        <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Назад</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Название:</strong> {{ $material->name }}</p>
        <p><strong>Цена:</strong> {{ $material->price ?? 0 }}</p>
    </div>
</div>
@endsection