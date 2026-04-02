@extends('layouts.admin')

@section('content')
    <h1>Categorias de Mídia</h1>
    <a href="{{ route('admin.media-categories.create') }}">Nova Categoria</a>
    <table>
        <thead><tr><th>Nome</th><th>Slug</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <a href="{{ route('admin.media-categories.show', $category) }}">Ver</a>
                        <a href="{{ route('admin.media-categories.edit', $category) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.media-categories.destroy', $category) }}">
                            @csrf @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
@endsection
