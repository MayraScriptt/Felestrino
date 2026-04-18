@extends('layouts.admin')

@section('content')
    <h1>Editar Midia</h1>
    <form method="POST" action="{{ route('admin.media.update', $mediaItem) }}" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')
        @include('admin.media._form')
        <button class="btn" type="submit">Atualizar</button>
    </form>
@endsection
