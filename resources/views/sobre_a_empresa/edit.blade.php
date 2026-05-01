@extends('layouts.admin')

@section('content')
    <article class="admin-surface">
        <div class="admin-section-head">
            <div>
                <div class="admin-section-kicker">Página institucional</div>
                <h2>Sobre a empresa</h2>
            </div>
            <button class="btn" type="submit" form="about-company-form">Salvar</button>
        </div>

        <form id="about-company-form" action="{{ route('admin.about-company.update') }}" method="POST">
            @csrf
            @method('PUT')

            <label>Conteúdo
                <textarea id="about-company-editor" name="content" rows="18">{{ old('content', $aboutPage->content ?? '') }}</textarea>
            </label>
        </form>
    </article>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    var pointerOffsetX = 0;
                    var pointerOffsetY = 0;
                    var mediaPositions = initialMediaPositions && typeof initialMediaPositions === 'object' ? initialMediaPositions : {};
                    var autosaveTimer = null;
                    var dragHighlightClass = 'about-editor-media--dragging';

                    function ensureBodyLayout() {
                        var body = editor.getBody();
                        if (!(body instanceof HTMLElement)) return null;
                        body.style.position = 'relative';
                        body.style.minHeight = 'max(70vh, 420px)';
                        body.classList.add('about-editor-canvas');
                        return body;
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

                    function getMaxBounds(body, element) {
                        var maxLeft = Math.max(0, body.clientWidth - element.offsetWidth);
                        var maxTop = Math.max(0, body.clientHeight - element.offsetHeight);
                        return { maxLeft: maxLeft, maxTop: maxTop };
                    }

                    function clampAndApply(element, left, top) {
                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;

                        var bounds = getMaxBounds(body, element);
                        var nextLeft = clamp(left, 0, bounds.maxLeft);
                        var nextTop = clamp(top, 0, bounds.maxTop);
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
                        ].join(''));
                    }

                    function getElementBox(element) {
                        return {
                            left: Number.parseFloat(element.style.left || '0') || 0,
                            top: Number.parseFloat(element.style.top || '0') || 0,
                            right: (Number.parseFloat(element.style.left || '0') || 0) + element.offsetWidth,
                            bottom: (Number.parseFloat(element.style.top || '0') || 0) + element.offsetHeight,
                            width: element.offsetWidth,
                            height: element.offsetHeight,
                        };
                    }

                    function resolveHeavyOverlap(target, mediaElements) {
                        if (!(target instanceof HTMLElement)) return;
                        var attempts = 0;
                        while (attempts < 12) {
                            attempts += 1;
                            var changed = false;
                            var targetBox = getElementBox(target);
                            for (var i = 0; i < mediaElements.length; i += 1) {
                                var other = mediaElements[i];
                                if (!(other instanceof HTMLElement) || other === target) continue;
                                var otherBox = getElementBox(other);

                                var interLeft = Math.max(targetBox.left, otherBox.left);
                                var interTop = Math.max(targetBox.top, otherBox.top);
                                var interRight = Math.min(targetBox.right, otherBox.right);
                                var interBottom = Math.min(targetBox.bottom, otherBox.bottom);
                                var interWidth = Math.max(0, interRight - interLeft);
                                var interHeight = Math.max(0, interBottom - interTop);
                                var interArea = interWidth * interHeight;
                                if (interArea <= 0) continue;

                                var minArea = Math.max(1, Math.min(targetBox.width * targetBox.height, otherBox.width * otherBox.height));
                                var overlapRatio = interArea / minArea;
                                if (overlapRatio < 0.72) continue;

                                clampAndApply(target, targetBox.left + 18, targetBox.top + 18);
                                changed = true;
                                break;
                            }

                            if (!changed) {
                                break;
                            }
                        }
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
                            var bounds = getMaxBounds(body, element);
                            clampAndApply(element, normalizedLeft * bounds.maxLeft, normalizedTop * bounds.maxTop);
                            if (Number.isFinite(zIndex) && zIndex > 0) {
                                element.style.zIndex = String(zIndex);
                                highestZ = Math.max(highestZ, zIndex);
                            }
                        });
                    }

                    function collectAndPersistPositions() {
                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;
                        var next = {};
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
                            var id = mediaId(element, index, sourceCount);
                            var bounds = getMaxBounds(body, element);
                            var leftPx = Number.parseFloat(element.style.left || '0') || 0;
                            var topPx = Number.parseFloat(element.style.top || '0') || 0;
                            var zIndex = Number.parseInt(element.style.zIndex || '1', 10);
                            next[id] = {
                                left: bounds.maxLeft > 0 ? clamp(leftPx / bounds.maxLeft, 0, 1) : 0,
                                top: bounds.maxTop > 0 ? clamp(topPx / bounds.maxTop, 0, 1) : 0,
                                width: body.clientWidth > 0 ? clamp(element.offsetWidth / body.clientWidth, 0, 1) : 0,
                                height: body.clientHeight > 0 ? clamp(element.offsetHeight / body.clientHeight, 0, 1) : 0,
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
                        var body = ensureBodyLayout();
                        if (!(body instanceof HTMLElement)) return;
                        var bodyRect = body.getBoundingClientRect();
                        var left = event.clientX - bodyRect.left - pointerOffsetX;
                        var top = event.clientY - bodyRect.top - pointerOffsetY;
                        clampAndApply(active, left, top);
                    }

                    function pointerUp() {
                        if (!(active instanceof HTMLElement)) return;
                        var body = ensureBodyLayout();
                        var allMedia = body ? getMediaElements(body) : [];
                        active.style.cursor = 'grab';
                        active.style.transition = 'left .16s ease, top .16s ease';
                        active.classList.remove(dragHighlightClass);
                        resolveHeavyOverlap(active, allMedia);
                        collectAndPersistPositions();
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
                    });

                    editor.on('SetContent change undo redo ObjectResized', function () {
                        window.requestAnimationFrame(function () {
                            restoreAllMedia();
                        });
                    });

                    window.addEventListener('resize', function () {
                        window.requestAnimationFrame(function () {
                            restoreAllMedia();
                            collectAndPersistPositions();
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
