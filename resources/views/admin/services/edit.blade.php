@extends('layouts.admin')

@section('content')
    <h1>Editar Servico</h1>
    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')
        @include('admin.services._form')
        <button class="btn" type="submit">Atualizar</button>
    </form>
@endsection
