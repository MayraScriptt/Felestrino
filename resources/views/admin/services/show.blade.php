@extends('layouts.admin')

@section('content')
    <h1>{{ $service->title }}</h1>
    <p>{{ $service->excerpt }}</p>
    <p>{{ $service->content }}</p>
    @if ($service->image_path)
        <img src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->title }}" width="320">
    @endif
@endsection
