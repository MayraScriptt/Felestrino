@extends('layouts.admin')

@section('content')
    <h1>Configurações do Site</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <label>Nome da Empresa</label>
        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
        <label>E-mail</label>
        <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email'] ?? '') }}" required>
        <label>Telefone</label>
        <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" required>
        <label>WhatsApp</label>
        <input type="text" name="company_whatsapp" value="{{ old('company_whatsapp', $settings['company_whatsapp'] ?? '') }}">
        <label>Endereço</label>
        <input type="text" name="company_address" value="{{ old('company_address', $settings['company_address'] ?? '') }}">
        <label>Resumo Institucional</label>
        <textarea name="about_summary">{{ old('about_summary', $settings['about_summary'] ?? '') }}</textarea>
        <label>Vídeo Hero (URL)</label>
        <input type="url" name="hero_video_url" value="{{ old('hero_video_url', $settings['hero_video_url'] ?? '') }}">
        <label>SEO Título Padrão</label>
        <input type="text" name="seo_default_title" value="{{ old('seo_default_title', $settings['seo_default_title'] ?? '') }}" required>
        <label>SEO Descrição Padrão</label>
        <textarea name="seo_default_description" required>{{ old('seo_default_description', $settings['seo_default_description'] ?? '') }}</textarea>
        <button type="submit">Salvar Configurações</button>
    </form>
@endsection
