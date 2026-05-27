<?php

namespace App\Http\Controllers;

use App\Models\PaymentStatus;
use App\Http\Requests\PaymentStatusStoreRequest;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function index()
    {
        $statuses = PaymentStatus::paginate(15);
        return view('payment-statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('payment-statuses.create');
    }

    public function store(PaymentStatusStoreRequest $request)
    {
        PaymentStatus::create($request->validated());
        return redirect()->route('admin.payment-statuses.index')->with('success', 'Статус платежа добавлен.');
    }

    public function edit(PaymentStatus $paymentStatus)
    {
        return view('payment-statuses.edit', compact('paymentStatus'));
    }

    public function update(PaymentStatusStoreRequest $request, PaymentStatus $paymentStatus)
    {
        $paymentStatus->update($request->validated());
        return redirect()->route('admin.payment-statuses.index')->with('success', 'Статус платежа обновлён.');
    }

    public function destroy(PaymentStatus $paymentStatus)
    {
        $paymentStatus->delete();
        return redirect()->route('admin.payment-statuses.index')->with('success', 'Статус платежа удалён.');
    }
}