(function () {
    function isFileDrag(event) {
        var dt = event && event.dataTransfer;
        if (!dt || !dt.types) return false;
        return Array.prototype.indexOf.call(dt.types, 'Files') !== -1;
    }

    function createObjectUrl(file) {
        try {
            return URL.createObjectURL(file);
        } catch (e) {
            return '';
        }
    }

    function revokeUrls(urls) {
        if (!urls) return;
        urls.forEach(function (url) {
            if (!url) return;
            try {
                URL.revokeObjectURL(url);
            } catch (e) {}
        });
    }

    function bytesToMb(bytes) {
        return Math.round((bytes / (1024 * 1024)) * 10) / 10;
    }

    function clampPercent(value, fallback) {
        var parsed = parseInt(value, 10);
        var next = isNaN(parsed) ? (typeof fallback === 'number' ? fallback : 50) : parsed;
        if (next < 0) return 0;
        if (next > 100) return 100;
        return next;
    }

    function readBannerPosition(guidesEl) {
        var form = guidesEl ? guidesEl.closest('form') : null;
        var xInput = form ? form.querySelector('input[name="banner_position_x"]') : null;
        var yInput = form ? form.querySelector('input[name="banner_position_y"]') : null;
        var x = xInput ? clampPercent(xInput.value, 50) : 50;
        var y = yInput ? clampPercent(yInput.value, 50) : 50;

        return {
            form: form,
            xInput: xInput,
            yInput: yInput,
            x: x,
            y: y,
        };
    }

    function applyBannerPosition(guidesEl, x, y) {
        if (!guidesEl) return;
        var nextX = clampPercent(x, 50);
        var nextY = clampPercent(y, 50);
        guidesEl.style.setProperty('--admin-banner-guides-x', nextX + '%');
        guidesEl.style.setProperty('--admin-banner-guides-y', nextY + '%');
        guidesEl.style.setProperty('--admin-banner-guides-pos', nextX + '% ' + nextY + '%');
    }

    function setupBannerGuidesInteraction(guidesEl) {
        if (!guidesEl || guidesEl.__bannerGuidesReady) return;
        guidesEl.__bannerGuidesReady = true;

        var initial = readBannerPosition(guidesEl);
        applyBannerPosition(guidesEl, initial.x, initial.y);
        if (!initial.xInput && !initial.yInput) return;

        var activePointerId = null;
        var activeFrame = null;

        function updateFromEvent(event) {
            if (!activeFrame) return;
            var rect = activeFrame.getBoundingClientRect();
            if (!rect.width || !rect.height) return;

            var relX = (event.clientX - rect.left) / rect.width;
            var relY = (event.clientY - rect.top) / rect.height;

            if (relX < 0) relX = 0;
            if (relX > 1) relX = 1;
            if (relY < 0) relY = 0;
            if (relY > 1) relY = 1;
            var x = clampPercent(Math.round(relX * 100), 50);
            var y = clampPercent(Math.round(relY * 100), 50);

            var payload = readBannerPosition(guidesEl);
            if (payload.xInput) payload.xInput.value = String(x);
            if (payload.yInput) payload.yInput.value = String(y);
            applyBannerPosition(guidesEl, x, y);
        }

        guidesEl.addEventListener('pointerdown', function (event) {
            if (guidesEl.classList.contains('is-marked')) return;
            var frame = event.target && event.target.closest ? event.target.closest('.admin-banner-guides__frame') : null;
            if (!frame || !guidesEl.contains(frame)) return;

            activePointerId = event.pointerId;
            activeFrame = frame;

            try {
                frame.setPointerCapture(activePointerId);
            } catch (e) {}

            updateFromEvent(event);
        });

        guidesEl.addEventListener('pointermove', function (event) {
            if (activePointerId === null) return;
            if (event.pointerId !== activePointerId) return;
            updateFromEvent(event);
        });

        function stop(event) {
            if (activePointerId === null) return;
            if (event.pointerId !== activePointerId) return;
            activePointerId = null;
            activeFrame = null;
        }

        guidesEl.addEventListener('pointerup', stop);
        guidesEl.addEventListener('pointercancel', stop);
    }

    function validateFile(file, maxBytes) {
        var allowedTypes = {
            'image/jpeg': true,
            'image/png': true,
            'image/gif': true,
            'image/webp': true,
        };

        var name = String(file && file.name ? file.name : '');
        var ext = name.toLowerCase().split('.').pop();
        var allowedExt = { jpg: true, jpeg: true, png: true, gif: true, webp: true };
        var typeOk = !!allowedTypes[String(file.type || '').toLowerCase()];
        var extOk = !!allowedExt[ext];

        if (!typeOk && !extOk) {
            return 'Formato inválido: ' + name + '. Use JPG, PNG, GIF ou WebP.';
        }

        if (file.size > maxBytes) {
            return 'Arquivo muito grande: ' + name + ' (' + bytesToMb(file.size) + 'MB). Máximo 5MB.';
        }

        return null;
    }

    function updateFileInput(input, files) {
        var dt = new DataTransfer();
        files.forEach(function (file) {
            dt.items.add(file);
        });
        input.files = dt.files;
    }

    function updateFileInputSafe(input, files) {
        try {
            updateFileInput(input, files);
            return true;
        } catch (e) {
            return false;
        }
    }

    function setupDropzone(root) {
        var input = root.querySelector('input[type="file"]');
        if (!(input instanceof HTMLInputElement)) {
            var fieldRoot = root.closest('.admin-dropzone-field');
            if (fieldRoot) {
                input = fieldRoot.querySelector('input[type="file"]');
            }
        }
        var area = root.querySelector('[data-dropzone-area]') || root;
        var errorsEl = root.querySelector('[data-dropzone-errors]');
        var previewsEl = root.querySelector('[data-dropzone-previews]');
        var counterEl = root.querySelector('[data-dropzone-count]');
        var metaEl = root.querySelector('[data-dropzone-meta]');
        var previewMode = root.getAttribute('data-preview-size') || '';
        var guidesVariant = root.getAttribute('data-banner-guides-variant') || '';
        var lastUrls = [];
        var guidesEl = null;

        if (!(input instanceof HTMLInputElement)) return;

        var maxBytes = 5 * 1024 * 1024;
        var acceptText = 'JPG, PNG, GIF ou WebP até 5MB';

        if (metaEl) metaEl.textContent = acceptText;

        function setErrors(errors) {
            if (!errorsEl) return;
            if (!errors || errors.length === 0) {
                errorsEl.hidden = true;
                errorsEl.textContent = '';
                return;
            }
            errorsEl.hidden = false;
            errorsEl.innerHTML = '';
            errors.forEach(function (msg) {
                var p = document.createElement('div');
                p.textContent = msg;
                errorsEl.appendChild(p);
            });
        }

        function renderPreviews(files) {
            if (!previewsEl) return;
            revokeUrls(lastUrls);
            lastUrls = [];
            previewsEl.innerHTML = '';
            var firstPreviewUrl = '';

            files.forEach(function (file) {
                var url = createObjectUrl(file);
                if (url) lastUrls.push(url);
                if (!firstPreviewUrl && url) firstPreviewUrl = url;
                var figure = document.createElement('figure');
                figure.className = 'admin-dropzone__preview';
                var img = document.createElement('img');
                img.alt = '';
                img.loading = 'lazy';
                img.decoding = 'async';
                img.src = url || '';
                var caption = document.createElement('figcaption');
                caption.textContent = file.name;
                figure.appendChild(img);
                figure.appendChild(caption);
                previewsEl.appendChild(figure);
            });

            if (previewMode === 'banner') {
                previewsEl.style.gridTemplateColumns = 'minmax(0, 1fr)';
            } else {
                previewsEl.style.gridTemplateColumns = '';
            }

            if (previewMode === 'banner') {
                updateBannerGuides(firstPreviewUrl);
            } else {
                updateBannerGuides('');
            }
        }

        function createBannerGuides() {
            if (guidesEl) return guidesEl;
            guidesEl = document.createElement('div');
            guidesEl.className = 'admin-banner-guides';
            guidesEl.setAttribute('data-banner-guides', '1');
            if (guidesVariant) {
                guidesEl.setAttribute('data-banner-guides-variant', guidesVariant);
            }
            guidesEl.hidden = true;

            var title = document.createElement('div');
            title.className = 'admin-banner-guides__title';
            title.textContent = 'Sugestão de banner';

            var hint = document.createElement('div');
            hint.className = 'admin-banner-guides__hint';
            hint.textContent = 'Tamanho ideal: 1920×1080 (ou maior). O site corta automaticamente (cover). Clique em qualquer prévia para ajustar o ponto focal (o centro do corte).';

            var grid = document.createElement('div');
            grid.className = 'admin-banner-guides__grid';

            function frame(label, ratioClass) {
                var box = document.createElement('div');
                box.className = 'admin-banner-guides__frame ' + ratioClass;
                var marker = document.createElement('span');
                marker.className = 'admin-banner-guides__marker';
                var pill = document.createElement('span');
                pill.className = 'admin-banner-guides__label';
                pill.textContent = label;
                box.appendChild(marker);
                box.appendChild(pill);
                return box;
            }

            grid.appendChild(frame('Desktop', 'admin-banner-guides__ratio-desktop'));
            grid.appendChild(frame('Tablet', 'admin-banner-guides__ratio-tablet'));
            grid.appendChild(frame('Celular', 'admin-banner-guides__ratio-mobile'));

            guidesEl.appendChild(title);
            guidesEl.appendChild(hint);
            guidesEl.appendChild(grid);

            if (previewsEl && previewsEl.parentNode) {
                previewsEl.parentNode.insertBefore(guidesEl, previewsEl.nextSibling);
            } else {
                root.appendChild(guidesEl);
            }

            setupBannerGuidesInteraction(guidesEl);
            return guidesEl;
        }

        function updateBannerGuides(url) {
            if (previewMode !== 'banner') return;
            var el = createBannerGuides();
            var nextUrl = String(url || '').trim();
            if (!nextUrl) {
                el.hidden = true;
                el.style.removeProperty('--admin-banner-guides-src');
                return;
            }
            el.hidden = false;
            el.style.setProperty('--admin-banner-guides-src', 'url("' + nextUrl.replace(/"/g, '\\"') + '")');
            setupBannerGuidesInteraction(el);
        }

        function updateCounter(files) {
            if (!counterEl) return;
            var count = files.length;
            counterEl.textContent = count === 1 ? '1 arquivo selecionado' : count + ' arquivos selecionados';
        }

        function applyFiles(incomingFiles, mode) {
            var incoming = Array.from(incomingFiles || []);
            if (incoming.length === 0) return;

            var existing = input.multiple && mode === 'drop' ? Array.from(input.files || []) : [];
            var merged = existing.concat(incoming);

            var errors = [];
            var valid = [];

            merged.forEach(function (file) {
                var err = validateFile(file, maxBytes);
                if (err) {
                    errors.push(err);
                    return;
                }
                valid.push(file);
            });

            if (!input.multiple && valid.length > 1) {
                valid = [valid[0]];
            }

            if (valid.length === 0) {
                input.value = '';
                if (previewsEl) {
                    revokeUrls(lastUrls);
                    lastUrls = [];
                    previewsEl.innerHTML = '';
                }
                if (previewMode === 'banner') updateBannerGuides('');
                updateCounter([]);
                setErrors(errors.length ? errors : ['Selecione uma imagem válida.']);
                return;
            }

            if (mode === 'drop') {
                updateFileInputSafe(input, valid);
            } else if (errors.length > 0 || (!input.multiple && incoming.length > 1)) {
                updateFileInputSafe(input, valid);
            }
            updateCounter(valid);
            renderPreviews(valid);
            setErrors(errors);
        }

        function openPicker() {
            input.click();
        }

        input.addEventListener('change', function () {
            if (!input.files || input.files.length === 0) {
                revokeUrls(lastUrls);
                lastUrls = [];
                if (previewsEl) previewsEl.innerHTML = '';
                if (previewMode === 'banner') updateBannerGuides('');
                updateCounter([]);
                setErrors([]);
                return;
            }
            applyFiles(input.files, 'select');
        });

        area.addEventListener('click', function (event) {
            if (event.target === input) return;
            event.preventDefault();
            openPicker();
        });

        area.setAttribute('tabindex', area.getAttribute('tabindex') || '0');
        area.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openPicker();
            }
        });

        area.addEventListener('dragenter', function (event) {
            if (!isFileDrag(event)) return;
            event.preventDefault();
            root.classList.add('is-dragover');
        });

        area.addEventListener('dragover', function (event) {
            if (!isFileDrag(event)) return;
            event.preventDefault();
            root.classList.add('is-dragover');
        });

        area.addEventListener('dragleave', function (event) {
            if (!isFileDrag(event)) return;
            if (event.target !== area) return;
            root.classList.remove('is-dragover');
        });

        area.addEventListener('drop', function (event) {
            if (!isFileDrag(event)) return;
            event.preventDefault();
            root.classList.remove('is-dragover');
            var dt = event.dataTransfer;
            if (!dt) return;
            applyFiles(dt.files, 'drop');
        });

        if (input.files && input.files.length) {
            var initial = Array.from(input.files);
            updateCounter(initial);
            renderPreviews(initial);
        } else {
            updateCounter([]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var zones = document.querySelectorAll('[data-admin-dropzone]');
        if (zones.length) {
            document.addEventListener('dragover', function (event) {
                if (isFileDrag(event)) event.preventDefault();
            });

            document.addEventListener('drop', function (event) {
                if (isFileDrag(event)) event.preventDefault();
            });

            zones.forEach(function (zone) {
                setupDropzone(zone);
            });
        }

        var guides = document.querySelectorAll('[data-banner-guides]');
        guides.forEach(function (guidesEl) {
            setupBannerGuidesInteraction(guidesEl);
        });
    });
})();
