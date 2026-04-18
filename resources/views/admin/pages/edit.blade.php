@extends('layouts.admin')

@section('content')
    <h1>Editar Pagina</h1>
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="admin-form">
        @csrf @method('PUT')
        @include('admin.pages._form')
        <button class="btn" type="submit">Atualizar</button>
    </form>
@endsection
