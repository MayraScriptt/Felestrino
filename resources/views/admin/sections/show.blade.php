@extends('layouts.admin')

@section('content')
    <h1>{{ $section->title }}</h1>
    <p>Página: {{ $section->page->title }}</p>
    <p>Tipo: {{ $section->type }}</p>
    <p>{{ $section->content }}</p>
    @if ($section->image_path)
        <img src="{{ asset('storage/'.$section->image_path) }}" alt="{{ $section->title }}" width="320">
    @endif
@endsection
