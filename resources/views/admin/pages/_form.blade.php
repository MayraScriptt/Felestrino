<label>Titulo
    <input type="text" name="title" required value="{{ old('title', $page->title) }}">
</label>
<label>Slug
    <input type="text" name="slug" required value="{{ old('slug', $page->slug) }}">
</label>
<label>Meta title
    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
</label>
<label>Meta description
    <textarea name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
</label>
<label>Conteudo
    <textarea name="content" rows="7">{{ old('content', $page->content) }}</textarea>
</label>
<label>Ordem
    <input type="number" name="display_order" min="0" value="{{ old('display_order', $page->display_order ?? 0) }}">
</label>
<label class="checkbox-line">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published ?? true))>
    Publicada
</label>
