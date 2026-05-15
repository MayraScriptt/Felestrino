@extends('layouts.site')

@section('content')
    @php
        $resolveBannerSrc = static function (?string $path): ?string {
            $path = is_string($path) ? trim($path) : '';
            if ($path === '') {
                return null;
            }
            if (str_starts_with($path, 'imagens/') || str_starts_with($path, 'images/')) {
                return asset($path);
            }
            return asset('storage/'.$path);
        };
        $bannerSrc = $resolveBannerSrc($banner_path ?? null);
    @endphp

    <style>
        .page-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(155deg, #0d1b3e 0%, #13285f 100%);
            color: #fff;
            padding: clamp(2.4rem, 7vw, 4.8rem) 0;
        }

        .page-hero h1 {
            margin: 0 0 .7rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .06em;
            font-size: clamp(2.1rem, 5.2vw, 3.6rem);
            line-height: 1;
            color: #ffffff;
        }

        .page-hero p {
            margin: 0;
            max-width: 72ch;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.75;
        }

        .page-hero.page-hero--banner {
            background: #0d1b3e;
            color: #ffffff;
        }

        .page-hero.page-hero--banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--page-banner);
            background-size: cover;
            background-position: center;
            transform: scale(1.02);
        }

        .page-hero.page-hero--banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(13, 27, 62, 0.25), rgba(13, 27, 62, 0.85));
        }

        .page-hero .container {
            position: relative;
            z-index: 2;
        }

        .page-hero.page-hero--banner h1 {
            color: #ffffff;
            text-shadow: 0 12px 30px rgba(8, 17, 42, 0.35);
        }
    </style>

    <section
        class="hero hero-small page-hero @if ($bannerSrc) page-hero--banner @endif"
        @if ($bannerSrc) style="--page-banner: url('{{ $bannerSrc }}')" @endif
    >
        <div class="container">
            <h1>{{ $page->title }}</h1>
            @if (! empty($banner_subtitle))
                <p>{{ $banner_subtitle }}</p>
            @endif
            @if (! empty($banner_description))
                <p style="margin-top:.9rem;">{{ $banner_description }}</p>
            @endif
        </div>
    </section>

    <section class="container prose">
        @if (($allowHtml ?? false) === true)
            <div>{!! (string) $page->content !!}</div>
        @else
            <div>{!! nl2br(e($page->content)) !!}</div>
        @endif
    </section>
@endsection
