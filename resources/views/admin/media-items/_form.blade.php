@csrf
@isset($mediaItem)
    @method('PUT')
@endisset
<label>Categoria</label>
<select name="media_category_id" required>
    @foreach ($categories as $category)
        <option value="{{ $category->id }}" @selected(old('media_category_id', $mediaItem->media_category_id ?? '') == $category->id)>{{ $category->name }}</option>
    @endforeach
</select>
<label>Título</label>
<input type="text" name="title" value="{{ old('title', $mediaItem->title ?? '') }}" required>
<label>Tipo</label>
<select name="type" required>
    @foreach (['image' => 'Imagem', 'video' => 'Vídeo', 'document' => 'Documento'] as $value => $label)
        <option value="{{ $value }}" @selected(old('type', $mediaItem->type ?? 'image') === $value)>{{ $label }}</option>
    @endforeach
</select>
<label>Arquivo</label>
<input type="file" name="file" accept=".jpg,.jpeg,.png,.webp,.mp4,.pdf,.doc,.docx">
<label>Texto Alternativo</label>
<input type="text" name="alt_text" value="{{ old('alt_text', $mediaItem->alt_text ?? '') }}">
<label>Ordem</label>
<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $mediaItem->sort_order ?? 0) }}">
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $mediaItem->is_active ?? true))> Ativo</label>
<button type="submit">Salvar</button>
