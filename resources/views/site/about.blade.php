@extends('layouts.site')

@section('content')
    <section class="inner-hero">
        <div class="container">
            <h1>{{ $page?->hero_title ?? 'Sobre a empresa' }}</h1>
            <p>{{ $page?->hero_subtitle }}</p>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <p>{{ $page?->content ?? ($siteSettings['about_summary'] ?? '') }}</p>
            @if ($page)
                @foreach ($page->sections as $section)
                    <article class="section-item">
                        <h2>{{ $section->title }}</h2>
                        <p>{{ $section->content }}</p>
                    </article>
                @endforeach
            @endif
        </div>
    </section>
@endsection
