@extends('layouts.app')

@section('content')
<h1 class="mb-4">Новый платёж</h1>

<form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Заказ</label>
                <select name="order_id" class="form-select" required>
                    <option value="">Выберите заказ</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            #{{ $order->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Сумма</label>
                <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', 0) }}">
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-3">Создать</button>
</form>
@endsection