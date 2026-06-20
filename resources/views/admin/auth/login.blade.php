<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Administrativo</title>
    @php
        $cssAsset = file_exists(public_path('mix-manifest.json')) ? mix('/css/app.css') : asset('css/app.css');
    @endphp
    <link rel="stylesheet" href="{{ $cssAsset }}">
</head>
<body class="login-body">
    <form method="POST" action="{{ route('admin.login.store') }}" class="login-card">
        @csrf
        <h1>Painel Administrativo</h1>
        <label>Email
            <input type="email" name="email" required value="{{ old('email') }}">
        </label>
        <label>Senha
            <input type="password" name="password" required>
        </label>
        @if ($errors->any())
            <p class="alert-error">{{ $errors->first() }}</p>
        @endif
        <button type="submit" class="btn">Entrar</button>
    </form>
</body>
</html>
