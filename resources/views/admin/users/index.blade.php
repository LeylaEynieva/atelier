@extends('layouts.app')

@section('page-title', 'Управление пользователями')

@section('content')
    <!-- Форма фильтрации, поиска и сортировки -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Поиск</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Поиск по имени, email или телефону"
                        value="{{ request('search') }}"
                    >
                </div>
                <div class="col-md-3">
                    <label class="form-label">Фильтр по роли</label>
                    <select name="role" class="form-select">
                        <option value="">Все роли</option>
                        @foreach($roles as $role)
                            <option
                                value="{{ $role->name }}"
                                {{ request('role') == $role->name ? 'selected' : '' }}
                            >
                                @if($role->name == 'admin')
                                    Администратор
                                @elseif($role->name == 'employee')
                                    Сотрудник
                                @else
                                    Клиент
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Сортировать по</label>
                    <select name="sort" class="form-select">
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Имени</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Дате регистрации</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Направление</label>
                    <select name="direction" class="form-select">
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Сбросить все</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица пользователей -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">Список пользователей</div>
            <span class="text-muted">Всего: {{ $users->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Email</th>
                            <th scope="col">Роль</th>
                            <th scope="col">Телефон</th>
                            <th scope="col" style="white-space: nowrap;">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle text-center">
                        @forelse($users as $user)
                        <tr class="user-row">
                            <td>{{ $user->id }}</td>
                            <td class="text-start ps-3">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    @if($user->role)
                                        @if($user->role->name == 'admin')
                                            <span class="badge" style="background: #dc3545; color: white;">Администратор</span>
                                        @elseif($user->role->name == 'employee')
                                            <span class="badge" style="background: #fd7e14; color: white;">Сотрудник</span>
                                        @else
                                            <span class="badge" style="background: #28a745; color: white;">Клиент</span>
                                        @endif
                                    @else
                                        <span class="badge" style="background: #6c757d;">Нет роли</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td class="align-middle text-center" style="white-space: nowrap;">
                                @if(auth()->id() !== $user->id)
                                    <div class="d-flex justify-content-center align-items-center">
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                style="min-width: 70px; padding: 0.25rem 0.5rem;"
                                                onclick="return confirm('Удалить пользователя?')"
                                            >
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row">
                            <td colspan="6" class="text-center py-4">Пользователи не найдены</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Показано {{ $users->firstItem() }} - {{ $users->lastItem() }} из {{ $users->total() }} пользователей
                </div>
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection