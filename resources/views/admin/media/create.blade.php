@extends('layouts.admin')

@section('content')
    <h1>Nova Midia</h1>
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @include('admin.media._form')
        <button class="btn" type="submit">Salvar</button>
    </form>
@endsection
