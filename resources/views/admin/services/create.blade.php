@extends('layouts.admin')

@section('content')
    <h1>Novo Servico</h1>
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @include('admin.services._form')
        <button class="btn" type="submit">Salvar</button>
    </form>
@endsection
