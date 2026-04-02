@extends('layouts.admin')

@section('content')
    <h1>{{ $mediaItem->title }}</h1>
    <p>Categoria: {{ $mediaItem->mediaCategory->name }}</p>
    <p>Tipo: {{ $mediaItem->type }}</p>
    @if ($mediaItem->type === 'image')
        <img src="{{ asset('storage/'.$mediaItem->file_path) }}" alt="{{ $mediaItem->alt_text ?: $mediaItem->title }}" width="320">
    @else
        <a href="{{ asset('storage/'.$mediaItem->file_path) }}" target="_blank">Abrir arquivo</a>
    @endif
@endsection
