@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Платежи</h1>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">Новый платёж</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Заказ</th>
                <th>Сумма</th>
                <th>Дата</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>#{{ $payment->order_id }}</td>
                    <td>{{ $payment->amount ?? 0 }}</td>
                    <td>{{ $payment->created_at?->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">Открыть</a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить платёж?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">Платежей нет</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection