<label>Titulo
    <input type="text" name="title" required value="{{ old('title', $mediaItem->title) }}">
</label>
<label>Categoria
    <input type="text" name="category" required value="{{ old('category', $mediaItem->category) }}" placeholder="Ex.: Galeria Home">
</label>
<label>Arquivo (imagem ou video)
    <input type="file" name="file" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm">
</label>
<label>Ordem
    <input type="number" name="display_order" min="0" value="{{ old('display_order', $mediaItem->display_order ?? 0) }}">
</label>
<label class="checkbox-line">
    <input type="checkbox" name="is_video" value="1" @checked(old('is_video', $mediaItem->is_video ?? false))>
    E video
</label>
