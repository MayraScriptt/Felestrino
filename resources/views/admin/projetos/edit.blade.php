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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            align-items: end;
        }

        .projects-card-list {
            display: grid;
            gap: .85rem;
        }

        .project-card-item {
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .7rem;
            padding: .85rem;
            display: grid;
            gap: .7rem;
            background: #fff;
        }

        .project-card-item__grid {
            display: grid;
            gap: .6rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            align-items: end;
        }

        .project-card-item__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .project-card-item__slug {
            font-size: .82rem;
            color: rgba(13, 27, 62, 0.68);
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
                <label>Link (slug)
                    <input type="text" name="slug" maxlength="190" placeholder="exemplo-projeto">
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
                    <h2>Editar cards e acessar páginas individuais</h2>
                </div>
            </div>

            @if (($projects ?? collect())->isEmpty())
                <p>Nenhum projeto cadastrado ainda.</p>
            @else
                <div class="projects-card-list">
                    @foreach ($projects as $project)
                        <div class="project-card-item">
                            <div class="project-card-item__slug">Link público: {{ route('site.projects.show', $project->slug) }}</div>
                            <form class="project-card-item__grid" action="{{ route('admin.projects.cards.update', $project) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label>Título
                                    <input type="text" name="title" maxlength="140" value="{{ $project->title }}" required>
                                </label>
                                <label>Subtítulo
                                    <input type="text" name="subtitle" maxlength="255" value="{{ $project->subtitle }}">
                                </label>
                                <label>Descrição
                                    <textarea name="description" rows="2">{{ $project->description }}</textarea>
                                </label>
                                <label>Link (slug)
                                    <input type="text" name="slug" maxlength="190" value="{{ $project->slug }}" required>
                                </label>
                                <label>Texto do botão
                                    <input type="text" name="button_text" maxlength="80" value="{{ $project->button_text }}">
                                </label>
                                <label>Ordem
                                    <input type="number" name="display_order" min="0" max="999999" value="{{ $project->display_order }}">
                                </label>
                                <label class="checkbox-line">
                                    <input type="checkbox" name="is_active" value="1" @checked($project->is_active)>
                                    Projeto ativo
                                </label>
                                <div class="project-card-item__actions">
                                    <button class="btn" type="submit">Salvar card</button>
                                    <a class="btn btn-secondary" href="{{ route('admin.projects.project.edit', $project) }}">Editar página do projeto</a>
                                </div>
                            </form>
                            <form action="{{ route('admin.projects.cards.destroy', $project) }}" method="POST" onsubmit="return confirm('Deseja remover este projeto?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-secondary" type="submit">Excluir projeto</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </article>
    </section>
@endsection
