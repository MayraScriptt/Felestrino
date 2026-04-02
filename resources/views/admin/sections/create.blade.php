@extends('layouts.admin')

@section('content')
    <h1>Nova Seção</h1>
    <form method="POST" action="{{ route('admin.sections.store') }}" enctype="multipart/form-data">
        @include('admin.sections._form')
    </form>
@endsection
