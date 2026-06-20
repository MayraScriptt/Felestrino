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
        }

        .admin-title-banner img {
            width: 100%;
            height: auto;
            display: block;
            aspect-ratio: 21 / 7;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .admin-title-banner img {
                aspect-ratio: 16 / 9;
            }
        }

        .project-detail-grid {
            display: grid;
            gap: 1rem;
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

        .project-image-item__thumb {
            display: grid;
            gap: .5rem;
            justify-items: start;
        }

        .project-image-item__meta {
            font-size: .82rem;
            color: rgba(13, 27, 62, 0.68);
            word-break: break-word;
        }

        .project-image-item__form {
            display: grid;
            gap: .6rem;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            align-items: end;
        }

        .project-media-grid {
            display: grid;
            gap: .85rem;
        }

        .admin-media-card {
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .85rem;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(13, 27, 62, 0.06);
        }

        .admin-media-card__head {
            display: grid;
            grid-template-columns: 110px 1fr auto;
            gap: .85rem;
            padding: .85rem;
            align-items: start;
        }

        .admin-media-card__thumb {
            width: 110px;
            height: 76px;
            border-radius: .65rem;
            overflow: hidden;
            border: 1px solid rgba(13, 27, 62, 0.12);
            background: rgba(13, 27, 62, 0.04);
        }

        .admin-media-card__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .admin-media-card__badges {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            align-items: center;
            margin-bottom: .35rem;
        }

        .admin-media-card__badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .15rem .55rem;
            border-radius: 999px;
            border: 1px solid rgba(184, 144, 42, 0.35);
            background: rgba(184, 144, 42, 0.12);
            color: #0d1b3e;
            font-family: "Rajdhani", sans-serif;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .admin-media-card__badge--video {
            border-color: rgba(13, 27, 62, 0.18);
            background: rgba(13, 27, 62, 0.06);
        }

        .admin-media-card__order {
            font-size: .78rem;
            color: rgba(13, 27, 62, 0.72);
            font-family: "Rajdhani", sans-serif;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .admin-media-card__status {
            font-size: .78rem;
            color: rgba(13, 27, 62, 0.62);
        }

        .admin-media-card__title {
            font-family: "Rajdhani", sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #0d1b3e;
            word-break: break-word;
        }

        .admin-media-card__subtitle {
            margin-top: .25rem;
            font-size: .9rem;
            color: rgba(13, 27, 62, 0.78);
            line-height: 1.45;
        }

        .admin-media-card__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            justify-content: flex-end;
            align-items: center;
        }

        .admin-media-card__actions form {
            margin: 0;
        }

        .admin-media-card__body {
            border-top: 1px solid rgba(13, 27, 62, 0.08);
            padding: .85rem;
            background: rgba(13, 27, 62, 0.02);
        }

        @media (max-width: 720px) {
            .admin-media-card__head {
                grid-template-columns: 96px 1fr;
            }

            .admin-media-card__thumb {
                width: 96px;
                height: 68px;
            }

            .admin-media-card__actions {
                grid-column: 1 / -1;
                justify-content: flex-start;
            }
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
        $bannerSrc = $resolveBannerSrc($project->banner_path ?? null);
    @endphp

    <div class="admin-pages-head">
        <h1>Projeto: {{ $project->title }}</h1>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;justify-content:flex-end;">
            <button class="btn" type="submit" form="project-main-form" data-project-save-btn>Salvar</button>
            <a class="btn btn-secondary" href="{{ route('admin.projects.edit') }}">Voltar para Projetos</a>
        </div>
    </div>

    <section data-admin-tabs="project">
        <div class="admin-home-tabs" role="tablist" aria-label="Seções do projeto">
            <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-admin-tab-trigger data-target="itens">Mídias</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-admin-tab-trigger data-target="dados">Dados</button>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="dados" hidden>
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Card e página</div>
                        <h2>Dados principais do projeto</h2>
                    </div>
                </div>

                <form id="project-main-form" class="admin-form" action="{{ route('admin.projects.cards.update', $project) }}" method="POST" enctype="multipart/form-data">
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
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="itens">
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Itens da galeria</div>
                        <h2>Editar mídias e descrições</h2>
                    </div>
                    <button class="btn" type="button" data-open-add-media>Adicionar mídias</button>
                </div>

                @if ($project->images->isEmpty())
                    <p>Este projeto ainda não possui mídias cadastradas.</p>
                @else
                    <div class="project-media-grid" data-media-list data-reorder-url="{{ route('admin.projects.images.reorder', $project) }}">
                        @foreach ($project->images as $image)
                            @php
                                $type = (string) ($image->type ?? 'image');
                                $isYoutube = $type === 'youtube' && ! empty($image->youtube_id);
                                $title = $isYoutube
                                    ? ('YouTube: '.(string) $image->youtube_id)
                                    : (is_string($image->image_path) && trim($image->image_path) !== '' ? basename($image->image_path) : 'Imagem');
                                $thumbSrc = $isYoutube
                                    ? ('https://img.youtube.com/vi/'.(string) $image->youtube_id.'/hqdefault.jpg')
                                    : ((str_starts_with((string) $image->image_path, 'imagens/') || str_starts_with((string) $image->image_path, 'images/')) ? asset($image->image_path) : asset('storage/'.$image->image_path));
                            @endphp

                            <article class="admin-media-card" data-media-card data-id="{{ $image->id }}">
                                <div class="admin-media-card__head">
                                    <div class="admin-media-card__thumb">
                                        <img src="{{ $thumbSrc }}" alt="" loading="lazy" decoding="async">
                                    </div>

                                    <div class="admin-media-card__info">
                                        <div class="admin-media-card__badges">
                                            <span class="admin-media-card__badge @if ($isYoutube) admin-media-card__badge--video @endif">
                                                @if ($isYoutube) Vídeo @else Imagem @endif
                                            </span>
                                            <span class="admin-media-card__order">Ordem: <span data-item-order>{{ $loop->iteration }}</span></span>
                                            <span class="admin-media-card__status" data-media-status>Pronto</span>
                                        </div>
                                        <div class="admin-media-card__title">{{ $title }}</div>
                                        @if ($image->description)
                                            <div class="admin-media-card__subtitle">{{ $image->description }}</div>
                                        @endif
                                    </div>

                                    <div class="admin-media-card__actions">
                                        <button class="btn btn-secondary" type="button" data-toggle-edit>Editar</button>
                                        <button class="btn btn-secondary" type="button" data-move-up aria-label="Mover para cima">↑</button>
                                        <button class="btn btn-secondary" type="button" data-move-down aria-label="Mover para baixo">↓</button>
                                        <form action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" method="POST" onsubmit="return confirm('Deseja remover este item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-secondary" type="submit">Excluir</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="admin-media-card__body" data-media-body hidden>
                                    <form class="admin-form" action="{{ route('admin.projects.images.update', [$project, $image]) }}" method="POST" data-media-update-form>
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="display_order" value="{{ $image->display_order }}" data-media-order-input>

                                        @if ($isYoutube)
                                            <label>Link do YouTube
                                                <input type="url" name="youtube_url" maxlength="2000" value="{{ $image->youtube_url ?: ('https://www.youtube.com/watch?v='.$image->youtube_id) }}">
                                            </label>
                                        @endif

                                        <label>Descrição
                                            <input type="text" name="description" maxlength="255" value="{{ $image->description }}">
                                        </label>

                                        <label class="checkbox-line">
                                            <input type="checkbox" name="is_active" value="1" @checked($image->is_active)>
                                            Item ativo
                                        </label>

                                        <button class="btn" type="submit">Salvar</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </article>
        </div>
    </section>

    @include('modals._project_add_media_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tabRoot = document.querySelector('[data-admin-tabs="project"]');
            if (!tabRoot) {
                return;
            }

            var buttons = tabRoot.querySelectorAll('[data-admin-tab-trigger]');
            var panels = tabRoot.querySelectorAll('[data-admin-tab-panel]');
            var saveBtn = document.querySelector('[data-project-save-btn]');

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

                if (saveBtn) {
                    saveBtn.style.display = target === 'dados' ? '' : 'none';
                }
            }

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    activateTab(button.getAttribute('data-target'));
                });
            });

            var activeBtn = tabRoot.querySelector('[data-admin-tab-trigger].is-active') || buttons[0];
            if (activeBtn) {
                activateTab(activeBtn.getAttribute('data-target'));
            }

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            if (!csrfToken) {
                return;
            }

            var addMediaModal = document.querySelector('[data-project-add-media-modal]');
            var openAddMediaBtn = document.querySelector('[data-open-add-media]');
            var addMediaStatus = addMediaModal ? addMediaModal.querySelector('[data-project-add-media-status]') : null;
            var addMediaUploads = addMediaModal ? addMediaModal.querySelector('[data-project-add-media-uploads]') : null;
            var addMediaCloseBtns = addMediaModal ? addMediaModal.querySelectorAll('[data-project-add-media-close]') : [];
            var hadSuccessfulUpload = false;

            var imagesForm = addMediaModal ? addMediaModal.querySelector('[data-project-images-form]') : null;
            var uploadImagesBtn = addMediaModal ? addMediaModal.querySelector('[data-project-upload-images]') : null;
            var videoForm = addMediaModal ? addMediaModal.querySelector('[data-project-video-form]') : null;
            var addVideoBtn = addMediaModal ? addMediaModal.querySelector('[data-project-add-video]') : null;

            var mediaUrl = @json(route('admin.projects.images.store', $project));
            var mediaUpdateUrlTemplate = @json(url('/admin/projetos/cards/'.$project->id.'/imagens/__IMAGE__'));

            function getMediaUpdateUrl(imageId) {
                return mediaUpdateUrlTemplate.replace('__IMAGE__', String(imageId));
            }

            function setAddMediaStatus(text) {
                if (addMediaStatus) addMediaStatus.textContent = text;
            }

            function setAddMediaOpen(open) {
                if (!addMediaModal) return;
                addMediaModal.hidden = !open;
                document.documentElement.style.overflow = open ? 'hidden' : '';
            }

            function closeAddMediaModal() {
                if (hadSuccessfulUpload) {
                    setAddMediaStatus('Mídias adicionadas com sucesso.');
                    window.setTimeout(function () {
                        setAddMediaOpen(false);
                        window.location.reload();
                    }, 250);
                    return;
                }
                setAddMediaOpen(false);
            }

            function appendUploadItem(label, status, opts) {
                if (!addMediaUploads) return null;

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
                            await putJson(updateUrl, { description: description || null });
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

                addMediaUploads.prepend(row);

                return {
                    row: row,
                    statusNode: right,
                    descInput: descInput,
                    setReady: function (updateUrl) {
                        row.setAttribute('data-update-url', updateUrl || '');
                        if (descInput) descInput.disabled = !(updateUrl && updateUrl !== '');
                    },
                };
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

            if (openAddMediaBtn && addMediaModal) {
                openAddMediaBtn.addEventListener('click', function () {
                    hadSuccessfulUpload = false;
                    if (addMediaUploads) addMediaUploads.innerHTML = '';
                    setAddMediaStatus('Selecione as mídias. As imagens serão enviadas automaticamente.');
                    setAddMediaOpen(true);
                });
            }

            addMediaCloseBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    closeAddMediaModal();
                });
            });

            if (addMediaModal) {
                addMediaModal.addEventListener('click', function (event) {
                    if (event.target === addMediaModal) {
                        closeAddMediaModal();
                    }
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && addMediaModal && !addMediaModal.hidden) {
                    closeAddMediaModal();
                }
            });

            var uploadingImages = false;
            async function uploadSelectedImages() {
                if (!imagesForm) return;
                if (uploadingImages) return;

                var fileInput = imagesForm.querySelector('input[type="file"]');
                if (!(fileInput instanceof HTMLInputElement) || !fileInput.files || fileInput.files.length === 0) {
                    setAddMediaStatus('Selecione ao menos uma imagem.');
                    return;
                }

                uploadingImages = true;
                if (uploadImagesBtn) uploadImagesBtn.disabled = true;
                setAddMediaStatus('Enviando imagens…');
                try {
                    if (addMediaUploads && typeof addMediaUploads.scrollIntoView === 'function') {
                        addMediaUploads.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {
                }

                var files = Array.from(fileInput.files);
                for (var i = 0; i < files.length; i += 1) {
                    var file = files[i];
                    var previewUrl = '';
                    try {
                        previewUrl = URL.createObjectURL(file);
                    } catch (e) {
                    }
                    var uploadItem = appendUploadItem(file.name, 'Enviando…', { previewUrl: previewUrl });
                    var statusNode = uploadItem ? uploadItem.statusNode : null;
                    try {
                        var fd = new FormData();
                        fd.append('file', file);
                        var result = await postForm(mediaUrl, fd);
                        var mediaId = result && result.media ? result.media.id : null;
                        if (uploadItem && mediaId) {
                            uploadItem.setReady(getMediaUpdateUrl(mediaId));
                        }
                        if (statusNode) statusNode.textContent = 'Salvo';
                        hadSuccessfulUpload = true;
                    } catch (e) {
                        if (statusNode) statusNode.textContent = e && e.message ? e.message : 'Erro';
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
                setAddMediaStatus('Imagens enviadas.');
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

            var addingYoutube = false;
            async function addYoutubeFromInput() {
                if (!videoForm || addingYoutube) return;

                var urlInput = videoForm.querySelector('input[name="youtube_url"]');
                if (!(urlInput instanceof HTMLInputElement)) return;

                var youtubeUrl = String(urlInput.value || '').trim();
                if (!youtubeUrl) {
                    setAddMediaStatus('Informe um link do YouTube.');
                    return;
                }

                var thumb = getYoutubeThumb(youtubeUrl);

                addingYoutube = true;
                if (addVideoBtn) addVideoBtn.disabled = true;
                setAddMediaStatus('Adicionando vídeo…');
                try {
                    if (addMediaUploads && typeof addMediaUploads.scrollIntoView === 'function') {
                        addMediaUploads.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {
                }

                var uploadItem = appendUploadItem('Vídeo do YouTube', 'Enviando…', { previewUrl: thumb || '' });
                var statusNode = uploadItem ? uploadItem.statusNode : null;
                try {
                    var result = await postJson(mediaUrl, { youtube_url: youtubeUrl });
                    var mediaId = result && result.media ? result.media.id : null;
                    if (uploadItem && mediaId) {
                        uploadItem.setReady(getMediaUpdateUrl(mediaId));
                    }
                    if (statusNode) statusNode.textContent = 'Salvo';
                    hadSuccessfulUpload = true;
                    urlInput.value = '';
                    setAddMediaStatus('Vídeo adicionado.');
                } catch (e) {
                    if (statusNode) statusNode.textContent = e && e.message ? e.message : 'Erro';
                    setAddMediaStatus(e && e.message ? e.message : 'Erro ao adicionar vídeo');
                } finally {
                    addingYoutube = false;
                    if (addVideoBtn) addVideoBtn.disabled = false;
                }
            }

            if (addVideoBtn) {
                addVideoBtn.addEventListener('click', function () {
                    addYoutubeFromInput();
                });
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

            var mediaList = document.querySelector('[data-media-list]');
            if (!mediaList) {
                return;
            }

            var reorderUrl = mediaList.getAttribute('data-reorder-url') || '';
            var reorderTimer = null;

            function getCards() {
                return Array.from(mediaList.querySelectorAll('[data-media-card]'));
            }

            function setStatus(card, text) {
                var el = card.querySelector('[data-media-status]');
                if (el) el.textContent = text;
            }

            function setAllStatus(text) {
                getCards().forEach(function (card) {
                    setStatus(card, text);
                });
            }

            function updateOrderUi() {
                getCards().forEach(function (card, index) {
                    var label = card.querySelector('[data-item-order]');
                    if (label) label.textContent = String(index + 1);
                    var orderInput = card.querySelector('[data-media-order-input]');
                    if (orderInput) orderInput.value = String(index + 1);
                });

                var cards = getCards();
                cards.forEach(function (card, index) {
                    var up = card.querySelector('[data-move-up]');
                    var down = card.querySelector('[data-move-down]');
                    if (up instanceof HTMLButtonElement) up.disabled = index === 0;
                    if (down instanceof HTMLButtonElement) down.disabled = index === cards.length - 1;
                });
            }

            function collectIds() {
                return getCards()
                    .map(function (card) {
                        return Number.parseInt(String(card.getAttribute('data-id') || ''), 10);
                    })
                    .filter(function (v) {
                        return !Number.isNaN(v);
                    });
            }

            function postReorder() {
                if (!reorderUrl) return;
                var ids = collectIds();
                if (ids.length === 0) return;
                setAllStatus('Salvando…');
                return fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ ids: ids }),
                })
                    .then(function (res) {
                        if (!res.ok) throw new Error('request_failed');
                        return res.json();
                    })
                    .then(function () {
                        setAllStatus('Salvo');
                    })
                    .catch(function () {
                        setAllStatus('Erro ao salvar');
                    });
            }

            function scheduleReorderSave() {
                if (reorderTimer) window.clearTimeout(reorderTimer);
                reorderTimer = window.setTimeout(function () {
                    postReorder();
                }, 220);
            }

            function moveCard(card, direction) {
                if (!card) return;
                if (direction === 'up') {
                    var prev = card.previousElementSibling;
                    if (!prev) return;
                    mediaList.insertBefore(card, prev);
                } else {
                    var next = card.nextElementSibling;
                    if (!next) return;
                    mediaList.insertBefore(next, card);
                }
                updateOrderUi();
                scheduleReorderSave();
            }

            mediaList.addEventListener('click', function (event) {
                var target = event.target;
                if (!(target instanceof HTMLElement)) return;
                var card = target.closest('[data-media-card]');
                if (!card) return;

                if (target.closest('[data-toggle-edit]')) {
                    var body = card.querySelector('[data-media-body]');
                    if (body instanceof HTMLElement) {
                        var nextHidden = !body.hidden;
                        body.hidden = nextHidden;
                        var btn = card.querySelector('[data-toggle-edit]');
                        if (btn instanceof HTMLButtonElement) {
                            btn.textContent = nextHidden ? 'Editar' : 'Fechar';
                        }
                    }
                }

                if (target.closest('[data-move-up]')) {
                    moveCard(card, 'up');
                }

                if (target.closest('[data-move-down]')) {
                    moveCard(card, 'down');
                }
            });

            mediaList.querySelectorAll('[data-media-update-form]').forEach(function (form) {
                form.addEventListener('submit', function () {
                    var card = form.closest('[data-media-card]');
                    if (!card) return;
                    setStatus(card, 'Salvando…');
                });
            });

            updateOrderUi();
        });
    </script>
@endsection
