@extends('layouts.admin')

@section('content')
    <h1>Editar Seção</h1>
    <form method="POST" action="{{ route('admin.sections.update', $section) }}" enctype="multipart/form-data">
        @include('admin.sections._form')
    </form>
@endsection
