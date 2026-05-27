<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ателье «Строчка»')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Jost', sans-serif;
            font-weight: 300;
            background: #f7f3ee;
            color: #2d2926;
            min-height: 100vh;
            display: flex;
        }

       /* ===== SIDEBAR ===== */
.sidebar {
    width: 260px;
    min-height: 100vh;
    background: #1a1714;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
}

.sidebar-logo {
    padding: 36px 28px 28px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-logo .brand {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem;
    font-weight: 600;
    color: #ffffff;
    letter-spacing: 0.04em;
}

.sidebar-logo .sub {
    font-size: 0.68rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #d4c4b2;
    margin-top: 4px;
}

.sidebar-nav {
    flex: 1;
    padding: 20px 0;
}

.nav-section {
    padding: 16px 28px 6px;
    font-size: 0.62rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #a09080;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 28px;
    color: #e0d5c8;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 400;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.nav-link:hover {
    color: #ffffff;
    background: rgba(255,255,255,0.08);
}

.nav-link.active {
    color: #ffffff;
    border-left-color: #b5462a;
    background: rgba(255,255,255,0.06);
}

.sidebar-footer {
    padding: 20px 28px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #b5462a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    color: #fff;
    font-weight: 500;
}

.user-name {
    font-size: 0.8rem;
    color: #ffffff;
    font-weight: 500;
}

.user-role {
    font-size: 0.67rem;
    color: #c0b0a0;
    letter-spacing: 0.06em;
}

.btn-logout {
    width: 100%;
    padding: 8px;
    background: none;
    border: 1px solid rgba(255,255,255,0.2);
    color: #e0d5c8;
    border-radius: 4px;
    font-family: 'Jost', sans-serif;
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-logout:hover {
    border-color: #b5462a;
    color: #b5462a;
}
        /* ===== MAIN CONTENT ===== */
        .main-wrap {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 64px;
            background: #f7f3ee;
            border-bottom: 1px solid #ede7dc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 36px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 400;
            color: #2d2926;
        }

        .topbar-actions {
            display: flex;
            gap: 10px;
        }

        .content {
            padding: 36px;
            flex: 1;
        }

        /* ===== CARDS & STATS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border: 1px solid #ede7dc;
            border-radius: 8px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #b5462a;
        }

        .stat-label {
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #8c7b68;
        }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 300;
            color: #1a1714;
            margin-top: 8px;
        }

        .card {
            background: white;
            border: 1px solid #ede7dc;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #ede7dc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 4px;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 400;
            letter-spacing: 0.06em;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #2d2926;
            color: white;
        }

        .btn-primary:hover {
            background: #1a1714;
        }

        .btn-accent {
            background: #b5462a;
            color: white;
        }

        .btn-accent:hover {
            background: #9e3a22;
        }

        .btn-outline {
            background: none;
            border-color: #ede7dc;
            color: #2d2926;
        }

        .btn-outline:hover {
            border-color: #c9b99a;
            background: #faf8f5;
        }

        /* ===== TABLE ===== */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8c7b68;
            border-bottom: 2px solid #ede7dc;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #ede7dc;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            color: white;
        }

        /* ===== FORMS ===== */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        label {
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8c7b68;
        }

        input, select, textarea {
            padding: 10px 14px;
            border: 1px solid #ede7dc;
            border-radius: 4px;
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            color: #2d2926;
            background: white;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #c9b99a;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 4px;
            margin-bottom: 24px;
        }

        .alert-success {
            background: #eaf3ec;
            border-left: 3px solid #4a7c59;
            color: #4a7c59;
        }

        .alert-error {
            background: #fbeae6;
            border-left: 3px solid #b5462a;
            color: #b5462a;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border: 1px solid #ede7dc;
            border-radius: 4px;
            text-decoration: none;
            color: #2d2926;
        }

        .pagination .active span {
            background: #2d2926;
            color: white;
            border-color: #2d2926;
        }
    </style>
    @stack('styles')
</head>
<body>
    
@auth
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="brand">Строчка</div>
        <div class="sub">Управление ателье</div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">Главное</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"> &#128202; Дашборд </a>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"> &#128203; Заказы </a>
        @if(auth()->user()->role->name === 'admin')
        <div class="nav-section">Управление</div>
        <a href="{{ route('admin.services.index') }}" class="nav-link">&#128295; Услуги</a>
        <a href="{{ route('admin.materials.index') }}" class="nav-link">&#128230; Материалы</a>
        <a href="{{ route('admin.order-statuses.index') }}" class="nav-link">&#127991; Статусы заказов</a>
        <a href="{{ route('admin.payment-statuses.index') }}" class="nav-link">&#128176; Статусы платежей</a>
        <a href="{{ route('admin.users.index') }}" class="nav-link">&#128101; Пользователи</a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">
                    @switch(auth()->user()->role->name)
                        @case('admin') Администратор @break
                        @case('employee') Сотрудник @break
                        @case('client') Клиент @break
                    @endswitch
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Выйти</button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">@yield('page-title', 'Панель управления')</div>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        
        @yield('content')
    </main>
</div>

@else
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>