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
        var lastUrls = [];

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

            files.forEach(function (file) {
                var url = createObjectUrl(file);
                if (url) lastUrls.push(url);
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
                updateCounter([]);
                setErrors(errors.length ? errors : ['Selecione uma imagem válida.']);
                return;
            }

            updateFileInput(input, valid);
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
                updateCounter([]);
                setErrors([]);
                return;
            }
            applyFiles(input.files, 'select');
        });

        area.addEventListener('click', function (event) {
            if (event.target === input) return;
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
        if (!zones.length) return;

        document.addEventListener('dragover', function (event) {
            if (isFileDrag(event)) event.preventDefault();
        });

        document.addEventListener('drop', function (event) {
            if (isFileDrag(event)) event.preventDefault();
        });

        zones.forEach(function (zone) {
            setupDropzone(zone);
        });
    });
})();
