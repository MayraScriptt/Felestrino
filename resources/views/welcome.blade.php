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

        .home-card__actions {
            margin-top: .9rem;
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .home-card__more {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid rgba(13, 27, 62, 0.14);
            background: #fff;
            color: #0d1b3e;
            font-family: "Rajdhani", sans-serif;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            text-decoration: none;
            padding: .5rem 1rem;
            transition: all .2s ease;
        }

        .home-card__more:hover {
            border-color: rgba(184, 144, 42, 0.7);
            background: #f8e8bf;
            color: #0d1b3e;
        }

        .home-card-details {
            display: grid;
            gap: 0;
            margin-top: 1.75rem;
        }

        .home-card-detail {
            padding: clamp(2.4rem, 6vw, 4.2rem) 0;
        }

        .home-card-detail--light {
            background: #f7f9fc;
            color: #0d1b3e;
        }

        .home-card-detail--dark {
            background: #0d1b3e;
            color: #ffffff;
        }

        .home-card-detail__inner {
            display: grid;
            gap: 1.4rem;
            align-items: center;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .home-card-detail__eyebrow {
            font-family: "Rajdhani", sans-serif;
            font-size: .78rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            margin-bottom: .35rem;
            font-weight: 700;
        }

        .home-card-detail--light .home-card-detail__eyebrow {
            color: #8a6b1f;
        }

        .home-card-detail--dark .home-card-detail__eyebrow {
            color: #d4ab4a;
        }

        .home-card-detail__title {
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .05em;
            line-height: 1;
            font-size: clamp(1.9rem, 4.4vw, 3rem);
            margin-bottom: .85rem;
        }

        .home-card-detail__subtitle {
            font-family: "Rajdhani", sans-serif;
            font-size: clamp(1.1rem, 2.2vw, 1.45rem);
            line-height: 1.25;
            margin-bottom: .95rem;
        }

        .home-card-detail__body {
            white-space: pre-line;
            line-height: 1.72;
            font-size: 1rem;
            max-width: 62ch;
        }

        .home-card-detail__media img {
            width: 100%;
            display: block;
            border-radius: .8rem;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 12px 30px rgba(8, 17, 42, 0.22);
        }

        .home-card-detail--light .home-card-detail__media img {
            border-color: rgba(13, 27, 62, 0.12);
            box-shadow: 0 14px 28px rgba(13, 27, 62, 0.1);
        }

        .home-card-detail__caption {
            margin-top: .4rem;
            font-size: .8rem;
            opacity: .74;
        }

        .hero-carousel__scroll {
            border-color: rgba(212, 171, 74, .38);
        }

        .hero-carousel {
            margin-top: var(--site-header-offset, 0px);
            height: calc(100vh - var(--site-header-offset, 0px));
            height: calc(100svh - var(--site-header-offset, 0px));
            height: calc(100dvh - var(--site-header-offset, 0px));
        }

        .home-contact-cta {
            padding: clamp(2.6rem, 6vw, 4.2rem) 0;
        }

        .home-contact-cta__card {
            max-width: 860px;
            margin: 0 auto;
            border: 1px solid rgba(13, 27, 62, 0.12);
            border-radius: 1rem;
            padding: clamp(1.8rem, 4vw, 2.4rem);
            background: linear-gradient(180deg, #ffffff, #f7f9fc);
            box-shadow: 0 14px 32px rgba(13, 27, 62, 0.08);
            text-align: center;
        }

        .home-contact-cta__card h2 {
            margin: 0 0 .8rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .05em;
            color: #0d1b3e;
            font-size: clamp(2rem, 4vw, 2.8rem);
            line-height: 1;
        }

        .home-contact-cta__card p {
            margin: 0 auto 1.25rem;
            max-width: 64ch;
            line-height: 1.7;
            font-size: 1.05rem;
            color: rgba(13, 27, 62, 0.8);
        }

        .home-contact-cta__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: .75rem 1.35rem;
            background: #1f5c2e;
            color: #ffffff;
            font-family: "Rajdhani", sans-serif;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
            border: 1px solid rgba(31, 92, 46, 0.4);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .home-contact-cta__btn:hover {
            background: #1b5329;
            box-shadow: 0 12px 26px rgba(31, 92, 46, 0.18);
            transform: translateY(-1px);
        }
    </style>

    @php
        $resolveImageSrc = static function (?string $path): string {
            $path = is_string($path) ? trim($path) : '';
            if ($path === '') {
                return asset('imagens/hero.jpg');
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            if (str_starts_with($path, '/')) {
                return $path;
            }
            if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/')) {
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
                'src' => asset('imagens/hero.jpg'),
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
        $detailCards = ($homeCards ?? collect())
            ->filter(function ($card) {
                if (! ($card->detail_enabled ?? false)) {
                    return false;
                }
                $title = trim((string) ($card->detail_title ?? ''));
                $subtitle = trim((string) ($card->detail_subtitle ?? ''));
                $body = trim((string) ($card->detail_body ?? ''));
                $image = trim((string) ($card->detail_image_path ?? ''));

                return $title !== '' || $subtitle !== '' || $body !== '' || $image !== '';
            })
            ->values();
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
                
                <h2 class="section-title-v2" data-reveal data-reveal-delay="100">Destaques</h2>
                <div class="home-cards">
                    @foreach ($homeCards as $card)
                        @php
                            $hasDetailSection = $detailCards->contains(fn ($detailCard) => (int) $detailCard->id === (int) $card->id);
                            $detailTarget = 'card-detail-'.$card->id;
                            $detailButtonText = trim((string) ($card->detail_button_text ?? '')) !== '' ? trim((string) ($card->detail_button_text ?? '')) : 'Ver mais';
                        @endphp
                        <article class="home-card" data-reveal data-reveal-delay="{{ 160 + ($loop->index * 90) }}">
                            @if ($card->icon)
                                <div class="home-card__icon">{{ $card->icon }}</div>
                            @endif
                            <h2 class="home-card__title">{{ $card->title }}</h2>
                            @if ($card->description)
                                <p class="home-card__description">{{ $card->description }}</p>
                            @endif
                            @if ($hasDetailSection || $card->link_url)
                                <div class="home-card__actions">
                                    @if ($hasDetailSection)
                                        <a href="#{{ $detailTarget }}" class="home-card__more" data-scroll-next>{{ $detailButtonText }}</a>
                                    @endif
                                    @if ($card->link_url)
                                        <a href="{{ $card->link_url }}" target="_blank" rel="noopener noreferrer" class="home-card__more">Acessar link</a>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($detailCards->isNotEmpty())
            <section class="home-card-details">
                @foreach ($detailCards as $detailCard)
                    @php
                        $themeClass = $loop->odd ? 'home-card-detail--light' : 'home-card-detail--dark';
                        $imageSrc = trim((string) ($detailCard->detail_image_path ?? '')) !== '' ? $resolveImageSrc($detailCard->detail_image_path) : null;
                        $detailTitle = trim((string) ($detailCard->detail_title ?? '')) !== '' ? $detailCard->detail_title : $detailCard->title;
                    @endphp
                    <article id="card-detail-{{ $detailCard->id }}" class="home-card-detail {{ $themeClass }}">
                        <div class="container home-card-detail__inner">
                            <div class="home-card-detail__copy">
                                <h3 class="home-card-detail__title">{{ $detailTitle }}</h3>
                                @if ($detailCard->detail_subtitle)
                                    <p class="home-card-detail__subtitle">{{ $detailCard->detail_subtitle }}</p>
                                @endif
                                @if ($detailCard->detail_body)
                                    <p class="home-card-detail__body">{{ $detailCard->detail_body }}</p>
                                @endif
                            </div>
                            @if ($imageSrc)
                                <figure class="home-card-detail__media">
                                    <img src="{{ $imageSrc }}" alt="{{ $detailTitle }}" loading="lazy" decoding="async">
                                    @if ($detailCard->detail_image_caption)
                                        <figcaption class="home-card-detail__caption">{{ $detailCard->detail_image_caption }}</figcaption>
                                    @endif
                                </figure>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>

    @php
        $whatsPhone = preg_replace('/\D+/', '', (string) ($settings['phone2'] ?? ''));
        $whatsMessage = trim((string) ($settings['message'] ?? ''));
        $whatsHref = $whatsPhone !== ''
            ? 'https://wa.me/'.$whatsPhone.($whatsMessage !== '' ? '?text='.rawurlencode($whatsMessage) : '')
            : null;
    @endphp

    @if ($whatsHref)
        <section id="contato" class="home-contact-cta" aria-label="Contato">
            <div class="container">
                <div class="home-contact-cta__card" data-reveal data-reveal-delay="120">
                    <h2>Entre em contato conosco!</h2>
                    <p>Junte-se aos nossos clientes satisfeitos e descubra como podemos transformar sua empresa com soluções modernas e eficientes.</p>
                    <a class="home-contact-cta__btn" href="{{ $whatsHref }}" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a>
                </div>
            </div>
        </section>

        <a class="whatsapp-float" href="{{ $whatsHref }}" target="_blank" rel="noopener noreferrer" aria-label="Falar no WhatsApp">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
        </a>
    @endif
@endsection
