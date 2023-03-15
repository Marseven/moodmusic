@extends('index')
@section('content')
    {!! Advert::get('header') !!}
    <div id="page-content" class="blogs">
        <div id="blogs">
            <h1 class="recent hide">{{ __('web.HOME_BLOG') }}</h1>
            <div class="blogs-catalogs">
                <div id="column1">
                    @foreach($posts as $index => $post)
                        @if($post->fixed)
                            <div class="article-catalog fixed">
                                <div class="article-category">
                                    <span>in</span>
                                    @foreach($post->categories as $category) <a href="{{ route('frontend.blog.category', ['category' => $category->alt_name]) }}">{{ $category->name }}</a>@if(!$loop->last), @endif @endforeach
                                </div>
                                <a href="{{ $post->permalink_url }}" class="article-title">{{ $post->title }}</a>
                                <div class="article-meta">
                                    <a class="article-publish" href="{{ route('frontend.blog.browse.by.day', ['year' =>  \Carbon\Carbon::parse($post->created_at)->format('Y'), 'month' => \Carbon\Carbon::parse($post->created_at)->format('m'), 'day' => \Carbon\Carbon::parse($post->created_at)->format('d')]) }}">{{ \Carbon\Carbon::parse($post->created_at)->format(config('settings.post_time_format')) }}</a>
                                    <a class="article-comments" href="{{ $post->permalink_url }}" class="article-title">Comments ({{ $post->comment_count }})</a>
                                </div>
                                <div class="article-content">
                                    @if($post->getFirstMediaUrl('artwork'))
                                        <a href="{{ $post->permalink_url }}" class="article-title">
                                            <div class="img-container">
                                                <img src="{{ $post->getFirstMediaUrl('artwork', 'thumbnail') }}" alt="{{ $post->title }}">
                                            </div>
                                        </a>
                                    @endif
                                    <div class="article-inner">
                                        <p class="article-description">{!! $post->short_content !!}</p>
                                    </div>
                                    <a href="{{ $post->permalink_url }}" class="btn btn-primary btn-secondary">Read More</a>
                                </div>
                            </div>
                        @else
                            <div class="article-catalog">
                                <div class="article-content">
                                    @if($post->getFirstMediaUrl('artwork'))
                                        <a href="{{ $post->permalink_url }}" class="article-title">
                                            <div class="img-container">
                                                <img src="{{ $post->getFirstMediaUrl('artwork', 'thumbnail') }}" alt="{{ $post->title }}">
                                            </div>
                                        </a>
                                    @endif
                                    <div class="article-inner">
                                        <a href="{{ $post->permalink_url }}" class="article-title">{{ $post->title }}</a>
                                        <div class="article-details">
                                            @if(isset($post->user))
                                                <a href="/{{ $post->user->username }}" class="article-author">{{ $post->user->name }}</a>
                                            @endif
                                            <a class="article-publish">{{ \Carbon\Carbon::parse($post->created_at)->format('F j Y') }}</a>
                                            @foreach($post->categories as $category)<a class="article-category" href="{{ route('frontend.blog.category', ['category' => $category->alt_name]) }}">{{ $category->name }}</a>@if(!$loop->last), @endif @endforeach
                                        </div>
                                        <p class="article-description">{!! $post->short_content !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div id="column2">
                    {!! Advert::get('sidebar') !!}
                    @include('post.widget.categories', ['categories' => $categories])
                    @include('post.widget.archives', ['archives' => $archives])
                    @include('post.widget.tags', ['tags' => $tags])
                </div>
            </div>
        </div>
    </div>
@endsection