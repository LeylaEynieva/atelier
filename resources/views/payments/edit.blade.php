@extends('layouts.app')

@section('content')
<h1 class="mb-4">Редактировать платёж #{{ $payment->id }}</h1>

<form action="{{ route('payments.update', $payment) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Заказ</label>
                <select name="order_id" class="form-select" required>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id', $payment->order_id) == $order->id ? 'selected' : '' }}>
                            #{{ $order->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Сумма</label>
                <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $payment->amount ?? 0) }}">
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-3">Сохранить</button>
</form>
@endsection