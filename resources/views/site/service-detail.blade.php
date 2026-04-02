@extends('layouts.site')

@section('content')
    <section class="inner-hero">
        <div class="container">
            <h1>{{ $service->title }}</h1>
            <p>{{ $service->excerpt }}</p>
        </div>
    </section>

    <section class="content">
        <div class="container">
            @if ($service->image_path)
                <img src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->title }}" loading="lazy">
            @endif
            <p>{{ $service->content }}</p>
            <a class="btn-primary" href="{{ route('site.services') }}">Voltar aos serviços</a>
        </div>
    </section>
@endsection
