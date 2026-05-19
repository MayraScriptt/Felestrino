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
