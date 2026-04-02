@extends('layouts.admin')

@section('content')
    <h1>Serviços</h1>
    <a href="{{ route('admin.services.create') }}">Novo Serviço</a>
    <table>
        <thead><tr><th>Título</th><th>Slug</th><th>Ordem</th><th>Ações</th></tr></thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->title }}</td>
                    <td>{{ $service->slug }}</td>
                    <td>{{ $service->sort_order }}</td>
                    <td>
                        <a href="{{ route('admin.services.show', $service) }}">Ver</a>
                        <a href="{{ route('admin.services.edit', $service) }}">Editar</a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST">
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
