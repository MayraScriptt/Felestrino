@extends('layouts.admin')

@section('content')
    <style>
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

    <div class="admin-pages-head">
        <h1>Projetos</h1>
    </div>

    <section class="projects-grid">
        <article class="admin-surface">
            <div class="admin-section-head">
                <div>
                    <div class="admin-section-kicker">Página pública</div>
                    <h2>Texto principal da página Projetos</h2>
                </div>
                <button class="btn" type="submit" form="projects-page-form">Salvar página</button>
            </div>

            <form id="projects-page-form" class="admin-form" action="{{ route('admin.projects.update-page') }}" method="POST">
                @csrf
                @method('PUT')
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
    </section>
@endsection
