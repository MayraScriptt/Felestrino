@extends('layouts.admin')

@section('content')
    <h1>Editar Mídia</h1>
    <form method="POST" action="{{ route('admin.media-items.update', $mediaItem) }}" enctype="multipart/form-data">
        @include('admin.media-items._form')
    </form>
@endsection
