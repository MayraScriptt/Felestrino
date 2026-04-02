@extends('layouts.site')

@section('content')
    <section class="hero">
        <div class="container">
            <h1>{{ $page?->hero_title ?? 'Soluções Inteligentes para Irrigação e Saneamento' }}</h1>
            <p>{{ $page?->hero_subtitle }}</p>
            <a class="btn-primary" href="{{ route('site.services') }}">Conheça os serviços</a>
        </div>
    </section>

    <section class="services-grid">
        <div class="container">
            <h2>Serviços Especializados</h2>
            <div class="cards">
                @foreach ($services as $service)
                    <article class="card">
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->excerpt }}</p>
                        <a href="{{ route('site.services.show', $service->slug) }}">Saiba mais</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="gallery">
        <div class="container">
            <h2>Galeria</h2>
            @foreach ($gallery as $category)
                <h3>{{ $category->name }}</h3>
                <div class="gallery-grid">
                    @foreach ($category->mediaItems as $item)
                        @if ($item->type === 'image')
                            <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->alt_text ?: $item->title }}" loading="lazy">
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endsection
