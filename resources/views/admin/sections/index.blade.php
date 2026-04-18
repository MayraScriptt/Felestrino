@extends('layouts.admin')

@section('content')
    <div class="title-row">
        <h1>Secoes</h1>
        <a class="btn" href="{{ route('admin.sections.create') }}">Nova secao</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Titulo</th><th>Pagina</th><th>Ordem</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($sections as $section)
                <tr>
                    <td>{{ $section->title }}</td>
                    <td>{{ $section->page?->title }}</td>
                    <td>{{ $section->display_order }}</td>
                    <td>{{ $section->is_published ? 'Publicada' : 'Oculta' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.sections.edit', $section) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.sections.destroy', $section) }}">
                            @csrf @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $sections->links() }}
@endsection
