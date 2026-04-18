@extends('layouts.admin')

@section('content')
    <div class="title-row">
        <h1>Servicos</h1>
        <a class="btn" href="{{ route('admin.services.create') }}">Novo servico</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Titulo</th><th>Slug</th><th>Ordem</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->title }}</td>
                    <td>{{ $service->slug }}</td>
                    <td>{{ $service->display_order }}</td>
                    <td>{{ $service->is_published ? 'Publicado' : 'Oculto' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.services.edit', $service) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
                            @csrf @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $services->links() }}
@endsection
