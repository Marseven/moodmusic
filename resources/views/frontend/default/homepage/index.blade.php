@extends('index')
@section('content')
    @include('homepage.nav')
    @if (auth()->check())
        <div id="home-dashboard">
            <div id="home-dashboard-user">
                <a href="{{ auth()->user()->permalink_url }}" class="user-img-container">
                    <img src="{!! auth()->user()->artwork_url !!}" class="user-img">
                </a>
                <div class="welcome-message" data-translate-text="WELCOME_BACK" data-name="{{ auth()->user()->name }}">{{ __('web.WELCOME_BACK', ['name' => auth()->user()->name]) }}</div>
                <div class="dashboard-links">
                    <a href="{{ route('frontend.user.favorites', ['username' => auth()->user()->username]) }}" class="dashboard-link" data-translate-text="MY_MUSIC">{{ __('web.MY_MUSIC') }}</a> • <a href="/{{ auth()->user()->username }}" class="dashboard-link" data-translate-text="VIEW_PROFILE">{{ __('web.VIEW_PROFILE') }}</a>
                </div>
            </div>
            <ul id="home-dashboard-stats" class="stat-summary">
                <li> <a href="{{ route('frontend.user.collection', ['username' => auth()->user()->username]) }}" class="stat"> <span id="user-collection" class="num">{{ auth()->user()->collection_count }}</span> <span class="label" data-translate-text="SONGS">{{ __('web.SONGS') }}</span> </a> </li>
                <li> <a href="{{ route('frontend.user.favorites', ['username' => auth()->user()->username]) }}" class="stat"> <span id="user-favorites" class="num">{{ auth()->user()->favorite_count }}</span> <span class="label" data-translate-text="FAVORITES">{{ __('web.FAVORITES') }}</span> </a> </li>
                <li> <a href="{{ route('frontend.user.playlists', ['username' => auth()->user()->username]) }}" class="stat"> <span id="user-playlists" class="num">{{ auth()->user()->playlist_count }}</span> <span class="label" data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}</span> </a> </li>
                <li> <a href="{{ route('frontend.user.following', ['username' => auth()->user()->username]) }}" class="stat"> <span id="user-following" class="num">{{ auth()->user()->following_count }}</span> <span class="label" data-translate-text="FOLLOWING">{{ __('web.FOLLOWING') }}</span> </a> </li>
                <li> <a href="{{ route('frontend.user.followers', ['username' => auth()->user()->username]) }}" class="stat last"> <span id="user-followers" class="num">{{ auth()->user()->follower_count }}</span> <span class="label" data-translate-text="FOLLOWERS">{{ __('web.FOLLOWERS') }}</span> </a> </li>
            </ul>
        </div>
    @endif
    <div id="page-content" class="home">
        <div id="column1" class="full">
            @include('commons.slideshow', ['slides' => $home->slides])
            @if (auth()->check())
                @if(isset($home->recentListens) && count($home->recentListens))
                    @include('commons.suggest', ['more_link' => auth()->user()->permalink_url, 'type' => 'recent', 'songs' => $home->recentListens, 'title' => '<span data-translate-text="LISTEN_AGAIN">' . __('web.LISTEN_AGAIN') . '</span>', 'description' => '<span class="section-tagline" data-translate-text="TAGLINE_LISTEN_AGAIN">' . __('web.TAGLINE_LISTEN_AGAIN') . '</span>'])
                @endif
                @if(isset($home->userCommunitySongs) && count($home->userCommunitySongs))
                    @include('commons.suggest', ['more_link' => route('frontend.community'), 'type' => 'community', 'songs' => $home->userCommunitySongs, 'title' => '<span data-translate-text="TOP_COMMUNITY_ALBUMS">' . __('web.TOP_COMMUNITY_ALBUMS') . '</span>', 'description' => '<span class="section-tagline" data-translate-text="TAGLINE_COMMUNITY">' . __('web.TAGLINE_COMMUNITY') . '</span>'])
                @endif
                @if(isset($home->obsessedSongs) && count($home->obsessedSongs))
                    @include('commons.suggest', ['more_link' => auth()->user()->permalink_url, 'type' => 'obsessed', 'songs' => $home->obsessedSongs, 'title' => '<span data-translate-text="YOU_ARE_OBSESSED_WITH_MUSIC">' . __('web.YOU_ARE_OBSESSED_WITH_MUSIC') . '</span>', 'description' => '<span class="section-tagline" data-translate-text="TAGLINE_SIMILAR_OBSESSED">' . __('web.TAGLINE_SIMILAR_OBSESSED') . '</span>'])
                @endif
            @endif
            @include('commons.channel', ['channels' => $home->channels])
            @if(isset($home->popularSongs) && count($home->popularSongs))
                @include('commons.suggest', ['more_link' => route('frontend.trending'), 'type' => 'popular', 'songs' => $home->popularSongs, 'title' => '<span data-translate-text="POPULAR">' . __('web.POPULAR') . '</span>', 'description' => '<span class="section-tagline" data-translate-text="TAGLINE_POPULAR">' . __('web.TAGLINE_POPULAR') . '</span>'])
            @endif
        </div>
    </div>
    {!! Advert::get('footer') !!}
    @include('homepage.footer')
@endsection