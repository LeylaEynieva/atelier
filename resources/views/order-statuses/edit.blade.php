@extends('layouts.app')

@section('page-title', 'Редактирование статуса заказа')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Редактирование статуса заказа</div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.order-statuses.update', $orderStatus) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Название статуса</label>
                <input type="text" name="name" class="form-control" value="{{ $orderStatus->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Цвет (HEX)</label>
                <input type="color" name="color" class="form-control" value="{{ $orderStatus->color }}" style="width: 80px; height: 40px;">
            </div>
            <div class="mb-3">
                <label class="form-label">Порядок сортировки</label>
                <input type="number" name="sort_order" class="form-control" value="{{ $orderStatus->sort_order }}">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-outline">Отмена</a>
        </form>
    </div>
</div>
@endsection