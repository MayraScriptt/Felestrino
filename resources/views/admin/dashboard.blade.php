@extends('layouts.admin')

@section('content')
    <h1>Dashboard</h1>
    <div class="stats-grid">
        <article class="stat-card"><strong>{{ $metrics['pages'] }}</strong><span>Paginas</span></article>
        <article class="stat-card"><strong>{{ $metrics['sections'] }}</strong><span>Secoes</span></article>
        <article class="stat-card"><strong>{{ $metrics['services'] }}</strong><span>Servicos</span></article>
        <article class="stat-card"><strong>{{ $metrics['media'] }}</strong><span>Midias</span></article>
    </div>
@endsection
