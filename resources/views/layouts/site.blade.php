<!DOCTYPE html>
<html lang="pt-BR" class="{{ request()->routeIs('site.home') ? 'is-home' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? 'Felestrino Soluções' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Solucoes inteligentes para irrigacao, agua e esgoto.' }}">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="{{ $seo['title'] ?? 'Felestrino Solucoes' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:type" content="website">
    @php
        $cssAsset = file_exists(public_path('mix-manifest.json')) ? mix('/css/app.css') : asset('css/app.css');
        $jsAsset = file_exists(public_path('mix-manifest.json')) ? mix('/js/app.js') : asset('js/app.js');
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $cssAsset }}">
    <style>
        body.site-tone {
            font-family: "DM Sans", "Segoe UI", Roboto, Arial, sans-serif;
            color: #0d1b3e;
            background: #ffffff;
        }

        .site-tone .site-header {
            background: rgba(8, 17, 42, 0.92);
            border-bottom: 1px solid rgba(184, 144, 42, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .site-tone .brand strong {
            font-family: "Rajdhani", sans-serif;
            font-size: 1.1rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #ffffff;
        }

        .site-tone .brand small {
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: .02em;
        }

        .site-tone .brand-icon {
            border-color: #b8902a;
            color: #d4ab4a;
            border-radius: 4px;
            background: rgba(184, 144, 42, 0.08);
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.3rem;
            letter-spacing: .06em;
        }

        .site-tone nav a {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(184, 144, 42, 0.2);
            color: rgba(255, 255, 255, 0.84);
            font-family: "Rajdhani", sans-serif;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .84rem;
            transition: all .2s ease;
        }

        .site-tone nav a:hover {
            background: rgba(184, 144, 42, 0.2);
            border-color: rgba(212, 171, 74, 0.5);
            color: #f0d080;
        }

        .site-tone nav a.nav-cta {
            border-color: #b8902a;
            color: #f0d080;
        }

        .site-nav {
            display: flex;
            align-items: center;
            gap: .65rem;
            position: relative;
        }

        .site-nav__toggle {
            appearance: none;
            border: 1px solid rgba(184, 144, 42, 0.32);
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.92);
            width: 44px;
            height: 44px;
            border-radius: 999px;
            padding: 0;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: background-color .2s ease, border-color .2s ease, transform .15s ease;
        }

        .site-nav__toggle:active {
            transform: translateY(1px);
        }

        .site-nav__toggle:focus-visible {
            outline: 2px solid rgba(240, 208, 128, 0.85);
            outline-offset: 3px;
        }

        .site-nav__bars {
            display: grid;
            gap: 4px;
        }

        .site-nav__bar {
            display: block;
            width: 18px;
            height: 2px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.92);
            transition: transform .2s ease, opacity .2s ease;
            transform-origin: center;
        }

        .site-nav__menu {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .site-nav.is-open .site-nav__bar:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }

        .site-nav.is-open .site-nav__bar:nth-child(2) {
            opacity: 0;
        }

        .site-nav.is-open .site-nav__bar:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        @media (max-width: 767.98px) {
            .site-tone .header-inner {
                flex-direction: row;
                align-items: center;
                flex-wrap: nowrap;
            }

            .site-nav {
                margin-left: auto;
                width: auto;
                flex: 0 0 auto;
            }

            .site-nav__toggle {
                display: inline-flex;
            }

            .site-nav__menu {
                position: absolute;
                top: calc(100% + .65rem);
                left: auto;
                right: 24px;
                display: grid;
                gap: .55rem;
                padding: .75rem;
                border-radius: .95rem;
                background: rgba(8, 17, 42, 0.96);
                border: 1px solid rgba(184, 144, 42, 0.24);
                box-shadow: 0 18px 42px rgba(8, 17, 42, 0.36);
                width: min(320px, calc(100vw - 24px));
                max-height: 0;
                opacity: 0;
                transform: translateY(-8px);
                overflow: hidden;
                pointer-events: none;
                transition: max-height .28s ease, opacity .2s ease, transform .2s ease;
                z-index: 50;
            }

            .site-nav.is-open .site-nav__menu {
                max-height: 60vh;
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
            }

            .site-nav__menu a {
                width: 100%;
                display: block;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .site-nav__toggle,
            .site-nav__bar,
            .site-nav__menu {
                transition: none;
            }
        }

        .site-tone .site-footer {
            background: linear-gradient(180deg, #101d43 0%, #08112a 100%);
            border-top: 3px solid #b8902a;
            color: rgba(255, 255, 255, 0.74);
        }

        .site-tone .site-footer h3,
        .site-tone .site-footer h4 {
            color: #ffffff;
            font-family: "Rajdhani", sans-serif;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
    </style>
</head>
<body class="{{ request()->routeIs('site.home') ? 'is-home' : '' }} site-tone">
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('site.home') }}" class="brand">
                <span class="brand-icon">F</span>
                <span>
                    <strong>{{ $settings['company_name'] ?? 'Felestrino Soluções' }}</strong>
                    <small>{{ $settings['tagline'] ?? '' }}</small>
                </span>
            </a>
            <nav class="site-nav" data-site-nav aria-label="Navegação principal">
                <button
                    class="site-nav__toggle"
                    type="button"
                    data-site-nav-toggle
                    aria-label="Abrir menu"
                    aria-controls="site-nav-menu"
                    aria-expanded="false"
                >
                    <span class="site-nav__bars" aria-hidden="true">
                        <span class="site-nav__bar"></span>
                        <span class="site-nav__bar"></span>
                        <span class="site-nav__bar"></span>
                    </span>
                </button>
                <div class="site-nav__menu" id="site-nav-menu" data-site-nav-menu aria-hidden="true">
                    <a href="{{ route('site.home') }}">Home</a>
                    <a href="{{ route('site.page', ['slug' => 'sobre']) }}">Sobre</a>
                    <a href="{{ route('site.projects') }}">Projetos</a>
                    <a href="#contato" class="nav-cta">Contato</a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="contato" class="site-footer">
        <div class="container footer-grid">
            <div>
                <h3>{{ $settings['company_name'] ?? 'Felestrino Soluções' }}</h3>
                <p>{{ $settings['about'] ?? 'Especialistas em monitoramento hidrico e irrigação.' }}</p>
            </div>
            <div>
                <h4>Contato</h4>
                <p>Telefone: {{ $settings['phone'] ?? '-' }}</p>
                <p>Email: {{ $settings['email'] ?? '-' }}</p>
                <p>{{ $settings['address'] ?? '' }}</p>
            </div>
        </div>
    </footer>

    <script src="{{ $jsAsset }}" defer></script>
</body>
</html>
