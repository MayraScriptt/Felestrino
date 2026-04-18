<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Painel Administrativo' }}</title>
    @php
        $cssAsset = file_exists(public_path('mix-manifest.json')) ? mix('/css/app.css') : asset('css/app.css');
        $jsAsset = file_exists(public_path('mix-manifest.json')) ? mix('/js/app.js') : asset('js/app.js');
    @endphp
    <link rel="stylesheet" href="{{ $cssAsset }}">
</head>
<body class="admin-body">
    <aside class="admin-sidebar">
        <h2>CMS Felestrino</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.pages.index') }}">Páginas</a>
        <a href="{{ route('admin.sections.index') }}">Seções</a>
        <a href="{{ route('admin.services.index') }}">Serviços</a>
        <a href="{{ route('admin.media.index') }}">Midias</a>
        <a href="{{ route('admin.settings.edit') }}">Configurações</a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary">Sair</button>
        </form>
    </aside>
    <section class="admin-content">
        @if (session('status'))
            <div class="alert-success">{{ session('status') }}</div>
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

    <script src="{{ $jsAsset }}" defer></script>
</body>
</html>
