@extends('layouts.app')

@section('page-title', 'Админ-панель')

@section('topbar-actions')
    <a href="{{ route('orders.create') }}" class="btn btn-primary">&#10133; Новый заказ</a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #b5462a;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Всего заказов</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['total_orders'] ?? 0 }}</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #28a745;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Активных заказов</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['active_orders'] ?? 0 }}</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #007bff;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Клиентов</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['total_clients'] ?? 0 }}</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #fd7e14;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Сотрудников</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['total_employees'] ?? 0 }}</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #17a2b8;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Услуг</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['total_services'] ?? 0 }}</div>
    </div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #20c997;">
        <div style="font-size: 13px; color: #8c7b68; text-transform: uppercase; letter-spacing: 1px;">Материалов</div>
        <div style="font-size: 32px; font-weight: bold; margin-top: 8px;">{{ $stats['total_materials'] ?? 0 }}</div>
    </div>
</div>

<div style="background: white; border-radius: 12px; margin-bottom: 30px; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 18px;">&#128203; Последние заказы</h3>
        <a href="{{ route('orders.index') }}" style="font-size: 13px;">Все заказы &#8594;</a>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f5f0;">
                <tr>
                    <th style="padding: 12px 16px; text-align: left;">ID</th>
                    <th style="padding: 12px 16px; text-align: left;">Клиент</th>
                    <th style="padding: 12px 16px; text-align: left;">Сотрудник</th>
                    <th style="padding: 12px 16px; text-align: left;">Статус</th>
                    <th style="padding: 12px 16px; text-align: right;">Сумма</th>
                    <th style="padding: 12px 16px; text-align: left;">Дата</th>
                    <th style="padding: 12px 16px; text-align: center;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr style="border-bottom: 1px solid #e0d5c8;">
                    <td style="padding: 12px 16px;">#{{ $order->id }}</td>
                    <td style="padding: 12px 16px;">{{ $order->client->name ?? '—' }}</td>
                    <td style="padding: 12px 16px;">{{ $order->employee->name ?? '—' }}</td>
                    <td style="padding: 12px 16px;">
                        <span style="background: {{ $order->orderStatus->color ?? '#6c757d' }}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px;">
                            {{ $order->orderStatus->name ?? 'Новый' }}
                        </span>
                    </td>
                    <td style="padding: 12px 16px; text-align: right;">{{ number_format($order->total_price, 2) }} ₽</td>
                    <td style="padding: 12px 16px;">{{ $order->created_at->format('d.m.Y') }}</td>
                    <td style="padding: 12px 16px; text-align: center;">
                        <a href="{{ route('orders.show', $order) }}" style="background: #b5462a; color: white; padding: 4px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">Открыть</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #8c7b68;">Нет заказов</td
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <div style="background: white; border-radius: 12px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8;">
            <h3 style="margin: 0; font-size: 18px;">&#128202; Распределение заказов по статусам</h3>
        </div>
        <div style="padding: 20px;">
            @foreach($ordersByStatus as $status)
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div>
                    <span style="display: inline-block; width: 12px; height: 12px; background: {{ $status->color }}; border-radius: 50%; margin-right: 10px;"></span>
                    <span>{{ $status->name }}</span>
                </div>
                <span style="background: #e0d5c8; padding: 4px 12px; border-radius: 20px; font-size: 13px;">{{ $status->orders_count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8;">
            <h3 style="margin: 0; font-size: 18px;">&#9889; Быстрые действия</h3>
        </div>
        <div style="padding: 20px;">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.services.index') }}" style="display: block; padding: 10px 15px; background: #f8f5f0; border-radius: 8px; text-decoration: none; color: #2c2418;">&#128295; Управление услугами</a>
                <a href="{{ route('admin.materials.index') }}" style="display: block; padding: 10px 15px; background: #f8f5f0; border-radius: 8px; text-decoration: none; color: #2c2418;">&#128230; Управление материалами</a>
                <a href="{{ route('admin.order-statuses.index') }}" style="display: block; padding: 10px 15px; background: #f8f5f0; border-radius: 8px; text-decoration: none; color: #2c2418;">&#127991; Статусы заказов</a>
                <a href="{{ route('admin.payment-statuses.index') }}" style="display: block; padding: 10px 15px; background: #f8f5f0; border-radius: 8px; text-decoration: none; color: #2c2418;">&#128176; Статусы платежей</a>
                <a href="{{ route('admin.users.index') }}" style="display: block; padding: 10px 15px; background: #f8f5f0; border-radius: 8px; text-decoration: none; color: #2c2418;">&#128101; Управление пользователями</a>
                <a href="{{ route('orders.create') }}" style="display: block; padding: 10px 15px; background: #b5462a; border-radius: 8px; text-decoration: none; color: white; text-align: center;">&#10133; Создать новый заказ</a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
    <div style="background: white; border-radius: 12px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8;">
            <h3 style="margin: 0; font-size: 18px;">&#127942; Топ клиентов по сумме заказов</h3>
        </div>
        <div style="padding: 20px;">
            @forelse($topClients as $index => $client)
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span><strong>{{ $index + 1 }}.</strong> {{ $client->name }}</span>
                <span style="color: #28a745; font-weight: bold;">{{ number_format($client->orders_as_client_sum_total_price ?? 0, 2) }} ₽</span>
            </div>
            @empty
            <p style="text-align: center; color: #8c7b68;">Нет данных</p>
            @endforelse
        </div>
    </div>

    <div style="background: white; border-radius: 12px; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8;">
            <h3 style="margin: 0; font-size: 18px;">&#127942; Топ сотрудников по выполненным заказам</h3>
        </div>
        <div style="padding: 20px;">
            @forelse($topEmployees as $index => $employee)
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span><strong>{{ $index + 1 }}.</strong> {{ $employee->name }}</span>
                <span style="background: #007bff; color: white; padding: 2px 10px; border-radius: 20px; font-size: 12px;">{{ $employee->completed_count ?? 0 }} заказов</span>
            </div>
            @empty
            <p style="text-align: center; color: #8c7b68;">Нет данных</p>
            @endforelse
        </div>
    </div>
</div>

@if(isset($monthlyRevenue) && $monthlyRevenue->count())
<div style="background: white; border-radius: 12px; margin-top: 30px; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid #e0d5c8;">
        <h3 style="margin: 0; font-size: 18px;">&#128200; Динамика выручки по месяцам</h3>
    </div>
    <div style="padding: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 8px; text-align: left;">Месяц</th>
                    <th style="padding: 8px; text-align: right;">Выручка</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyRevenue as $revenue)
                <tr style="border-bottom: 1px solid #e0d5c8;">
                    <td style="padding: 10px 8px;">{{ $revenue->month }}</td>
                    <td style="padding: 10px 8px; text-align: right;"><strong>{{ number_format($revenue->total, 2) }} ₽</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection