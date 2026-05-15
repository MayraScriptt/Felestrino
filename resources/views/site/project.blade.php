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
            align-items: start;
        }

        .project-photo-card {
            background: transparent;
            border: 0;
            border-radius: 0;
            overflow: visible;
            box-shadow: none;
            margin: 0;
            line-height: 0;
        }

        .project-photo-card--no-caption {
        }

        .project-photo-card img {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            border-radius: .85rem;
            cursor: zoom-in;
            display: block;
            border: 1px solid rgba(13, 27, 62, 0.1);
            box-shadow: 0 10px 24px rgba(13, 27, 62, 0.08);
        }

        .project-photo-card__video {
            width: 100%;
            aspect-ratio: 16 / 9;
            border: 0;
            display: block;
        }

        .project-video-thumb {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: rgba(13, 27, 62, 0.06);
            cursor: pointer;
            line-height: 0;
            border-radius: .85rem;
            border: 1px solid rgba(13, 27, 62, 0.1);
            box-shadow: 0 10px 24px rgba(13, 27, 62, 0.08);
        }

        .project-video-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            cursor: pointer;
        }

        .project-video-thumb__btn {
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(8, 17, 42, 0.6);
            color: #ffffff;
            width: 56px;
            height: 56px;
            border-radius: 999px;
            position: absolute;
            inset: 0;
            margin: auto;
            display: grid;
            place-items: center;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
        }

        .project-video-thumb__btn:focus-visible {
            outline: 2px solid rgba(212, 171, 74, 0.85);
            outline-offset: 3px;
        }

        .project-lightbox {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(8, 17, 42, 0.88);
            display: none;
            place-items: center;
            padding: 1rem;
        }

        .project-lightbox.is-open {
            display: grid;
        }

        .project-lightbox__panel {
            position: relative;
            width: min(1100px, 100%);
            max-height: calc(100vh - 2rem);
            display: grid;
            gap: .75rem;
        }

        .project-lightbox__img {
            width: 100%;
            height: auto;
            max-height: calc(100vh - 6rem);
            object-fit: contain;
            border-radius: .85rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .project-lightbox__video {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: .85rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(0, 0, 0, 0.2);
        }

        .project-lightbox__close {
            appearance: none;
            position: absolute;
            top: .65rem;
            right: .65rem;
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(8, 17, 42, 0.55);
            color: #fff;
            font-size: 1.35rem;
            line-height: 1;
            display: grid;
            place-items: center;
            cursor: pointer;
        }

        .project-lightbox__caption {
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.6;
            font-size: .95rem;
            max-width: 85ch;
        }

        .project-photo-card figcaption {
            margin-top: .65rem;
            padding: 0;
            font-size: .92rem;
            color: rgba(13, 27, 62, 0.82);
            line-height: 1.55;
            display: block;
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
                        @php
                            $caption = trim((string) ($image->description ?? ''));
                            $hasCaption = $caption !== '';
                        @endphp
                        <figure class="project-photo-card @if (! $hasCaption) project-photo-card--no-caption @endif">
                            @if (($image->type ?? 'image') === 'youtube' && $image->youtube_id)
                                <div
                                    class="project-video-thumb"
                                    role="button"
                                    tabindex="0"
                                    data-lightbox-video-id="{{ $image->youtube_id }}"
                                    data-lightbox-caption="{{ $caption }}"
                                    aria-label="Abrir vídeo em tela cheia"
                                >
                                    <img
                                        src="https://img.youtube.com/vi/{{ $image->youtube_id }}/hqdefault.jpg"
                                        alt="{{ $caption ?: ('Vídeo do projeto '.$project->title) }}"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <button class="project-video-thumb__btn" type="button" aria-label="Reproduzir em tela cheia">▶</button>
                                </div>
                            @else
                                <img
                                    src="{{ (str_starts_with((string) $image->image_path, 'imagens/') || str_starts_with((string) $image->image_path, 'images/')) ? asset($image->image_path) : asset('storage/'.$image->image_path) }}"
                                    alt="{{ $caption ?: 'Imagem do projeto '.$project->title }}"
                                    loading="lazy"
                                    decoding="async"
                                    data-lightbox-src="{{ (str_starts_with((string) $image->image_path, 'imagens/') || str_starts_with((string) $image->image_path, 'images/')) ? asset($image->image_path) : asset('storage/'.$image->image_path) }}"
                                    data-lightbox-caption="{{ $caption }}"
                                >
                            @endif
                            @if ($hasCaption)
                                <figcaption>{{ $caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="project-lightbox" data-project-lightbox aria-hidden="true">
        <div class="project-lightbox__panel" role="dialog" aria-modal="true" aria-label="Imagem em tela cheia" tabindex="-1">
            <button class="project-lightbox__close" type="button" data-project-lightbox-close aria-label="Fechar">×</button>
            <img class="project-lightbox__img" data-project-lightbox-img alt="">
            <iframe
                class="project-lightbox__video"
                data-project-lightbox-video
                title="Vídeo em tela cheia"
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                hidden
            ></iframe>
            <div class="project-lightbox__caption" data-project-lightbox-caption hidden></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var lightbox = document.querySelector('[data-project-lightbox]');
            var lightboxImg = document.querySelector('[data-project-lightbox-img]');
            var lightboxVideo = document.querySelector('[data-project-lightbox-video]');
            var lightboxCaption = document.querySelector('[data-project-lightbox-caption]');
            var closeBtn = document.querySelector('[data-project-lightbox-close]');
            var panel = lightbox ? lightbox.querySelector('[role="dialog"]') : null;

            if (!lightbox || !lightboxImg || !lightboxVideo || !closeBtn || !panel) {
                return;
            }

            var lastActive = null;

            var setCaption = function (caption) {
                if (!lightboxCaption) return;
                var c = (caption || '').trim();
                lightboxCaption.textContent = c;
                lightboxCaption.hidden = c === '';
            };

            var openBase = function () {
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.documentElement.style.overflow = 'hidden';
                closeBtn.focus();
            };

            var openImage = function (src, alt, caption) {
                if (!src) return;
                lastActive = document.activeElement;
                lightboxVideo.hidden = true;
                lightboxVideo.removeAttribute('src');
                lightboxImg.hidden = false;
                lightboxImg.src = src;
                lightboxImg.alt = alt || '';
                setCaption(caption);
                openBase();
            };

            var openVideo = function (youtubeId, caption) {
                if (!youtubeId) return;
                lastActive = document.activeElement;
                lightboxImg.hidden = true;
                lightboxImg.removeAttribute('src');
                lightboxVideo.hidden = false;
                lightboxVideo.setAttribute(
                    'src',
                    'https://www.youtube.com/embed/' +
                        encodeURIComponent(String(youtubeId)) +
                        '?autoplay=1&rel=0&modestbranding=1'
                );
                setCaption(caption);
                openBase();
            };

            var close = function () {
                if (!lightbox.classList.contains('is-open')) return;
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                document.documentElement.style.overflow = '';
                lightboxImg.removeAttribute('src');
                lightboxImg.hidden = false;
                lightboxVideo.removeAttribute('src');
                lightboxVideo.hidden = true;
                if (lightboxCaption) {
                    lightboxCaption.textContent = '';
                    lightboxCaption.hidden = true;
                }
                if (lastActive && typeof lastActive.focus === 'function') {
                    lastActive.focus();
                }
                lastActive = null;
            };

            document.querySelectorAll('img[data-lightbox-src]').forEach(function (img) {
                img.addEventListener('click', function () {
                    openImage(
                        img.getAttribute('data-lightbox-src'),
                        img.getAttribute('alt'),
                        img.getAttribute('data-lightbox-caption') || ''
                    );
                });
            });

            document.querySelectorAll('[data-lightbox-video-id]').forEach(function (el) {
                var openFromEl = function () {
                    openVideo(
                        el.getAttribute('data-lightbox-video-id'),
                        el.getAttribute('data-lightbox-caption') || ''
                    );
                };

                el.addEventListener('click', function (event) {
                    if (event.target && event.target.tagName === 'BUTTON') {
                        event.preventDefault();
                    }
                    openFromEl();
                });

                el.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openFromEl();
                    }
                });
            });

            closeBtn.addEventListener('click', close);

            lightbox.addEventListener('click', function (event) {
                if (event.target === lightbox) {
                    close();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    close();
                }
            });
        });
    </script>
@endsection
