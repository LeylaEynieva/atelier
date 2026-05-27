<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = Auth::id();
        
        // Статистика по клиенту
        $stats = [
            'my_orders_total' => Order::where('client_id', $clientId)->count(),
            'my_orders_active' => Order::where('client_id', $clientId)
                ->whereHas('status', function($q) {
                    $q->whereNotIn('name', ['Выдан', 'Отменён']);
                })->count(),
            'my_orders_completed' => Order::where('client_id', $clientId)
                ->whereHas('status', fn($q) => $q->where('name', 'Выдан'))
                ->count(),
            'my_orders_cancelled' => Order::where('client_id', $clientId)
                ->whereHas('status', fn($q) => $q->where('name', 'Отменён'))
                ->count(),
        ];

        // Общая сумма потраченная клиентом
        $totalSpent = Order::where('client_id', $clientId)
            ->whereHas('status', fn($q) => $q->where('name', 'Выдан'))
            ->sum('total_price');

        // Активные заказы (в работе)
        $activeOrders = Order::with(['employee', 'status'])
            ->where('client_id', $clientId)
            ->whereHas('status', function($q) {
                $q->whereNotIn('name', ['Выдан', 'Отменён']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // История заказов (последние 10)
        $orderHistory = Order::with(['employee', 'status'])
            ->where('client_id', $clientId)
            ->latest()
            ->limit(10)
            ->get();

        // Платежи (последние 5)
        $recentPayments = Payment::whereHas('order', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            })
            ->with('order')
            ->latest()
            ->limit(5)
            ->get();

        // Заказы по статусам (для круговой диаграммы)
        $ordersByStatus = Order::where('client_id', $clientId)
            ->with('status')
            ->get()
            ->groupBy('status.name')
            ->map->count();

        // Заказы по месяцам (для графика)
        $monthlyOrders = Order::where('client_id', $clientId)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total_price) as spent')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        // Ближайший дедлайн
        $nextDeadline = Order::where('client_id', $clientId)
            ->where('deadline', '>=', now())
            ->whereHas('status', function($q) {
                $q->whereNotIn('name', ['Выдан', 'Отменён']);
            })
            ->orderBy('deadline', 'asc')
            ->first();

        return view('client.dashboard', compact(
            'stats',
            'totalSpent',
            'activeOrders',
            'orderHistory',
            'recentPayments',
            'ordersByStatus',
            'monthlyOrders',
            'nextDeadline'
        ));
    }

    // Метод для отслеживания статуса конкретного заказа
    public function trackOrder(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему клиенту
        if ($order->client_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этому заказу');
        }

        $order->load(['status', 'employee', 'services', 'materials']);

        // Получаем историю изменения статусов (если есть отдельная таблица)
        // $statusHistory = $order->statusHistory()->get();

        return view('client.track-order', compact('order'));
    }

    // Получение уведомлений для клиента (AJAX)
    public function notifications()
    {
        $clientId = Auth::id();

        // Заказы, где статус изменился за последние 7 дней
        $recentStatusChanges = Order::where('client_id', $clientId)
            ->where('updated_at', '>=', now()->subDays(7))
            ->with('status')
            ->latest('updated_at')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'message' => "Статус заказа #{$order->id} изменён на: {$order->status->name}",
                    'date' => $order->updated_at->diffForHumans(),
                    'url' => route('orders.show', $order),
                ];
            });

        // Просроченные платежи (если есть)
        $overduePayments = Payment::whereHas('order', function($q) use ($clientId) {
                $q->where('client_id', $clientId);
            })
            ->where('status', 'pending')
            ->where('payment_date', '<', now())
            ->with('order')
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'message' => "Просрочен платёж по заказу #{$payment->order->id} на сумму {$payment->amount} ₽",
                    'date' => $payment->payment_date->diffForHumans(),
                    'url' => route('orders.show', $payment->order),
                ];
            });

        $notifications = $recentStatusChanges->concat($overduePayments);

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->take(10),
        ]);
    }
}