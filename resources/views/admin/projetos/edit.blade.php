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

        .admin-banner-guides {
            margin-bottom: .9rem;
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

        .admin-banner-guides.is-marked .admin-banner-remove-btn {
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
            position: relative;
        }

        .project-card__remove-form {
            margin: 0;
        }

        .project-card__remove-btn {
            appearance: none;
            border: 1px solid rgba(13, 27, 62, 0.18);
            background: #f8eac7;
            color: #0d1b3e;
            width: 38px;
            height: 38px;
            border-radius: .55rem;
            position: absolute;
            top: .65rem;
            right: .65rem;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
            transition: filter .2s ease, background-color .2s ease, border-color .2s ease;
            z-index: 2;
        }

        .project-card__remove-btn:hover,
        .project-card__remove-btn:focus-visible {
            border-color: rgba(184, 144, 42, 0.55);
            filter: brightness(1.02);
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

        .admin-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(8, 17, 42, 0.68);
            display: grid;
            place-items: center;
            padding: 1rem;
        }

        .admin-modal[hidden] {
            display: none !important;
        }

        .admin-modal__panel {
            width: min(920px, 100%);
            max-height: calc(100vh - 2rem);
            overflow: auto;
            border-radius: .95rem;
            border: 1px solid rgba(13, 27, 62, 0.14);
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(8, 17, 42, 0.28);
        }

        .admin-modal__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid rgba(13, 27, 62, 0.08);
        }

        .admin-modal__title {
            margin: 0;
            font-family: "Rajdhani", sans-serif;
            letter-spacing: .04em;
        }

        .admin-modal__close {
            appearance: none;
            border: 1px solid rgba(13, 27, 62, 0.18);
            background: rgba(13, 27, 62, 0.04);
            color: #0d1b3e;
            width: 40px;
            height: 40px;
            border-radius: .65rem;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
        }

        .admin-modal__body {
            padding: 1rem 1.1rem 1.2rem;
            display: grid;
            gap: 1rem;
        }

        .admin-modal__grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr 1fr;
        }

        .admin-modal__grid--single {
            grid-template-columns: 1fr;
        }

        .admin-modal__grid .admin-surface {
            margin: 0;
        }

        .admin-modal__status {
            font-size: .9rem;
            color: rgba(13, 27, 62, 0.78);
            padding: .65rem .75rem;
            border-radius: .65rem;
            background: rgba(184, 144, 42, 0.10);
            border: 1px solid rgba(184, 144, 42, 0.18);
        }

        .admin-modal__uploads {
            display: grid;
            gap: .55rem;
        }

        .admin-modal__uploads-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: .55rem .75rem;
            padding: .55rem .65rem;
            border-radius: .65rem;
            border: 1px solid rgba(13, 27, 62, 0.1);
            background: rgba(13, 27, 62, 0.02);
            font-size: .9rem;
            color: rgba(13, 27, 62, 0.86);
        }

        .admin-modal__upload-trigger {
            appearance: none;
            border: 0;
            padding: 0;
            background: transparent;
            text-align: left;
            font: inherit;
            font-weight: 700;
            color: #0d1b3e;
            cursor: pointer;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-modal__upload-trigger:hover,
        .admin-modal__upload-trigger:focus-visible {
            text-decoration: underline;
        }

        .admin-modal__uploads-item span {
            color: rgba(13, 27, 62, 0.68);
            white-space: nowrap;
        }

        .admin-modal__upload-editor {
            grid-column: 1 / -1;
            display: grid;
            gap: .55rem;
            padding-top: .55rem;
            border-top: 1px dashed rgba(13, 27, 62, 0.18);
        }

        .admin-modal__upload-editor-row {
            display: flex;
            gap: .75rem;
            align-items: flex-start;
        }

        .admin-modal__upload-thumb {
            width: 72px;
            height: 72px;
            border-radius: .55rem;
            border: 1px solid rgba(13, 27, 62, 0.14);
            background: rgba(13, 27, 62, 0.03);
            object-fit: cover;
            flex: 0 0 auto;
        }

        .admin-modal__upload-input {
            width: 100%;
            border: 1px solid rgba(13, 27, 62, 0.15);
            border-radius: .45rem;
            padding: .6rem .7rem;
            background: #ffffff;
            color: var(--text);
            font-family: "DM Sans", sans-serif;
            font-size: .92rem;
            outline: none;
        }

        .admin-modal__upload-input:focus {
            border-color: rgba(184, 144, 42, 0.7);
            box-shadow: 0 0 0 3px rgba(184, 144, 42, 0.14);
        }

        .admin-project-media-previews {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(96px, 1fr));
            gap: .65rem;
            margin-top: .75rem;
        }

        .admin-project-media-preview {
            position: relative;
            border-radius: .75rem;
            overflow: hidden;
            border: 1px solid rgba(13, 27, 62, 0.12);
            background: #fff;
            box-shadow: 0 10px 22px rgba(8, 17, 42, 0.06);
            display: grid;
        }

        .admin-project-media-preview-remove {
            appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(8, 17, 42, 0.62);
            color: #ffffff;
            width: 26px;
            height: 26px;
            border-radius: 999px;
            position: absolute;
            top: .35rem;
            right: .35rem;
            display: grid;
            place-items: center;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
            z-index: 2;
        }

        .admin-project-media-preview-remove:hover,
        .admin-project-media-preview-remove:focus-visible {
            border-color: rgba(212, 171, 74, 0.75);
        }

        .admin-project-media-preview img {
            width: 100%;
            height: 96px;
            object-fit: cover;
            display: block;
        }

        .admin-project-media-preview figcaption {
            padding: .45rem .55rem;
            font-size: .75rem;
            color: rgba(13, 27, 62, 0.78);
            line-height: 1.25;
            word-break: break-word;
        }

        @media (max-width: 840px) {
            .admin-modal__grid {
                grid-template-columns: 1fr;
            }
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
        $bannerPosX = (int) old('banner_position_x', $projectPage->banner_position_x ?? 50);
        $bannerPosY = (int) old('banner_position_y', $projectPage->banner_position_y ?? 50);
        $bannerPosX = max(0, min(100, $bannerPosX));
        $bannerPosY = max(0, min(100, $bannerPosY));
    @endphp

    <div class="admin-pages-head">
        <h1>Projetos</h1>
    </div>

    <section data-admin-tabs="projects">
        <div class="admin-home-tabs" role="tablist" aria-label="Seções da página">
            <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-admin-tab-trigger data-target="lista">Projetos</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-admin-tab-trigger data-target="pagina">Banner</button>
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

                <form id="projects-page-form" class="admin-form" action="{{ route('admin.projects.update-page') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="banner_position_x" value="{{ $bannerPosX }}">
                    <input type="hidden" name="banner_position_y" value="{{ $bannerPosY }}">

                    @if ($bannerSrc)
                        <div
                            class="admin-banner-guides"
                            data-banner-root
                            data-banner-guides
                            data-banner-guides-variant="page"
                            style="--admin-banner-guides-src: url('{{ $bannerSrc }}'); --admin-banner-guides-x: {{ $bannerPosX }}%; --admin-banner-guides-y: {{ $bannerPosY }}%; --admin-banner-guides-pos: {{ $bannerPosX }}% {{ $bannerPosY }}%;"
                        >
                            <button class="admin-banner-remove-btn" type="button" data-banner-remove aria-label="Remover banner">×</button>
                            <div class="admin-banner-guides__title">Prévia do banner</div>
                            <div class="admin-banner-guides__hint">Clique em qualquer prévia para ajustar o ponto focal (o centro do corte). O site usa cover.</div>
                            <div class="admin-banner-guides__grid">
                                <div class="admin-banner-guides__frame admin-banner-guides__ratio-desktop">
                                    <span class="admin-banner-guides__marker" aria-hidden="true"></span>
                                    <span class="admin-banner-guides__label">Desktop</span>
                                </div>
                                <div class="admin-banner-guides__frame admin-banner-guides__ratio-tablet">
                                    <span class="admin-banner-guides__marker" aria-hidden="true"></span>
                                    <span class="admin-banner-guides__label">Tablet</span>
                                </div>
                                <div class="admin-banner-guides__frame admin-banner-guides__ratio-mobile">
                                    <span class="admin-banner-guides__marker" aria-hidden="true"></span>
                                    <span class="admin-banner-guides__label">Celular</span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="banner_remove" value="0" data-banner-remove-input>
                        <div class="admin-banner-remove-hint" data-banner-hint hidden>Salve para que o banner seja excluído</div>
                    @endif

                    <label class="admin-dropzone-field">Banner do título (opcional)
                        <input type="file" name="banner_file" accept=".jpg,.jpeg,.png,.webp,.gif" hidden>
                        <div class="admin-dropzone" data-admin-dropzone data-preview-size="banner" data-banner-guides-variant="page">
                            <div class="admin-dropzone__area" data-dropzone-area>
                                <div class="admin-dropzone__head">
                                    <div class="admin-dropzone__title">Arraste e solte a imagem aqui</div>
                                    <div class="admin-dropzone__subtitle">ou clique para selecionar <span data-dropzone-count></span></div>
                                </div>
                                <div class="admin-dropzone__meta" data-dropzone-meta></div>
                            </div>
                            <div class="admin-dropzone__previews" data-dropzone-previews></div>
                        </div>
                    </label>
                    <div class="admin-home-upload__hint">Tamanho ideal: 1920×1080 (16:9) ou maior • o site corta automaticamente (cover)</div>
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

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="lista">
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Projetos cadastrados</div>
                        <h2>Cards (para editar, entre no projeto)</h2>
                    </div>
                    <button class="btn" type="button" data-open-project-modal>Novo projeto</button>
                </div>

                @if (($projects ?? collect())->isEmpty())
                    <p>Nenhum projeto cadastrado ainda.</p>
                @else
                    <div class="projects-cards-grid">
                        @foreach ($projects as $project)
                            <article class="project-card">
                                <form class="project-card__remove-form" action="{{ route('admin.projects.cards.destroy', $project) }}" method="POST" onsubmit="return confirm('Deseja excluir este projeto? Isso removerá também as mídias associadas.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="project-card__remove-btn" type="submit" title="Excluir projeto" aria-label="Excluir projeto">×</button>
                                </form>

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

    <div class="admin-modal" data-project-modal hidden>
        <div class="admin-modal__panel" role="dialog" aria-modal="true" aria-label="Novo projeto">
            <div class="admin-modal__head">
                <h2 class="admin-modal__title">Novo projeto</h2>
                <button class="admin-modal__close" type="button" data-project-modal-close aria-label="Fechar">×</button>
            </div>

            <div class="admin-modal__body">
                <div class="admin-modal__status" data-project-modal-status>Adicione mídias se quiser e finalize criando o projeto.</div>

                <div class="admin-modal__grid admin-modal__grid--single" data-project-modal-step="create">
                    <article class="admin-surface">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Projeto</div>
                                <h2>Dados do projeto</h2>
                            </div>
                        </div>

                        <form class="admin-form" data-project-create-form id="project-create-form">
                            <label>Título
                                <input type="text" name="title" maxlength="140" required>
                            </label>
                            <label>Subtítulo
                                <input type="text" name="subtitle" maxlength="255">
                            </label>
                            <label>Descrição
                                <textarea name="description" rows="3"></textarea>
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

                <div class="admin-modal__grid admin-modal__grid--single" data-project-modal-step="media">
                    <article class="admin-surface">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Mídias</div>
                                <h2>Adicionar imagens e vídeos</h2>
                            </div>
                            <button class="btn" type="button" data-project-open-add-media>Adicionar mídias</button>
                        </div>
                        <p>Use o botão acima para adicionar mídias ao projeto.</p>
                        <div class="alert-success" data-project-media-indicator hidden></div>
                <div class="admin-project-media-previews" data-project-media-previews hidden></div>
                    </article>
                </div>

                <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;justify-content:flex-end;">
                    <button class="btn" type="submit" form="project-create-form" data-project-create-submit>Criar projeto</button>
                </div>

            </div>
        </div>
    </div>

    @include('modals._project_add_media_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bannerRoot = document.querySelector('[data-banner-root]');
            var bannerRemoveBtn = document.querySelector('[data-banner-remove]');
            var bannerRemoveInput = document.querySelector('[data-banner-remove-input]');
            var bannerHint = document.querySelector('[data-banner-hint]');
            var flashKey = 'admin_flash_status';
            var flashParam = 'project_created';
            var flashMessage = 'Projeto criado com sucesso';

            (function () {
                try {
                    var msg = '';
                    try {
                        var url = new URL(window.location.href);
                        if (url.searchParams.get(flashParam) === '1') {
                            msg = flashMessage;
                            url.searchParams.delete(flashParam);
                            window.history.replaceState({}, '', url.toString());
                        }
                    } catch (e) {
                    }
                    if (!msg) {
                        msg = window.sessionStorage ? window.sessionStorage.getItem(flashKey) : '';
                        if (window.sessionStorage) window.sessionStorage.removeItem(flashKey);
                    }
                    if (!msg) return;
                    var container = document.querySelector('.admin-content');
                    if (!container) return;
                    var el = document.createElement('div');
                    el.className = 'alert-success';
                    el.textContent = msg;
                    container.prepend(el);
                } catch (e) {
                }
            })();

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

            var modal = document.querySelector('[data-project-modal]');
            var openModalBtn = document.querySelector('[data-open-project-modal]');
            var closeModalBtn = document.querySelector('[data-project-modal-close]');
            var statusEl = document.querySelector('[data-project-modal-status]');
            var stepCreate = document.querySelector('[data-project-modal-step="create"]');
            var stepMediaBlocks = document.querySelectorAll('[data-project-modal-step="media"]');
            var createForm = document.querySelector('[data-project-create-form]');
            var createSubmit = document.querySelector('[data-project-create-submit]');
            var finishBtn = document.querySelector('[data-project-finish]');
            var openEditLink = document.querySelector('[data-project-open-edit]');
            var openAddMediaBtn = document.querySelector('[data-project-open-add-media]');

            var addMediaModal = document.querySelector('[data-project-add-media-modal]');
            var addMediaStatusEl = addMediaModal ? addMediaModal.querySelector('[data-project-add-media-status]') : null;
            var addMediaUploadsEl = addMediaModal ? addMediaModal.querySelector('[data-project-add-media-uploads]') : null;
            var addMediaCloseBtns = addMediaModal ? addMediaModal.querySelectorAll('[data-project-add-media-close]') : [];

            var imagesForm = addMediaModal ? addMediaModal.querySelector('[data-project-images-form]') : null;
            var uploadImagesBtn = addMediaModal ? addMediaModal.querySelector('[data-project-upload-images]') : null;
            var videoForm = addMediaModal ? addMediaModal.querySelector('[data-project-video-form]') : null;
            var addVideoBtn = addMediaModal ? addMediaModal.querySelector('[data-project-add-video]') : null;

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            var createUrl = @json(route('admin.projects.cards.store'));
            var mediaUrlTemplate = @json(url('/admin/projetos/cards/__PROJECT__/imagens'));
            var mediaUpdateUrlTemplate = @json(url('/admin/projetos/cards/__PROJECT__/imagens/__IMAGE__'));
            var tempMediaUrl = @json(route('admin.projects.temp-media.store'));
            var tempMediaUpdateUrlTemplate = @json(url('/admin/projetos/midias-temporarias/__MEDIA__'));
            var tempMediaDeleteUrlTemplate = @json(url('/admin/projetos/midias-temporarias/__MEDIA__'));

            var createdProject = null;
            var draftToken = '';
            var hadSuccessfulUpload = false;
            var successfulMediaCount = 0;
            var mediaIndicatorEl = modal ? modal.querySelector('[data-project-media-indicator]') : null;
            var mediaPreviewsEl = modal ? modal.querySelector('[data-project-media-previews]') : null;
            var mediaPreviewBlobUrls = [];

            function revokePreviewBlobs() {
                if (!mediaPreviewBlobUrls.length) return;
                mediaPreviewBlobUrls.forEach(function (url) {
                    try {
                        if (String(url || '').startsWith('blob:')) {
                            URL.revokeObjectURL(url);
                        }
                    } catch (e) {
                    }
                });
                mediaPreviewBlobUrls = [];
            }

            function clearMediaPreviews() {
                revokePreviewBlobs();
                if (!mediaPreviewsEl) return;
                mediaPreviewsEl.innerHTML = '';
                mediaPreviewsEl.hidden = true;
            }

            function getTempMediaDeleteUrl(mediaId) {
                return tempMediaDeleteUrlTemplate.replace('__MEDIA__', String(mediaId));
            }

            function updateMediaCountIndicator() {
                if (successfulMediaCount <= 0) {
                    successfulMediaCount = 0;
                    setMediaIndicator('');
                    if (createSubmit && createSubmit.disabled) return;
                    if (hadSuccessfulUpload) {
                        setStatus('Nenhuma mídia selecionada.');
                    }
                    return;
                }

                var msg = successfulMediaCount === 1
                    ? '1 mídia adicionada com sucesso.'
                    : successfulMediaCount + ' mídias adicionadas com sucesso.';

                if (!createdProject || !createdProject.id) {
                    msg += ' (serão vinculadas ao projeto ao clicar em "Criar projeto")';
                }

                setMediaIndicator(msg);
                if (createSubmit && createSubmit.disabled) return;
                setStatus(msg);
            }

            function bumpMediaIndicator(countToAdd) {
                var count = Number.parseInt(String(countToAdd || '0'), 10);
                if (Number.isNaN(count) || count <= 0) return;
                successfulMediaCount += count;
                hadSuccessfulUpload = true;
                updateMediaCountIndicator();
            }

            function decrementMediaIndicator(countToSubtract) {
                var count = Number.parseInt(String(countToSubtract || '0'), 10);
                if (Number.isNaN(count) || count <= 0) return;
                successfulMediaCount -= count;
                updateMediaCountIndicator();
            }

            function addMediaPreview(url, label) {
                var src = String(url || '').trim();
                if (!mediaPreviewsEl || !src) return;

                var figure = document.createElement('figure');
                figure.className = 'admin-project-media-preview';
                figure.setAttribute('data-preview-status', 'pending');

                var img = document.createElement('img');
                img.alt = '';
                img.loading = 'lazy';
                img.decoding = 'async';
                img.src = src;

                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'admin-project-media-preview-remove';
                removeBtn.setAttribute('aria-label', 'Remover mídia');
                removeBtn.textContent = '×';

                var caption = document.createElement('figcaption');
                caption.textContent = String(label || '').trim();

                removeBtn.addEventListener('click', async function () {
                    figure._canceled = true;

                    var mediaKind = figure.getAttribute('data-media-kind') || '';
                    var mediaId = figure.getAttribute('data-media-id') || '';

                    if (mediaKind === 'temp' && mediaId) {
                        removeBtn.disabled = true;
                        try {
                            await deleteJson(getTempMediaDeleteUrl(mediaId), { draft_token: draftToken });
                            decrementMediaIndicator(1);
                            setMediaStatus('Mídia removida.');
                        } catch (e) {
                            removeBtn.disabled = false;
                            setMediaStatus(e && e.message ? e.message : 'Erro ao remover mídia');
                            return;
                        }
                    }

                    try {
                        if (src.startsWith('blob:')) {
                            URL.revokeObjectURL(src);
                        }
                    } catch (e) {
                    }

                    if (figure.parentNode) {
                        figure.parentNode.removeChild(figure);
                    }

                    if (mediaPreviewsEl && mediaPreviewsEl.children.length === 0) {
                        mediaPreviewsEl.hidden = true;
                    }
                });

                figure.appendChild(removeBtn);
                figure.appendChild(img);
                figure.appendChild(caption);
                mediaPreviewsEl.appendChild(figure);
                mediaPreviewsEl.hidden = false;

                if (src.startsWith('blob:')) {
                    mediaPreviewBlobUrls.push(src);
                }

                return figure;
            }

            function makeDraftToken() {
                if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                    return window.crypto.randomUUID();
                }
                var tpl = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
                return tpl.replace(/[xy]/g, function (c) {
                    var r = Math.random() * 16 | 0;
                    var v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function setStatus(text) {
                if (statusEl) statusEl.textContent = text;
            }

            function setMediaStatus(text) {
                if (addMediaStatusEl) addMediaStatusEl.textContent = text;
            }

            function setMediaIndicator(text) {
                if (!mediaIndicatorEl) return;
                var msg = String(text || '').trim();
                if (!msg) {
                    mediaIndicatorEl.hidden = true;
                    mediaIndicatorEl.textContent = '';
                    return;
                }
                mediaIndicatorEl.hidden = false;
                mediaIndicatorEl.textContent = msg;
            }

            function setOpen(open) {
                if (!modal) return;
                modal.hidden = !open;
                document.documentElement.style.overflow = open ? 'hidden' : '';
            }

            function setAddMediaOpen(open) {
                if (!addMediaModal) return;
                addMediaModal.hidden = !open;
                document.documentElement.style.overflow = (open || (modal && !modal.hidden)) ? 'hidden' : '';
            }

            function closeAddMediaModal() {
                if (hadSuccessfulUpload) {
                    setMediaStatus('Mídias adicionadas com sucesso.');
                    if (successfulMediaCount > 0) {
                        if (mediaIndicatorEl && mediaIndicatorEl.textContent) {
                            setStatus(mediaIndicatorEl.textContent);
                        } else {
                            setStatus('Mídias adicionadas com sucesso.');
                        }
                    } else {
                        setStatus('Mídias adicionadas com sucesso.');
                    }
                    window.setTimeout(function () {
                        setAddMediaOpen(false);
                    }, 250);
                    return;
                }
                setAddMediaOpen(false);
            }

            function showCreateStep() {
                createdProject = null;
                draftToken = makeDraftToken();
                successfulMediaCount = 0;
                setMediaIndicator('');
                clearMediaPreviews();
                if (stepCreate) stepCreate.hidden = false;
                stepMediaBlocks.forEach(function (el) {
                    el.hidden = false;
                });
                if (openEditLink) {
                    openEditLink.hidden = true;
                    openEditLink.setAttribute('href', '#');
                }
                setStatus('Adicione mídias se quiser e finalize criando o projeto.');
            }

            function showMediaStep(project) {
                createdProject = project;
                if (stepCreate) stepCreate.hidden = true;
                stepMediaBlocks.forEach(function (el) {
                    el.hidden = false;
                });
                if (openEditLink && project && project.edit_url) {
                    openEditLink.hidden = false;
                    openEditLink.setAttribute('href', project.edit_url);
                }
                setStatus('Projeto criado. Use o botão "Adicionar mídias" para enviar imagens e vídeos.');
            }

            function appendUploadItemTo(container, label, status, opts) {
                if (!container) return null;

                var row = document.createElement('div');
                row.className = 'admin-modal__uploads-item';

                var trigger = document.createElement('button');
                trigger.type = 'button';
                trigger.className = 'admin-modal__upload-trigger';
                trigger.textContent = label;

                var right = document.createElement('span');
                right.textContent = status;

                row.appendChild(trigger);
                row.appendChild(right);

                var editor = null;
                var descInput = null;

                if (opts && opts.previewUrl) {
                    editor = document.createElement('div');
                    editor.className = 'admin-modal__upload-editor';
                    editor.hidden = true;

                    var editorRow = document.createElement('div');
                    editorRow.className = 'admin-modal__upload-editor-row';

                    var img = document.createElement('img');
                    img.className = 'admin-modal__upload-thumb';
                    img.alt = '';
                    img.loading = 'lazy';
                    img.decoding = 'async';
                    img.src = opts.previewUrl;

                    descInput = document.createElement('input');
                    descInput.type = 'text';
                    descInput.maxLength = 255;
                    descInput.placeholder = 'Descrição da imagem';
                    descInput.className = 'admin-modal__upload-input';
                    descInput.disabled = true;

                    editorRow.appendChild(img);
                    editorRow.appendChild(descInput);
                    editor.appendChild(editorRow);
                    row.appendChild(editor);

                    trigger.addEventListener('click', function () {
                        editor.hidden = !editor.hidden;
                        if (!editor.hidden) {
                            descInput.focus();
                        }
                    });

                    descInput.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            descInput.blur();
                        }
                    });

                    descInput.addEventListener('blur', async function () {
                        if (descInput.disabled) return;
                        var updateUrl = row.getAttribute('data-update-url') || '';
                        if (!updateUrl) return;
                        var description = String(descInput.value || '').trim();
                        right.textContent = 'Salvando…';
                        try {
                            var kind = row.getAttribute('data-media-kind') || '';
                            if (kind === 'temp') {
                                await putJson(updateUrl, { draft_token: draftToken, description: description || null });
                            } else {
                                await putJson(updateUrl, { description: description || null });
                            }
                            right.textContent = 'Descrição salva';
                            window.setTimeout(function () {
                                if (right.textContent === 'Descrição salva') {
                                    right.textContent = 'Salvo';
                                }
                            }, 1200);
                        } catch (e) {
                            right.textContent = e && e.message ? e.message : 'Erro';
                        }
                    });
                }

                container.prepend(row);

                return {
                    row: row,
                    statusNode: right,
                    descInput: descInput,
                    setReady: function (mediaKind, updateUrl) {
                        row.setAttribute('data-media-kind', mediaKind || '');
                        row.setAttribute('data-update-url', updateUrl || '');
                        if (descInput) descInput.disabled = !(updateUrl && updateUrl !== '');
                    },
                };
            }

            function appendUploadItem(label, status, opts) {
                return appendUploadItemTo(addMediaUploadsEl, label, status, opts);
            }

            function setUploadStatus(node, text) {
                if (!node) return;
                node.textContent = text;
            }

            function getMediaUrl(projectId) {
                return mediaUrlTemplate.replace('__PROJECT__', String(projectId));
            }

            function getMediaUpdateUrl(projectId, imageId) {
                return mediaUpdateUrlTemplate
                    .replace('__PROJECT__', String(projectId))
                    .replace('__IMAGE__', String(imageId));
            }

            function getTempMediaUpdateUrl(mediaId) {
                return tempMediaUpdateUrlTemplate.replace('__MEDIA__', String(mediaId));
            }

            async function postJson(url, payload) {
                var res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload || {}),
                });
                var json = await res.json().catch(function () {
                    return null;
                });
                if (!res.ok) {
                    var msg = (json && (json.message || json.error)) ? (json.message || json.error) : 'Erro';
                    throw new Error(msg);
                }
                return json;
            }

            async function putJson(url, payload) {
                var res = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload || {}),
                });
                var json = await res.json().catch(function () {
                    return null;
                });
                if (!res.ok) {
                    var msg = (json && (json.message || json.error)) ? (json.message || json.error) : 'Erro';
                    throw new Error(msg);
                }
                return json;
            }

            async function deleteJson(url, payload) {
                var res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload || {}),
                });
                var json = await res.json().catch(function () {
                    return null;
                });
                if (!res.ok) {
                    var msg = (json && (json.message || json.error)) ? (json.message || json.error) : 'Erro';
                    throw new Error(msg);
                }
                return json;
            }

            async function postForm(url, formData) {
                var res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                var json = await res.json().catch(function () {
                    return null;
                });
                if (!res.ok) {
                    var msg = (json && (json.message || json.error)) ? (json.message || json.error) : 'Erro';
                    throw new Error(msg);
                }
                return json;
            }

            if (openModalBtn && modal) {
                openModalBtn.addEventListener('click', function () {
                    showCreateStep();
                    setOpen(true);
                });
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function () {
                    setOpen(false);
                });
            }

            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        setOpen(false);
                    }
                });
            }

            if (addMediaModal) {
                addMediaModal.addEventListener('click', function (event) {
                    if (event.target === addMediaModal) {
                        closeAddMediaModal();
                    }
                });
            }

            document.addEventListener('click', function (event) {
                var target = event.target;
                if (!(target instanceof HTMLElement)) return;

                if (target.closest('[data-project-add-media-close]')) {
                    closeAddMediaModal();
                    return;
                }

                if (target.closest('[data-project-open-add-media]')) {
                    hadSuccessfulUpload = false;
                    if (addMediaUploadsEl) addMediaUploadsEl.innerHTML = '';
                    if (createdProject && createdProject.id) {
                        setMediaStatus('Selecione as mídias. As imagens serão enviadas automaticamente.');
                    } else {
                        setMediaStatus('As mídias serão salvas como rascunho até você criar o projeto.');
                    }
                    setAddMediaOpen(true);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal && !modal.hidden) {
                    setOpen(false);
                }
                if (event.key === 'Escape' && addMediaModal && !addMediaModal.hidden) {
                    closeAddMediaModal();
                }
            });

            if (createForm) {
                createForm.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    if (!csrfToken) return;
                    if (createdProject && createdProject.id) {
                        setStatus('Este projeto já foi criado.');
                        return;
                    }
                    if (createSubmit) createSubmit.disabled = true;
                    setStatus('Criando projeto…');

                    var formData = new FormData(createForm);
                    var payload = {};
                    formData.forEach(function (value, key) {
                        if (key === 'is_active') {
                            payload[key] = true;
                            return;
                        }
                        payload[key] = value;
                    });
                    if (!payload.is_active) {
                        payload.is_active = false;
                    }
                    if (draftToken) {
                        payload.draft_token = draftToken;
                    }

                    try {
                        var result = await postJson(createUrl, payload);
                        if (result && result.project) {
                            createdProject = result.project;
                            try {
                                if (window.sessionStorage) window.sessionStorage.setItem(flashKey, flashMessage);
                            } catch (e) {
                            }
                            setAddMediaOpen(false);
                            setOpen(false);
                            try {
                                var url = new URL(window.location.href);
                                url.searchParams.set(flashParam, '1');
                                window.location.href = url.toString();
                            } catch (e) {
                                window.location.reload();
                            }
                            return;
                        } else {
                            setStatus('Projeto criado.');
                        }
                    } catch (e) {
                        setStatus(e && e.message ? e.message : 'Erro ao criar');
                    } finally {
                        if (createSubmit) createSubmit.disabled = false;
                    }
                });
            }

            var uploadingImages = false;
            async function uploadSelectedImages() {
                if (!imagesForm) return;
                if (uploadingImages) return;

                var fileInput = imagesForm.querySelector('input[type="file"]');
                if (!(fileInput instanceof HTMLInputElement) || !fileInput.files || fileInput.files.length === 0) {
                    setMediaStatus('Selecione ao menos uma imagem.');
                    return;
                }

                uploadingImages = true;
                if (uploadImagesBtn) uploadImagesBtn.disabled = true;
                setMediaStatus('Enviando imagens…');
                try {
                    if (addMediaUploadsEl && typeof addMediaUploadsEl.scrollIntoView === 'function') {
                        addMediaUploadsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {
                }

                var url = (createdProject && createdProject.id) ? getMediaUrl(createdProject.id) : tempMediaUrl;
                var files = Array.from(fileInput.files);
                var batchSuccessCount = 0;

                for (var i = 0; i < files.length; i += 1) {
                    var file = files[i];
                    var previewUrl = '';
                    try {
                        previewUrl = URL.createObjectURL(file);
                    } catch (e) {
                    }
                    var previewEl = addMediaPreview(previewUrl, file.name);
                    if (previewEl && previewEl._canceled) {
                        continue;
                    }
                    var uploadItem = appendUploadItem(file.name, 'Enviando…', { previewUrl: previewUrl });
                    var statusNode = uploadItem ? uploadItem.statusNode : null;
                    try {
                        var fd = new FormData();
                        fd.append('file', file);
                        if (!createdProject || !createdProject.id) {
                            fd.append('draft_token', draftToken);
                        }
                        var result = await postForm(url, fd);
                        var mediaId = result && result.media ? result.media.id : null;
                        if (previewEl) {
                            previewEl.setAttribute('data-preview-status', 'ready');
                            if (!createdProject || !createdProject.id) {
                                previewEl.setAttribute('data-media-kind', 'temp');
                                if (mediaId) previewEl.setAttribute('data-media-id', String(mediaId));
                            }
                        }
                        if (uploadItem && mediaId) {
                            if (createdProject && createdProject.id) {
                                uploadItem.setReady('project', getMediaUpdateUrl(createdProject.id, mediaId));
                            } else {
                                uploadItem.setReady('temp', getTempMediaUpdateUrl(mediaId));
                            }
                        }
                        setUploadStatus(statusNode, 'Salvo');
                        hadSuccessfulUpload = true;
                        if (previewEl && previewEl._canceled && mediaId && (!createdProject || !createdProject.id)) {
                            try {
                                await deleteJson(getTempMediaDeleteUrl(mediaId), { draft_token: draftToken });
                            } catch (e) {
                            }
                        } else {
                            batchSuccessCount += 1;
                        }
                    } catch (e) {
                        setUploadStatus(statusNode, e && e.message ? e.message : 'Erro');
                        if (previewEl) previewEl.setAttribute('data-preview-status', 'error');
                    }
                }

                try {
                    fileInput.value = '';
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (e) {
                    fileInput.value = '';
                }

                uploadingImages = false;
                if (uploadImagesBtn) uploadImagesBtn.disabled = false;
                setMediaStatus('Imagens adicionadas com sucesso.');
                if (batchSuccessCount > 0) {
                    bumpMediaIndicator(batchSuccessCount);
                }
            }

            if (uploadImagesBtn) {
                uploadImagesBtn.addEventListener('click', function () {
                    uploadSelectedImages();
                });
            }

            if (imagesForm) {
                var autoFileInput = imagesForm.querySelector('input[type="file"]');
                if (autoFileInput instanceof HTMLInputElement) {
                    autoFileInput.addEventListener('change', function () {
                        if (!autoFileInput.files || autoFileInput.files.length === 0) return;
                        window.setTimeout(function () {
                            uploadSelectedImages();
                        }, 0);
                    });
                }

                var autoDropzone = imagesForm.querySelector('[data-admin-dropzone]');
                if (autoDropzone) {
                    autoDropzone.addEventListener('drop', function () {
                        window.setTimeout(function () {
                            uploadSelectedImages();
                        }, 0);
                    });
                }
            }

            function extractYoutubeId(rawUrl) {
                var url = String(rawUrl || '').trim();
                if (!url) return '';

                var match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{6,})/);
                if (match && match[1]) return match[1];

                try {
                    var u = new URL(url);
                    var v = u.searchParams.get('v');
                    if (v) return v;
                } catch (e) {
                }

                return '';
            }

            function getYoutubeThumb(rawUrl) {
                var id = extractYoutubeId(rawUrl);
                if (!id) return '';
                return 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
            }

            if (addVideoBtn && videoForm) {
                addVideoBtn.addEventListener('click', async function () {
                    addYoutubeFromInput();
                });
            }

            var addingYoutube = false;
            async function addYoutubeFromInput() {
                if (!videoForm || addingYoutube) return;

                var urlInput = videoForm.querySelector('input[name="youtube_url"]');
                if (!(urlInput instanceof HTMLInputElement)) return;

                var youtubeUrl = String(urlInput.value || '').trim();
                if (!youtubeUrl) {
                    setMediaStatus('Informe um link do YouTube.');
                    return;
                }

                var thumb = getYoutubeThumb(youtubeUrl);
                var previewEl = addMediaPreview(thumb || '', 'YouTube');
                if (previewEl && previewEl._canceled) {
                    return;
                }

                addingYoutube = true;
                if (addVideoBtn) addVideoBtn.disabled = true;
                setMediaStatus('Adicionando vídeo…');
                try {
                    if (addMediaUploadsEl && typeof addMediaUploadsEl.scrollIntoView === 'function') {
                        addMediaUploadsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {
                }

                var uploadItem = appendUploadItem('Vídeo do YouTube', 'Enviando…', { previewUrl: thumb || '' });
                var statusNode = uploadItem ? uploadItem.statusNode : null;

                try {
                    var url = (createdProject && createdProject.id) ? getMediaUrl(createdProject.id) : tempMediaUrl;
                    var payload = { youtube_url: youtubeUrl };
                    if (!createdProject || !createdProject.id) {
                        payload.draft_token = draftToken;
                    }
                    var result = await postJson(url, payload);
                    var mediaId = result && result.media ? result.media.id : null;
                    if (previewEl) {
                        previewEl.setAttribute('data-preview-status', 'ready');
                        if (!createdProject || !createdProject.id) {
                            previewEl.setAttribute('data-media-kind', 'temp');
                            if (mediaId) previewEl.setAttribute('data-media-id', String(mediaId));
                        }
                    }
                    if (uploadItem && mediaId) {
                        if (createdProject && createdProject.id) {
                            uploadItem.setReady('project', getMediaUpdateUrl(createdProject.id, mediaId));
                        } else {
                            uploadItem.setReady('temp', getTempMediaUpdateUrl(mediaId));
                        }
                    }
                    setUploadStatus(statusNode, 'Salvo');
                    hadSuccessfulUpload = true;
                    if (previewEl && previewEl._canceled && mediaId && (!createdProject || !createdProject.id)) {
                        try {
                            await deleteJson(getTempMediaDeleteUrl(mediaId), { draft_token: draftToken });
                        } catch (e) {
                        }
                    } else {
                        bumpMediaIndicator(1);
                    }
                    urlInput.value = '';
                    setMediaStatus('Vídeo adicionado.');
                } catch (e) {
                    setUploadStatus(statusNode, e && e.message ? e.message : 'Erro');
                    setMediaStatus(e && e.message ? e.message : 'Erro ao adicionar vídeo');
                    if (previewEl) previewEl.setAttribute('data-preview-status', 'error');
                } finally {
                    addingYoutube = false;
                    if (addVideoBtn) addVideoBtn.disabled = false;
                }
            }

            if (videoForm) {
                var autoYoutubeInput = videoForm.querySelector('input[name="youtube_url"]');
                if (autoYoutubeInput instanceof HTMLInputElement) {
                    autoYoutubeInput.addEventListener('keydown', function (event) {
                        if (event.key !== 'Enter') return;
                        event.preventDefault();
                        window.setTimeout(function () {
                            addYoutubeFromInput();
                        }, 0);
                    });
                    autoYoutubeInput.addEventListener('blur', function () {
                        if (!String(autoYoutubeInput.value || '').trim()) return;
                        window.setTimeout(function () {
                            addYoutubeFromInput();
                        }, 0);
                    });
                }
            }

            if (finishBtn) {
                finishBtn.addEventListener('click', function () {
                    window.location.reload();
                });
            }
        });
    </script>
@endsection
