<div class="admin-modal" data-project-add-media-modal hidden>
    <div class="admin-modal__panel" role="dialog" aria-modal="true" aria-label="Adicionar mídias">
        <div class="admin-modal__head">
            <h2 class="admin-modal__title">Adicionar mídias</h2>
            <button class="admin-modal__close" type="button" data-project-add-media-close aria-label="Fechar">×</button>
        </div>

        <div class="admin-modal__body">
            <div class="admin-modal__status" data-project-add-media-status>Selecione as mídias e envie.</div>

            <div class="admin-modal__grid">
                @include('modals._addmidia')
            </div>

            <div class="admin-surface">
                <div class="admin-section-head">
                    <div>
                        <div class="admin-section-kicker">Status</div>
                        <h2>Envios</h2>
                    </div>
                    <button class="btn" type="button" data-project-add-media-close>Salvar</button>
                </div>
                <div class="admin-modal__uploads" data-project-add-media-uploads></div>
            </div>
        </div>
    </div>
</div>
