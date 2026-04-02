@csrf
@isset($section)
    @method('PUT')
@endisset
<label>Página</label>
<select name="page_id" required>
    @foreach ($pages as $pageItem)
        <option value="{{ $pageItem->id }}" @selected(old('page_id', $section->page_id ?? '') == $pageItem->id)>{{ $pageItem->title }}</option>
    @endforeach
</select>
<label>Título</label>
<input type="text" name="title" value="{{ old('title', $section->title ?? '') }}" required>
<label>Tipo</label>
<input type="text" name="type" value="{{ old('type', $section->type ?? 'text') }}" required>
<label>Conteúdo</label>
<textarea name="content">{{ old('content', $section->content ?? '') }}</textarea>
<label>Imagem</label>
<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
<label>Vídeo (URL)</label>
<input type="url" name="video_url" value="{{ old('video_url', $section->video_url ?? '') }}">
<label>Ordem</label>
<input type="number" name="sort_order" value="{{ old('sort_order', $section->sort_order ?? 0) }}" min="0">
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $section->is_active ?? true))> Ativa</label>
<button type="submit">Salvar</button>
