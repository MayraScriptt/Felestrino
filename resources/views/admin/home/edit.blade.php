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

        .admin-content .admin-home-actions {
            display: grid;
            gap: .5rem;
            align-content: start;
            justify-items: stretch;
        }

        .admin-home-order {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .35rem;
            margin-bottom: .5rem;
        }

        .admin-home-order__label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.1rem;
            padding: .12rem .4rem;
            border: 1px solid rgba(13, 27, 62, 0.14);
            border-radius: .55rem;
            font-family: "Rajdhani", sans-serif;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            opacity: .6;
        }

        .admin-home-order__buttons {
            display: inline-flex;
            gap: .25rem;
        }

        .admin-home-order-btn {
            padding: .25rem .55rem;
            min-width: 2.15rem;
            line-height: 1;
        }

        .admin-home-order-btn[disabled] {
            opacity: .45;
            cursor: not-allowed;
        }

        .admin-card-detail-media {
            display: grid;
            gap: .45rem;
            padding: .55rem;
            border: 1px solid rgba(13, 27, 62, 0.08);
            border-radius: .55rem;
            background: rgba(13, 27, 62, 0.02);
        }

        .admin-card-detail-media__preview {
            width: 100%;
            max-width: 220px;
            aspect-ratio: 16 / 9;
            border-radius: .45rem;
            object-fit: cover;
            border: 1px solid rgba(13, 27, 62, 0.12);
            background: #dfe4ef;
        }

        .admin-card-detail-media__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            align-items: center;
        }

        .admin-file-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .admin-file-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .45rem .8rem;
            border: 1px solid rgba(13, 27, 62, 0.16);
            border-radius: .45rem;
            background: #fff;
            color: #0d1b3e;
            font-family: "Rajdhani", sans-serif;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all .18s ease;
        }

        .admin-file-trigger:hover,
        .admin-file-trigger:focus-visible {
            border-color: rgba(184, 144, 42, 0.45);
            color: #142150;
        }

        .admin-card-editor {
            display: grid;
            gap: .75rem;
        }

        .admin-card-block {
            border: 1px solid rgba(13, 27, 62, 0.1);
            border-radius: .6rem;
            padding: .75rem;
            background: #fff;
        }

        .admin-card-block__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
            margin-bottom: .55rem;
            font-family: "Rajdhani", sans-serif;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #142150;
        }

        .admin-card-block__hint {
            margin: 0 0 .65rem;
            font-size: .84rem;
            color: rgba(13, 27, 62, 0.72);
        }

        .admin-card-detail-fields[hidden] {
            display: none !important;
        }

        .admin-home-save-feedback {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 1000;
            max-width: min(360px, calc(100vw - 2rem));
            padding: .65rem .85rem;
            border-radius: .7rem;
            border: 1px solid rgba(13, 27, 62, 0.12);
            background: #fff;
            box-shadow: 0 12px 28px rgba(8, 17, 42, 0.12);
            font-family: "Rajdhani", sans-serif;
            font-size: .92rem;
            font-weight: 700;
            letter-spacing: .03em;
            color: #0d1b3e;
        }

        .admin-home-save-feedback.is-success {
            border-color: rgba(46, 128, 70, 0.35);
            background: rgba(46, 128, 70, 0.08);
        }

        .admin-home-save-feedback.is-error {
            border-color: rgba(184, 48, 48, 0.35);
            background: rgba(184, 48, 48, 0.08);
        }
    </style>

    <div class="admin-pages-head">
        <h1>Home</h1>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;justify-content:flex-end;">
            <button class="btn" type="submit" form="home-form" data-home-save-all>Salvar</button>
        </div>
    </div>

    <form id="home-form" data-home-admin data-home-tabs>
        <div class="admin-home-save-feedback" data-home-save-feedback role="status" aria-live="polite" hidden></div>
        <div class="admin-home-tabs" role="tablist" aria-label="Seções da Home">
            <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-home-tab-trigger data-target="banner">Banner</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="cards">Cards</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="seo">SEO</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="config">Configurações do site</button>
            @if (($audits ?? collect())->isNotEmpty())
                <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="historico">Histórico de alterações</button>
            @endif
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="banner">
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Mídia dinâmica</div>
                        <h2>Carrossel</h2>
                    </div>
                </div>
                <div class="admin-hero-preview-wrap">
                    <div class="admin-hero-preview-head">
                        <strong>Preview hero (sincronizado com o item selecionado)</strong>
                        <div class="admin-hero-preview-actions">
                            <button class="btn btn-secondary" type="button" data-home-carousel-prev>Anterior</button>
                            <button class="btn btn-secondary" type="button" data-home-carousel-next>Próximo</button>
                        </div>
                    </div>
                    <div class="admin-hero-preview" data-home-hero-preview>
                        <img class="admin-hero-preview__img" data-home-hero-img alt="" decoding="async">
                        <div class="admin-hero-preview__overlay">
                            <div class="admin-hero-preview__title" data-home-hero-title></div>
                            <div class="admin-hero-preview__subtitle" data-home-hero-subtitle></div>
                            <a href="#home-main" class="btn" data-home-hero-button data-home-hero-button-default="Ver conteúdo">Ver conteúdo</a>
                        </div>
                    </div>
                </div>
                <div class="admin-home-upload">
                    <label class="admin-dropzone-field">Imagem do banner
                        <input type="file" accept=".jpg,.jpeg,.png,.webp,.gif" data-home-carousel-file hidden>
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
                    <div class="admin-home-upload__hint">JPG/PNG/GIF/WebP • máx. 5MB • recomendado: 1920×1080 (16:9) • otimização automática</div>
                </div>

                <div class="admin-home-list" data-home-carousel-list>
                    @foreach ($carouselItems as $item)
                        <div class="admin-home-item" data-carousel-item data-id="{{ $item->id }}">
                            <img src="{{ (str_starts_with($item->image_path, 'imagens/') || str_starts_with($item->image_path, 'images/')) ? asset($item->image_path) : asset('storage/'.$item->image_path) }}" alt="" class="admin-home-thumb" loading="lazy" decoding="async" width="110" height="78">
                            <div class="admin-home-fields">
                                <label>Título
                                    <input type="text" maxlength="100" value="{{ $item->title }}" data-carousel-title>
                                </label>
                                <label>Subtítulo
                                    <input type="text" maxlength="255" value="{{ $item->subtitle }}" data-carousel-subtitle>
                                </label>
                                <label>Texto do botão
                                    <input type="text" maxlength="80" value="{{ $item->button_text }}" placeholder="Ex.: Conhecer serviços" data-carousel-button-text>
                                </label>
                                <label>Link do botão
                                    <input type="url" maxlength="2048" value="{{ $item->button_url }}" placeholder="https://..." data-carousel-button-url>
                                </label>
                                <label class="checkbox-line">
                                    <input type="checkbox" @checked($item->is_active) data-carousel-active>
                                    Ativa
                                </label>
                            </div>
                            <div class="admin-home-actions">
                                <div class="admin-home-order">
                                    <span class="admin-home-order__label" data-item-order></span>
                                    <div class="admin-home-order__buttons">
                                        <button class="btn btn-secondary admin-home-order-btn" type="button" data-move-up aria-label="Mover para cima">↑</button>
                                        <button class="btn btn-secondary admin-home-order-btn" type="button" data-move-down aria-label="Mover para baixo">↓</button>
                                    </div>
                                </div>
                                <button class="btn btn-secondary" type="button" data-carousel-delete>Remover</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="cards" hidden>
            <article class="admin-surface">
                <div class="admin-home-cards-head">
                    <div>
                        <div class="admin-section-kicker">Blocos da home</div>
                        <h2>Cards informativos</h2>
                    </div>
                    <button class="btn" type="button" data-home-card-add>Adicionar card</button>
                </div>

                <div class="admin-home-list" data-home-cards-list>
                    @foreach ($cards as $card)
                        <div class="admin-home-item" data-card-item data-id="{{ $card->id }}" data-detail-image-path="{{ $card->detail_image_path }}">
                            <div class="admin-home-thumb admin-home-thumb--placeholder"></div>
                            <div class="admin-home-fields">
                                <div class="admin-card-editor">
                                    <section class="admin-card-block">
                                        <div class="admin-card-block__head">Dados do card</div>
                                        <label>Título
                                            <input type="text" maxlength="100" value="{{ $card->title }}" data-card-title>
                                        </label>
                                        <label>Descrição curta (no quadrado)
                                            <textarea rows="3" data-card-description>{{ $card->description }}</textarea>
                                        </label>
                                        <label>Ícone (texto)
                                            <input type="text" maxlength="60" value="{{ $card->icon }}" placeholder="Ex.: irrigação" data-card-icon>
                                        </label>
                                        <label>Link externo opcional
                                            <input type="url" maxlength="2048" value="{{ $card->link_url }}" placeholder="https://..." data-card-link>
                                        </label>
                                        <label class="checkbox-line">
                                            <input type="checkbox" @checked($card->is_active) data-card-active>
                                            Card ativo
                                        </label>
                                    </section>

                                    <section class="admin-card-block">
                                        <div class="admin-card-block__head">
                                            <span>Descrição vinculada</span>
                                            <label class="checkbox-line">
                                                <input type="checkbox" @checked($card->detail_enabled) data-card-detail-enabled>
                                                Ativar descrição vinculada
                                            </label>
                                        </div>
                                        <p class="admin-card-block__hint">Quando ativada, o card mostra o botão "Ver mais" e rola para a seção detalhada abaixo dos cards.</p>
                                        <div class="admin-card-detail-fields" data-card-detail-fields @if (! $card->detail_enabled) hidden @endif>
                                            <label>Título da descrição detalhada
                                                <input type="text" maxlength="140" value="{{ $card->detail_title }}" placeholder="Ex.: Compromisso com a evolução conjunta" data-card-detail-title>
                                            </label>
                                            <label>Subtítulo da descrição detalhada
                                                <input type="text" maxlength="255" value="{{ $card->detail_subtitle }}" placeholder="Texto complementar do bloco" data-card-detail-subtitle>
                                            </label>
                                            <label>Texto da descrição detalhada
                                                <textarea rows="6" data-card-detail-body>{{ $card->detail_body }}</textarea>
                                            </label>
                                            <label>Texto do botão "Ver mais"
                                                <input type="text" maxlength="80" value="{{ $card->detail_button_text }}" placeholder="Ex.: Ver mais" data-card-detail-button-text>
                                            </label>
                                            <div class="admin-card-detail-media">
                                                <img
                                                    class="admin-card-detail-media__preview"
                                                    data-card-detail-image-preview
                                                    alt=""
                                                    src="{{ $card->detail_image_path ? ((str_starts_with($card->detail_image_path, 'imagens/') || str_starts_with($card->detail_image_path, 'images/')) ? asset($card->detail_image_path) : asset('storage/'.$card->detail_image_path)) : asset('imagens/hero.jpg') }}"
                                                >
                                                <div class="admin-card-detail-media__actions">
                                                    <input class="admin-file-input" type="file" accept=".jpg,.jpeg,.png,.webp" data-card-detail-image-file>
                                                    <button class="admin-file-trigger" type="button" data-card-detail-image-pick>Selecionar imagem</button>
                                                </div>
                                                <label>Legenda da imagem
                                                    <input type="text" maxlength="160" value="{{ $card->detail_image_caption }}" placeholder="Ex.: Foto: Equipe em campo" data-card-detail-image-caption>
                                                </label>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <div class="admin-home-actions">
                                <div class="admin-home-order">
                                    <span class="admin-home-order__label" data-item-order></span>
                                    <div class="admin-home-order__buttons">
                                        <button class="btn btn-secondary admin-home-order-btn" type="button" data-move-up aria-label="Mover para cima">↑</button>
                                        <button class="btn btn-secondary admin-home-order-btn" type="button" data-move-down aria-label="Mover para baixo">↓</button>
                                    </div>
                                </div>
                                <button class="btn btn-secondary" type="button" data-card-delete>Excluir</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="seo" hidden>
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Busca</div>
                        <h2>SEO</h2>
                    </div>
                </div>
                <div class="admin-form">
                    <label>Título SEO
                        <input type="text" name="seo_title" maxlength="160" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Descrição SEO
                        <textarea name="seo_description" rows="2" maxlength="255" data-home-setting>{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                    </label>
                </div>
            </article>
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="config" hidden>
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Identidade</div>
                        <h2>Configurações do site</h2>
                    </div>
                </div>
                <div class="admin-form">
                    @php
                        $faviconCandidate = 'imagens/favicon.png';
                        $faviconFallback = 'imagens/logo.png';
                        $faviconSource = file_exists(public_path($faviconCandidate)) ? $faviconCandidate : $faviconFallback;
                        $faviconVersion = file_exists(public_path($faviconSource)) ? ('?v='.filemtime(public_path($faviconSource))) : '';
                        $faviconUrl = asset($faviconSource).$faviconVersion;
                    @endphp
                    <div class="admin-surface" style="margin:0;">
                        <div class="admin-section-head">
                            <div>
                                <div class="admin-section-kicker">Identidade</div>
                                <h2>Favicon</h2>
                            </div>
                        </div>
                        <div style="display:flex;gap:.9rem;align-items:center;flex-wrap:wrap;">
                            <img src="{{ $faviconUrl }}" alt="" width="48" height="48" data-home-favicon-current style="border-radius:.7rem;border:1px solid rgba(13,27,62,.12);background:#fff;">
                            <label class="admin-dropzone-field" style="margin:0;min-width:min(520px,100%);">Alterar favicon
                                <input type="file" accept=".png" data-home-favicon-file hidden>
                                <div class="admin-dropzone" data-admin-dropzone>
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
                        </div>
                        <div class="admin-home-upload__hint">PNG • máx. 5MB • será gerado favicon (64×64) + ícone iOS (180×180)</div>
                    </div>
                    <label>Nome da empresa
                        <input type="text" name="company_name" maxlength="150" value="{{ old('company_name', $settings['company_name'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Slogan
                        <input type="text" name="tagline" maxlength="255" value="{{ old('tagline', $settings['tagline'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Telefone
                        <input type="text" name="phone" maxlength="40" value="{{ old('phone', $settings['phone'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Número do link do WhatsApp
                        <input type="text" name="phone2" maxlength="40" value="{{ old('phone2', $settings['phone2'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Mensagem do WhatsApp
                        <textarea name="message" rows="2" maxlength="500" data-home-setting>{{ old('message', $settings['message'] ?? '') }}</textarea>
                    </label>
                    <label>Email
                        <input type="email" name="email" maxlength="150" value="{{ old('email', $settings['email'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Endereço
                        <input type="text" name="address" maxlength="255" value="{{ old('address', $settings['address'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Sobre (rodapé)
                        <textarea name="about" rows="4" data-home-setting>{{ old('about', $settings['about'] ?? '') }}</textarea>
                    </label>
                </div>
            </article>
        </div>

        @if (($audits ?? collect())->isNotEmpty())
            <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="historico" hidden>
                <article class="admin-surface">
                    <div class="admin-section-head">
                        <div>
                            <div class="admin-section-kicker">Rastreabilidade</div>
                            <h2>Histórico de alterações</h2>
                        </div>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr><th>Quando</th><th>Usuário</th><th>Tipo</th><th>Ação</th><th>Alterações</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    <td>{{ $audit->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $audit->user?->name ?? ($audit->user?->email ?? '-') }}</td>
                                    <td>{{ $audit->entity_type }}</td>
                                    <td>{{ $audit->action }}</td>
                                    <td>
                                        @foreach (($audit->changes ?? []) as $field => $diff)
                                            @if (is_array($diff) && array_key_exists('old', $diff) && array_key_exists('new', $diff))
                                                <div><strong>{{ $field }}</strong>: {{ $diff['old'] ?? '-' }} → {{ $diff['new'] ?? '-' }}</div>
                                            @else
                                                <div><strong>{{ $field }}</strong>: {{ is_array($diff) ? json_encode($diff) : $diff }}</div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </article>
            </div>
        @endif
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tabRoot = document.querySelector('[data-home-tabs]');
            if (!tabRoot) {
                return;
            }

            var buttons = tabRoot.querySelectorAll('[data-home-tab-trigger]');
            var panels = tabRoot.querySelectorAll('[data-home-tab-panel]');

            function activateTab(target) {
                buttons.forEach(function (button) {
                    var active = button.getAttribute('data-target') === target;
                    button.classList.toggle('is-active', active);
                    button.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                panels.forEach(function (panel) {
                    var visible = panel.getAttribute('data-home-tab-panel') === target;
                    panel.hidden = !visible;
                });
            }

            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    activateTab(button.getAttribute('data-target'));
                });
            });
        });
    </script>
@endsection
