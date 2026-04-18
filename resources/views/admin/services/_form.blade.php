<label>Titulo
    <input type="text" name="title" required value="{{ old('title', $service->title) }}">
</label>
<label>Slug
    <input type="text" name="slug" required value="{{ old('slug', $service->slug) }}">
</label>
<label>Resumo
    <textarea name="short_description" rows="2">{{ old('short_description', $service->short_description) }}</textarea>
</label>
<label>Descricao
    <textarea name="description" rows="6">{{ old('description', $service->description) }}</textarea>
</label>
<label>Imagem
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
</label>
<label>Ordem
    <input type="number" name="display_order" min="0" value="{{ old('display_order', $service->display_order ?? 0) }}">
</label>
<label class="checkbox-line">
    <input type="checkbox" name="is_highlight" value="1" @checked(old('is_highlight', $service->is_highlight ?? false))>
    Destacar
</label>
<label class="checkbox-line">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $service->is_published ?? true))>
    Publicado
</label>
