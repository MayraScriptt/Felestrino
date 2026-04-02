<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Administrativo</title>
        <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    </head>
    <body class="auth-body">
        <form method="POST" action="{{ route('login.store') }}" class="auth-card">
            @csrf
            <h1>Login Administrativo</h1>
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email')<small>{{ $message }}</small>@enderror
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" required>
            @error('password')<small>{{ $message }}</small>@enderror
            <button type="submit">Entrar</button>
        </form>
    </body>
</html>
