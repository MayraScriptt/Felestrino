@extends('layouts.admin')

@section('content')
    <h1>Nova Mídia</h1>
    <form method="POST" action="{{ route('admin.media-items.store') }}" enctype="multipart/form-data">
        @include('admin.media-items._form')
    </form>
@endsection
