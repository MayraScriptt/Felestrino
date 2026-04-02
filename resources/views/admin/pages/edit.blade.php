@extends('layouts.admin')

@section('content')
    <h1>Editar Página</h1>
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @include('admin.pages._form')
    </form>
@endsection
