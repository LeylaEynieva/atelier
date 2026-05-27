@extends('layouts.app')

@section('page-title', 'Редактирование заказа #'.$order->id)

@section('content')
<form action="{{ route('orders.update', $order) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Основная информация</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                
                @if(auth()->user()->role->name === 'admin')
                <div class="col-md-6">
                    <label class="form-label">Клиент <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Выберите клиента --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $order->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Сотрудник (опционально)</label>
                    <select name="employee_id" class="form-select">
                        <option value="">-- Не назначен --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $order->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->position ?? 'Сотрудник' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @elseif(auth()->user()->role->name === 'employee')
                <div class="col-md-6">
                    <label class="form-label">Клиент <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Выберите клиента --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $order->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Сотрудник</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                    <input type="hidden" name="employee_id" value="{{ auth()->id() }}">
                </div>
                
                @else
                <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                @endif

                @if(auth()->user()->role->name !== 'client')
<div class="col-md-4">
    <label class="form-label">Статус <span class="text-danger">*</span></label>
    <select name="order_status_id" class="form-select" required>
        @foreach($statuses as $status)
            <option value="{{ $status->id }}" {{ old('order_status_id', $order->order_status_id) == $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
</div>
@else
    <input type="hidden" name="order_status_id" value="{{ $order->order_status_id }}">
@endif

                <div class="col-md-4">
                    <label class="form-label">Дата заказа <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control" value="{{ old('order_date', $order->order_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Срок выполнения</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $order->deadline?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Дата завершения</label>
                    <input type="date" name="completed_at" class="form-control" value="{{ old('completed_at', $order->completed_at?->format('Y-m-d')) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $order->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Услуги -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Услуги</div>
        </div>
        <div class="card-body">
            @foreach($services as $service)
                @php
                    $pivot = $order->services->firstWhere('id', $service->id)?->pivot;
                @endphp
                <div class="border rounded p-3 mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="services[{{ $service->id }}][selected]" value="1" id="service_{{ $service->id }}" {{ old("services.$service->id.selected", $pivot ? true : false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="service_{{ $service->id }}">
                            {{ $service->name }} — {{ number_format($service->price, 2) }} ₽
                        </label>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label small">Количество</label>
                            <input type="number" min="1" name="services[{{ $service->id }}][quantity]" class="form-control" value="{{ old("services.$service->id.quantity", $pivot->quantity ?? 1) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Цена (₽)</label>
                            <input type="number" step="0.01" min="0" name="services[{{ $service->id }}][price]" class="form-control" value="{{ old("services.$service->id.price", $pivot->price ?? $service->price) }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Материалы -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Материалы</div>
        </div>
        <div class="card-body">
            @foreach($materials as $material)
                @php
                    $pivot = $order->materials->firstWhere('id', $material->id)?->pivot;
                @endphp
                <div class="border rounded p-3 mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="materials[{{ $material->id }}][selected]" value="1" id="material_{{ $material->id }}" {{ old("materials.$material->id.selected", $pivot ? true : false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="material_{{ $material->id }}">
                            {{ $material->name }} ({{ $material->unit }}) — {{ number_format($material->price_per_unit, 2) }} ₽
                        </label>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label small">Количество</label>
                            <input type="number" min="1" name="materials[{{ $material->id }}][quantity]" class="form-control" value="{{ old("materials.$material->id.quantity", $pivot->quantity ?? 1) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Цена (₽)</label>
                            <input type="number" step="0.01" min="0" name="materials[{{ $material->id }}][price]" class="form-control" value="{{ old("materials.$material->id.price", $pivot->price ?? $material->price_per_unit) }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline">Отмена</a>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </div>
</form>
@endsection