@extends('layouts.admin')

@section('content')
    <h1>{{ $page->title }}</h1>
    <p>Slug: {{ $page->slug }}</p>
    <p>{{ $page->content }}</p>
    <h2>Seções</h2>
    <ul>
        @foreach ($page->sections as $section)
            <li>{{ $section->title }} ({{ $section->type }})</li>
        @endforeach
    </ul>
@endsection
