@extends('layouts.app')

@section('page-title', 'Управление статусами платежей')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Статусы платежей</h1>
    <a href="{{ route('admin.payment-statuses.create') }}" class="btn btn-primary">+ Новый статус</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.payment-statuses.index') }}" class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Поиск</label>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Название статуса"
                    value="{{ request('search') }}"
                >
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ route('admin.payment-statuses.index') }}" class="btn btn-outline">Сбросить</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цвет</th>
                    <th style="white-space: nowrap;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->name }}</td>
                    <td>
                        <span class="badge" style="background: {{ $status->color }}; color: white; padding: 6px 12px; border-radius: 20px;">
                            {{ $status->name }}
                        </span>
                    </td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.payment-statuses.edit', $status) }}" class="btn btn-warning" style="padding: 2px 8px; font-size: 11px; width: 100px; display: inline-block; text-align: center; margin-right: 5px;">Редактировать</a>
                        <form action="{{ route('admin.payment-statuses.destroy', $status) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 2px 8px; font-size: 11px; width: 100px; display: inline-block; text-align: center;" onclick="return confirm('Удалить статус?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Статусы платежей не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">
        {{ $statuses->appends(request()->query())->links() }}
    </div>
</div>
@endsection