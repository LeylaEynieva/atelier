@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Платёж #{{ $payment->id }}</h1>
    <div>
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">Редактировать</a>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Назад</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p><strong>Заказ:</strong> #{{ $payment->order_id }}</p>
        <p><strong>Сумма:</strong> {{ $payment->amount ?? 0 }}</p>
    </div>
</div>
@endsection