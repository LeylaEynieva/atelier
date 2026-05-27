@extends('layouts.app')

@section('page-title', 'Новый заказ')

@section('content')
<form action="{{ route('orders.store') }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Основная информация</div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @if(auth()->user()->role === 'admin')
                <div class="col-md-6">
                    <label class="form-label">Клиент <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Выберите клиента --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Сотрудник (опционально)</label>
                    <select name="employee_id" class="form-select">
                        <option value="">-- Не назначен --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->position ?? 'Сотрудник' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
                    <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                    @if(auth()->user()->role === 'employee')
                    <div class="col-md-6">
                        <label class="form-label">Клиент <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Выберите клиента --</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                @endif

                <div class="col-md-4">
                    <label class="form-label">Статус <span class="text-danger">*</span></label>
                    <select name="order_status_id" class="form-select" required>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ old('order_status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Дата заказа <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Срок выполнения</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Услуги и материалы (оставляем как есть) -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Услуги</div>
        </div>
        <div class="card-body">
            @foreach($services as $service)
                <div class="border rounded p-3 mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="services[{{ $service->id }}][selected]" value="1" id="service_{{ $service->id }}" {{ old("services.$service->id.selected") ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="service_{{ $service->id }}">
                            {{ $service->name }} — {{ number_format($service->price, 2) }} ₽
                        </label>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label small">Количество</label>
                            <input type="number" min="1" name="services[{{ $service->id }}][quantity]" class="form-control" value="{{ old("services.$service->id.quantity", 1) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Цена (₽)</label>
                            <input type="number" step="0.01" min="0" name="services[{{ $service->id }}][price]" class="form-control" value="{{ old("services.$service->id.price", $service->price) }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">Материалы</div>
        </div>
        <div class="card-body">
            @foreach($materials as $material)
                <div class="border rounded p-3 mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="materials[{{ $material->id }}][selected]" value="1" id="material_{{ $material->id }}" {{ old("materials.$material->id.selected") ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="material_{{ $material->id }}">
                            {{ $material->name }} ({{ $material->unit }}) — {{ number_format($material->price_per_unit, 2) }} ₽
                        </label>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="form-label small">Количество</label>
                            <input type="number" min="1" name="materials[{{ $material->id }}][quantity]" class="form-control" value="{{ old("materials.$material->id.quantity", 1) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Цена (₽)</label>
                            <input type="number" step="0.01" min="0" name="materials[{{ $material->id }}][price]" class="form-control" value="{{ old("materials.$material->id.price", $material->price_per_unit) }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('orders.index') }}" class="btn btn-outline">Отмена</a>
        <button type="submit" class="btn btn-primary">Создать заказ</button>
    </div>
</form>
@endsection