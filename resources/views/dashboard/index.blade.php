@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4">Панель управления</h1>
    
    {{-- Статистика из массива --}}
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Всего заказов
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['orders_total'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Активные заказы
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['orders_active'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Клиенты
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['clients_total'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Сотрудники
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['employees_total'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Последние заказы --}}
        <div class="col-xl-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Последние заказы</h6>
                </div>
                <div class="card-body">
                    @forelse($recent_orders as $order)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <h6 class="mb-1">#{{ $order->id }}</h6>
                                <small class="text-muted">
                                    {{ $order->client->user->name ?? 'Клиент' }} 
                                    • {{ $order->created_at->format('d.m.Y H:i') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $order->status->color ?? 'secondary' }} fs-6">
                                    {{ $order->status->name ?? 'Неизвестно' }}
                                </span>
                                @if($order->employee)
                                    <small class="d-block">{{ $order->employee->user->name ?? '' }}</small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Заказы отсутствуют</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Статусы --}}
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Статусы заказов</h6>
                </div>
                <div class="card-body">
                    @foreach($statuses as $status)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>{{ $status->name }}</span>
                            <span class="badge bg-primary fs-6">{{ $status->orders_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection