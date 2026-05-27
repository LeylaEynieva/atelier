<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\Service;
use App\Models\Material;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['client', 'employee', 'orderStatus']);
        
        if ($user->role->name === 'client') {
            $query->where('client_id', $user->id);
        } elseif ($user->role->name === 'employee') {
            $query->where('employee_id', $user->id);
        }
        
        if ($request->filled('order_status_id')) {
            $query->where('order_status_id', $request->order_status_id);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('client', fn($q) => 
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            );
        }
        
        $orders = $query->orderByDesc('created_at')->paginate(15);
        $statuses = OrderStatus::all();
        
        return view('orders.index', compact('orders', 'statuses'));
    }

    public function create()
    {
        $clients = User::whereHas('role', function($q) {
            $q->where('name', 'client');
        })->get();
        
        $employees = User::whereHas('role', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        $statuses = OrderStatus::all();
        $services = Service::all();
        $materials = Material::all();
        
        return view('orders.create', compact('clients', 'employees', 'statuses', 'services', 'materials'));
    }

    public function store(OrderStoreRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        
        if ($user->role->name === 'client') {
            $data['client_id'] = $user->id;
        }
        
        $order = Order::create($data);

        if ($request->filled('services')) {
            foreach ($request->services as $serviceId => $serviceData) {
                if (!empty($serviceData['selected'])) {
                    $service = Service::find($serviceId);
                    $price = $serviceData['price'] ?? $service->price;
                    $quantity = $serviceData['quantity'] ?? 1;
                    
                    $order->services()->attach($serviceId, [
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                }
            }
        }
        
        if ($request->filled('materials')) {
            foreach ($request->materials as $materialId => $materialData) {
                if (!empty($materialData['selected'])) {
                    $material = Material::find($materialId);
                    $price = $materialData['price'] ?? $material->price_per_unit;
                    $quantity = $materialData['quantity'] ?? 1;
                    
                    $order->materials()->attach($materialId, [
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                    
                    $material->decrement('stock_quantity', $quantity);
                }
            }
        }
        
        $order->recalcTotal();
        
        return redirect()->route('orders.show', $order)->with('success', 'Заказ успешно создан!');
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['client', 'employee', 'orderStatus', 'services', 'materials', 'payments']);
        $cancelledStatusId = OrderStatus::where('name', 'Отменён')->first()->id;
        
        return view('orders.show', compact('order', 'cancelledStatusId'));
    }

    public function edit(Order $order)
    {
        $this->authorizeOrder($order);
        
        $clients = User::whereHas('role', function($q) {
            $q->where('name', 'client');
        })->get();
        
        $employees = User::whereHas('role', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        $statuses = OrderStatus::all();
        $services = Service::all();
        $materials = Material::all();
        $order->load(['services', 'materials']);
        
        return view('orders.edit', compact('order', 'clients', 'employees', 'statuses', 'services', 'materials'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->authorizeOrder($order);
        
        $data = $request->validated();
        $order->update($data);
        
        $syncServices = [];
        if ($request->filled('services')) {
            foreach ($request->services as $serviceId => $serviceData) {
                if (!empty($serviceData['selected'])) {
                    $service = Service::find($serviceId);
                    $price = $serviceData['price'] ?? $service->price;
                    $quantity = $serviceData['quantity'] ?? 1;
                    
                    $syncServices[$serviceId] = [
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }
            }
        }
        $order->services()->sync($syncServices);
        
        $syncMaterials = [];
        if ($request->filled('materials')) {
            foreach ($request->materials as $materialId => $materialData) {
                if (!empty($materialData['selected'])) {
                    $material = Material::find($materialId);
                    $price = $materialData['price'] ?? $material->price_per_unit;
                    $quantity = $materialData['quantity'] ?? 1;
                    
                    $syncMaterials[$materialId] = [
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }
            }
        }
        $order->materials()->sync($syncMaterials);
        
        $order->recalcTotal();
        
        return redirect()->route('orders.show', $order)->with('success', 'Заказ обновлён.');
    }

    public function destroy(Order $order)
    {
        $this->authorizeOrder($order);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Заказ удалён.');
    }
    
    public function cancel(Order $order)
    {
        if (auth()->user()->role->name !== 'client' || $order->client_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'У вас нет доступа к этому действию');
        }
        
        $cancelledStatusId = OrderStatus::where('name', 'Отменён')->first()->id;
        
        if ($order->order_status_id == $cancelledStatusId) {
            return redirect()->route('orders.show', $order)->with('error', 'Заказ уже отменён');
        }
        
        $order->update(['order_status_id' => $cancelledStatusId]);
        
        return redirect()->route('orders.show', $order)->with('success', 'Заказ успешно отменён');
    }
    
    private function authorizeOrder(Order $order): void
    {
        $user = Auth::user();
        
        if ($user->role->name === 'admin') {
            return;
        }
        
        if ($user->role->name === 'employee' && $order->employee_id !== $user->id) {
            redirect()->route('home')->send();
            exit;
        }
        
        if ($user->role->name === 'client' && $order->client_id !== $user->id) {
            redirect()->route('home')->send();
            exit;
        }
    }
}