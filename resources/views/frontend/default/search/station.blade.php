@extends('index')
@section('pagination')
    @include('commons.station', ['stations' => $result->stations, 'element' => 'genre'])
@stop
@section('content')
    @include('search.nav')
    <div id="page-content" class="search">
        <div class="container">
            <div class="page-header desktop">
                <h1> <span data-translate-text="SEARCH_RESULTS">{{ __('web.SEARCH_RESULTS') }}</span> <span>&nbsp;/&nbsp;</span> <strong>{{ $term }}</strong> </h1>
                <div class="did-you-mean hide" data-term="{{ $term }}">
                    <p class="did-you-mean-text">
                        <span data-translate-text="SEARCH_DID_YOU_MEAN">{{ __('web.SEARCH_DID_YOU_MEAN') }} </span>
                        <a class="did-you-mean-search-link search-link"></a> <span>?</span>
                    </p>
                    <a class="did-you-mean-remove">×</a>
                </div>
            </div>
            <div id="column1">
                <div class="content">
                    @if(count($result->stations))
                        <div class="sub-header">
                            <h2 id="search-type-header" data-translate-text="STATION_SEARCH_MATCHES">{{ __('web.STATION_SEARCH_MATCHES') }}</h2>
                        </div>
                        <div id="grid-toolbar-container">
                            <div class="grid-toolbar">
                                <div class="grid-toolbar-inner">
                                    <ul class="actions primary"></ul>
                                    <ul class="actions secondary">
                                        <li>
                                            <a class="btn sort-button" data-sort-relevance="true" data-sort-station="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="26" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                                <span class="sort-label" data-translate-text="RELEVANCE">{{ __('web.RELEVANCE') }}</span>
                                                <span class="caret"></span>
                                            </a>
                                        </li>
                                        <li>
                                            <form class="search-bar">
                                                <svg class="icon search" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                                <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter">
                                                <a class="icon ex clear-filter">
                                                    <svg height="16px" width="16px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531 0 68.382812 26.628906 132.667969 74.980469 181.019531 48.351562 48.351563 112.640625 74.980469 181.019531 74.980469s132.667969-26.628906 181.019531-74.980469c48.351563-48.351562 74.980469-112.636719 74.980469-181.019531 0-68.378906-26.628906-132.667969-74.980469-181.019531zm-70.292969 256.386719c9.761719 9.765624 9.761719 25.59375 0 35.355468-4.882812 4.882813-11.28125 7.324219-17.679687 7.324219s-12.796875-2.441406-17.679687-7.324219l-75.367188-75.367187-75.367188 75.371093c-4.882812 4.878907-11.28125 7.320313-17.679687 7.320313s-12.796875-2.441406-17.679687-7.320313c-9.761719-9.765624-9.761719-25.59375 0-35.355468l75.371093-75.371094-75.371093-75.367188c-9.761719-9.765624-9.761719-25.59375 0-35.355468 9.765624-9.765625 25.59375-9.765625 35.355468 0l75.371094 75.367187 75.367188-75.367187c9.765624-9.761719 25.59375-9.765625 35.355468 0 9.765625 9.761718 9.765625 25.589844 0 35.355468l-75.367187 75.367188zm0 0"/></svg>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="grid" class="infinity-load-more items-sort-able" data-total-page="5">
                            @yield('pagination')
                        </div>
                    @else
                        <div class="sub-header">
                            <h2 id="search-no-match-header" data-translate-text="NO_STATION_MATCHES">{{ __('web.NO_STATION_MATCHES') }}</h2>
                        </div>
                        <div id="grid-no-results">
                            <p data-translate-text="SEARCH_CHECK_SPELLING">Check your spelling, or try a <a href="#!/">new search</a>!</p>
                        </div>
                    @endif
                </div>
            </div>
            <div id="column2">
                <div class="content">
                    {!! Advert::get('sidebar') !!}
                    <div class="hide" data-content="snapshot-search" data-type="artist" data-keyword="{{ $term }}">
                        <div class="sub-header">
                            <h3 data-translate-text="TOP_ARTIST_MATCHES">{{ __('web.TOP_ARTIST_MATCHES') }}</h3>
                            <a href="{{ route('frontend.search.artist', ['slug' => $term]) }}" class="view-more search-link" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot"></ul>
                        <div class="divider"></div>
                    </div>
                    <div class="hide" data-content="snapshot-search" data-type="album" data-keyword="{{ $term }}">
                        <div class="sub-header">
                            <h3 data-translate-text="TOP_ALBUM_MATCHES">{{ __('web.TOP_ALBUM_MATCHES') }}</h3>
                            <a href="{{ route('frontend.search.album', ['slug' => $term]) }}" class="view-more search-link" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot"></ul>
                        <div class="divider"></div>
                    </div>
                    <div class="hide" data-content="snapshot-search" data-type="playlist" data-keyword="{{ $term }}">
                        <div class="sub-header">
                            <h3 data-translate-text="TOP_PLAYLIST_MATCHES">{{ __('web.TOP_PLAYLIST_MATCHES') }}</h3>
                            <a href="{{ route('frontend.search.playlist', ['slug' => $term]) }}" class="view-more search-link" data-translate-text="SEE_ALL" data-searchquery="c" data-searchtype="playlist">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot"></ul>
                        <div class="divider"></div>
                    </div>
                    <div class="hide" data-content="snapshot-search" data-type="user" data-keyword="{{ $term }}">
                        <div class="sub-header">
                            <h3 data-translate-text="TOP_USER_MATCHES">{{ __('web.TOP_USER_MATCHES') }}</h3>
                            <a href="{{ route('frontend.search.user', ['slug' => $term]) }}" class="view-more search-link" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot"></ul>
                        <div class="divider"></div>
                    </div>
                    <div class="hide" data-content="snapshot-search" data-type="event" data-keyword="{{ $term }}">
                        <div class="sub-header">
                            <h3 data-translate-text="TOP_EVENT_MATCHES">{{ __('web.TOP_EVENT_MATCHES') }}s</h3> <a class="view-more search-link" data-translate-text="SEE_ALL" data-searchquery="c" data-searchtype="event">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot"></ul>
                        <div class="divider"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection