@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Статус #{{ $status->id }}</h1>
    <div>
        <a href="{{ route('admin.statuses.edit', $status) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('admin.statuses.index') }}" class="btn btn-secondary">Назад</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Название:</strong> {{ $status->name }}</p>
    </div>
</div>
@endsection