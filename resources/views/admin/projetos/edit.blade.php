@extends('layouts.admin')

@section('content')
    <style>
        .admin-home-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: .85rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid rgba(13, 27, 62, 0.08);
        }

        .admin-home-tab-btn {
            appearance: none;
            border: 1px solid rgba(13, 27, 62, 0.16);
            border-radius: .45rem;
            background: #fff;
            color: #0d1b3e;
            padding: .5rem .75rem;
            font-family: "Rajdhani", sans-serif;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all .18s ease;
        }

        .admin-home-tab-btn:hover,
        .admin-home-tab-btn:focus-visible {
            border-color: rgba(184, 144, 42, 0.45);
            color: #142150;
        }

        .admin-home-tab-btn.is-active {
            border-color: rgba(184, 144, 42, 0.75);
            background: linear-gradient(180deg, #f8e5ba, #edd18e);
            color: #08112a;
        }

        .admin-home-tab-panel[hidden] {
            display: none !important;
        }

        .admin-home-tab-panel {
            display: grid;
            gap: 1rem;
        }

        .admin-title-banner {
            width: 100%;
            border-radius: .8rem;
            overflow: hidden;
            border: 1px solid rgba(13, 27, 62, 0.12);
            background: linear-gradient(180deg, rgba(20, 33, 80, 0.12), rgba(184, 144, 42, 0.10));
            margin-bottom: .9rem;
            position: relative;
        }

        .admin-title-banner img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 21 / 7;
            object-fit: cover;
        }

        .admin-banner-remove-btn {
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(8, 17, 42, 0.6);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 999px;
            position: absolute;
            top: .65rem;
            right: .65rem;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
            transition: transform .12s ease, filter .2s ease, background-color .2s ease, border-color .2s ease;
            z-index: 2;
        }

        .admin-banner-remove-btn:hover,
        .admin-banner-remove-btn:focus-visible {
            filter: brightness(1.05);
            border-color: rgba(212, 171, 74, 0.65);
        }

        .admin-title-banner.is-marked img {
            filter: grayscale(1);
            opacity: .72;
        }

        .admin-title-banner.is-marked .admin-banner-remove-btn {
            background: rgba(153, 27, 27, 0.72);
            border-color: rgba(255, 255, 255, 0.55);
        }

        .admin-banner-remove-hint {
            font-size: .86rem;
            color: rgba(13, 27, 62, 0.78);
            margin-top: .6rem;
            padding: .6rem .75rem;
            border-radius: .65rem;
            background: rgba(184, 144, 42, 0.12);
            border: 1px solid rgba(184, 144, 42, 0.22);
        }

        @media (max-width: 768px) {
            .admin-title-banner img {
                aspect-ratio: 16 / 9;
            }
        }

        .projects-grid {
            display: grid;
            gap: 1rem;
        }

        .projects-create-form {
            display: grid;
            gap: .7rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            align-items: end;
        }

        .projects-card-list {
            display: grid;
            gap: .85rem;
        }

        .projects-cards-grid {
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
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .02em;
        }

        .project-card p {
            margin: 0;
            color: rgba(13, 27, 62, 0.8);
            line-height: 1.65;
            font-size: .95rem;
        }

        .project-card__meta {
            font-size: .82rem;
            color: rgba(13, 27, 62, 0.68);
            word-break: break-word;
        }

        .project-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
            align-items: center;
        }

        .project-card__actions form {
            margin: 0;
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

        .project-card__btn--secondary {
            background: rgba(13, 27, 62, 0.04);
            border-color: rgba(13, 27, 62, 0.16);
            color: #0d1b3e;
        }

        .project-card__btn--danger {
            background: rgba(153, 27, 27, 0.08);
            border-color: rgba(153, 27, 27, 0.22);
            color: #991b1b;
        }

        .project-card__btn--danger:hover {
            filter: none;
        }

        .project-card__status {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(13, 27, 62, 0.62);
        }

        .project-card__status.is-inactive {
            color: rgba(153, 27, 27, 0.78);
        }

        .project-card__order {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(13, 27, 62, 0.62);
        }

        .project-card__order strong {
            color: rgba(13, 27, 62, 0.9);
        }
    </style>

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

    <div class="admin-pages-head">
        <h1>Projetos</h1>
    </div>

    <section data-admin-tabs="projects">
        <div class="admin-home-tabs" role="tablist" aria-label="Seções da página">
            <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-admin-tab-trigger data-target="lista">Projetos</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-admin-tab-trigger data-target="pagina">Página</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-admin-tab-trigger data-target="novo">Novo projeto</button>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="pagina" hidden>
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Página pública</div>
                        <h2>Texto principal da página Projetos</h2>
                    </div>
                    <button class="btn" type="submit" form="projects-page-form">Salvar página</button>
                </div>

                @if ($bannerSrc)
                    <div class="admin-title-banner" data-banner-root>
                        <img src="{{ $bannerSrc }}" alt="" loading="lazy" decoding="async">
                        <button class="admin-banner-remove-btn" type="button" data-banner-remove aria-label="Remover banner">×</button>
                    </div>
                    <input type="hidden" name="banner_remove" value="0" form="projects-page-form" data-banner-remove-input>
                    <div class="admin-banner-remove-hint" data-banner-hint hidden>Salve para que o banner seja excluído</div>
                @endif

                <form id="projects-page-form" class="admin-form" action="{{ route('admin.projects.update-page') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <label>Banner do título (opcional)
                        <input type="file" name="banner_file" accept=".jpg,.jpeg,.png,.webp,.gif">
                    </label>
                    <label>Título
                        <input type="text" name="title" maxlength="140" value="{{ old('title', $projectPage->title ?? 'Projetos') }}">
                    </label>
                    <label>Subtítulo
                        <input type="text" name="subtitle" maxlength="255" value="{{ old('subtitle', $projectPage->subtitle ?? '') }}" placeholder="Texto de apoio da seção">
                    </label>
                    <label>Descrição
                        <textarea name="description" rows="4">{{ old('description', $projectPage->description ?? '') }}</textarea>
                    </label>
                </form>
            </article>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="novo" hidden>
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Cards de projetos</div>
                        <h2>Adicionar novo projeto</h2>
                    </div>
                    <button class="btn" type="submit" form="project-create-form">Criar projeto</button>
                </div>

                <form id="project-create-form" class="projects-create-form" action="{{ route('admin.projects.cards.store') }}" method="POST">
                    @csrf
                    <label>Título
                        <input type="text" name="title" maxlength="140" required>
                    </label>
                    <label>Subtítulo
                        <input type="text" name="subtitle" maxlength="255">
                    </label>
                    <label>Texto do botão
                        <input type="text" name="button_text" maxlength="80" value="Ver projeto">
                    </label>
                    <label class="checkbox-line">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Projeto ativo
                    </label>
                </form>
            </article>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="lista">
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Projetos cadastrados</div>
                        <h2>Cards (para editar, entre no projeto)</h2>
                    </div>
                </div>

                @if (($projects ?? collect())->isEmpty())
                    <p>Nenhum projeto cadastrado ainda.</p>
                @else
                    <div class="projects-cards-grid">
                        @foreach ($projects as $project)
                            <article class="project-card">
                                @if ($project->subtitle)
                                    <div class="project-card__subtitle">{{ $project->subtitle }}</div>
                                @endif
                                <h2>{{ $project->title }}</h2>
                                @if ($project->description)
                                    <p>{{ $project->description }}</p>
                                @endif
                                <div class="project-card__meta">
                                    Link público: {{ route('site.projects.show', $project->slug) }}
                                </div>
                                <div class="project-card__actions">
                                    <a class="project-card__btn" href="{{ route('admin.projects.project.edit', $project) }}">Editar projeto</a>
                                    <a class="project-card__btn project-card__btn--secondary" href="{{ route('site.projects.show', $project->slug) }}" target="_blank" rel="noopener noreferrer">Ver no site</a>
                                    <span class="project-card__status @if (! $project->is_active) is-inactive @endif">
                                        @if ($project->is_active) Ativo @else Inativo @endif
                                    </span>
                                    <span class="project-card__order">Ordem: <strong>{{ $project->display_order }}</strong></span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </article>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bannerRoot = document.querySelector('[data-banner-root]');
            var bannerRemoveBtn = document.querySelector('[data-banner-remove]');
            var bannerRemoveInput = document.querySelector('[data-banner-remove-input]');
            var bannerHint = document.querySelector('[data-banner-hint]');

            var tabRoot = document.querySelector('[data-admin-tabs="projects"]');
            if (tabRoot) {
                var buttons = tabRoot.querySelectorAll('[data-admin-tab-trigger]');
                var panels = tabRoot.querySelectorAll('[data-admin-tab-panel]');

                function activateTab(target) {
                    buttons.forEach(function (button) {
                        var active = button.getAttribute('data-target') === target;
                        button.classList.toggle('is-active', active);
                        button.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    panels.forEach(function (panel) {
                        var visible = panel.getAttribute('data-admin-tab-panel') === target;
                        panel.hidden = !visible;
                    });
                }

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        activateTab(button.getAttribute('data-target'));
                    });
                });
            }

            if (bannerRoot && bannerRemoveBtn && bannerRemoveInput) {
                bannerRemoveBtn.addEventListener('click', function () {
                    var marked = bannerRoot.classList.toggle('is-marked');
                    bannerRemoveInput.value = marked ? '1' : '0';
                    if (bannerHint) {
                        bannerHint.hidden = !marked;
                    }
                });
            }
        });
    </script>
@endsection
