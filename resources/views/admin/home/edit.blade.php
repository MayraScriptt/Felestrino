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
    </style>

    <div class="admin-pages-head">
        <h1>Home</h1>
        <div class="admin-autosave" data-autosave-indicator>Pronto</div>
    </div>

    <section data-home-admin data-home-tabs>
        <div class="admin-home-tabs" role="tablist" aria-label="Secoes da Home">
            <button class="admin-home-tab-btn is-active" type="button" role="tab" aria-selected="true" data-home-tab-trigger data-target="banner">Banner</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="cards">Cards</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="seo">SEO</button>
            <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="config">Configuracoes do site</button>
            @if (($audits ?? collect())->isNotEmpty())
                <button class="admin-home-tab-btn" type="button" role="tab" aria-selected="false" data-home-tab-trigger data-target="historico">Historico de alteracoes</button>
            @endif
        </div>

        <div class="admin-home-tab-panel" role="tabpanel" data-home-tab-panel="banner">
            <article class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Midia dinamica</div>
                        <h2>Carrossel</h2>
                    </div>
                </div>
                <div class="admin-hero-preview-wrap">
                    <div class="admin-hero-preview-head">
                        <strong>Preview hero (sincronizado com o item selecionado)</strong>
                        <div class="admin-hero-preview-actions">
                            <button class="btn btn-secondary" type="button" data-home-carousel-prev>Anterior</button>
                            <button class="btn btn-secondary" type="button" data-home-carousel-next>Proximo</button>
                        </div>
                    </div>
                    <div class="admin-hero-preview" data-home-hero-preview>
                        <img class="admin-hero-preview__img" data-home-hero-img alt="" decoding="async">
                        <div class="admin-hero-preview__overlay">
                            <div class="admin-hero-preview__title" data-home-hero-title></div>
                            <div class="admin-hero-preview__subtitle" data-home-hero-subtitle></div>
                            <a href="#home-main" class="btn" data-home-hero-button data-home-hero-button-default="Ver conteudo">Ver conteudo</a>
                        </div>
                    </div>
                </div>
                <div class="admin-home-upload">
                    <input type="file" accept=".jpg,.jpeg,.png,.webp" data-home-carousel-file>
                    <button class="btn" type="button" data-home-carousel-upload>Adicionar imagem</button>
                    <div class="admin-home-upload__hint">JPEG/PNG/WebP • max 5MB • otimizacao automatica</div>
                </div>

                <div class="admin-home-list" data-home-carousel-list>
                    @foreach ($carouselItems as $item)
                        <div class="admin-home-item" draggable="true" data-carousel-item data-id="{{ $item->id }}">
                            <img src="{{ str_starts_with($item->image_path, 'images/') ? asset($item->image_path) : asset('storage/'.$item->image_path) }}" alt="" class="admin-home-thumb" loading="lazy" decoding="async" width="110" height="78">
                            <div class="admin-home-fields">
                                <label>Titulo
                                    <input type="text" maxlength="100" value="{{ $item->title }}" data-carousel-title>
                                </label>
                                <label>Subtitulo
                                    <input type="text" maxlength="255" value="{{ $item->subtitle }}" data-carousel-subtitle>
                                </label>
                                <label>Link
                                    <input type="url" maxlength="2048" value="{{ $item->link_url }}" placeholder="https://..." data-carousel-link>
                                </label>
                                <label>Texto do botao
                                    <input type="text" maxlength="80" value="{{ $item->button_text }}" placeholder="Ex.: Conhecer servicos" data-carousel-button-text>
                                </label>
                                <label>Link do botao
                                    <input type="url" maxlength="2048" value="{{ $item->button_url }}" placeholder="https://..." data-carousel-button-url>
                                </label>
                                <label class="checkbox-line">
                                    <input type="checkbox" @checked($item->is_active) data-carousel-active>
                                    Ativa
                                </label>
                            </div>
                            <div class="admin-home-actions">
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
                        <div class="admin-home-item" draggable="true" data-card-item data-id="{{ $card->id }}">
                            <div class="admin-home-thumb admin-home-thumb--placeholder"></div>
                            <div class="admin-home-fields">
                                <label>Titulo
                                    <input type="text" maxlength="100" value="{{ $card->title }}" data-card-title>
                                </label>
                                <label>Descricao
                                    <textarea rows="3" data-card-description>{{ $card->description }}</textarea>
                                </label>
                                <label>Icone (texto)
                                    <input type="text" maxlength="60" value="{{ $card->icon }}" placeholder="Ex.: irrigacao" data-card-icon>
                                </label>
                                <label>Link
                                    <input type="url" maxlength="2048" value="{{ $card->link_url }}" placeholder="https://..." data-card-link>
                                </label>
                                <label class="checkbox-line">
                                    <input type="checkbox" @checked($card->is_active) data-card-active>
                                    Ativo
                                </label>
                            </div>
                            <div class="admin-home-actions">
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
                    <label>SEO Title
                        <input type="text" name="seo_title" maxlength="160" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" data-home-setting>
                    </label>
                    <label>SEO Description
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
                        <h2>Configuracoes do site</h2>
                    </div>
                </div>
                <div class="admin-form">
                    <label>Nome da empresa
                        <input type="text" name="company_name" maxlength="150" value="{{ old('company_name', $settings['company_name'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Slogan
                        <input type="text" name="tagline" maxlength="255" value="{{ old('tagline', $settings['tagline'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Telefone
                        <input type="text" name="phone" maxlength="40" value="{{ old('phone', $settings['phone'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Email
                        <input type="email" name="email" maxlength="150" value="{{ old('email', $settings['email'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Endereco
                        <input type="text" name="address" maxlength="255" value="{{ old('address', $settings['address'] ?? '') }}" data-home-setting>
                    </label>
                    <label>Sobre (rodape)
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
                            <h2>Historico de alteracoes</h2>
                        </div>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr><th>Quando</th><th>Usuario</th><th>Tipo</th><th>Acao</th><th>Alteracoes</th></tr>
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
    </section>

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
