@extends('layouts.app')

@section('page-title', 'Заказы')

@section('topbar-actions')
    @if(auth()->user()->role->name === 'client')
        <a href="{{ route('orders.create') }}" class="btn btn-accent">Новый заказ</a>
    @endif
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Поиск по клиенту" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="order_status_id" class="form-select">
                    <option value="">Все статусы</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('order_status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">Фильтр</button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline">Сброс</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Сотрудник</th>
                    <th>Статус</th>
                    <th>Дата заказа</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client->name ?? 'Не указан' }}</td>
                        <td>{{ $order->employee->name ?? 'Не назначен' }}</td>
                        <td>
                            <span class="badge" style="background: {{ $order->orderStatus->color ?? '#6c757d' }}; color: white;">
                                {{ $order->orderStatus->name ?? 'Новый' }}
                            </span>
                        </td>
                        <td>{{ $order->order_date?->format('d.m.Y') ?? '—' }}</td>
                        <td>{{ number_format($order->total_price, 2, ',', ' ') }} ₽</td>
                       <td>
    <div class="order-actions">
        <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm action-btn">Открыть</a>
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm action-btn">Редактировать</a>
        <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Удалить заказ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm action-btn">Удалить</button>
        </form>
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Заказов не найдено</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card-body">
    {{ $orders->links() }}
</div>
@endsection