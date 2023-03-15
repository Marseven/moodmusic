@extends('index')
@section('content')
    @include('profile.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header user main  small">
                <div class="img"><img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40"></div>
                <div class="inner">
                    <div class="actions-primary">
                        @if (auth()->check() && auth()->user()->username == $profile->username)
                            <a class="btn create-playlist"><svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg><span data-translate-text="CREATE_PLAYLIST">{{ __('web.CREATE_PLAYLIST') }}</span></a>
                        @endif
                        <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg>
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                    </div>
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / Playlists</span></h1>
                    @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            <div id="column1" class="full">
                @if (count($profile->playlists))
                    <div class="content">
                        <div id="grid-toolbar-container">
                            <div class="grid-toolbar">
                                <div class="grid-toolbar-inner">
                                    @if (auth()->check() && auth()->user()->username == $profile->username)
                                        <ul class="actions primary">
                                            <li>
                                                <div class="btn-group first create-playlist"> <a class="btn playlist-new-button create-playlist"> <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg> <span class="playlist-new-label" data-translate-text="CREATE_PLAYLIST">Create Playlist</span> </a> </div>
                                            </li>
                                        </ul>
                                    @endif
                                    <ul class="actions secondary">
                                        <li>
                                            <div class="btn-group first">
                                                <a  href="{{ route('frontend.user.playlists', ['username' => $profile->username]) }}" class="btn my-playlists active"><span data-translate-text="MY_PLAYLISTS">{{ __('web.MY_PLAYLISTS') }}</span></a>
                                                <a href="{{ route('frontend.user.playlists.subscribed', ['username' => $profile->username]) }}" class="btn subscribed "><span data-translate-text="SUBSCRIBED">{{ __('web.SUBSCRIBED') }}</span></a>
                                            </div>
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
                        <div id="user-profile-grid" class="playlists playlists-grid row">
                            @include('commons.playlist', ['playlists' => $profile->playlists, 'element' => 'profile'])
                        </div>
                    </div>
                @else
                    <div class="empty-page following">
                        <div class="empty-inner">
                            @if (auth()->check() && auth()->user()->username == $profile->username)
                                <h2 data-translate-text="EMPTY_PLAYLISTS_OWNER">{{ __('web.EMPTY_PLAYLISTS_OWNER', ['name' => $profile->name]) }}</h2>
                                <p data-translate-text="EMPTY_PLAYLISTS_DESC_OWNER">{{ __('web.EMPTY_PLAYLISTS_DESC_OWNER') }}</p>
                            @else
                                <h2 data-translate-text="EMPTY_PLAYLISTS">{{ __('web.EMPTY_PLAYLISTS', ['name' => $profile->name]) }}</h2>
                                <p data-translate-text="EMPTY_PLAYLISTS_DESC">{{ __('web.EMPTY_PLAYLISTS_DESC') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection