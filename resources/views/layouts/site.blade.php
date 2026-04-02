<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $metaTitle ?? ($siteSettings['seo_default_title'] ?? 'Felestrino Soluções') }}</title>
        <meta name="description" content="{{ $metaDescription ?? ($siteSettings['seo_default_description'] ?? '') }}">
        <meta name="robots" content="index,follow">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ mix('css/site.css') }}">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <a href="{{ route('site.home') }}" class="brand">
                    <span class="brand-mark">F</span>
                    <span>{{ $siteSettings['company_name'] ?? 'Felestrino Soluções' }}</span>
                </a>
                <button class="menu-toggle" type="button" data-menu-toggle>Menu</button>
                <nav class="main-nav" data-main-nav>
                    <a href="{{ route('site.home') }}">Home</a>
                    <a href="{{ route('site.about') }}">Sobre</a>
                    <a href="{{ route('site.services') }}">Serviços</a>
                    <a href="#contato">Contato</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="site-footer" id="contato">
            <div class="container">
                <h2>Contato</h2>
                <p>{{ $siteSettings['company_email'] ?? '' }} | {{ $siteSettings['company_phone'] ?? '' }}</p>
                <p>{{ $siteSettings['company_address'] ?? '' }}</p>
            </div>
        </footer>

        <script src="{{ mix('js/site.js') }}" defer></script>
    </body>
</html>
