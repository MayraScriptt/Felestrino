@extends('layouts.site')

@section('content')
    @php
        $heroImage = $settings['hero_image_url'] ?? null;
        $heroImage = is_string($heroImage) ? trim($heroImage) : '';
        $heroImage = $heroImage !== '' ? $heroImage : '/images/hero.jpg';

        if (str_starts_with($heroImage, 'http')) {
            $heroImageSrc = $heroImage;
        } elseif (str_starts_with($heroImage, '/')) {
            $heroImageSrc = $heroImage;
        } else {
            $heroImageSrc = asset($heroImage);
        }
    @endphp

    <section class="hero hero-image" style="--hero-image: url('{{ $heroImageSrc }}');">
        <div class="container hero-inner">
            <h1>{{ $settings['hero_title'] ?? 'Soluções inteligentes para irrigação e pivos' }}</h1>
            <p>{{ $settings['hero_subtitle'] ?? '' }}</p>
        </div>
    </section>

    <section class="container section-grid">
        @foreach ($homePage?->sections ?? [] as $section)
            <article class="card">
                @if ($section->image_path)
                    <img src="{{ asset('storage/'.$section->image_path) }}" alt="{{ $section->title }}" loading="lazy">
                @endif
                <h2>{{ $section->title }}</h2>
                <p>{{ $section->subtitle }}</p>
                <div>{!! nl2br(e($section->content)) !!}</div>
            </article>
        @endforeach
    </section>

    <section id="servicos" class="container">
        <h2>Serviços</h2>
        <div class="section-grid">
            @foreach ($services as $service)
                <article class="card">
                    @if ($service->image_path)
                        <img src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->title }}" loading="lazy">
                    @endif
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->short_description }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="container">
        <h2>Galeria de Midias</h2>
        <div class="gallery-grid">
            @foreach ($gallery as $item)
                <figure>
                    <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->title }}" loading="lazy">
                    <figcaption>{{ $item->title }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endsection
