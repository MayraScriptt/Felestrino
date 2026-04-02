<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Painel Administrativo</title>
        <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    </head>
    <body class="admin-body">
        <aside class="admin-sidebar">
            <h1>Felestrino</h1>
            <nav>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.pages.index') }}">Páginas</a>
                <a href="{{ route('admin.sections.index') }}">Seções</a>
                <a href="{{ route('admin.services.index') }}">Serviços</a>
                <a href="{{ route('admin.media-categories.index') }}">Categorias</a>
                <a href="{{ route('admin.media-items.index') }}">Mídias</a>
                <a href="{{ route('admin.settings.index') }}">Configurações</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Sair</button>
            </form>
        </aside>
        <div class="admin-main">
            @if (session('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif
            @yield('content')
        </div>
        <script src="{{ mix('js/admin.js') }}" defer></script>
    </body>
</html>
