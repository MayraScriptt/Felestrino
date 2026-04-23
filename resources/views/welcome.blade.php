@extends('layouts.site')

@section('content')
    <style>
        .home-main section.container {
            margin-bottom: 2rem;
        }

        .section-eyebrow {
            font-family: "Rajdhani", sans-serif;
            font-size: .78rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #b8902a;
            margin-bottom: .4rem;
            font-weight: 700;
        }

        .section-title-v2 {
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .05em;
            color: #0d1b3e;
            margin-bottom: .9rem;
        }

        .home-card {
            border-color: rgba(13, 27, 62, 0.1);
            box-shadow: 0 8px 24px rgba(13, 27, 62, 0.06);
        }

        .home-card__icon {
            background: rgba(184, 144, 42, .12);
            color: #8a6b1f;
        }

        .hero-carousel__scroll {
            border-color: rgba(212, 171, 74, .38);
        }
    </style>

    @php
        $resolveImageSrc = static function (?string $path): string {
            $path = is_string($path) ? trim($path) : '';
            if ($path === '') {
                return asset('images/hero.jpg');
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            if (str_starts_with($path, '/')) {
                return $path;
            }
            if (str_starts_with($path, 'images/')) {
                return asset($path);
            }

            return asset('storage/'.$path);
        };

        $transparentPixel = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

        $slides = collect();

        if (($homeCarousel ?? collect())->isNotEmpty()) {
            foreach ($homeCarousel as $item) {
                $title = is_string($item->title) ? trim($item->title) : '';
                $subtitle = is_string($item->subtitle) ? trim($item->subtitle) : '';
                $buttonText = is_string($item->button_text) ? trim($item->button_text) : '';
                $buttonUrl = is_string($item->button_url) ? trim($item->button_url) : '';
                $slides->push([
                    'src' => $resolveImageSrc($item->image_path),
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'alt' => $title !== '' ? $title : 'Banner',
                    'href' => $item->link_url ?: null,
                    'button_text' => $buttonText,
                    'button_url' => $buttonUrl,
                ]);
            }
        } else {
            $slides->push([
                'src' => asset('images/hero.jpg'),
                'title' => '',
                'subtitle' => '',
                'alt' => 'Banner',
                'href' => null,
                'button_text' => '',
                'button_url' => '',
            ]);

            foreach ($gallery as $item) {
                if (! $item?->file_path) {
                    continue;
                }
                $title = is_string($item->title) && trim($item->title) !== '' ? trim($item->title) : 'Banner';
                $slides->push([
                    'src' => asset('storage/'.$item->file_path),
                    'title' => $title,
                    'subtitle' => '',
                    'alt' => $title,
                    'href' => $item->link_url ?: null,
                    'button_text' => '',
                    'button_url' => '',
                ]);
            }
        }

        $slides = $slides
            ->unique('src')
            ->values()
            ->take(6);

        $initialTitle = trim((string) ($slides[0]['title'] ?? ''));
        $initialSubtitle = trim((string) ($slides[0]['subtitle'] ?? ''));
        $initialButtonText = trim((string) ($slides[0]['button_text'] ?? ''));
        $initialButtonUrl = trim((string) ($slides[0]['button_url'] ?? ''));
        $initialButtonHref = $initialButtonUrl !== '' ? $initialButtonUrl : '#home-main';
        $hasHeroCopy = $initialTitle !== '' || $initialSubtitle !== '' || $initialButtonText !== '';
    @endphp

    <section class="hero-carousel" data-carousel="hero" aria-roledescription="carrossel">
        <div class="hero-carousel__slides" aria-label="Banner principal" role="region">
            @foreach ($slides as $index => $slide)
                <div class="hero-carousel__slide @if ($index === 0) is-active @endif" data-carousel-slide data-carousel-title="{{ $slide['title'] ?? '' }}" data-carousel-subtitle="{{ $slide['subtitle'] ?? '' }}" data-carousel-button-text="{{ $slide['button_text'] ?? '' }}" data-carousel-button-url="{{ $slide['button_url'] ?? '' }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                    @if (! empty($slide['href']))
                        <a class="hero-carousel__slide-link" href="{{ $slide['href'] }}" target="_blank" rel="noopener noreferrer">
                            <img
                                class="hero-carousel__img"
                                alt="{{ $slide['alt'] }}"
                                @if ($index === 0)
                                    src="{{ $slide['src'] }}"
                                    loading="eager"
                                    fetchpriority="high"
                                @else
                                    src="{{ $transparentPixel }}"
                                    data-src="{{ $slide['src'] }}"
                                    loading="lazy"
                                @endif
                                decoding="async"
                            >
                        </a>
                    @else
                        <img
                            class="hero-carousel__img"
                            alt="{{ $slide['alt'] }}"
                            @if ($index === 0)
                                src="{{ $slide['src'] }}"
                                loading="eager"
                                fetchpriority="high"
                            @else
                                src="{{ $transparentPixel }}"
                                data-src="{{ $slide['src'] }}"
                                loading="lazy"
                            @endif
                            decoding="async"
                        >
                    @endif
                </div>
            @endforeach
        </div>

        <div class="hero-carousel__content">
            <div class="container hero-carousel__copy" data-hero-copy @if (! $hasHeroCopy) hidden @endif>
                <h1 data-hero-title data-reveal data-reveal-delay="0" @if ($initialTitle === '') hidden @endif>{{ $initialTitle }}</h1>
                <p data-hero-subtitle data-reveal data-reveal-delay="120" @if ($initialSubtitle === '') hidden @endif>{{ $initialSubtitle }}</p>
                @if ($initialButtonText !== '')
                    <a class="hero-carousel__scroll" href="{{ $initialButtonHref }}" @if ($initialButtonHref === '#home-main') data-scroll-next @endif data-hero-button data-reveal data-reveal-delay="240">{{ $initialButtonText }}</a>
                @endif
            </div>
        </div>

        <button class="hero-carousel__arrow hero-carousel__arrow--prev" type="button" data-carousel-prev aria-label="Slide anterior">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button class="hero-carousel__arrow hero-carousel__arrow--next" type="button" data-carousel-next aria-label="Próximo slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="hero-carousel__dots" role="tablist" aria-label="Selecionar slide">
            @foreach ($slides as $index => $slide)
                <button
                    class="hero-carousel__dot @if ($index === 0) is-active @endif"
                    type="button"
                    data-carousel-dot="{{ $index }}"
                    aria-label="Ir para o slide {{ $index + 1 }}"
                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                ></button>
            @endforeach
        </div>
    </section>

    <div id="home-main" class="home-main">
        @if (($homeCards ?? collect())->isNotEmpty())
            <section class="container">
                <div class="section-eyebrow" data-reveal data-reveal-delay="0">Destaques</div>
                <h2 class="section-title-v2" data-reveal data-reveal-delay="100">Cards Informativos</h2>
                <div class="home-cards">
                    @foreach ($homeCards as $card)
                        <article class="home-card" data-reveal data-reveal-delay="{{ 160 + ($loop->index * 90) }}">
                            @if ($card->link_url)
                                <a href="{{ $card->link_url }}" target="_blank" rel="noopener noreferrer" class="home-card__link">
                                    @if ($card->icon)
                                        <div class="home-card__icon">{{ $card->icon }}</div>
                                    @endif
                                    <h2 class="home-card__title">{{ $card->title }}</h2>
                                    @if ($card->description)
                                        <p class="home-card__description">{{ $card->description }}</p>
                                    @endif
                                </a>
                            @else
                                @if ($card->icon)
                                    <div class="home-card__icon">{{ $card->icon }}</div>
                                @endif
                                <h2 class="home-card__title">{{ $card->title }}</h2>
                                @if ($card->description)
                                    <p class="home-card__description">{{ $card->description }}</p>
                                @endif
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
