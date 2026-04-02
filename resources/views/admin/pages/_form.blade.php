@csrf
@isset($page)
    @method('PUT')
@endisset
<label>Título</label>
<input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required>
<label>Slug</label>
<input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" required>
<label>Título Hero</label>
<input type="text" name="hero_title" value="{{ old('hero_title', $page->hero_title ?? '') }}">
<label>Subtítulo Hero</label>
<textarea name="hero_subtitle">{{ old('hero_subtitle', $page->hero_subtitle ?? '') }}</textarea>
<label>Conteúdo</label>
<textarea name="content">{{ old('content', $page->content ?? '') }}</textarea>
<label>Meta Title</label>
<input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}">
<label>Meta Description</label>
<textarea name="meta_description">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
<label>Ordem</label>
<input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" min="0">
<label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published ?? true))> Publicada</label>
<button type="submit">Salvar</button>
