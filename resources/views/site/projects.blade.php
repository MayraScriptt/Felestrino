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
            background-position: center;
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
            gap: .75rem;
        }

        .project-card__subtitle {
            color: #b8902a;
            font-family: "Rajdhani", sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .project-card h2 {
            margin: 0;
            color: #0d1b3e;
            font-size: 1.2rem;
            line-height: 1.25;
        }

        .project-card p {
            margin: 0;
            color: rgba(13, 27, 62, 0.8);
            line-height: 1.65;
        }

        .project-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
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
            transition: filter .2s ease, transform .15s ease;
        }

        .project-card__btn:hover {
            filter: brightness(1.04);
            transform: translateY(-1px);
        }
    </style>

    <section
        class="projects-hero @if ($bannerSrc) projects-hero--banner @endif"
        @if ($bannerSrc) style="--projects-banner: url('{{ $bannerSrc }}')" @endif
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
                        <article class="project-card">
                            @if ($project->subtitle)
                                <div class="project-card__subtitle">{{ $project->subtitle }}</div>
                            @endif
                            <h2>{{ $project->title }}</h2>
                            @if ($project->description)
                                <p>{{ $project->description }}</p>
                            @endif
                            <div class="project-card__actions">
                                <a class="project-card__btn" href="{{ route('site.projects.show', $project->slug) }}">
                                    {{ $project->button_text ?: 'Ver projeto' }}
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
