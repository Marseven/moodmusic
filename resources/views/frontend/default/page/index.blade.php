@extends('index')
@section('content')
    {!! Advert::get('header') !!}
    <div id="page-content" class="pages">
        <div class="page-header no-separator">
            <h1>{{ $page->title }}</h1>
        </div>
        <div class="content">
            <p>Last Updated: {{ $page->updated_at }}</p>
            {!! $page->content !!}
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection