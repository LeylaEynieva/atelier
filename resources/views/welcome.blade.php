<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ателье «Строчка»</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-light: #faf7f2;
            --bg-card: #ffffff;
            --text-dark: #1e1b18;
            --text-muted: #5c4e3d;
            --border-light: #e5dbcf;
            --accent: #c16a4a;
            --accent-dark: #a04e30;
            --accent-light: #e8cbbc;
            --shadow: rgba(0, 0, 0, 0.06);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Jost', sans-serif;
            font-weight: 400;
            background: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px;
        }

        .topbar {
            height: 72px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 18px;
            z-index: 50;
            box-shadow: 0 8px 24px var(--shadow);
        }

        .brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--text-dark);
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: center;
        }

        .nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: color .2s;
        }

        .nav a:hover { color: var(--accent); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 22px;
            border-radius: 40px;
            text-decoration: none;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .06em;
            border: 1px solid transparent;
            transition: all .2s;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-dark); }

        .btn-outline {
            background: transparent;
            border-color: var(--border-light);
            color: var(--text-dark);
        }
        .btn-outline:hover {
            border-color: var(--accent);
            background: rgba(193, 106, 74, 0.05);
            color: var(--accent);
        }

        .hero {
            margin-top: 32px;
            display: grid;
            grid-template-columns: 1.4fr 0.9fr;
            gap: 28px;
            align-items: stretch;
        }

        .hero-main,
        .hero-side,
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 28px var(--shadow);
        }

        .hero-main {
            padding: 56px 48px;
        }

        .eyebrow {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
        }

        h1, h2, h3 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            color: var(--text-dark);
        }

        h1 {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .lead {
            font-size: 1.05rem;
            font-weight: 400;
            line-height: 1.6;
            color: var(--text-muted);
            max-width: 760px;
        }

        .hero-actions {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .hero-side {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .info-block {
            padding: 20px;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            background: #fefcf9;
        }

        .info-block h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 12px;
        }

        .info-block p, .info-block a {
            font-size: .95rem;
            line-height: 1.7;
            color: var(--text-muted);
            text-decoration: none;
        }

        .info-block a:hover {
            color: var(--accent);
        }

        .info-block strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        .section {
            margin-top: 32px;
            display: grid;
            gap: 24px;
        }

        .section-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .section-card {
            padding: 32px 28px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px var(--shadow);
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 16px;
        }

        .section-card h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
        }

        .muted {
            color: var(--text-muted);
            line-height: 1.65;
            font-size: .9rem;
            font-weight: 400;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .contact-item {
            padding: 24px;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            background: var(--bg-card);
            text-align: center;
        }

        .contact-item strong {
            display: block;
            margin-bottom: 8px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--accent);
        }

        .contact-item a, .contact-item span {
            font-size: 1rem;
            color: var(--text-dark);
            text-decoration: none;
        }

        .contact-item a:hover {
            color: var(--accent);
        }

        .footer {
            padding: 32px 6px 16px;
            color: var(--text-muted);
            font-size: .8rem;
            text-align: center;
            border-top: 1px solid var(--border-light);
            margin-top: 32px;
        }

        @media (max-width: 980px) {
            .hero, .section-grid, .contact-grid {
                grid-template-columns: 1fr;
            }

            h1 { font-size: 2.8rem; }

            .topbar {
                height: auto;
                padding: 18px 20px;
                gap: 16px;
                flex-direction: column;
                align-items: flex-start;
            }

            .nav {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .hero-main {
                padding: 34px 28px;
            }
        }

        @media (max-width: 480px) {
            .page {
                padding: 16px;
            }
            
            .hero-main {
                padding: 24px 20px;
            }
            
            h1 {
                font-size: 2.2rem;
            }
            
            .btn {
                padding: 8px 18px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="topbar">
            <div class="brand">Строчка</div>
            <nav class="nav">
                <a href="#about">О нас</a>
                <a href="#services">Услуги</a>
                <a href="#contacts">Контакты</a>
                @auth
                    <a class="btn btn-primary" href="{{ route('dashboard') }}">Личный кабинет</a>
                @else
                    <a href="{{ route('login') }}">Войти</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Регистрация</a>
                @endauth
            </nav>
        </header>

        <section class="hero">
            <div class="hero-main">
                <div class="eyebrow">Ателье «Строчка»</div>
                <h1>Пошив, ремонт и подгонка одежды с вниманием к деталям</h1>
                <p class="lead">
                    Мы создаём и ремонтируем одежду с аккуратным подходом и спокойной эстетикой.
                </p>
                @guest
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary">Зарегистрироваться</a>
                    <a href="{{ route('login') }}" class="btn btn-outline">Войти</a>
                </div>
                @endguest
            </div>

            <div class="hero-side">
                <div class="info-block">
                    <h3>Контакты</h3>
                    <p>
                        <strong>Телефон:</strong><br>
                        <a href="tel:+79154395783">+79154395783</a><br><br>
                        <strong>Email:</strong><br>
                        <a href="mailto:strochka@mail.ru">strochka@mail.ru</a><br><br>
                        <strong>Адрес:</strong><br>
                        г. Москва, ул. Земляной Вал, 1/4с2
                    </p>
                </div>

                <div class="info-block">
                    <h3>График работы</h3>
                    <p>
                        Пн–Пт: 10:00–19:00<br>
                        Сб: 10:00–16:00<br>
                        Вс: выходной
                    </p>
                </div>
            </div>
        </section>

        <section class="section" id="about">
            <div class="section-card">
                <div class="eyebrow">О нас</div>
                <h2 class="section-title">Надёжное ателье для повседневных и индивидуальных заказов</h2>
                <p class="muted">
                    Мы выполняем ремонт одежды, корректировку посадки, замену фурнитуры и индивидуальный пошив.
                </p>
            </div>
        </section>

        <section class="section" id="services">
            <div class="eyebrow">Услуги</div>
            <div class="section-grid">
                <div class="section-card">
                    <h3>Ремонт одежды</h3>
                    <p class="muted">Подшив, штопка, замена молний и восстановление швов.</p>
                </div>
                <div class="section-card">
                    <h3>Подгонка по фигуре</h3>
                    <p class="muted">Корректировка длины, ширины и посадки изделий.</p>
                </div>
                <div class="section-card">
                    <h3>Индивидуальный пошив</h3>
                    <p class="muted">Создание изделий по меркам и пожеланиям клиента.</p>
                </div>
            </div>
        </section>

        <section class="section" id="contacts">
            <div class="eyebrow">Контакты</div>
            <div class="contact-grid">
                <div class="contact-item">
                    <strong>Телефон</strong>
                    <a href="tel:+79154395783">+79154395783</a>
                </div>
                <div class="contact-item">
                    <strong>Email</strong>
                    <a href="mailto:strochka@mail.ru">strochka@mail.ru</a>
                </div>
                <div class="contact-item">
                    <strong>Адрес</strong>
                    <span class="muted">г. Москва, ул. Земляной Вал, 1/4с2</span>
                </div>
                <div class="contact-item">
                    <strong>Как попасть в кабинет</strong>
                    <span class="muted">Нажмите “Войти” в верхнем меню.</span>
                </div>
            </div>
        </section>

        <div class="footer">
            © {{ date('Y') }} Ателье «Строчка»
        </div>
    </div>
</body>
</html>