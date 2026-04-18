@extends('layouts.admin')

@section('content')
    <h1>Nova Pagina</h1>
    <form method="POST" action="{{ route('admin.pages.store') }}" class="admin-form">
        @csrf
        @include('admin.pages._form')
        <button class="btn" type="submit">Salvar</button>
    </form>
@endsection
