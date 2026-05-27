@extends('layouts.app')

@section('page-title', 'Кабинет сотрудника')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Мои заказы</div>
            <div class="stat-value">{{ $myOrders ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">В работе</div>
            <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Выполнено</div>
            <div class="stat-value">{{ $completedOrders ?? 0 }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Активные заказы</div>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline">Все заказы →</a>
        </div>
        <div class="card-body">
            @forelse($recentOrders ?? [] as $order)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>Заказ #{{ $order->id }}</strong>
                        <span class="text-muted ms-2">Клиент: {{ $order->client->name }}</span>
                    </div>
                    <div>
                        <span class="badge" style="background: {{ $order->status->color ?? '#6c757d' }}; color:white;">
                            {{ $order->status->name ?? 'Новый' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">Нет активных заказов</p>
            @endforelse
        </div>
    </div>
@endsection