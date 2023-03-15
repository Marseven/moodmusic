@extends('index')
@section('pagination')
    @include('commons.activity', ['activities' => $activities, 'type' => 'full'])
@stop
@section('content')
    <div id="page-nav">
        <div class="outer">
            <ul>
                <li><a href="{{ route('frontend.hashtag', ['slug' => $tag]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.hashtag') active @endif">
                        <span data-translate-text="TOP">{{ __('web.TOP') }}</span>
                        <div class="arrow"></div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.hashtag.latest', ['slug' => $tag]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.hashtag.latest') active @endif">
                        <span data-translate-text="LATEST">{{ __('web.LATEST') }}</span>
                        <div class="arrow"></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    {!! Advert::get('header') !!}
    <div id="page-content" class="community">
        <div class="container">
            <div class="page-header community main no-separator desktop">
                <h1>#{{ $tag }}</h1>
                <div class="byline"><span>{{ $total }} people are listening about this.</span></div>
            </div>
            <div id="column1" class="community-feed full">
                <div id="community" class="content infinity-load-more" data-total-page="{{ 20 }}">
                    @yield('pagination')
                </div>
            </div>
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection