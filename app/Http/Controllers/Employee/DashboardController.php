<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employeeId = Auth::id();
        
        // Статистика по сотруднику
        $stats = [
            'my_orders_total' => Order::where('employee_id', $employeeId)->count(),
            'my_orders_active' => Order::where('employee_id', $employeeId)
                ->whereHas('status', function($q) {
                    $q->whereNotIn('name', ['Выдан', 'Отменён']);
                })->count(),
            'my_orders_completed' => Order::where('employee_id', $employeeId)
                ->whereHas('status', fn($q) => $q->where('name', 'Выдан'))
                ->count(),
            'my_orders_today' => Order::where('employee_id', $employeeId)
                ->whereDate('created_at', today())
                ->count(),
        ];

        // Заказы в работе (сроки)
        $activeOrders = Order::with(['client', 'status'])
            ->where('employee_id', $employeeId)
            ->whereHas('status', function($q) {
                $q->whereNotIn('name', ['Выдан', 'Отменён']);
            })
            ->orderBy('deadline', 'asc')
            ->get();

        // Просроченные заказы
        $overdueOrders = Order::with(['client', 'status'])
            ->where('employee_id', $employeeId)
            ->where('deadline', '<', now())
            ->whereHas('status', function($q) {
                $q->whereNotIn('name', ['Выдан', 'Отменён']);
            })
            ->orderBy('deadline', 'asc')
            ->get();

        // Последние 10 заказов
        $recentOrders = Order::with(['client', 'status'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->limit(10)
            ->get();

        // Заказы по статусам (для диаграммы)
        $ordersByStatus = Order::where('employee_id', $employeeId)
            ->with('status')
            ->get()
            ->groupBy('status.name')
            ->map->count();

        // Сумма выполненных заказов (за всё время)
        $totalCompletedAmount = Order::where('employee_id', $employeeId)
            ->whereHas('status', fn($q) => $q->where('name', 'Выдан'))
            ->sum('total_price');

        // Заказы по месяцам (для графика)
        $monthlyOrders = Order::where('employee_id', $employeeId)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total_price) as revenue')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return view('employee.dashboard', compact(
            'stats',
            'activeOrders',
            'overdueOrders',
            'recentOrders',
            'ordersByStatus',
            'totalCompletedAmount',
            'monthlyOrders'
        ));
    }

    // Календарь заказов (для отображения в виде календаря)
    public function calendar(Request $request)
    {
        $employeeId = Auth::id();
        
        $orders = Order::where('employee_id', $employeeId)
            ->whereNotNull('deadline')
            ->select('id', 'deadline', 'client_id', 'total_price')
            ->with('client')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'title' => 'Заказ #' . $order->id . ' - ' . ($order->client->name ?? 'Клиент'),
                    'start' => $order->deadline->format('Y-m-d'),
                    'url' => route('orders.show', $order),
                    'backgroundColor' => '#fd7e14',
                ];
            });

        if ($request->ajax()) {
            return response()->json($orders);
        }

        return view('employee.calendar', compact('orders'));
    }
}