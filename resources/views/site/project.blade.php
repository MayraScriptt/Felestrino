@extends('layouts.site')

@section('content')
    <style>
        .project-hero {
            background: linear-gradient(155deg, #0d1b3e 0%, #13285f 100%);
            color: #fff;
            padding: clamp(2.3rem, 7vw, 4.6rem) 0;
        }

        .project-hero__subtitle {
            margin: 0 0 .45rem;
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #d4ab4a;
            font-size: .82rem;
            font-weight: 700;
        }

        .project-hero h1 {
            margin: 0;
            font-family: "Bebas Neue", sans-serif;
            letter-spacing: .06em;
            font-size: clamp(2.1rem, 5vw, 3.6rem);
            line-height: 1;
        }

        .project-hero p {
            margin: .85rem 0 0;
            max-width: 70ch;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.7;
        }

        .project-gallery {
            padding: clamp(1.6rem, 4vw, 3.2rem) 0;
        }

        .project-gallery-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        }

        .project-photo-card {
            background: #fff;
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .85rem;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(13, 27, 62, 0.08);
        }

        .project-photo-card img {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: 0;
        }

        .project-photo-card figcaption {
            padding: .85rem;
            font-size: .92rem;
            color: rgba(13, 27, 62, 0.82);
            line-height: 1.55;
        }

        .project-empty {
            border: 1px dashed rgba(13, 27, 62, 0.2);
            border-radius: .8rem;
            padding: 1rem;
            color: rgba(13, 27, 62, 0.82);
        }
    </style>

    <section class="project-hero">
        <div class="container">
            @if ($project->subtitle)
                <p class="project-hero__subtitle">{{ $project->subtitle }}</p>
            @endif
            <h1>{{ $project->title }}</h1>
            @if ($project->description)
                <p>{{ $project->description }}</p>
            @endif
        </div>
    </section>

    <section class="project-gallery">
        <div class="container">
            @if ($project->images->isEmpty())
                <div class="project-empty">Este projeto ainda não possui fotos publicadas.</div>
            @else
                <div class="project-gallery-grid">
                    @foreach ($project->images as $image)
                        <figure class="project-photo-card">
                            <img
                                src="{{ (str_starts_with($image->image_path, 'imagens/') || str_starts_with($image->image_path, 'images/')) ? asset($image->image_path) : asset('storage/'.$image->image_path) }}"
                                alt="{{ $image->description ?: 'Imagem do projeto '.$project->title }}"
                                loading="lazy"
                                decoding="async"
                            >
                            @if ($image->description)
                                <figcaption>{{ $image->description }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
