@extends('layouts.app')

@section('page-title', 'Редактирование статуса платежа')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Редактирование статуса платежа</div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payment-statuses.update', $paymentStatus) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Название статуса</label>
                <input type="text" name="name" class="form-control" value="{{ $paymentStatus->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Цвет (HEX)</label>
                <input type="color" name="color" class="form-control" value="{{ $paymentStatus->color }}" style="width: 80px; height: 40px;">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.payment-statuses.index') }}" class="btn btn-outline">Отмена</a>
        </form>
    </div>
</div>
@endsection