@extends('layouts.site')

@section('content')
    <section class="hero hero-small">
        <div class="container">
            <h1>{{ $page->title }}</h1>
        </div>
    </section>

    <section class="container prose">
        <div>{!! nl2br(e($page->content)) !!}</div>
    </section>

    <section class="container section-grid">
        @foreach ($page->sections as $section)
            <article class="card">
                @if ($section->image_path)
                    <img src="{{ asset('storage/'.$section->image_path) }}" alt="{{ $section->title }}" loading="lazy">
                @endif
                <h2>{{ $section->title }}</h2>
                <p>{{ $section->subtitle }}</p>
                <div>{!! nl2br(e($section->content)) !!}</div>
            </article>
        @endforeach
    </section>
@endsection
