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
            background: #f7f8fc;
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
            <nav>
                <a href="{{ route('site.home') }}">Home</a>
                <a href="{{ route('site.page', ['slug' => 'sobre']) }}">Sobre</a>
                <a href="#contato" class="nav-cta">Contato</a>
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
