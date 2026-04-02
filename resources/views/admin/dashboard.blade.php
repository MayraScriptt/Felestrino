@extends('layouts.admin')

@section('content')
    <h1>Dashboard</h1>
    <div class="admin-stats">
        <div class="stat-card">Páginas: {{ $stats['pages'] }}</div>
        <div class="stat-card">Seções: {{ $stats['sections'] }}</div>
        <div class="stat-card">Serviços: {{ $stats['services'] }}</div>
        <div class="stat-card">Mídias: {{ $stats['media'] }}</div>
    </div>
@endsection
