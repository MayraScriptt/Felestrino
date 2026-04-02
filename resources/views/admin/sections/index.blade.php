@extends('layouts.admin')

@section('content')
    <h1>Seções</h1>
    <a href="{{ route('admin.sections.create') }}">Nova Seção</a>
    <table>
        <thead><tr><th>Título</th><th>Página</th><th>Tipo</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach ($sections as $section)
                <tr>
                    <td>{{ $section->title }}</td>
                    <td>{{ $section->page->title }}</td>
                    <td>{{ $section->type }}</td>
                    <td>
                        <a href="{{ route('admin.sections.show', $section) }}">Ver</a>
                        <a href="{{ route('admin.sections.edit', $section) }}">Editar</a>
                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST">
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
