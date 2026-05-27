<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use App\Http\Requests\StatusStoreRequest;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::paginate(15);
        return view('order-statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('order-statuses.create');
    }

    public function store(StatusStoreRequest $request)
    {
        OrderStatus::create($request->validated());
        return redirect()->route('admin.order-statuses.index')->with('success', 'Статус заказа добавлен.');
    }

    public function edit(OrderStatus $orderStatus)
    {
        return view('order-statuses.edit', compact('orderStatus'));
    }

    public function update(StatusStoreRequest $request, OrderStatus $orderStatus)
    {
        $orderStatus->update($request->validated());
        return redirect()->route('admin.order-statuses.index')->with('success', 'Статус заказа обновлён.');
    }

    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();
        return redirect()->route('admin.order-statuses.index')->with('success', 'Статус заказа удалён.');
    }
}