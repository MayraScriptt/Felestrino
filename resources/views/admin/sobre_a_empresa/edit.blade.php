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
    </style>

    <div class="admin-pages-head">
        <h1>Sobre a empresa</h1>
        <button class="btn" type="submit" form="about-company-form">Salvar</button>
    </div>

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
        $bannerSrc = $resolveBannerSrc($aboutPage->banner_path ?? null);
        $bannerPosX = (int) old('banner_position_x', $aboutPage->banner_position_x ?? 50);
        $bannerPosY = (int) old('banner_position_y', $aboutPage->banner_position_y ?? 50);
        $bannerPosX = max(0, min(100, $bannerPosX));
        $bannerPosY = max(0, min(100, $bannerPosY));
    @endphp

    <form id="about-company-form" action="{{ route('admin.about-company.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <section data-admin-tabs="about">
            <div class="admin-home-tabs" role="tablist" aria-label="Seções da página">
                <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-admin-tab-trigger data-target="conteudo">Conteúdo</button>
                <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-admin-tab-trigger data-target="banner">Banner</button>
            </div>

            <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="conteudo">
                <article class="admin-surface">
                    <div class="admin-section-head">
                        <div>
                            <div class="admin-section-kicker">Página institucional</div>
                            <h2>Conteúdo</h2>
                        </div>
                    </div>

                    <label>Texto
                        <textarea id="about-company-editor" name="content" rows="18">{{ old('content', $aboutPage->content ?? '') }}</textarea>
                    </label>
                </article>
            </div>

            <div class="admin-home-tab-panel" role="tabpanel" data-admin-tab-panel="banner" hidden>
                <article class="admin-surface">
                    <div class="admin-section-head">
                        <div>
                            <div class="admin-section-kicker">Página institucional</div>
                            <h2>Banner do título</h2>
                        </div>
                    </div>

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

                    <label class="admin-dropzone-field">Imagem do banner (opcional)
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

                    <label>Subtítulo do banner
                        <input type="text" name="banner_subtitle" maxlength="255" value="{{ old('banner_subtitle', $aboutPage->banner_subtitle ?? '') }}">
                    </label>

                    <label>Descrição do banner
                        <textarea name="banner_description" rows="3">{{ old('banner_description', $aboutPage->banner_description ?? '') }}</textarea>
                    </label>
                </article>
            </div>
        </section>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bannerRoot = document.querySelector('[data-banner-root]');
            var bannerRemoveBtn = document.querySelector('[data-banner-remove]');
            var bannerRemoveInput = document.querySelector('[data-banner-remove-input]');
            var bannerHint = document.querySelector('[data-banner-hint]');

            var tabRoot = document.querySelector('[data-admin-tabs="about"]');
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

            if (!window.tinymce) {
                return;
            }

            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            var uploadUrl = @json(route('admin.about-company.upload'));
            var mediaLayoutUrl = @json(route('admin.about-company.media-layout'));
            var initialMediaPositions = @json($aboutPage->media_positions ?? []);
            var cssAsset = @json(file_exists(public_path('mix-manifest.json')) ? mix('/css/app.css') : asset('css/app.css'));
            var googleFonts = 'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@400;500;700&display=swap';

            window.tinymce.init({
                selector: '#about-company-editor',
                height: 560,
                menubar: false,
                branding: false,
                language: 'pt_BR',
                language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@26.4.6/langs6/pt_BR.min.js',
                plugins: 'autolink link image media lists table code quickbars',
                toolbar: 'undo redo | blocks styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media table | code',
                block_formats: 'Parágrafo=p;Título 2=h2;Título 3=h3;Título 4=h4;Citação=blockquote',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                media_live_embeds: true,
                object_resizing: true,
                image_advtab: true,
                image_dimensions: true,
                image_description: true,
                image_title: true,
                style_formats: [
                    { title: 'Imagem centralizada', selector: 'img', styles: { display: 'block', margin: '1rem auto', float: 'none' } },
                    { title: 'Imagem à esquerda', selector: 'img', styles: { display: 'inline-block', float: 'left', margin: '0 1rem 1rem 0' } },
                    { title: 'Imagem à direita', selector: 'img', styles: { display: 'inline-block', float: 'right', margin: '0 0 1rem 1rem' } },
                    { title: 'Imagem sem flutuar', selector: 'img', styles: { display: 'inline-block', float: 'none', margin: '0' } }
                ],
                content_css: [googleFonts, cssAsset],
                body_class: 'prose',
                content_style: [
                    'body{background:#ffffff;color:#0d1b3e;font-family:"DM Sans","Segoe UI",Roboto,Arial,sans-serif;margin:0 auto;width:min(1140px,92%);padding:2rem 0;line-height:1.7;}',
                    'img{max-width:100%;height:auto;display:inline-block;border-radius:10px;}',
                    'iframe,video{max-width:100%;height:auto;}',
                    'p{margin:0 0 1rem;}'
                ].join(''),
                quickbars_selection_toolbar: 'bold italic underline | alignleft aligncenter alignright | bullist numlist | link',
                quickbars_insert_toolbar: 'image media',
                extended_valid_elements: 'img[src|alt|title|width|height|style|class],iframe[src|frameborder|style|scrolling|class|width|height|name|align|allow|allowfullscreen],video[src|controls|width|height|style|class],source[src|type]',
                setup: function (editor) {
                    var highestZ = 1;
                    var active = null;
                    var selected = null;
                    var pointerOffsetX = 0;
                    var pointerOffsetY = 0;
                    var mediaPositions = initialMediaPositions && typeof initialMediaPositions === 'object' ? initialMediaPositions : {};
                    var autosaveTimer = null;
                    var dragHighlightClass = 'about-editor-media--dragging';
                    var coordsBadge = null;
                    var zControls = null;

                    function ensureBodyLayout() {
                        var body = editor.getBody();
                        if (!(body instanceof HTMLElement)) return null;
                        body.style.position = 'relative';
                        body.style.minHeight = 'max(70vh, 420px)';
                        body.classList.add('about-editor-canvas');
                        return body;
                    }

                    function getEditorWin() {
                        var win = editor.getWin();
                        return win && typeof win === 'object' ? win : null;
                    }

                    function getViewportMetrics() {
                        var win = getEditorWin();
                        if (!win) {
                            return { scrollLeft: 0, scrollTop: 0, width: 0, height: 0 };
                        }
                        var scrollLeft = typeof win.pageXOffset === 'number' ? win.pageXOffset : (typeof win.scrollX === 'number' ? win.scrollX : 0);
                        var scrollTop = typeof win.pageYOffset === 'number' ? win.pageYOffset : (typeof win.scrollY === 'number' ? win.scrollY : 0);
                        var width = typeof win.innerWidth === 'number' ? win.innerWidth : 0;
                        var height = typeof win.innerHeight === 'number' ? win.innerHeight : 0;
                        return {
                            scrollLeft: Math.max(0, Math.round(scrollLeft)),
                            scrollTop: Math.max(0, Math.round(scrollTop)),
                            width: Math.max(0, Math.round(width)),
                            height: Math.max(0, Math.round(height)),
                        };
                    }

                    function hashString(input) {
                        var str = String(input || '');
                        var hash = 2166136261;
                        for (var i = 0; i < str.length; i += 1) {
                            hash ^= str.charCodeAt(i);
                            hash += (hash << 1) + (hash << 4) + (hash << 7) + (hash << 8) + (hash << 24);
                        }
                        return (hash >>> 0).toString(36);
                    }

                    function mediaId(element, index, sourceCount) {
                        var existing = element.getAttribute('data-media-id');
                        if (existing) return existing;
                        var tag = String(element.tagName || 'media').toLowerCase();
                        var source = '';
                        if (element instanceof HTMLImageElement) {
                            source = element.currentSrc || element.getAttribute('src') || '';
                        } else if (element instanceof HTMLVideoElement) {
                            source = element.currentSrc || element.getAttribute('src') || element.getAttribute('poster') || '';
                        } else if (element instanceof HTMLIFrameElement) {
                            source = element.getAttribute('src') || '';
                        }
                        var count = sourceCount[source] || 1;
                        var nextId = 'm-' + tag + '-' + hashString(tag + '|' + source) + '-' + String(count) + '-' + String(index);
                        element.setAttribute('data-media-id', nextId);
                        return nextId;
                    }

                    function clamp(value, min, max) {
                        return Math.min(Math.max(value, min), max);
                    }

                    function getMediaElements(body) {
                        return Array.from(body.querySelectorAll('img,video,iframe'));
                    }

                    function clampAndApplyToViewport(element, desiredLeft, desiredTop) {
                        if (!(element instanceof HTMLElement)) return;
                        var metrics = getViewportMetrics();
                        var width = element.offsetWidth;
                        var height = element.offsetHeight;

                        var maxLeft = metrics.scrollLeft + Math.max(0, metrics.width - width);
                        var maxTop = metrics.scrollTop + Math.max(0, metrics.height - height);
                        var minLeft = metrics.scrollLeft;
                        var minTop = metrics.scrollTop;

                        var nextLeft = clamp(desiredLeft, minLeft, maxLeft);
                        var nextTop = clamp(desiredTop, minTop, maxTop);

                        element.style.left = Math.round(nextLeft) + 'px';
                        element.style.top = Math.round(nextTop) + 'px';
                    }

                    function makeMediaDraggable(element) {
                        if (!(element instanceof HTMLElement)) return;
                        element.classList.add('about-editor-media');
                        element.style.cursor = 'grab';
                        element.style.touchAction = 'none';
                        element.style.userSelect = 'none';
                        element.style.position = 'absolute';
                        element.style.transition = 'left .16s ease, top .16s ease, box-shadow .16s ease, transform .16s ease';
                        element.style.maxWidth = 'min(100%, 92vw)';
                        element.style.maxHeight = 'min(100%, 82vh)';
                        var currentZ = Number.parseInt(element.style.zIndex || '1', 10);
                        if (Number.isFinite(currentZ)) {
                            highestZ = Math.max(highestZ, currentZ);
                        } else {
                            element.style.zIndex = '1';
                        }
                    }

                    function injectEditorDragStyles() {
                        editor.dom.addStyle([
                            '.about-editor-canvas{position:relative;overflow:auto;}',
                            '.about-editor-media{outline:1px dashed rgba(184,144,42,.4);outline-offset:2px;}',
                            '.about-editor-media--dragging{cursor:grabbing!important;box-shadow:0 14px 30px rgba(13,27,62,.24);transform:scale(1.01);outline:2px solid rgba(184,144,42,.7);}',
                            '.about-editor-coords{position:fixed;right:10px;top:10px;z-index:2147483647;background:rgba(13,27,62,.86);color:#fff;font:600 12px/1.2 "DM Sans",Arial,sans-serif;border-radius:10px;padding:8px 10px;pointer-events:none;opacity:0;transform:translateY(-4px);transition:opacity .12s ease,transform .12s ease;}',
                            '.about-editor-coords.is-visible{opacity:1;transform:translateY(0);}',
                            '.about-editor-z{position:fixed;z-index:2147483646;display:flex;gap:6px;align-items:center;background:rgba(255,255,255,.96);border:1px solid rgba(184,144,42,.35);border-radius:999px;padding:6px 8px;box-shadow:0 10px 26px rgba(8,17,42,.18);}',
                            '.about-editor-z button{appearance:none;border:1px solid rgba(13,27,62,.18);background:#fff;border-radius:999px;padding:4px 8px;font:700 12px/1 "Rajdhani",Arial,sans-serif;letter-spacing:.06em;text-transform:uppercase;color:#0d1b3e;cursor:pointer;}',
                            '.about-editor-z button:active{transform:translateY(1px);}',
                            '.about-editor-z span{font:700 12px/1 "Rajdhani",Arial,sans-serif;letter-spacing:.06em;color:rgba(13,27,62,.72);padding:0 4px;}',
                        ].join(''));
                    }

                    function ensureCoordsBadge() {
                        if (coordsBadge instanceof HTMLElement) return coordsBadge;
                        var doc = editor.getDoc();
                        if (!(doc instanceof Document)) return null;
                        var el = doc.createElement('div');
                        el.className = 'about-editor-coords';
                        el.textContent = '';
                        doc.body.appendChild(el);
                        coordsBadge = el;
                        return el;
                    }

                    function showCoordsFor(element) {
                        if (!(element instanceof HTMLElement)) return;
                        var badge = ensureCoordsBadge();
                        if (!(badge instanceof HTMLElement)) return;
                        var left = Math.round(Number.parseFloat(element.style.left || '0') || 0);
                        var top = Math.round(Number.parseFloat(element.style.top || '0') || 0);
                        var zIndex = Number.parseInt(element.style.zIndex || '1', 10);
                        badge.textContent = 'x: ' + left + 'px  y: ' + top + 'px  z: ' + (Number.isFinite(zIndex) ? zIndex : 1);
                        badge.classList.add('is-visible');
                    }

                    function hideCoords() {
                        if (!(coordsBadge instanceof HTMLElement)) return;
                        coordsBadge.classList.remove('is-visible');
                    }

                    function ensureZControls() {
                        if (zControls instanceof HTMLElement) return zControls;
                        var doc = editor.getDoc();
                        if (!(doc instanceof Document)) return null;

                        var wrap = doc.createElement('div');
                        wrap.className = 'about-editor-z';

                        var down = doc.createElement('button');
                        down.type = 'button';
                        down.textContent = 'Z-';

                        var label = doc.createElement('span');
                        label.textContent = 'z';

                        var up = doc.createElement('button');
                        up.type = 'button';
                        up.textContent = 'Z+';

                        wrap.appendChild(down);
                        wrap.appendChild(label);
                        wrap.appendChild(up);
                        doc.body.appendChild(wrap);
                        zControls = wrap;

                        down.addEventListener('click', function (event) {
                            event.preventDefault();
                            if (!(selected instanceof HTMLElement)) return;
                            var current = Number.parseInt(selected.style.zIndex || '1', 10);
                            if (!Number.isFinite(current)) current = 1;
                            var next = Math.max(1, current - 1);
                            selected.style.zIndex = String(next);
                            collectAndPersistPositions();
                            positionZControls(selected);
                        });

                        up.addEventListener('click', function (event) {
                            event.preventDefault();
                            if (!(selected instanceof HTMLElement)) return;
                            var current = Number.parseInt(selected.style.zIndex || '1', 10);
                            if (!Number.isFinite(current)) current = 1;
                            var next = Math.min(9999, current + 1);
                            highestZ = Math.max(highestZ, next);
                            selected.style.zIndex = String(next);
                            collectAndPersistPositions();
                            positionZControls(selected);
                        });

                        return wrap;
                    }

                    function positionZControls(element) {
                        var wrap = ensureZControls();
                        if (!(wrap instanceof HTMLElement)) return;
                        if (!(element instanceof HTMLElement)) {
                            wrap.style.display = 'none';
                            return;
                        }
                        var win = getEditorWin();
                        if (!win) return;
                        var rect = element.getBoundingClientRect();
                        wrap.style.display = 'flex';
                        var left = Math.max(10, Math.min(rect.left, win.innerWidth - wrap.offsetWidth - 10));
                        var top = Math.max(10, Math.min(rect.top - wrap.offsetHeight - 10, win.innerHeight - wrap.offsetHeight - 10));
                        wrap.style.left = Math.round(left) + 'px';
                        wrap.style.top = Math.round(top) + 'px';
                    }

                    function restoreAllMedia() {
                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;
                        var sourceCount = {};
                        var mediaElements = getMediaElements(body);

                        mediaElements.forEach(function (element, index) {
                            if (!(element instanceof HTMLElement)) return;
                            var source = '';
                            if (element instanceof HTMLImageElement) {
                                source = element.currentSrc || element.getAttribute('src') || '';
                            } else if (element instanceof HTMLVideoElement) {
                                source = element.currentSrc || element.getAttribute('src') || element.getAttribute('poster') || '';
                            } else if (element instanceof HTMLIFrameElement) {
                                source = element.getAttribute('src') || '';
                            }
                            sourceCount[source] = (sourceCount[source] || 0) + 1;

                            makeMediaDraggable(element);
                            var id = mediaId(element, index, sourceCount);
                            var saved = mediaPositions[id];
                            if (!saved || typeof saved !== 'object') return;
                            var left = Number(saved.left);
                            var top = Number(saved.top);
                            var zIndex = Number.parseInt(String(saved.z || 1), 10);
                            if (!Number.isFinite(left) || !Number.isFinite(top)) return;
                            var normalizedLeft = clamp(left, 0, 1);
                            var normalizedTop = clamp(top, 0, 1);
                            var metrics = getViewportMetrics();
                            var maxLeft = Math.max(0, metrics.width - element.offsetWidth);
                            var maxTop = Math.max(0, metrics.height - element.offsetHeight);
                            clampAndApplyToViewport(
                                element,
                                metrics.scrollLeft + (maxLeft > 0 ? normalizedLeft * maxLeft : 0),
                                metrics.scrollTop + (maxTop > 0 ? normalizedTop * maxTop : 0)
                            );
                            if (Number.isFinite(zIndex) && zIndex > 0) {
                                element.style.zIndex = String(zIndex);
                                highestZ = Math.max(highestZ, zIndex);
                            }
                        });

                        if (selected instanceof HTMLElement) {
                            positionZControls(selected);
                        }
                    }

                    function collectAndPersistPositions() {
                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;
                        var next = {};
                        var sourceCount = {};
                        var mediaElements = getMediaElements(body);
                        var metrics = getViewportMetrics();

                        mediaElements.forEach(function (element, index) {
                            if (!(element instanceof HTMLElement)) return;
                            var source = '';
                            if (element instanceof HTMLImageElement) {
                                source = element.currentSrc || element.getAttribute('src') || '';
                            } else if (element instanceof HTMLVideoElement) {
                                source = element.currentSrc || element.getAttribute('src') || element.getAttribute('poster') || '';
                            } else if (element instanceof HTMLIFrameElement) {
                                source = element.getAttribute('src') || '';
                            }
                            sourceCount[source] = (sourceCount[source] || 0) + 1;
                            var id = mediaId(element, index, sourceCount);
                            var leftPx = Number.parseFloat(element.style.left || '0') || 0;
                            var topPx = Number.parseFloat(element.style.top || '0') || 0;
                            var zIndex = Number.parseInt(element.style.zIndex || '1', 10);
                            var maxLeft = Math.max(0, metrics.width - element.offsetWidth);
                            var maxTop = Math.max(0, metrics.height - element.offsetHeight);
                            var visibleLeft = leftPx - metrics.scrollLeft;
                            var visibleTop = topPx - metrics.scrollTop;
                            next[id] = {
                                left: maxLeft > 0 ? clamp(visibleLeft / maxLeft, 0, 1) : 0,
                                top: maxTop > 0 ? clamp(visibleTop / maxTop, 0, 1) : 0,
                                width: metrics.width > 0 ? clamp(element.offsetWidth / metrics.width, 0, 1) : 0,
                                height: metrics.height > 0 ? clamp(element.offsetHeight / metrics.height, 0, 1) : 0,
                                z: Number.isFinite(zIndex) && zIndex > 0 ? zIndex : 1,
                            };
                        });

                        mediaPositions = next;
                        if (autosaveTimer) {
                            window.clearTimeout(autosaveTimer);
                        }
                        autosaveTimer = window.setTimeout(function () {
                            window.fetch(mediaLayoutUrl, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    media_positions: mediaPositions
                                })
                            }).catch(function () {});
                        }, 420);
                    }

                    function pointerMove(event) {
                        if (!(active instanceof HTMLElement)) return;
                        var metrics = getViewportMetrics();
                        var left = metrics.scrollLeft + event.clientX - pointerOffsetX;
                        var top = metrics.scrollTop + event.clientY - pointerOffsetY;
                        clampAndApplyToViewport(active, left, top);
                        showCoordsFor(active);
                        if (selected === active) {
                            positionZControls(active);
                        }
                    }

                    function pointerUp() {
                        if (!(active instanceof HTMLElement)) return;
                        active.style.cursor = 'grab';
                        active.style.transition = 'left .16s ease, top .16s ease';
                        active.classList.remove(dragHighlightClass);
                        collectAndPersistPositions();
                        hideCoords();
                        active = null;
                    }

                    editor.on('init', function () {
                        injectEditorDragStyles();
                        restoreAllMedia();

                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;

                        body.addEventListener('pointerdown', function (event) {
                            var target = event.target;
                            if (!(target instanceof Element)) return;
                            var media = target.closest('img,video,iframe');
                            if (!(media instanceof HTMLElement)) return;
                            event.preventDefault();

                            makeMediaDraggable(media);
                            selected = media;
                            positionZControls(media);
                            media.style.transition = 'none';
                            media.style.cursor = 'grabbing';
                            media.classList.add(dragHighlightClass);
                            highestZ += 1;
                            media.style.zIndex = String(highestZ);
                            active = media;

                            var rect = media.getBoundingClientRect();
                            pointerOffsetX = event.clientX - rect.left;
                            pointerOffsetY = event.clientY - rect.top;
                            if (media.setPointerCapture) {
                                try {
                                    media.setPointerCapture(event.pointerId);
                                } catch (e) {}
                            }
                        });

                        body.addEventListener('pointermove', pointerMove, { passive: true });
                        body.addEventListener('pointerup', pointerUp);
                        body.addEventListener('pointercancel', pointerUp);
                        body.addEventListener('lostpointercapture', pointerUp);

                        body.addEventListener('click', function (event) {
                            var target = event.target;
                            if (!(target instanceof Element)) return;
                            var media = target.closest('img,video,iframe');
                            if (!(media instanceof HTMLElement)) return;
                            selected = media;
                            positionZControls(media);
                        });

                        var win = getEditorWin();
                        if (win) {
                            win.addEventListener('scroll', function () {
                                if (selected instanceof HTMLElement) {
                                    positionZControls(selected);
                                }
                            }, { passive: true });
                        }
                    });

                    editor.on('SetContent change undo redo ObjectResized', function () {
                        window.requestAnimationFrame(function () {
                            restoreAllMedia();
                        });
                    });

                    window.addEventListener('resize', function () {
                        window.requestAnimationFrame(function () {
                            restoreAllMedia();
                        });
                    }, { passive: true });
                },
                images_upload_handler: function (blobInfo) {
                    return new Promise(function (resolve, reject) {
                        var formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        window.fetch(uploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        }).then(function (response) {
                            return response.json().then(function (json) {
                                if (!response.ok) {
                                    reject('Erro ao enviar imagem');
                                    return;
                                }
                                if (!json || typeof json.location !== 'string') {
                                    reject('Resposta inválida do servidor');
                                    return;
                                }
                                resolve(json.location);
                            }).catch(function () {
                                reject('Erro ao processar resposta');
                            });
                        }).catch(function () {
                            reject('Erro ao enviar imagem');
                        });
                    });
                },
            });
        });
    </script>
@endsection
