@extends('layouts.admin')

@section('content')
    <h1>Páginas</h1>
    <a href="{{ route('admin.pages.create') }}">Nova Página</a>
    <table>
        <thead><tr><th>Título</th><th>Slug</th><th>Ordem</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.pages.show', $page) }}">Ver</a>
                        <a href="{{ route('admin.pages.edit', $page) }}">Editar</a>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $pages->links() }}
@endsection
