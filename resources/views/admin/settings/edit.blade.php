@extends('layouts.admin')

@section('content')
    <h1>Configuracoes do Site</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-form">
        @csrf @method('PUT')
        <label>Nome da empresa
            <input type="text" name="company_name" required value="{{ old('company_name', $settings['company_name'] ?? '') }}">
        </label>
        <label>Slogan
            <input type="text" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}">
        </label>
        <label>Telefone
            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}">
        </label>
        <label>Email
            <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}">
        </label>
        <label>Endereco
            <input type="text" name="address" value="{{ old('address', $settings['address'] ?? '') }}">
        </label>
        <label>Sobre
            <textarea name="about" rows="4">{{ old('about', $settings['about'] ?? '') }}</textarea>
        </label>
        <label>Titulo da Home
            <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? '') }}">
        </label>
        <label>Subtitulo da Home
            <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}">
        </label>
        <label>Imagem da Home (URL ou /caminho)
            <input type="text" name="hero_image_url" value="{{ old('hero_image_url', $settings['hero_image_url'] ?? '/images/hero.jpg') }}">
        </label>
        <label>SEO Title
            <input type="text" name="seo_title" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}">
        </label>
        <label>SEO Description
            <textarea name="seo_description" rows="2">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
        </label>
        <button class="btn" type="submit">Salvar configuracoes</button>
    </form>
@endsection
