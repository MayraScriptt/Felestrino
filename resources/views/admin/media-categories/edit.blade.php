@extends('layouts.admin')

@section('content')
    <h1>Editar Categoria</h1>
    <form method="POST" action="{{ route('admin.media-categories.update', $category) }}">
        @include('admin.media-categories._form')
    </form>
@endsection
