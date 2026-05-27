@extends('layouts.app')

@section('page-title', 'Новый статус платежа')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Добавление статуса платежа</div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payment-statuses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название статуса</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Цвет (HEX)</label>
                <input type="color" name="color" class="form-control" value="#6c757d" style="width: 80px; height: 40px;">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.payment-statuses.index') }}" class="btn btn-outline">Отмена</a>
        </form>
    </div>
</div>
@endsection