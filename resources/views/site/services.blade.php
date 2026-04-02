@extends('layouts.site')

@section('content')
    <section class="inner-hero">
        <div class="container">
            <h1>Nossos Serviços</h1>
            <p>Soluções técnicas e monitoramento em tempo real para operações críticas.</p>
        </div>
    </section>

    <section class="services-grid">
        <div class="container">
            <div class="cards">
                @foreach ($services as $service)
                    <article class="card">
                        <h2>{{ $service->title }}</h2>
                        <p>{{ $service->excerpt }}</p>
                        <a href="{{ route('site.services.show', $service->slug) }}">Ver detalhes</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
