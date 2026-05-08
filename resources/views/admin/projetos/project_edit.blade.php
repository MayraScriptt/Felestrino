@extends('layouts.admin')

@section('content')
    <style>
        .project-detail-grid {
            display: grid;
            gap: 1rem;
        }

        .project-detail-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .7rem;
            flex-wrap: wrap;
        }

        .project-image-list {
            display: grid;
            gap: .85rem;
        }

        .project-image-item {
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .7rem;
            padding: .75rem;
            display: grid;
            gap: .65rem;
            background: #fff;
        }

        .project-image-item img {
            width: 100%;
            max-width: 360px;
            border-radius: .65rem;
            object-fit: cover;
            border: 1px solid rgba(13, 27, 62, 0.12);
        }

        .project-image-item__form {
            display: grid;
            gap: .6rem;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            align-items: end;
        }
    </style>

    <div class="project-detail-head">
        <h1>Projeto: {{ $project->title }}</h1>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <a class="btn btn-secondary" href="{{ route('admin.projects.edit') }}">Voltar para Projetos</a>
            <form action="{{ route('admin.projects.cards.destroy', $project) }}" method="POST" onsubmit="return confirm('Deseja remover este projeto?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-secondary" type="submit">Excluir projeto</button>
            </form>
        </div>
    </div>

    <section class="project-detail-grid">
        <article class="admin-surface">
            <div class="admin-section-head">
                <div>
                    <div class="admin-section-kicker">Card e página</div>
                    <h2>Dados principais do projeto</h2>
                </div>
                <button class="btn" type="submit" form="project-main-form">Salvar projeto</button>
            </div>

            <form id="project-main-form" class="admin-form" action="{{ route('admin.projects.cards.update', $project) }}" method="POST">
                @csrf
                @method('PUT')
                <label>Título
                    <input type="text" name="title" maxlength="140" value="{{ $project->title }}" required>
                </label>
                <label>Subtítulo
                    <input type="text" name="subtitle" maxlength="255" value="{{ $project->subtitle }}">
                </label>
                <label>Descrição
                    <textarea name="description" rows="4">{{ $project->description }}</textarea>
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
            </form>
        </article>

        <article class="admin-surface">
            <div class="admin-section-head">
                <div>
                    <div class="admin-section-kicker">Galeria de fotos</div>
                    <h2>Adicionar nova imagem</h2>
                </div>
                <button class="btn" type="submit" form="project-image-create-form">Adicionar imagem</button>
            </div>

            <form id="project-image-create-form" class="admin-form" action="{{ route('admin.projects.images.store', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Arquivo de imagem
                    <input type="file" name="file" accept=".jpg,.jpeg,.png,.webp" required>
                </label>
                <label>Descrição da imagem
                    <input type="text" name="description" maxlength="255" placeholder="Descrição exibida abaixo da foto">
                </label>
                <label>Ordem
                    <input type="number" name="display_order" min="0" max="999999" placeholder="Ordem opcional">
                </label>
                <label class="checkbox-line">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Imagem ativa
                </label>
            </form>
        </article>

        <article class="admin-surface">
            <div class="admin-section-head">
                <div>
                    <div class="admin-section-kicker">Itens da galeria</div>
                    <h2>Editar imagens e descrições</h2>
                </div>
            </div>

            @if ($project->images->isEmpty())
                <p>Este projeto ainda não possui imagens cadastradas.</p>
            @else
                <div class="project-image-list">
                    @foreach ($project->images as $image)
                        <div class="project-image-item">
                            <img
                                src="{{ (str_starts_with($image->image_path, 'imagens/') || str_starts_with($image->image_path, 'images/')) ? asset($image->image_path) : asset('storage/'.$image->image_path) }}"
                                alt="{{ $image->description ?: 'Imagem do projeto' }}"
                                loading="lazy"
                                decoding="async"
                            >

                            <form class="project-image-item__form" action="{{ route('admin.projects.images.update', [$project, $image]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label>Descrição
                                    <input type="text" name="description" maxlength="255" value="{{ $image->description }}">
                                </label>
                                <label>Ordem
                                    <input type="number" name="display_order" min="0" max="999999" value="{{ $image->display_order }}">
                                </label>
                                <label class="checkbox-line">
                                    <input type="checkbox" name="is_active" value="1" @checked($image->is_active)>
                                    Imagem ativa
                                </label>
                                <button class="btn" type="submit">Salvar imagem</button>
                            </form>

                            <form action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" method="POST" onsubmit="return confirm('Deseja remover esta imagem?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-secondary" type="submit">Excluir imagem</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </article>
    </section>
@endsection
