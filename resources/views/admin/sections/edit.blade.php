@extends('layouts.admin')

@section('content')
    <h1>Editar Secao</h1>
    <form method="POST" action="{{ route('admin.sections.update', $section) }}" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')
        @include('admin.sections._form')
        <button class="btn" type="submit">Atualizar</button>
    </form>
@endsection
