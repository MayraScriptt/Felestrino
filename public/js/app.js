(() => {
    const prefersReducedMotion = (() => {
        try {
            return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        } catch (e) {
            return false;
        }
    })();

    const header = document.querySelector('.site-header');

    const getHeaderOffset = () => (header ? header.getBoundingClientRect().height : 0);

    const scrollToElement = (element) => {
        if (!element) return;
        const headerOffset = getHeaderOffset();
        const top = Math.max(0, Math.round(element.getBoundingClientRect().top + window.scrollY - headerOffset));
        try {
            window.scrollTo({ top, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
            } catch (e) {
            window.scrollTo(0, top);
        }
    };

    const navLinks = document.querySelectorAll('nav a[href^="#"]');

    navLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            if (!href) return;
            const target = document.querySelector(href);
            if (!target) return;

            event.preventDefault();
            scrollToElement(target);
        });
    });

    const scrollNextLinks = document.querySelectorAll('[data-scroll-next]');
    scrollNextLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            if (!href) return;
            const target = document.querySelector(href);
            if (!target) return;

            event.preventDefault();
            scrollToElement(target);
        });
    });

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    const debounce = (fn, delayMs) => {
        let timerId = null;
        return (...args) => {
            if (timerId) window.clearTimeout(timerId);
            timerId = window.setTimeout(() => fn(...args), delayMs);
        };
    };

    const fetchJson = async (url, options) => {
        const response = await window.fetch(url, options);
        const text = await response.text();
        let json = null;
        try {
            json = text ? JSON.parse(text) : null;
        } catch (e) {
            json = null;
        }
        if (!response.ok) {
            const error = new Error('Request failed');
            error.status = response.status;
            error.body = json;
            throw error;
        }
        return json;
    };

    const initHomeAdmin = (root) => {
        const indicator = document.querySelector('[data-autosave-indicator]');

        const setIndicator = (state, text) => {
            if (!indicator) return;
            indicator.classList.remove('is-saving', 'is-saved', 'is-error');
            if (state) indicator.classList.add(state);
            indicator.textContent = text;
        };

        const saveSettings = debounce(async (name, value) => {
            if (!csrfToken) return;
            setIndicator('is-saving', 'Salvando…');
            try {
                await fetchJson('/admin/home', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ [name]: value }),
                });
                setIndicator('is-saved', 'Salvo');
            } catch (e) {
                setIndicator('is-error', 'Erro ao salvar');
            }
        }, 650);

        const settingInputs = root.querySelectorAll('[data-home-setting]');
        settingInputs.forEach((input) => {
            input.addEventListener('input', () => {
                const name = input.getAttribute('name');
                if (!name) return;
                const value = input.value;
                saveSettings(name, value);
            });
        });

        const carouselList = root.querySelector('[data-home-carousel-list]');
        const carouselFile = root.querySelector('[data-home-carousel-file]');
        const carouselUpload = root.querySelector('[data-home-carousel-upload]');
        const carouselPrev = root.querySelector('[data-home-carousel-prev]');
        const carouselNext = root.querySelector('[data-home-carousel-next]');
        const heroPreviewImg = root.querySelector('[data-home-hero-img]');
        const heroPreviewTitle = root.querySelector('[data-home-hero-title]');
        const heroPreviewSubtitle = root.querySelector('[data-home-hero-subtitle]');
        const heroPreviewButton = root.querySelector('[data-home-hero-button]');

        let previewFadeTimerId = null;
        const setPreviewTitle = (title) => {
            if (!heroPreviewTitle) return;
            const nextTitle = String(title || '').trim();
            if (heroPreviewTitle.textContent === nextTitle) return;

            if (prefersReducedMotion) {
                heroPreviewTitle.textContent = nextTitle;
                return;
            }

            heroPreviewTitle.classList.add('is-fading');
            if (previewFadeTimerId) window.clearTimeout(previewFadeTimerId);
            previewFadeTimerId = window.setTimeout(() => {
                heroPreviewTitle.textContent = nextTitle;
                heroPreviewTitle.classList.remove('is-fading');
            }, 160);
        };

        const updatePreviewFromItem = (itemEl) => {
            if (!itemEl) return;
            const thumb = itemEl.querySelector('.admin-home-thumb');
            const titleInput = itemEl.querySelector('[data-carousel-title]');
            const subtitleInput = itemEl.querySelector('[data-carousel-subtitle]');
            const buttonTextInput = itemEl.querySelector('[data-carousel-button-text]');
            const buttonUrlInput = itemEl.querySelector('[data-carousel-button-url]');
            const title = titleInput && titleInput.value ? titleInput.value : '';
            const resolvedTitle = String(title || '').trim() || 'Banner principal';
            if (heroPreviewImg && thumb instanceof HTMLImageElement && thumb.src) {
                heroPreviewImg.src = thumb.src;
            }
            setPreviewTitle(resolvedTitle);
            if (heroPreviewSubtitle) {
                const subtitle = subtitleInput && subtitleInput.value ? subtitleInput.value : '';
                heroPreviewSubtitle.textContent = subtitle;
            }
            if (heroPreviewButton instanceof HTMLAnchorElement) {
                const defaultButtonText = heroPreviewButton.getAttribute('data-home-hero-button-default') || 'Ver conteudo';
                const buttonText = buttonTextInput && buttonTextInput.value ? buttonTextInput.value : '';
                const buttonUrl = buttonUrlInput && buttonUrlInput.value ? buttonUrlInput.value : '';
                heroPreviewButton.textContent = buttonText || defaultButtonText;
                heroPreviewButton.href = buttonUrl || '#home-main';
            }
        };

        const getCarouselItems = () => Array.from(root.querySelectorAll('[data-home-carousel-list] [data-carousel-item]'));
        const localStorageKey = 'adminHomeSelectedCarouselId';
        let selectedCarouselId = null;

        const setSelectedCarouselItem = (itemEl) => {
            if (!itemEl) return;
            const idValue = itemEl.getAttribute('data-id');
            if (!idValue) return;
            selectedCarouselId = idValue;

            getCarouselItems().forEach((el) => {
                el.classList.toggle('is-selected', el === itemEl);
            });

            try {
                window.localStorage.setItem(localStorageKey, selectedCarouselId);
            } catch (e) {}

            updatePreviewFromItem(itemEl);
        };

        const selectCarouselByOffset = (offset) => {
            const items = getCarouselItems();
            if (items.length === 0) {
                setPreviewTitle('Banner principal');
                if (heroPreviewSubtitle) heroPreviewSubtitle.textContent = '';
                if (heroPreviewButton instanceof HTMLAnchorElement) {
                    const defaultButtonText = heroPreviewButton.getAttribute('data-home-hero-button-default') || 'Ver conteudo';
                    heroPreviewButton.textContent = defaultButtonText;
                    heroPreviewButton.href = '#home-main';
                }
                return;
            }
            let index = 0;
            if (selectedCarouselId) {
                const foundIndex = items.findIndex((el) => el.getAttribute('data-id') === selectedCarouselId);
                if (foundIndex >= 0) index = foundIndex;
            }
            const nextIndex = ((index + offset) % items.length + items.length) % items.length;
            setSelectedCarouselItem(items[nextIndex]);
        };

        const collectIds = (selector) =>
            Array.from(root.querySelectorAll(selector))
                .map((el) => el.getAttribute('data-id'))
                .filter(Boolean)
                .map((v) => Number.parseInt(String(v), 10))
                .filter((v) => !Number.isNaN(v));

        const postReorder = debounce(async (url, selector) => {
            if (!csrfToken) return;
            const ids = collectIds(selector);
            if (ids.length === 0) return;
            setIndicator('is-saving', 'Salvando…');
            try {
                await fetchJson(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ ids }),
                });
                setIndicator('is-saved', 'Salvo');
            } catch (e) {
                setIndicator('is-error', 'Erro ao salvar');
            }
        }, 200);

        const makeDraggableList = (listEl, itemSelector, reorderUrl) => {
            if (!listEl) return;
            let dragged = null;

            const handleDragStart = (event) => {
                const item = event.currentTarget;
                if (!(item instanceof HTMLElement)) return;
                dragged = item;
                item.classList.add('is-dragging');
                try {
                    event.dataTransfer.effectAllowed = 'move';
                    event.dataTransfer.setData('text/plain', item.getAttribute('data-id') || '');
                } catch (e) {}
            };

            const handleDragEnd = (event) => {
                const item = event.currentTarget;
                if (item instanceof HTMLElement) item.classList.remove('is-dragging');
                dragged = null;
                postReorder(reorderUrl, itemSelector);
            };

            const handleDragOver = (event) => {
                event.preventDefault();
                const target = event.target;
                if (!(target instanceof Element)) return;
                const overItem = target.closest(itemSelector);
                if (!overItem || !(overItem instanceof HTMLElement)) return;
                if (!dragged || dragged === overItem) return;

                const rect = overItem.getBoundingClientRect();
                const after = event.clientY > rect.top + rect.height / 2;
                if (after) {
                    if (overItem.nextSibling !== dragged) {
                        listEl.insertBefore(dragged, overItem.nextSibling);
                    }
                } else {
                    if (overItem !== dragged.nextSibling) {
                        listEl.insertBefore(dragged, overItem);
                    }
                }
            };

            listEl.addEventListener('dragover', handleDragOver);

            const bindItem = (item) => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            };

            Array.from(listEl.querySelectorAll(itemSelector)).forEach((item) => {
                if (item instanceof HTMLElement) bindItem(item);
            });

            return { bindItem };
        };

        const carouselDraggable = makeDraggableList(carouselList, '[data-carousel-item]', '/admin/home/carousel/reorder');

        const bindCarouselItem = (itemEl) => {
            if (!itemEl) return;

            const idValue = itemEl.getAttribute('data-id');
            if (!idValue) return;

            const itemId = Number.parseInt(idValue, 10);
            if (Number.isNaN(itemId)) return;

            const titleInput = itemEl.querySelector('[data-carousel-title]');
            const subtitleInput = itemEl.querySelector('[data-carousel-subtitle]');
            const linkInput = itemEl.querySelector('[data-carousel-link]');
            const buttonTextInput = itemEl.querySelector('[data-carousel-button-text]');
            const buttonUrlInput = itemEl.querySelector('[data-carousel-button-url]');
            const activeInput = itemEl.querySelector('[data-carousel-active]');
            const deleteBtn = itemEl.querySelector('[data-carousel-delete]');

            const saveItem = debounce(async () => {
                if (!csrfToken) return;
                setIndicator('is-saving', 'Salvando…');
                try {
                    await fetchJson(`/admin/home/carousel/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            title: titleInput ? titleInput.value : '',
                            subtitle: subtitleInput ? subtitleInput.value : '',
                            link_url: linkInput ? linkInput.value : '',
                            button_text: buttonTextInput ? buttonTextInput.value : '',
                            button_url: buttonUrlInput ? buttonUrlInput.value : '',
                            is_active: activeInput ? !!activeInput.checked : true,
                        }),
                    });
                    setIndicator('is-saved', 'Salvo');
                } catch (e) {
                    setIndicator('is-error', 'Erro ao salvar');
                }
            }, 650);

            if (titleInput) titleInput.addEventListener('input', saveItem);
            if (subtitleInput) subtitleInput.addEventListener('input', saveItem);
            if (linkInput) linkInput.addEventListener('input', saveItem);
            if (buttonTextInput) buttonTextInput.addEventListener('input', saveItem);
            if (buttonUrlInput) buttonUrlInput.addEventListener('input', saveItem);
            if (activeInput) activeInput.addEventListener('change', saveItem);

            const maybeUpdatePreview = () => {
                if (selectedCarouselId && itemEl.getAttribute('data-id') === selectedCarouselId) {
                    updatePreviewFromItem(itemEl);
                }
            };
            if (titleInput) titleInput.addEventListener('input', maybeUpdatePreview);
            if (subtitleInput) subtitleInput.addEventListener('input', maybeUpdatePreview);
            if (buttonTextInput) buttonTextInput.addEventListener('input', maybeUpdatePreview);
            if (buttonUrlInput) buttonUrlInput.addEventListener('input', maybeUpdatePreview);

            itemEl.addEventListener('click', (event) => {
                const target = event.target;
                if (target instanceof Element && target.closest('a, button, input, textarea, select, [contenteditable="true"]')) {
                    return;
                }
                setSelectedCarouselItem(itemEl);
            });

            if (deleteBtn) {
                deleteBtn.addEventListener('click', async () => {
                    if (!csrfToken) return;
                    if (!window.confirm('Remover esta imagem do carrossel?')) return;
                    setIndicator('is-saving', 'Salvando…');
                    try {
                        await fetchJson(`/admin/home/carousel/${itemId}`, {
                            method: 'DELETE',
                            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        });
                        const wasSelected = selectedCarouselId && selectedCarouselId === String(itemId);
                        itemEl.remove();
                        postReorder('/admin/home/carousel/reorder', '[data-carousel-item]');
                        if (wasSelected) {
                            selectedCarouselId = null;
                            selectCarouselByOffset(0);
                        }
                        setIndicator('is-saved', 'Salvo');
                    } catch (e) {
                        setIndicator('is-error', 'Erro ao salvar');
                    }
                });
            }

            if (carouselDraggable && carouselDraggable.bindItem) carouselDraggable.bindItem(itemEl);
        };

        if (carouselList) {
            Array.from(carouselList.querySelectorAll('[data-carousel-item]')).forEach((el) => bindCarouselItem(el));
        }

        if (carouselPrev) carouselPrev.addEventListener('click', () => selectCarouselByOffset(-1));
        if (carouselNext) carouselNext.addEventListener('click', () => selectCarouselByOffset(1));

        try {
            selectedCarouselId = window.localStorage.getItem(localStorageKey);
        } catch (e) {
            selectedCarouselId = null;
        }
        if (selectedCarouselId) {
            const items = getCarouselItems();
            const el = items.find((x) => x.getAttribute('data-id') === selectedCarouselId);
            if (el) {
                setSelectedCarouselItem(el);
            } else {
                selectedCarouselId = null;
                selectCarouselByOffset(0);
            }
        } else {
            selectCarouselByOffset(0);
        }

        const createCarouselItemEl = (item) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'admin-home-item';
            wrapper.setAttribute('draggable', 'true');
            wrapper.setAttribute('data-carousel-item', '');
            wrapper.setAttribute('data-id', String(item.id));

            const img = document.createElement('img');
            img.className = 'admin-home-thumb';
            img.alt = '';
            img.src = item.image_url;
            img.loading = 'lazy';
            img.decoding = 'async';
            img.width = 110;
            img.height = 78;

            const fields = document.createElement('div');
            fields.className = 'admin-home-fields';

            const titleLabel = document.createElement('label');
            titleLabel.textContent = 'Titulo';
            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.maxLength = 100;
            titleInput.value = item.title || '';
            titleInput.setAttribute('data-carousel-title', '');
            titleLabel.appendChild(titleInput);

            const subtitleLabel = document.createElement('label');
            subtitleLabel.textContent = 'Subtitulo';
            const subtitleInput = document.createElement('input');
            subtitleInput.type = 'text';
            subtitleInput.maxLength = 255;
            subtitleInput.value = item.subtitle || '';
            subtitleInput.setAttribute('data-carousel-subtitle', '');
            subtitleLabel.appendChild(subtitleInput);

            const linkLabel = document.createElement('label');
            linkLabel.textContent = 'Link';
            const linkInput = document.createElement('input');
            linkInput.type = 'url';
            linkInput.maxLength = 2048;
            linkInput.placeholder = 'https://...';
            linkInput.value = item.link_url || '';
            linkInput.setAttribute('data-carousel-link', '');
            linkLabel.appendChild(linkInput);

            const buttonTextLabel = document.createElement('label');
            buttonTextLabel.textContent = 'Texto do botao';
            const buttonTextInput = document.createElement('input');
            buttonTextInput.type = 'text';
            buttonTextInput.maxLength = 80;
            buttonTextInput.placeholder = 'Ex.: Conhecer servicos';
            buttonTextInput.value = item.button_text || '';
            buttonTextInput.setAttribute('data-carousel-button-text', '');
            buttonTextLabel.appendChild(buttonTextInput);

            const buttonUrlLabel = document.createElement('label');
            buttonUrlLabel.textContent = 'Link do botao';
            const buttonUrlInput = document.createElement('input');
            buttonUrlInput.type = 'url';
            buttonUrlInput.maxLength = 2048;
            buttonUrlInput.placeholder = 'https://...';
            buttonUrlInput.value = item.button_url || '';
            buttonUrlInput.setAttribute('data-carousel-button-url', '');
            buttonUrlLabel.appendChild(buttonUrlInput);

            const activeLabel = document.createElement('label');
            activeLabel.className = 'checkbox-line';
            const activeInput = document.createElement('input');
            activeInput.type = 'checkbox';
            activeInput.checked = !!item.is_active;
            activeInput.setAttribute('data-carousel-active', '');
            activeLabel.appendChild(activeInput);
            activeLabel.appendChild(document.createTextNode(' Ativa'));

            fields.appendChild(titleLabel);
            fields.appendChild(subtitleLabel);
            fields.appendChild(linkLabel);
            fields.appendChild(buttonTextLabel);
            fields.appendChild(buttonUrlLabel);
            fields.appendChild(activeLabel);

            const actions = document.createElement('div');
            actions.className = 'admin-home-actions';
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'btn btn-secondary';
            deleteBtn.textContent = 'Remover';
            deleteBtn.setAttribute('data-carousel-delete', '');
            actions.appendChild(deleteBtn);

            wrapper.appendChild(img);
            wrapper.appendChild(fields);
            wrapper.appendChild(actions);

            return wrapper;
        };

        if (carouselUpload) {
            carouselUpload.addEventListener('click', async () => {
                if (!csrfToken) return;
                if (!carouselFile || !carouselFile.files || carouselFile.files.length === 0) return;
                const file = carouselFile.files[0];
                const form = new FormData();
                form.append('file', file);

                setIndicator('is-saving', 'Enviando…');
                try {
                    const json = await fetchJson('/admin/home/carousel', {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: form,
                    });
                    if (json && json.item && carouselList) {
                        const el = createCarouselItemEl(json.item);
                        carouselList.appendChild(el);
                        bindCarouselItem(el);
                        setSelectedCarouselItem(el);
                        carouselFile.value = '';
                    }
                    setIndicator('is-saved', 'Salvo');
                } catch (e) {
                    setIndicator('is-error', 'Erro ao enviar');
                }
            });
        }

        const cardsList = root.querySelector('[data-home-cards-list]');
        const cardAdd = root.querySelector('[data-home-card-add]');
        const cardsDraggable = makeDraggableList(cardsList, '[data-card-item]', '/admin/home/cards/reorder');

        const bindCardItem = (itemEl) => {
            if (!itemEl) return;
            const idValue = itemEl.getAttribute('data-id');
            if (!idValue) return;
            const cardId = Number.parseInt(idValue, 10);
            if (Number.isNaN(cardId)) return;

            const titleInput = itemEl.querySelector('[data-card-title]');
            const descriptionInput = itemEl.querySelector('[data-card-description]');
            const iconInput = itemEl.querySelector('[data-card-icon]');
            const linkInput = itemEl.querySelector('[data-card-link]');
            const activeInput = itemEl.querySelector('[data-card-active]');
            const deleteBtn = itemEl.querySelector('[data-card-delete]');

            const saveCard = debounce(async () => {
                if (!csrfToken) return;
                setIndicator('is-saving', 'Salvando…');
                try {
                    await fetchJson(`/admin/home/cards/${cardId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            title: titleInput ? titleInput.value : '',
                            description: descriptionInput ? descriptionInput.value : '',
                            icon: iconInput ? iconInput.value : '',
                            link_url: linkInput ? linkInput.value : '',
                            is_active: activeInput ? !!activeInput.checked : true,
                        }),
                    });
                    setIndicator('is-saved', 'Salvo');
                } catch (e) {
                    setIndicator('is-error', 'Erro ao salvar');
                }
            }, 650);

            if (titleInput) titleInput.addEventListener('input', saveCard);
            if (descriptionInput) descriptionInput.addEventListener('input', saveCard);
            if (iconInput) iconInput.addEventListener('input', saveCard);
            if (linkInput) linkInput.addEventListener('input', saveCard);
            if (activeInput) activeInput.addEventListener('change', saveCard);

            if (deleteBtn) {
                deleteBtn.addEventListener('click', async () => {
                    if (!csrfToken) return;
                    if (!window.confirm('Excluir este card?')) return;
                    setIndicator('is-saving', 'Salvando…');
                    try {
                        await fetchJson(`/admin/home/cards/${cardId}`, {
                            method: 'DELETE',
                            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        });
                        itemEl.remove();
                        postReorder('/admin/home/cards/reorder', '[data-card-item]');
                        setIndicator('is-saved', 'Salvo');
                    } catch (e) {
                        setIndicator('is-error', 'Erro ao salvar');
                    }
                });
            }

            if (cardsDraggable && cardsDraggable.bindItem) cardsDraggable.bindItem(itemEl);
        };

        if (cardsList) {
            Array.from(cardsList.querySelectorAll('[data-card-item]')).forEach((el) => bindCardItem(el));
        }

        const createCardEl = (card) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'admin-home-item';
            wrapper.setAttribute('draggable', 'true');
            wrapper.setAttribute('data-card-item', '');
            wrapper.setAttribute('data-id', String(card.id));

            const spacer = document.createElement('div');
            spacer.className = 'admin-home-thumb admin-home-thumb--placeholder';

            const fields = document.createElement('div');
            fields.className = 'admin-home-fields';

            const titleLabel = document.createElement('label');
            titleLabel.textContent = 'Titulo';
            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.maxLength = 100;
            titleInput.value = card.title || '';
            titleInput.setAttribute('data-card-title', '');
            titleLabel.appendChild(titleInput);

            const descLabel = document.createElement('label');
            descLabel.textContent = 'Descricao';
            const desc = document.createElement('textarea');
            desc.rows = 3;
            desc.value = card.description || '';
            desc.setAttribute('data-card-description', '');
            descLabel.appendChild(desc);

            const iconLabel = document.createElement('label');
            iconLabel.textContent = 'Icone (texto)';
            const icon = document.createElement('input');
            icon.type = 'text';
            icon.maxLength = 60;
            icon.value = card.icon || '';
            icon.placeholder = 'Ex.: 💧 ou irrigacao';
            icon.setAttribute('data-card-icon', '');
            iconLabel.appendChild(icon);

            const linkLabel = document.createElement('label');
            linkLabel.textContent = 'Link';
            const link = document.createElement('input');
            link.type = 'url';
            link.maxLength = 2048;
            link.placeholder = 'https://...';
            link.value = card.link_url || '';
            link.setAttribute('data-card-link', '');
            linkLabel.appendChild(link);

            const activeLabel = document.createElement('label');
            activeLabel.className = 'checkbox-line';
            const active = document.createElement('input');
            active.type = 'checkbox';
            active.checked = !!card.is_active;
            active.setAttribute('data-card-active', '');
            activeLabel.appendChild(active);
            activeLabel.appendChild(document.createTextNode(' Ativo'));

            fields.appendChild(titleLabel);
            fields.appendChild(descLabel);
            fields.appendChild(iconLabel);
            fields.appendChild(linkLabel);
            fields.appendChild(activeLabel);

            const actions = document.createElement('div');
            actions.className = 'admin-home-actions';
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'btn btn-secondary';
            deleteBtn.textContent = 'Excluir';
            deleteBtn.setAttribute('data-card-delete', '');
            actions.appendChild(deleteBtn);

            wrapper.appendChild(spacer);
            wrapper.appendChild(fields);
            wrapper.appendChild(actions);

            return wrapper;
        };

        if (cardAdd) {
            cardAdd.addEventListener('click', async () => {
                if (!csrfToken) return;
                const title = window.prompt('Título do card:', 'Novo card');
                if (!title) return;
                setIndicator('is-saving', 'Salvando…');
                try {
                    const json = await fetchJson('/admin/home/cards', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ title }),
                    });
                    if (json && json.card && cardsList) {
                        const el = createCardEl(json.card);
                        cardsList.appendChild(el);
                        bindCardItem(el);
                    }
                    setIndicator('is-saved', 'Salvo');
                } catch (e) {
                    setIndicator('is-error', 'Erro ao salvar');
                }
            });
        }
    };

    const homeAdminRoot = document.querySelector('[data-home-admin]');
    if (homeAdminRoot) initHomeAdmin(homeAdminRoot);

    const updateHeaderState = () => {
        if (!header) return;
        if (window.scrollY > 10) {
            header.classList.add('is-solid');
        } else {
            header.classList.remove('is-solid');
        }
    };
    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });

    const initHeroCarousel = (root) => {
        const slides = Array.from(root.querySelectorAll('[data-carousel-slide]'));
        const dots = Array.from(root.querySelectorAll('[data-carousel-dot]'));
        const prevButton = root.querySelector('[data-carousel-prev]');
        const nextButton = root.querySelector('[data-carousel-next]');
        const heroCopyEl = root.querySelector('[data-hero-copy]');
        const heroTitleEl = root.querySelector('[data-hero-title]');
        const heroSubtitleEl = root.querySelector('[data-hero-subtitle]');
        let heroButtonEl = root.querySelector('[data-hero-button]');

        if (slides.length === 0) return;

        if (!root.hasAttribute('tabindex')) {
            root.setAttribute('tabindex', '0');
        }

        const normalizeIndex = (value) => {
            const len = slides.length;
            return ((value % len) + len) % len;
        };

        const getSlideImage = (slide) => slide.querySelector('img');

        const ensureImageLoaded = (slideIndex) => {
            const slide = slides[normalizeIndex(slideIndex)];
            const img = getSlideImage(slide);
            if (!img) return;
            const dataSrc = img.getAttribute('data-src');
            if (!dataSrc) return;
            if (img.getAttribute('data-loaded') === '1') return;
            img.setAttribute('data-loaded', '1');
            img.src = dataSrc;
            img.removeAttribute('data-src');
        };

        let activeIndex = slides.findIndex((s) => s.classList.contains('is-active'));
        if (activeIndex < 0) activeIndex = 0;

        let titleFadeTimerId = null;
        const getActiveTitle = () => {
            const slide = slides[activeIndex];
            if (!slide) return '';
            const title = slide.getAttribute('data-carousel-title');
            return title || '';
        };

        const getActiveSubtitle = () => {
            const slide = slides[activeIndex];
            if (!slide) return '';
            const subtitle = slide.getAttribute('data-carousel-subtitle');
            return subtitle || '';
        };

        const getActiveButtonText = () => {
            const slide = slides[activeIndex];
            if (!slide) return '';
            const text = slide.getAttribute('data-carousel-button-text');
            return text || '';
        };

        const getActiveButtonUrl = () => {
            const slide = slides[activeIndex];
            if (!slide) return '#home-main';
            const url = slide.getAttribute('data-carousel-button-url');
            return url || '#home-main';
        };

        const setHeroTitle = (nextTitle) => {
            if (!heroTitleEl) return;
            const normalizedTitle = String(nextTitle || '').trim();
            heroTitleEl.hidden = normalizedTitle === '';
            if (normalizedTitle === '') {
                heroTitleEl.textContent = '';
                return;
            }
            if (heroTitleEl.textContent === normalizedTitle) return;

            if (prefersReducedMotion) {
                heroTitleEl.textContent = normalizedTitle;
                return;
            }

            heroTitleEl.classList.add('is-fading');
            if (titleFadeTimerId) window.clearTimeout(titleFadeTimerId);
            titleFadeTimerId = window.setTimeout(() => {
                heroTitleEl.textContent = normalizedTitle;
                heroTitleEl.classList.remove('is-fading');
            }, 160);
        };

        const ensureHeroButtonEl = () => {
            if (heroButtonEl instanceof HTMLAnchorElement) return heroButtonEl;
            if (!(heroCopyEl instanceof HTMLElement)) return null;

            const button = document.createElement('a');
            button.className = 'hero-carousel__scroll reveal is-visible';
            button.setAttribute('data-hero-button', '');
            button.setAttribute('data-reveal', '');
            button.setAttribute('data-reveal-delay', '240');
            button.style.transitionDelay = '240ms';
            heroCopyEl.appendChild(button);
            heroButtonEl = button;

            return button;
        };

        const syncUi = () => {
            slides.forEach((slide, i) => {
                const isActive = i === activeIndex;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });
            dots.forEach((dot, i) => {
                const isActive = i === activeIndex;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-current', isActive ? 'true' : 'false');
            });
            const activeTitle = getActiveTitle();
            const activeSubtitle = getActiveSubtitle();
            const activeButtonText = getActiveButtonText();
            const hasCopy = String(activeTitle || '').trim() !== '' || String(activeSubtitle || '').trim() !== '' || String(activeButtonText || '').trim() !== '';

            setHeroTitle(activeTitle);
            if (heroSubtitleEl) {
                const subtitle = String(activeSubtitle || '').trim();
                heroSubtitleEl.hidden = subtitle === '';
                heroSubtitleEl.textContent = subtitle;
            }
            const buttonElement = ensureHeroButtonEl();
            if (buttonElement instanceof HTMLAnchorElement) {
                const buttonUrl = getActiveButtonUrl();
                const buttonText = String(activeButtonText || '').trim();
                if (buttonText === '') {
                    buttonElement.remove();
                    heroButtonEl = null;
                } else {
                    buttonElement.textContent = buttonText;
                    buttonElement.href = buttonUrl || '#home-main';
                }
                if (buttonText !== '' && (buttonUrl || '#home-main') === '#home-main') {
                    buttonElement.setAttribute('data-scroll-next', '');
                } else if (buttonText !== '') {
                    buttonElement.removeAttribute('data-scroll-next');
                }
            }
            if (heroCopyEl instanceof HTMLElement) {
                heroCopyEl.hidden = !hasCopy;
            }
        };

        ensureImageLoaded(activeIndex);
        ensureImageLoaded(activeIndex + 1);
        syncUi();

        let isPaused = false;
        let autoplayId = null;
        const autoplayIntervalMs = 4000;

        const stopAutoplay = () => {
            if (autoplayId) {
                window.clearInterval(autoplayId);
                autoplayId = null;
            }
        };

        const startAutoplay = () => {
            if (prefersReducedMotion) return;
            if (slides.length < 2) return;
            stopAutoplay();
            autoplayId = window.setInterval(() => {
                if (isPaused || document.hidden) return;
                goTo(activeIndex + 1, false);
            }, autoplayIntervalMs);
        };

        const restartAutoplay = () => {
            if (prefersReducedMotion) return;
            stopAutoplay();
            startAutoplay();
        };

        const goTo = (index, userInitiated = true) => {
            const nextIndex = normalizeIndex(index);
            if (nextIndex === activeIndex) return;
            ensureImageLoaded(nextIndex);
            ensureImageLoaded(nextIndex + 1);
            activeIndex = nextIndex;
            syncUi();
            if (userInitiated) restartAutoplay();
        };

        if (prevButton) prevButton.addEventListener('click', () => goTo(activeIndex - 1));
        if (nextButton) nextButton.addEventListener('click', () => goTo(activeIndex + 1));

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const value = dot.getAttribute('data-carousel-dot');
                if (value === null) return;
                const parsed = Number.parseInt(value, 10);
                if (Number.isNaN(parsed)) return;
                goTo(parsed);
            });
        });

        root.addEventListener('mouseenter', () => {
            isPaused = true;
        });
        root.addEventListener('mouseleave', () => {
            isPaused = false;
        });
        root.addEventListener('focusin', () => {
            isPaused = true;
        });
        root.addEventListener('focusout', () => {
            isPaused = false;
        });

        root.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                goTo(activeIndex - 1);
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                goTo(activeIndex + 1);
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                isPaused = true;
            }
        });

        startAutoplay();
    };

    const initHeroScrollBehavior = (heroRoot) => {
        const main = document.querySelector('#home-main');
        if (!main) return;

        let hasAutoScrolled = false;

        const shouldIgnoreEventTarget = (target) => {
            if (!(target instanceof Element)) return false;
            return !!target.closest('a, button, input, textarea, select, [contenteditable="true"]');
        };

        const isHeroMostlyVisible = () => {
            const rect = heroRoot.getBoundingClientRect();
            const vh = Math.max(window.innerHeight, 1);
            const visible = Math.min(vh, rect.bottom) - Math.max(0, rect.top);
            return visible / vh > 0.6;
        };

        const onWheel = (event) => {
            if (hasAutoScrolled) return;
            if (prefersReducedMotion) return;
            if (shouldIgnoreEventTarget(event.target)) return;
            if (window.scrollY > 4) return;
            if (event.deltaY <= 0) return;
            if (!isHeroMostlyVisible()) return;

            hasAutoScrolled = true;
            event.preventDefault();
            scrollToElement(main);
        };

        window.addEventListener('wheel', onWheel, { passive: false });

        const onKeyDown = (event) => {
            if (hasAutoScrolled) return;
            if (prefersReducedMotion) return;
            if (shouldIgnoreEventTarget(event.target)) return;
            if (window.scrollY > 4) return;
            if (!isHeroMostlyVisible()) return;

            const keys = ['ArrowDown', 'PageDown', ' ', 'Spacebar'];
            if (!keys.includes(event.key)) return;

            hasAutoScrolled = true;
            event.preventDefault();
            scrollToElement(main);
        };

        window.addEventListener('keydown', onKeyDown);

        let touchStartY = null;
        let touchStartTime = null;

        window.addEventListener(
            'touchstart',
            (event) => {
                if (hasAutoScrolled) return;
                if (!event.touches || event.touches.length !== 1) return;
                touchStartY = event.touches[0].clientY;
                touchStartTime = Date.now();
            },
            { passive: true },
        );

        window.addEventListener(
            'touchend',
            (event) => {
                if (hasAutoScrolled) return;
                if (prefersReducedMotion) return;
                if (window.scrollY > 4) return;
                if (!isHeroMostlyVisible()) return;
                if (touchStartY === null || touchStartTime === null) return;

                const elapsed = Date.now() - touchStartTime;
                const changed = event.changedTouches && event.changedTouches[0];
                if (!changed) return;

                const delta = touchStartY - changed.clientY;
                touchStartY = null;
                touchStartTime = null;

                if (elapsed > 700) return;
                if (delta < 80) return;

                hasAutoScrolled = true;
                scrollToElement(main);
            },
            { passive: true },
        );
    };

    const initRevealAnimations = () => {
        const elements = Array.from(document.querySelectorAll('[data-reveal]'));
        if (elements.length === 0) return;

        elements.forEach((el) => {
            if (el instanceof HTMLElement) el.classList.add('reveal');
        });

        const showAll = () => {
            elements.forEach((el) => {
                if (el instanceof HTMLElement) el.classList.add('is-visible');
            });
        };

        if (prefersReducedMotion) {
            showAll();
            return;
        }

        if (!('IntersectionObserver' in window)) {
            showAll();
            return;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const target = entry.target;
                    if (target instanceof HTMLElement) target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
        );

        elements.forEach((el) => {
            if (!(el instanceof HTMLElement)) return;
            const delayValue = el.getAttribute('data-reveal-delay');
            if (delayValue) {
                const parsed = Number.parseInt(String(delayValue), 10);
                if (!Number.isNaN(parsed) && parsed > 0) {
                    el.style.transitionDelay = `${parsed}ms`;
                }
            }
            observer.observe(el);
        });
    };

    const heroCarousel = document.querySelector('[data-carousel="hero"]');
    initRevealAnimations();
    if (heroCarousel) {
        initHeroCarousel(heroCarousel);
        initHeroScrollBehavior(heroCarousel);
    }
})();
