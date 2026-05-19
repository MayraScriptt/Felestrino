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
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: .55rem .65rem;
            border-radius: .65rem;
            border: 1px solid rgba(13, 27, 62, 0.1);
            background: rgba(13, 27, 62, 0.02);
            font-size: .9rem;
            color: rgba(13, 27, 62, 0.86);
        }

        .admin-modal__uploads-item strong {
            font-weight: 700;
            color: #0d1b3e;
        }

        .admin-modal__uploads-item span {
            color: rgba(13, 27, 62, 0.68);
            white-space: nowrap;
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
                    <label class="admin-dropzone-field">Banner do título (opcional)
                        <input type="file" name="banner_file" accept=".jpg,.jpeg,.png,.webp,.gif" hidden>
                        <div class="admin-dropzone" data-admin-dropzone data-preview-size="banner">
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
                <div class="admin-modal__status" data-project-modal-status>Preencha os dados e crie o projeto.</div>

                <div class="admin-modal__grid admin-modal__grid--single" data-project-modal-step="create">
                    <article class="admin-surface">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Projeto</div>
                                <h2>Dados do projeto</h2>
                            </div>
                        </div>

                        <form class="admin-form" data-project-create-form>
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
                            <button class="btn" type="submit" data-project-create-submit>Criar projeto</button>
                        </form>
                    </article>
                </div>

                <div class="admin-modal__grid" data-project-modal-step="media" hidden>
                    <article class="admin-surface">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Mídias</div>
                                <h2>Adicionar imagens</h2>
                            </div>
                            <button class="btn" type="button" data-project-upload-images>Enviar</button>
                        </div>

                        <form class="admin-form" data-project-images-form>
                            <label class="admin-dropzone-field">Arquivos
                                <input type="file" name="files" accept=".jpg,.jpeg,.png,.webp,.gif" multiple hidden>
                                <div class="admin-dropzone" data-admin-dropzone>
                                    <div class="admin-dropzone__area" data-dropzone-area>
                                        <div class="admin-dropzone__head">
                                            <div class="admin-dropzone__title">Arraste e solte as imagens aqui</div>
                                            <div class="admin-dropzone__subtitle">ou clique para selecionar <span data-dropzone-count></span></div>
                                        </div>
                                        <div class="admin-dropzone__meta" data-dropzone-meta></div>
                                    </div>
                                    <div class="admin-dropzone__previews" data-dropzone-previews></div>
                                </div>
                            </label>
                            <label>Descrição (opcional)
                                <input type="text" name="description" maxlength="255" placeholder="Aplica para os uploads enviados agora">
                            </label>
                        </form>
                    </article>

                    <article class="admin-surface">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Mídias</div>
                                <h2>Adicionar vídeo do YouTube</h2>
                            </div>
                            <button class="btn" type="button" data-project-add-video>Adicionar</button>
                        </div>

                        <form class="admin-form" data-project-video-form>
                            <label>Link do YouTube
                                <input type="url" name="youtube_url" maxlength="2000" placeholder="https://www.youtube.com/watch?v=..." required>
                            </label>
                            <label>Descrição (opcional)
                                <input type="text" name="description" maxlength="255">
                            </label>
                        </form>
                    </article>
                </div>

                <div class="admin-surface" data-project-modal-step="media" hidden>
                    <div class="admin-section-head">
                        <div>
                            <div class="admin-section-kicker">Status</div>
                            <h2>Envios</h2>
                        </div>
                        <div style="display:flex;gap:.6rem;flex-wrap:wrap;justify-content:flex-end;">
                            <a class="btn btn-secondary" href="#" data-project-open-edit hidden>Abrir projeto</a>
                            <button class="btn" type="button" data-project-finish>Concluir</button>
                        </div>
                    </div>
                    <div class="admin-modal__uploads" data-project-uploads></div>
                </div>
            </div>
        </div>
    </div>

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

            var modal = document.querySelector('[data-project-modal]');
            var openModalBtn = document.querySelector('[data-open-project-modal]');
            var closeModalBtn = document.querySelector('[data-project-modal-close]');
            var statusEl = document.querySelector('[data-project-modal-status]');
            var stepCreate = document.querySelector('[data-project-modal-step="create"]');
            var stepMediaBlocks = document.querySelectorAll('[data-project-modal-step="media"]');
            var createForm = document.querySelector('[data-project-create-form]');
            var createSubmit = document.querySelector('[data-project-create-submit]');
            var imagesForm = document.querySelector('[data-project-images-form]');
            var uploadImagesBtn = document.querySelector('[data-project-upload-images]');
            var videoForm = document.querySelector('[data-project-video-form]');
            var addVideoBtn = document.querySelector('[data-project-add-video]');
            var uploadsEl = document.querySelector('[data-project-uploads]');
            var finishBtn = document.querySelector('[data-project-finish]');
            var openEditLink = document.querySelector('[data-project-open-edit]');

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            var createUrl = @json(route('admin.projects.cards.store'));
            var mediaUrlTemplate = @json(url('/admin/projetos/cards/__PROJECT__/imagens'));

            var createdProject = null;

            function setStatus(text) {
                if (statusEl) statusEl.textContent = text;
            }

            function setOpen(open) {
                if (!modal) return;
                modal.hidden = !open;
                document.documentElement.style.overflow = open ? 'hidden' : '';
            }

            function showCreateStep() {
                createdProject = null;
                if (stepCreate) stepCreate.hidden = false;
                stepMediaBlocks.forEach(function (el) {
                    el.hidden = true;
                });
                if (uploadsEl) uploadsEl.innerHTML = '';
                if (openEditLink) {
                    openEditLink.hidden = true;
                    openEditLink.setAttribute('href', '#');
                }
                setStatus('Preencha os dados e crie o projeto.');
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
                setStatus('Projeto criado. Agora você pode adicionar imagens e vídeos.');
            }

            function appendUploadItem(label, status) {
                if (!uploadsEl) return null;
                var row = document.createElement('div');
                row.className = 'admin-modal__uploads-item';
                var left = document.createElement('strong');
                left.textContent = label;
                var right = document.createElement('span');
                right.textContent = status;
                row.appendChild(left);
                row.appendChild(right);
                uploadsEl.prepend(row);
                return right;
            }

            function getMediaUrl(projectId) {
                return mediaUrlTemplate.replace('__PROJECT__', String(projectId));
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

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal && !modal.hidden) {
                    setOpen(false);
                }
            });

            if (createForm) {
                createForm.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    if (!csrfToken) return;
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

                    try {
                        var result = await postJson(createUrl, payload);
                        if (result && result.project) {
                            showMediaStep(result.project);
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

            if (uploadImagesBtn && imagesForm) {
                uploadImagesBtn.addEventListener('click', async function () {
                    if (!createdProject || !createdProject.id) {
                        setStatus('Crie o projeto antes de enviar mídias.');
                        return;
                    }
                    var fileInput = imagesForm.querySelector('input[type="file"]');
                    if (!(fileInput instanceof HTMLInputElement) || !fileInput.files || fileInput.files.length === 0) {
                        setStatus('Selecione ao menos uma imagem.');
                        return;
                    }
                    var descInput = imagesForm.querySelector('input[name="description"]');
                    var description = descInput && descInput.value ? String(descInput.value) : '';

                    uploadImagesBtn.disabled = true;
                    setStatus('Enviando imagens…');

                    var url = getMediaUrl(createdProject.id);
                    var files = Array.from(fileInput.files);

                    for (var i = 0; i < files.length; i += 1) {
                        var file = files[i];
                        var statusNode = appendUploadItem(file.name, 'Enviando…');
                        try {
                            var fd = new FormData();
                            fd.append('file', file);
                            if (description) fd.append('description', description);
                            await postForm(url, fd);
                            if (statusNode) statusNode.textContent = 'Salvo';
                        } catch (e) {
                            if (statusNode) statusNode.textContent = e && e.message ? e.message : 'Erro';
                        }
                    }

                    fileInput.value = '';
                    uploadImagesBtn.disabled = false;
                    setStatus('Imagens enviadas.');
                });
            }

            if (addVideoBtn && videoForm) {
                addVideoBtn.addEventListener('click', async function () {
                    if (!createdProject || !createdProject.id) {
                        setStatus('Crie o projeto antes de adicionar vídeos.');
                        return;
                    }
                    var urlInput = videoForm.querySelector('input[name="youtube_url"]');
                    if (!(urlInput instanceof HTMLInputElement)) return;
                    var youtubeUrl = String(urlInput.value || '').trim();
                    if (!youtubeUrl) {
                        setStatus('Informe um link do YouTube.');
                        return;
                    }
                    var descInput = videoForm.querySelector('input[name="description"]');
                    var description = descInput && descInput.value ? String(descInput.value) : '';

                    addVideoBtn.disabled = true;
                    setStatus('Adicionando vídeo…');

                    var statusNode = appendUploadItem('Vídeo do YouTube', 'Enviando…');
                    try {
                        await postJson(getMediaUrl(createdProject.id), {
                            youtube_url: youtubeUrl,
                            description: description || null,
                        });
                        if (statusNode) statusNode.textContent = 'Salvo';
                        urlInput.value = '';
                        if (descInput) descInput.value = '';
                        setStatus('Vídeo adicionado.');
                    } catch (e) {
                        if (statusNode) statusNode.textContent = e && e.message ? e.message : 'Erro';
                        setStatus(e && e.message ? e.message : 'Erro ao adicionar vídeo');
                    } finally {
                        addVideoBtn.disabled = false;
                    }
                });
            }

            if (finishBtn) {
                finishBtn.addEventListener('click', function () {
                    window.location.reload();
                });
            }
        });
    </script>
@endsection
