<label>Pagina
    <select name="page_id" required>
        @foreach ($pages as $item)
            <option value="{{ $item->id }}" @selected(old('page_id', $section->page_id) == $item->id)>{{ $item->title }}</option>
        @endforeach
    </select>
</label>
<label>Titulo
    <input type="text" name="title" required value="{{ old('title', $section->title) }}">
</label>
<label>Chave tecnica
    <input type="text" name="section_key" value="{{ old('section_key', $section->section_key) }}">
</label>
<label>Subtitulo
    <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle) }}">
</label>
<label>Conteudo
    <textarea name="content" rows="6">{{ old('content', $section->content) }}</textarea>
</label>
<label>Imagem
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
</label>
<label>Ordem
    <input type="number" name="display_order" min="0" value="{{ old('display_order', $section->display_order ?? 0) }}">
</label>
<label class="checkbox-line">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $section->is_published ?? true))>
    Publicada
</label>
