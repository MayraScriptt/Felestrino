@extends('layouts.admin')

@section('content')
    <h1>Mídias</h1>
    <a href="{{ route('admin.media-items.create') }}">Nova Mídia</a>
    <table>
        <thead><tr><th>Título</th><th>Categoria</th><th>Tipo</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach ($mediaItems as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->mediaCategory->name }}</td>
                    <td>{{ $item->type }}</td>
                    <td>
                        <a href="{{ route('admin.media-items.show', $item) }}">Ver</a>
                        <a href="{{ route('admin.media-items.edit', $item) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.media-items.destroy', $item) }}">
                            @csrf @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $mediaItems->links() }}
@endsection
