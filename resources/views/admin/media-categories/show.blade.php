@extends('layouts.admin')

@section('content')
    <h1>{{ $category->name }}</h1>
    <p>Slug: {{ $category->slug }}</p>
    <h2>Mídias</h2>
    <ul>
        @foreach ($category->mediaItems as $item)
            <li>{{ $item->title }} ({{ $item->type }})</li>
        @endforeach
    </ul>
@endsection
