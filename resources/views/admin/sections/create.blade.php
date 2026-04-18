@extends('layouts.admin')

@section('content')
    <h1>Nova Secao</h1>
    <form method="POST" action="{{ route('admin.sections.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @include('admin.sections._form')
        <button class="btn" type="submit">Salvar</button>
    </form>
@endsection
