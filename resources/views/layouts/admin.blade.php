<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Painel Administrativo' }}</title>
    @php
        $cssAsset = file_exists(public_path('mix-manifest.json')) ? mix('/css/app.css') : asset('css/app.css');
        $jsAsset = file_exists(public_path('mix-manifest.json')) ? mix('/js/app.js') : asset('js/app.js');
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $cssAsset }}">
    <style>
        :root {
            color-scheme: light;
            --navy: #0d1b3e;
            --navy-deep: #08112a;
            --navy-mid: #142150;
            --gold: #b8902a;
            --gold-light: #d4ab4a;
            --text: #0d1b3e;
            --text-muted: #667294;
            --panel: #ffffff;
            --bg: #f5f7fc;
            --border: rgba(13, 27, 62, 0.1);
            --shadow-soft: 0 14px 40px rgba(8, 17, 42, 0.08);
            --shadow-hard: 0 22px 48px rgba(8, 17, 42, 0.14);
            --success-bg: #ecfdf5;
            --success-text: #065f46;
            --error-bg: #fef2f2;
            --error-text: #991b1b;
        }

        * {
            box-sizing: border-box;
        }

        body.admin-body {
            margin: 0;
            display: block;
            width: 100%;
            min-height: 100vh;
            font-family: "DM Sans", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(1000px 520px at -10% -10%, rgba(212, 171, 74, 0.12) 0%, transparent 48%),
                radial-gradient(900px 450px at 120% -20%, rgba(20, 33, 80, 0.11) 0%, transparent 45%),
                var(--bg);
        }

        .admin-app {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            min-height: 100vh;
        }

        .admin-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(2px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
            z-index: 15;
        }

        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: min(86vw, 320px);
            background:
                radial-gradient(circle at 100% 0%, rgba(212, 171, 74, 0.15), transparent 45%),
                linear-gradient(160deg, var(--navy-deep), var(--navy));
            color: #fff;
            padding: 1.5rem 1rem;
            transform: translateX(-100%);
            transition: transform .25s ease;
            z-index: 20;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            box-shadow: var(--shadow-hard);
        }

        .admin-sidebar-brand {
            display: flex;
            flex-direction: column;
            gap: .3rem;
            padding: .5rem;
            border-bottom: 1px solid rgba(212, 171, 74, 0.24);
            padding-bottom: 1rem;
        }

        .admin-sidebar-brand h2 {
            margin: 0;
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.65rem;
            letter-spacing: .08em;
        }

        .admin-sidebar-brand p {
            margin: 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: .82rem;
            letter-spacing: .02em;
        }

        .admin-nav {
            display: grid;
            gap: .5rem;
        }

        .admin-nav-link {
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .8rem .9rem;
            border-radius: .55rem;
            font-weight: 600;
            font-size: .9rem;
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
            border: 1px solid rgba(212, 171, 74, 0.08);
            transition: background-color .2s ease, color .2s ease, border-color .2s ease;
        }

        .admin-nav-link::before {
            content: "";
            width: .5rem;
            height: .5rem;
            border-radius: 2px;
            background: var(--gold);
            box-shadow: 0 0 0 4px rgba(212, 171, 74, .2);
        }

        .admin-nav-link:hover,
        .admin-nav-link:focus-visible {
            background: rgba(212, 171, 74, 0.12);
            border-color: rgba(212, 171, 74, 0.35);
            color: #fff;
        }

        .admin-sidebar-actions {
            margin-top: auto;
            padding: .5rem;
        }

        .admin-btn {
            appearance: none;
            border: none;
            border-radius: .45rem;
            padding: .72rem 1rem;
            font-weight: 700;
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .07em;
            text-transform: uppercase;
            font-size: .82rem;
            cursor: pointer;
            transition: transform .1s ease, filter .2s ease;
        }

        .admin-btn:active {
            transform: translateY(1px);
        }

        .admin-btn-ghost {
            width: 100%;
            background: rgba(212, 171, 74, 0.12);
            color: #f8df95;
            border: 1px solid rgba(212, 171, 74, 0.28);
        }

        .admin-btn-ghost:hover,
        .admin-btn-ghost:focus-visible {
            filter: brightness(1.15);
        }

        .admin-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            padding: 1rem;
        }

        .admin-topbar {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(212, 171, 74, 0.2);
            border-radius: .8rem;
            backdrop-filter: blur(6px);
            box-shadow: var(--shadow-soft);
            padding: .85rem .95rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .admin-menu-btn {
            width: 42px;
            height: 42px;
            border-radius: .45rem;
            border: 1px solid rgba(212, 171, 74, 0.28);
            background: linear-gradient(180deg, #ffffff, #f8f9fd);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--navy);
        }

        .admin-page-info {
            min-width: 0;
        }

        .admin-page-info h1 {
            margin: 0;
            font-family: "Rajdhani", sans-serif;
            font-size: 1.04rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        .admin-page-info p {
            margin: .15rem 0 0;
            color: var(--text-muted);
            font-size: .8rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-content {
            min-width: 0;
            width: 100%;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: .8rem;
            box-shadow: var(--shadow-soft);
            padding: 1rem;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1rem;
            overflow: hidden;
        }

        .admin-content h1,
        .admin-content h2,
        .admin-content h3 {
            margin: 0;
            color: var(--navy);
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .admin-content h1 {
            font-size: clamp(1.55rem, 2.2vw, 2rem);
        }

        .admin-content h2 {
            font-size: 1.03rem;
        }

        .admin-content .admin-pages-head {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(13, 27, 62, 0.08);
            padding-bottom: .75rem;
        }

        .admin-content .admin-home-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-content .admin-home-col {
            display: grid;
            gap: 1rem;
        }

        .admin-surface {
            background: linear-gradient(180deg, #ffffff, #fbfcff);
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .8rem;
            padding: .95rem;
            box-shadow: 0 8px 26px rgba(8, 17, 42, 0.06);
            display: grid;
            gap: .85rem;
        }

        .admin-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
            border-bottom: 1px dashed rgba(13, 27, 62, 0.15);
            padding-bottom: .55rem;
        }

        .admin-section-kicker {
            font-family: "Rajdhani", sans-serif;
            font-size: .74rem;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: .16em;
            font-weight: 700;
        }

        .admin-content .admin-autosave {
            font-weight: 700;
            font-size: .8rem;
            color: var(--navy);
            background: rgba(212, 171, 74, 0.1);
            border: 1px solid rgba(212, 171, 74, 0.28);
            border-radius: 999px;
            padding: .35rem .7rem;
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .admin-content .admin-form {
            display: grid;
            gap: .8rem;
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .admin-content label {
            display: grid;
            gap: .35rem;
            font-size: .84rem;
            color: var(--text-muted);
            font-family: "Rajdhani", sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .admin-content input,
        .admin-content textarea,
        .admin-content select {
            width: 100%;
            border: 1px solid rgba(13, 27, 62, 0.15);
            border-radius: .45rem;
            padding: .62rem .72rem;
            background: #ffffff;
            color: var(--text);
            font-family: "DM Sans", sans-serif;
            font-size: .92rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .admin-content input:focus,
        .admin-content textarea:focus,
        .admin-content select:focus {
            border-color: rgba(184, 144, 42, 0.7);
            box-shadow: 0 0 0 3px rgba(184, 144, 42, 0.14);
        }

        .admin-content .checkbox-line {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: var(--text);
        }

        .admin-content .checkbox-line input {
            width: auto;
            accent-color: var(--gold);
        }

        .admin-content .btn {
            background: linear-gradient(180deg, var(--gold-light), var(--gold));
            color: var(--navy-deep);
            border: 0;
            border-radius: .45rem;
            padding: .66rem .95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: "Rajdhani", sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: filter .2s ease, transform .12s ease;
        }

        .admin-content .btn:hover,
        .admin-content .btn:focus-visible {
            filter: brightness(1.05);
        }

        .admin-content .btn:active {
            transform: translateY(1px);
        }

        .admin-content .btn-secondary {
            background: linear-gradient(180deg, #1f2b52, #142150);
            color: #fff;
            width: auto;
            margin-top: 0;
        }

        .admin-content .admin-home-upload {
            display: grid;
            gap: .65rem;
            align-items: center;
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-content .admin-home-upload__hint {
            font-size: .78rem;
            color: var(--text-muted);
        }

        .admin-content .admin-home-list {
            display: grid;
            gap: .8rem;
        }

        .admin-content .admin-home-item {
            background: #fff;
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .7rem;
            padding: .75rem;
            display: grid;
            gap: .7rem;
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-content .admin-home-thumb {
            width: 100%;
            max-width: 130px;
            height: 88px;
            object-fit: cover;
            border-radius: .55rem;
            border: 1px solid rgba(13, 27, 62, 0.08);
            box-shadow: none;
        }

        .admin-content .admin-home-thumb--placeholder {
            width: 100%;
            max-width: 130px;
            height: 88px;
            border-radius: .55rem;
            background: rgba(20, 33, 80, 0.04);
            border: 1px dashed rgba(13, 27, 62, 0.18);
        }

        .admin-content .admin-home-fields {
            display: grid;
            gap: .55rem;
        }

        .admin-content .admin-home-actions {
            display: flex;
            align-items: flex-start;
        }

        .admin-content .admin-home-cards-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .admin-content .admin-hero-preview-wrap {
            display: grid;
            gap: .7rem;
        }

        .admin-content .admin-hero-preview-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .admin-content .admin-hero-preview-head strong {
            font-family: "Rajdhani", sans-serif;
            text-transform: uppercase;
            letter-spacing: .07em;
            font-size: .8rem;
            color: var(--text-muted);
        }

        .admin-content .admin-hero-preview-actions {
            display: flex;
            gap: .55rem;
            flex-wrap: wrap;
        }

        .admin-content .admin-hero-preview {
            position: relative;
            border-radius: .8rem;
            overflow: hidden;
            height: 220px;
            background: var(--navy-deep);
            border: 1px solid rgba(13, 27, 62, 0.2);
        }

        .admin-content .admin-hero-preview__img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-content .admin-hero-preview::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(8, 17, 42, 0.15), rgba(8, 17, 42, 0.88));
            z-index: 1;
            pointer-events: none;
        }

        .admin-content .admin-hero-preview__overlay {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 2;
            color: #fff;
            padding: .9rem;
            display: grid;
            gap: .3rem;
        }

        .admin-content .admin-hero-preview__title {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
            max-width: 30ch;
        }

        .admin-content .admin-hero-preview__subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: .9rem;
        }

        .admin-content .admin-home-item.is-selected {
            outline: 2px solid rgba(184, 144, 42, 0.45);
            outline-offset: 2px;
        }

        .admin-content .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            margin: 0;
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .65rem;
            overflow: hidden;
            font-size: .9rem;
        }

        .admin-content .admin-table th,
        .admin-content .admin-table td {
            border-bottom: 1px solid rgba(13, 27, 62, 0.08);
            padding: .65rem;
            text-align: left;
            vertical-align: top;
        }

        .admin-content .admin-table th {
            font-family: "Rajdhani", sans-serif;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .75rem;
            color: var(--text-muted);
            background: rgba(13, 27, 62, 0.03);
        }

        .alert-success,
        .alert-error {
            border-radius: .65rem;
            padding: .75rem .85rem;
            font-size: .9rem;
            font-weight: 600;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid #fecaca;
        }

        .alert-error p {
            margin: .2rem 0;
        }

        .admin-body.menu-open .admin-sidebar {
            transform: translateX(0);
        }

        .admin-body.menu-open .admin-menu-overlay {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 992px) {
            .admin-app {
                grid-template-columns: 280px minmax(0, 1fr);
            }

            .admin-sidebar {
                position: sticky;
                transform: none;
                width: auto;
                height: 100vh;
            }

            .admin-menu-btn,
            .admin-menu-overlay {
                display: none;
            }

            .admin-main {
                padding: 1.25rem 1.25rem 1.25rem .75rem;
            }

            .admin-content .admin-home-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }

            .admin-content .admin-home-upload {
                grid-template-columns: minmax(0, 1fr) auto;
            }

            .admin-content .admin-home-upload__hint {
                grid-column: 1 / -1;
            }

            .admin-content .admin-home-item {
                grid-template-columns: 130px minmax(0, 1fr) auto;
                align-items: start;
            }

            .admin-content .admin-home-actions {
                min-width: 120px;
                justify-content: flex-end;
            }
        }

        @media (max-width: 768px) {
            .admin-main {
                padding: .75rem;
            }

            .admin-content {
                padding: .8rem;
            }

            .admin-section-head {
                align-items: flex-start;
            }

            .admin-content .admin-hero-preview {
                height: 190px;
            }

            .admin-content .admin-home-thumb,
            .admin-content .admin-home-thumb--placeholder {
                max-width: 100%;
                height: 170px;
            }

            .admin-content .admin-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-app">
        <div class="admin-menu-overlay" data-menu-overlay></div>

        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="admin-sidebar-brand">
                <h2>CMS Felestrino</h2>
                <p>Painel de gerenciamento da Home</p>
            </div>

            <nav class="admin-nav" aria-label="Navegação administrativa">
                <a class="admin-nav-link" href="{{ route('admin.home.edit') }}">Home</a>
                <a class="admin-nav-link" href="{{ route('admin.about-company.edit') }}">Sobre a empresa</a>
                <a class="admin-nav-link" href="{{ route('admin.projects.edit') }}">Projetos</a>
            </nav>

            <div class="admin-sidebar-actions">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-ghost">Sair do painel</button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <button class="admin-menu-btn admin-btn" type="button" data-menu-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Abrir menu">
                    ☰
                </button>
                <div class="admin-page-info">
                    <h1>{{ $title ?? 'Painel Administrativo' }}</h1>
                </div>
            </header>

            <section class="admin-content">
                @if (session('status'))
                    <div class="alert-success">{{ session('status') }}</div>
                @endif
                @if (! empty($loadErrors))
                    <div class="alert-error">
                        @foreach ($loadErrors as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </section>
        </main>
    </div>

    <script src="{{ $jsAsset }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var body = document.body;
            var toggleButton = document.querySelector('[data-menu-toggle]');
            var overlay = document.querySelector('[data-menu-overlay]');

            if (!toggleButton || !overlay) {
                return;
            }

            function closeMenu() {
                body.classList.remove('menu-open');
                toggleButton.setAttribute('aria-expanded', 'false');
            }

            function openMenu() {
                body.classList.add('menu-open');
                toggleButton.setAttribute('aria-expanded', 'true');
            }

            toggleButton.addEventListener('click', function () {
                if (body.classList.contains('menu-open')) {
                    closeMenu();
                    return;
                }
                openMenu();
            });

            overlay.addEventListener('click', closeMenu);

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    closeMenu();
                }
            });
        });
    </script>
</body>
</html>
