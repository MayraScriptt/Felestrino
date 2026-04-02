@csrf
@isset($service)
    @method('PUT')
@endisset
<label>Título</label>
<input type="text" name="title" value="{{ old('title', $service->title ?? '') }}" required>
<label>Slug</label>
<input type="text" name="slug" value="{{ old('slug', $service->slug ?? '') }}" required>
<label>Ícone</label>
<input type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}">
<label>Resumo</label>
<input type="text" name="excerpt" value="{{ old('excerpt', $service->excerpt ?? '') }}">
<label>Conteúdo</label>
<textarea name="content">{{ old('content', $service->content ?? '') }}</textarea>
<label>Imagem</label>
<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
<label>Ordem</label>
<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> Ativo</label>
<button type="submit">Salvar</button>
