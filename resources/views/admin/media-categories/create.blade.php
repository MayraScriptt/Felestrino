@extends('layouts.admin')

@section('content')
    <h1>Nova Categoria</h1>
    <form method="POST" action="{{ route('admin.media-categories.store') }}">
        @include('admin.media-categories._form')
    </form>
@endsection
