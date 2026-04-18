<!DOCTYPE html>
<html lang="pt-BR">
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
    <link rel="stylesheet" href="{{ $cssAsset }}">
</head>
<body class="{{ request()->routeIs('site.home') ? 'is-home' : '' }}">
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
                <a href="#servicos">Serviços</a>
                <a href="#contato">Contato</a>
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
