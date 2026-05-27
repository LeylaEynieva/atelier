@extends('layouts.app')

@section('page-title', 'Заказ #'.$order->id)

@section('topbar-actions')
    @if(auth()->user()->role->name === 'admin' || 
        (auth()->user()->role->name === 'employee' && $order->employee_id === auth()->id()) ||
        (auth()->user()->role->name === 'client' && $order->client_id === auth()->id()))
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
            <i class="icon">✏️</i> Редактировать
        </a>
    @endif
    
    @if(auth()->user()->role->name === 'admin')
        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ? Это действие нельзя отменить.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="icon">🗑️</i> Удалить
            </button>
        </form>
    @endif
    
    @if(auth()->user()->role->name === 'client' && $order->order_status_id != $cancelledStatusId)
        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите отменить этот заказ? Отменить отмену будет невозможно.')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">
                <i class="icon">❌</i> Отменить заказ
            </button>
        </form>
    @endif
    
    <a href="{{ route('orders.index') }}" class="btn btn-outline">
        <i class="icon">←</i> Назад к списку
    </a>
@endsection

@section('content')
<div class="row g-4">
    <!-- Левая колонка - информация о заказе -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="icon">📋</i> Информация о заказе
                </div>
            </div>
            <div class="card-body">
                <dl class="detail-grid">
                    <div class="detail-row">
                        <dt>Номер заказа</dt>
                        <dd>#{{ $order->id }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Клиент</dt>
                        <dd>
                            {{ $order->client->name ?? 'Не указан' }}
                            @if($order->client->phone)
                                <br><small class="text-muted">📞 {{ $order->client->phone }}</small>
                            @endif
                            @if($order->client->email)
                                <br><small class="text-muted">✉️ {{ $order->client->email }}</small>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt>Сотрудник</dt>
                        <dd>
                            @if($order->employee)
                                {{ $order->employee->name }}
                                @if($order->employee->position)
                                    <br><small class="text-muted">Должность: {{ $order->employee->position }}</small>
                                @endif
                            @else
                                <span class="text-muted">Не назначен</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt>Статус</dt>
                        <dd>
                            <span class="badge" style="background: {{ $order->orderStatus->color ?? '#6c757d' }}; color:white; padding: 6px 12px; font-size: 0.85rem;">
                                {{ $order->orderStatus->name ?? 'Новый' }}
                            </span>
                            @if($order->orderStatus->name === 'Отменён')
                                <span class="text-muted ms-2">Заказ был отменён</span>
                            @elseif($order->orderStatus->name === 'Выдан')
                                <span class="text-muted ms-2">Заказ получен клиентом</span>
                            @elseif($order->orderStatus->name === 'Готов к выдаче')
                                <span class="text-muted ms-2">Заказ готов, можно забирать</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt>Дата заказа</dt>
                        <dd>{{ $order->order_date?->format('d.m.Y') ?? '—' }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Срок выполнения</dt>
                        <dd>
                            {{ $order->deadline?->format('d.m.Y') ?? '—' }}
                            @if($order->deadline && $order->deadline->isPast() && $order->orderStatus->name !== 'Выдан' && $order->orderStatus->name !== 'Отменён')
                                <span class="text-danger ms-2">(Просрочен!)</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt>Дата завершения</dt>
                        <dd>{{ $order->completed_at?->format('d.m.Y') ?? '—' }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Описание</dt>
                        <dd class="text-break">{{ $order->description ?? '—' }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Итоговая сумма</dt>
                        <dd><strong style="font-size: 1.2rem;">{{ number_format($order->total_price, 2) }} ₽</strong></dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Дополнительная карточка с информацией о платеже (если есть) -->
        @if($order->payments && $order->payments->count())
        <div class="card mt-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="icon">💰</i> Информация об оплате
                </div>
            </div>
            <div class="card-body">
                @foreach($order->payments as $payment)
                    <dl class="detail-grid">
                        <div class="detail-row">
                            <dt>Сумма оплаты</dt>
                            <dd>{{ number_format($payment->amount, 2) }} ₽</dd>
                        </div>
                        <div class="detail-row">
                            <dt>Способ оплаты</dt>
                            <dd>
                                @if($payment->payment_method === 'cash')
                                    Наличные
                                @elseif($payment->payment_method === 'card')
                                    Банковская карта
                                @else
                                    {{ $payment->payment_method }}
                                @endif
                            </dd>
                        </div>
                        <div class="detail-row">
                            <dt>Дата оплаты</dt>
                            <dd>{{ $payment->payment_date?->format('d.m.Y') ?? '—' }}</dd>
                        </div>
                        <div class="detail-row">
                            <dt>Статус платежа</dt>
                            <dd>
                                <span class="badge" style="background: {{ $payment->paymentStatus->color ?? '#6c757d' }};">
                                    {{ $payment->paymentStatus->name ?? 'Ожидает оплаты' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Правая колонка - услуги и материалы -->
    <div class="col-md-6">
        <!-- Блок услуг -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="card-title">
                    <i class="icon">🔧</i> Услуги
                </div>
                @if(auth()->user()->role->name === 'admin' || 
                    (auth()->user()->role->name === 'employee' && $order->employee_id === auth()->id()))
                    <small class="text-muted">Изменить состав можно через редактирование</small>
                @endif
            </div>
            <div class="card-body p-0">
                @if($order->services->count())
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Услуга</th>
                                    <th class="text-center" style="width: 15%">Кол-во</th>
                                    <th class="text-end" style="width: 20%">Цена</th>
                                    <th class="text-end" style="width: 25%">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->services as $service)
                                <tr>
                                    <td>
                                        <strong>{{ $service->name }}</strong>
                                        @if($service->description)
                                            <br><small class="text-muted">{{ Str::limit($service->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $service->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($service->pivot->price, 2) }} ₽</td>
                                    <td class="text-end"><strong>{{ number_format($service->pivot->total, 2) }} ₽</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Итого по услугам:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->services->sum(fn($s) => $s->pivot->total), 2) }} ₽</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="icon">🔧</i> Нет услуг
                    </div>
                @endif
            </div>
        </div>

        <!-- Блок материалов -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="icon">📦</i> Материалы
                </div>
                @if(auth()->user()->role->name === 'admin' || 
                    (auth()->user()->role->name === 'employee' && $order->employee_id === auth()->id()))
                    <small class="text-muted">Изменить состав можно через редактирование</small>
                @endif
            </div>
            <div class="card-body p-0">
                @if($order->materials->count())
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Материал</th>
                                    <th class="text-center" style="width: 15%">Кол-во</th>
                                    <th class="text-end" style="width: 20%">Цена</th>
                                    <th class="text-end" style="width: 25%">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->materials as $material)
                                <tr>
                                    <td>
                                        <strong>{{ $material->name }}</strong>
                                        <br><small class="text-muted">Ед. изм.: {{ $material->unit }}</small>
                                    </td>
                                    <td class="text-center">{{ $material->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($material->pivot->price, 2) }} ₽</td>
                                    <td class="text-end"><strong>{{ number_format($material->pivot->total, 2) }} ₽</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Итого по материалам:</td>
                                    <td class="text-end fw-bold">{{ number_format($order->materials->sum(fn($m) => $m->pivot->total), 2) }} ₽</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="icon">📦</i> Нет материалов
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Блок итоговой суммы -->
        <div class="card mt-4 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-8 text-end fw-bold fs-5">ОБЩАЯ СТОИМОСТЬ:</div>
                    <div class="col-4 text-end fw-bold fs-4 text-success">{{ number_format($order->total_price, 2) }} ₽</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection