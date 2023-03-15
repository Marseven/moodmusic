@extends('index')
@section('pagination')
    @include('commons.activity', ['activities' => $community->activities, 'type' => 'full'])
@stop
@section('content')
    @include('homepage.nav')
    <div id="page-content" class="community logged-out">
        <div class="container">
            <div class="page-header community main no-separator desktop">
                <h1 id="community-header" data-translate-text="COMMUNITY">{{ __('web.COMMUNITY') }}</h1>
            </div>
            <div id="column1" class="community-feed full">
                @include('commons.slideshow', ['slides' => $slides])
                @include('commons.channel', ['channels' => $channels])
                @if (auth()->check())
                    <div id="post-to-feed" class="post-item">
                        <div class="user-img-link">
                            <img src="{!! auth()->user()->artwork_url !!}" class="user-img">
                        </div>
                        <div class="post-container">
                            <div class="post-msg">
                                <div class="post-feed-msg" contentEditable="true" placeholder="{{ __('web.PLACEHOLDER_USER_FEED_SHARE') }}"></div>
                                <div class="post-item-search">
                                    <svg class="icon search icon-search-m-gray-flat" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                    <input class="post-item-input" type="text" autocomplete="off">
                                    <div class="tooltip attach-music-tooltip hide">
                                        <div id="search-attach-music-content"></div>
                                    </div>
                                </div>
                                <div class="selected-item">
                                    <img class="item-image">
                                    <h3 class="item-name"></h3>
                                    <p class="item-subtext"></p>
                                    <svg class="icon remove-item" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                </div>
                                <div class="selected-module"></div>
                            </div>
                            <a class="btn dropdown-toggle attach-music">
                                <span class="label" data-translate-text="ATTACH_MUSIC">{{ __('web.ATTACH_MUSIC') }}</span>
                                <span class="caret"></span>
                            </a>
                            <button class="btn share-post">
                                <svg width="18" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" xml:space="preserve"><path d="M17.9,11.9c0.6-0.4,0.6-1.4,0-1.8l-6.4-4.4c-0.7-0.5-1.7,0-1.7,0.9v2c-4.4,0-6.7,2.6-6.7,7.3c0,0.4,0.3,0.6,0.6,0.6c0.3,0,0.5-0.2,0.7-0.7C5,14,6.7,13.4,9.8,13.4v2c0,0.9,1,1.4,1.7,0.9L17.9,11.9z"/></svg>
                                <span data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div id="community" class="content infinity-load-more" data-total-page="{{ 20 }}">
                    @yield('pagination')
                </div>
            </div>
        </div>
    </div>
@endsection