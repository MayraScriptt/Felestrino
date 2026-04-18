@extends('layouts.admin')

@section('content')
    <div class="title-row">
        <h1>Midias</h1>
        <a class="btn" href="{{ route('admin.media.create') }}">Nova midia</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Titulo</th><th>Categoria</th><th>Tipo</th><th>Arquivo</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($mediaItems as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->is_video ? 'Video' : 'Imagem' }}</td>
                    <td><a href="{{ asset('storage/'.$item->file_path) }}" target="_blank">Visualizar</a></td>
                    <td class="actions">
                        <a href="{{ route('admin.media.edit', $item) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.media.destroy', $item) }}">
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
