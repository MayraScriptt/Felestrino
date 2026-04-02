@extends('layouts.admin')

@section('content')
    <h1>Nova Página</h1>
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @include('admin.pages._form')
    </form>
@endsection
