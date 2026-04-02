@extends('layouts.admin')

@section('content')
    <h1>Novo Serviço</h1>
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @include('admin.services._form')
    </form>
@endsection
