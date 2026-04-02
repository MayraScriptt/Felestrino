@extends('layouts.admin')

@section('content')
    <h1>Editar Serviço</h1>
    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @include('admin.services._form')
    </form>
@endsection
