<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Регистрация — Ателье Строчка</title>
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
            background: linear-gradient(180deg, #e9e0d3 0%, #e4d8ca 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: white;
            width: 100%;
            max-width: 460px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-header {
            text-align: center;
            padding: 40px 24px 20px;
            border-bottom: 1px solid #ede7dc;
        }

        .auth-header .brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: #1a1714;
        }

        .auth-header .sub {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #8c7b68;
            margin-top: 6px;
        }

        .auth-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8c7b68;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ede7dc;
            border-radius: 4px;
            font-family: 'Jost', sans-serif;
            font-size: 0.9rem;
            color: #2d2926;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #ae9679;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-primary {
            background: #2d2926;
            color: white;
        }

        .btn-primary:hover {
            background: #1a1714;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 24px;
            font-size: 0.8rem;
        }

        .alert-error {
            background: #fbeae6;
            border-left: 3px solid #b5462a;
            color: #b5462a;
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #ede7dc;
            font-size: 0.8rem;
            color: #8c7b68;
        }

        .auth-footer a {
            color: #b5462a;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 24px;
            text-align: center;
            color: #2d2926;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="brand">Строчка</div>
            <div class="sub">Ателье</div>
        </div>

        <div class="auth-body">
            <h2>Регистрация</h2>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Иван Иванов" required autofocus>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label>Телефон (опционально)</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67">
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Минимум 6 символов" required>
                </div>

                <div class="form-group">
                    <label>Повторите пароль</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>

            <div class="auth-footer">
                Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
            </div>
        </div>
    </div>
</body>
</html>