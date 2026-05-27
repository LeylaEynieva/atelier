<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role->name === 'employee') {
            $myOrders = Order::where('employee_id', $user->id)->count();
            $activeOrders = Order::where('employee_id', $user->id)
                ->whereHas('orderStatus', fn($q) => $q->whereNotIn('name', ['Выдан', 'Отменён']))
                ->count();
            $completedOrders = Order::where('employee_id', $user->id)
                ->whereHas('orderStatus', fn($q) => $q->where('name', 'Выдан'))
                ->count();
            $recentOrders = Order::with(['client', 'orderStatus'])
                ->where('employee_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();

            return view('employee.dashboard', compact('myOrders', 'activeOrders', 'completedOrders', 'recentOrders'));
        }

        if ($user->role->name === 'client') {
            $myOrders = Order::where('client_id', $user->id)->count();
            $activeOrders = Order::where('client_id', $user->id)
                ->whereHas('orderStatus', fn($q) => $q->whereNotIn('name', ['Выдан', 'Отменён']))
                ->count();
            $completedOrders = Order::where('client_id', $user->id)
                ->whereHas('orderStatus', fn($q) => $q->where('name', 'Выдан'))
                ->count();
            $recentOrders = Order::with(['employee', 'orderStatus'])
                ->where('client_id', $user->id)
                ->latest()
                ->limit(5)
                ->get();

            return view('client.dashboard', compact('myOrders', 'activeOrders', 'completedOrders', 'recentOrders'));
        }

        return redirect()->route('home');
    }
}