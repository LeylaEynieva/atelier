@extends('layouts.app')

@section('page-title', 'Новый статус заказа')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Добавление статуса заказа</div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.order-statuses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название статуса</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Цвет (HEX)</label>
                <input type="color" name="color" class="form-control" value="#6c757d" style="width: 80px; height: 40px;">
            </div>
            <div class="mb-3">
                <label class="form-label">Порядок сортировки</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline">Отмена</a>
        </form>
    </div>
</div>
@endsection