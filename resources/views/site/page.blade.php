@extends('layouts.site')

@section('content')
    <section class="hero hero-small">
        <div class="container">
            <h1>{{ $page->title }}</h1>
        </div>
    </section>

    <section class="container prose">
        @if (($allowHtml ?? false) === true)
            <div>{!! (string) $page->content !!}</div>
        @else
            <div>{!! nl2br(e($page->content)) !!}</div>
        @endif
    </section>
@endsection
