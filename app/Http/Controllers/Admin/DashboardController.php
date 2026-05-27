<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Material;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'active_orders' => Order::whereHas('orderStatus', function($q) {
                $q->whereNotIn('name', ['Выдан', 'Отменён']);
            })->count(),
            'total_clients' => User::whereHas('role', function($q) {
                $q->where('name', 'client');
            })->count(),
            'total_employees' => User::whereHas('role', function($q) {
                $q->where('name', 'employee');
            })->count(),
            'total_services' => Service::count(),
            'total_materials' => Material::count(),
        ];

        $ordersByStatus = OrderStatus::withCount('orders')->get();

        $recentOrders = Order::with(['client', 'employee', 'orderStatus'])
            ->latest()
            ->limit(10)
            ->get();

        $monthlyRevenue = Order::whereNotNull('completed_at')
    ->where('completed_at', '>=', now()->subMonths(6))
    ->get()
    ->groupBy(fn($order) => $order->completed_at->format('Y-m'))
    ->map(fn($orders) => $orders->sum('total_price'))
    ->map(function($total, $month) {
        return (object) ['month' => $month, 'total' => $total];
    })
    ->values();

        $topClients = User::whereHas('role', function($q) {
                $q->where('name', 'client');
            })
            ->withSum('ordersAsClient', 'total_price')
            ->orderBy('orders_as_client_sum_total_price', 'desc')
            ->limit(5)
            ->get();

        $topEmployees = User::whereHas('role', function($q) {
                $q->where('name', 'employee');
            })
            ->withCount(['ordersAsEmployee as completed_count' => function($q) {
                $q->whereHas('orderStatus', fn($s) => $s->where('name', 'Выдан'));
            }])
            ->orderBy('completed_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'ordersByStatus',
            'recentOrders',
            'monthlyRevenue',
            'topClients',
            'topEmployees'
        ));
    }
}