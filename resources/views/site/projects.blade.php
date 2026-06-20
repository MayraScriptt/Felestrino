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
        $bannerSrc = $resolveBannerSrc($projectPage->banner_path ?? null);
        $bannerPosX = is_numeric($projectPage?->banner_position_x ?? null) ? (int) $projectPage->banner_position_x : 50;
        $bannerPosY = is_numeric($projectPage?->banner_position_y ?? null) ? (int) $projectPage->banner_position_y : 50;
        $bannerPosX = max(0, min(100, $bannerPosX));
        $bannerPosY = max(0, min(100, $bannerPosY));
    @endphp

    <style>
        .projects-hero {
            background: linear-gradient(155deg, #0d1b3e 0%, #13285f 100%);
            color: #fff;
            padding: clamp(2.4rem, 7vw, 4.8rem) 0;
        }

        .projects-hero.projects-hero--banner {
            position: relative;
            overflow: hidden;
            background: #0d1b3e;
        }

        .projects-hero.projects-hero--banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--projects-banner);
            background-size: cover;
            background-position: var(--projects-banner-position, center);
            transform: scale(1.02);
        }

        .projects-hero.projects-hero--banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(13, 27, 62, 0.25), rgba(13, 27, 62, 0.88));
        }

        .projects-hero .container {
            position: relative;
            z-index: 2;
        }

        .projects-hero h1 {
            margin: 0 0 .7rem;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .06em;
            font-size: clamp(2.1rem, 5.2vw, 3.6rem);
            line-height: 1;
        }

        .projects-hero p {
            margin: 0;
            max-width: 72ch;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.75;
        }

        .projects-section {
            padding: clamp(1.6rem, 4vw, 3rem) 0;
        }

        .projects-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .project-card {
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .9rem;
            padding: 1rem;
            background: #fff;
            box-shadow: 0 12px 28px rgba(8, 17, 42, 0.07);
            display: grid;
            grid-template-columns: 132px 1fr;
            gap: .85rem;
            min-height: 200px;
            height: 100%;
        }

        .project-card__thumb {
            width: 132px;
            height: 92px;
            border-radius: .85rem;
            overflow: hidden;
            border: 1px solid rgba(13, 27, 62, 0.1);
            background: rgba(13, 27, 62, 0.06);
            box-shadow: 0 10px 24px rgba(13, 27, 62, 0.08);
            position: relative;
            line-height: 0;
            align-self: start;
        }

        .project-card__thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            opacity: 0;
            transition: opacity .5s ease;
        }

        .project-card__thumb img.is-active {
            opacity: 1;
        }

        .project-card__thumb-btn {
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(8, 17, 42, 0.6);
            color: #ffffff;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            position: absolute;
            inset: 0;
            margin: auto;
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            line-height: 1;
            pointer-events: none;
        }

        .project-card__body {
            display: flex;
            flex-direction: column;
            gap: .55rem;
            min-width: 0;
        }

        .project-card__subtitle {
            color: #b8902a;
            font-family: "Rajdhani", sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .project-card__subtitle.is-empty {
            min-height: 1.1em;
        }

        .project-card h2 {
            margin: 0;
            color: #0d1b3e;
            font-size: 1.2rem;
            line-height: 1.25;
        }

        .project-card__description {
            margin: 0;
            color: rgba(13, 27, 62, 0.8);
            line-height: 1.65;
        }

        .project-card__description.is-empty {
            min-height: calc(1.65em * 2);
        }

        .project-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
            margin-top: auto;
        }

        .project-card__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid rgba(184, 144, 42, 0.38);
            background: #f8eac7;
            color: #0d1b3e;
            font-family: "Rajdhani", sans-serif;
            font-size: .84rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-decoration: none;
            padding: .58rem 1rem;
            min-width: 160px;
            transition: filter .2s ease, transform .15s ease;
        }

        .project-card__btn:hover {
            filter: brightness(1.04);
            transform: translateY(-1px);
        }

        @media (max-width: 560px) {
            .project-card {
                grid-template-columns: 1fr;
            }

            .project-card__thumb {
                width: 100%;
                height: auto;
                aspect-ratio: 16 / 10;
            }
        }
    </style>

    <section
        class="projects-hero @if ($bannerSrc) projects-hero--banner @endif"
        @if ($bannerSrc) style="--projects-banner: url('{{ $bannerSrc }}'); --projects-banner-position: {{ $bannerPosX }}% {{ $bannerPosY }}%;" @endif
    >
        <div class="container">
            <h1>{{ $projectPage->title ?: 'Projetos' }}</h1>
            @if ($projectPage?->subtitle)
                <p>{{ $projectPage->subtitle }}</p>
            @endif
            @if ($projectPage?->description)
                <p style="margin-top:.9rem;">{{ $projectPage->description }}</p>
            @endif
        </div>
    </section>

    <section class="projects-section">
        <div class="container">
            @if (($projects ?? collect())->isEmpty())
                <p>Nenhum projeto ativo no momento.</p>
            @else
                <div class="projects-grid">
                    @foreach ($projects as $project)
                        @php
                            $projectSubtitle = trim((string) ($project->subtitle ?? ''));
                            $projectDescription = trim((string) ($project->description ?? ''));
                            $imageMedias = ($project->images ?? collect())
                                ->filter(fn ($media) => (string) ($media->type ?? 'image') !== 'youtube' && trim((string) ($media->image_path ?? '')) !== '')
                                ->values();

                            $thumbSources = $imageMedias
                                ->take(6)
                                ->map(function ($media) {
                                    return (str_starts_with((string) $media->image_path, 'imagens/') || str_starts_with((string) $media->image_path, 'images/'))
                                        ? asset($media->image_path)
                                        : asset('storage/'.$media->image_path);
                                })
                                ->values();

                            $thumbIsYoutube = false;
                            if ($thumbSources->isEmpty()) {
                                $youtubeMedia = ($project->images ?? collect())
                                    ->first(fn ($media) => (string) ($media->type ?? 'image') === 'youtube' && trim((string) ($media->youtube_id ?? '')) !== '');

                                if ($youtubeMedia && trim((string) ($youtubeMedia->youtube_id ?? '')) !== '') {
                                    $thumbIsYoutube = true;
                                    $thumbSources = collect(['https://img.youtube.com/vi/'.(string) $youtubeMedia->youtube_id.'/hqdefault.jpg']);
                                } elseif (is_string($project->banner_path ?? null) && trim((string) $project->banner_path) !== '') {
                                    $thumbSources = collect([
                                        (str_starts_with((string) $project->banner_path, 'imagens/') || str_starts_with((string) $project->banner_path, 'images/'))
                                            ? asset($project->banner_path)
                                            : asset('storage/'.$project->banner_path),
                                    ]);
                                }
                            }
                        @endphp
                        <article class="project-card">
                            @if ($thumbSources->isNotEmpty())
                                <a
                                    class="project-card__thumb"
                                    href="{{ route('site.projects.show', $project->slug) }}"
                                    aria-label="Ver projeto {{ $project->title }}"
                                    data-project-thumb
                                    data-interval="2300"
                                >
                                    @foreach ($thumbSources as $src)
                                        <img src="{{ $src }}" alt="Prévia do projeto {{ $project->title }}" loading="lazy" decoding="async" @if ($loop->first) class="is-active" @endif>
                                    @endforeach
                                    @if ($thumbIsYoutube)
                                        <span class="project-card__thumb-btn" aria-hidden="true">▶</span>
                                    @endif
                                </a>
                            @endif
                            <div class="project-card__body">
                                <div class="project-card__subtitle @if ($projectSubtitle === '') is-empty @endif" @if ($projectSubtitle === '') aria-hidden="true" @endif>{{ $projectSubtitle }}</div>
                                <h2>{{ $project->title }}</h2>
                                <p class="project-card__description @if ($projectDescription === '') is-empty @endif" @if ($projectDescription === '') aria-hidden="true" @endif>{{ $projectDescription }}</p>
                                <div class="project-card__actions">
                                    <a class="project-card__btn" href="{{ route('site.projects.show', $project->slug) }}">
                                        {{ $project->button_text ?: 'Ver projeto' }}
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            var thumbs = document.querySelectorAll('[data-project-thumb]');
            thumbs.forEach(function (thumb) {
                var images = Array.prototype.slice.call(thumb.querySelectorAll('img'));
                if (images.length <= 1) {
                    return;
                }

                var intervalMs = Number.parseInt(String(thumb.getAttribute('data-interval') || ''), 10);
                if (!Number.isFinite(intervalMs) || intervalMs < 800) {
                    intervalMs = 2300;
                }

                var index = 0;
                var timerId = null;

                function show(nextIndex) {
                    images.forEach(function (img, i) {
                        img.classList.toggle('is-active', i === nextIndex);
                    });
                }

                function start() {
                    if (timerId !== null) return;
                    timerId = window.setInterval(function () {
                        index = (index + 1) % images.length;
                        show(index);
                    }, intervalMs);
                }

                function stop() {
                    if (timerId === null) return;
                    window.clearInterval(timerId);
                    timerId = null;
                }

                thumb.addEventListener('mouseenter', stop);
                thumb.addEventListener('mouseleave', start);
                thumb.addEventListener('focusin', stop);
                thumb.addEventListener('focusout', start);

                start();
            });
        });
    </script>
@endsection
