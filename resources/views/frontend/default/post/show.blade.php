@extends('index')
@section('content')
    <div id="page-content" class="blogs">
        @if($post->getFirstMediaUrl('artwork'))
            <div class="featured-image-overlay">
                <img alt="{{ $post->title }}" src="{{ $post->getFirstMediaUrl('artwork') }}"/>
            </div>
        @endif
        <div id="blogs" class="post @if(isset($post->artwork)) artwork @endif">
            <h1>{{ $post->title }}</h1>
            <div class="post-details">
                <span class="post-author">
                    <a href="{{ $post->user->permalink_url }}">{{ $post->user->name }}</a>
                </span>
                <span class="post-publish">
                    <a class="post-publish" href="{{ route('frontend.blog.browse.by.day', ['year' =>  \Carbon\Carbon::parse($post->created_at)->format('Y'), 'month' => \Carbon\Carbon::parse($post->created_at)->format('m'), 'day' => \Carbon\Carbon::parse($post->created_at)->format('d')]) }}">{{ \Carbon\Carbon::parse($post->created_at)->format(config('settings.post_time_format')) }}</a>
                </span>
                <span class="post-category">
                    <strong>In</strong>
                    @foreach($post->categories as $category) <a href="{{ route('frontend.blog.category', ['category' => $category->alt_name]) }}">{{ $category->name }}</a>@if(!$loop->last), @endif @endforeach
                </span>
            </div>
            <div class="post-content">
                {!! $post->full_content ? $post->full_content : $post->short_content !!}
            </div>
            {!! $pages !!}
        </div>
        <div id="column1" class="full">
            <div class="comments-container">
                <div class="sub-header">
                    <h2 data-translate-text="COMMENTS">Comments</h2>
                </div>
                <div id="comments">
                    @include('comments.index', ['object' => (Object) ['id' => $post->id, 'type' => 'App\Models\Post', 'title' => $post->title]])
                </div>
            </div>
        </div>
    </div>
@endsection