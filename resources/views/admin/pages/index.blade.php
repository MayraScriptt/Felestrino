@extends('layouts.admin')

@section('content')
    <div class="title-row">
        <h1>Paginas</h1>
        <a class="btn" href="{{ route('admin.pages.create') }}">Nova pagina</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Titulo</th><th>Slug</th><th>Ordem</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>{{ $page->display_order }}</td>
                    <td>{{ $page->is_published ? 'Publicada' : 'Oculta' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.pages.edit', $page) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}">
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
