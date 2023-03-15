@extends('index')
@section('content')
    @include('podcast.nav', ['podcast' => $podcast])
    <div id="page-content">
        <div class="container">
            <div class="page-header podcast main small desktop">
                <a class="img ">
                    <img src="{{ $podcast->artwork_url }}" alt="{{ $podcast->title}}">
                </a>
                <div class="inner">
                    <h1 title="{{ $podcast->title }}">{{ $podcast->title }}<span class="subpage-header"> / {{ __('web.SUBSCRIBERS') }}</span></h1>
                    <div class="byline">
                        <span class="label">{{ __('web.PODCAST') }}</span>
                    </div>
                    <div class="actions-primary">
                        @include('podcast.actions')
                    </div>
                </div>
            </div>
            <div id="column1" class="full">
                @if(count($podcast->subscribers))
                    <div class="row">
                        @include('commons.user', ['users' => $podcast->subscribers, 'element' => 'profile'])
                    </div>
                @else
                    <div class="empty-page followers">
                        <div class="empty-inner">
                            <h2 data-translate-text="PODCAST_EMPTY_SUBSCRIBER">{{ __('web.PODCAST_EMPTY_SUBSCRIBER') }}</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection