@csrf
@isset($category)
    @method('PUT')
@endisset
<label>Nome</label>
<input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required>
<label>Slug</label>
<input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required>
<label>Ordem</label>
<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
<button type="submit">Salvar</button>
