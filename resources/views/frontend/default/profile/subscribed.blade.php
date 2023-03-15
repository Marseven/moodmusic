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
                        <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}"><i class="icon icon-station-gray"></i><span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span></a></div>
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / Playlists</span></h1>
                    @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            <div id="column1" class="full">
                @if (count($profile->subscribed))
                    <div class="content">
                        <div id="grid-toolbar-container">
                            <div class="grid-toolbar">
                                <div class="grid-toolbar-inner">
                                    <ul class="actions secondary">
                                        <li>
                                            <div class="btn-group first">
                                                <a href="/{{ $profile->username }}/playlists" class="btn my-playlists"><span data-translate-text="MY_PLAYLISTS">My Playlists</span></a>
                                                <a href="/{{ $profile->username }}/playlists/subscribed" class="btn subscribed active"><span data-translate-text="SUBSCRIBED">Subscribed</span></a>
                                            </div>
                                        </li>
                                        <li>
                                            <form class="search-bar"> <i class="icon search icon-search-m-gray-flat"></i> <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter"> <a class="icon ex icon-circle-ex-l-gray-flat clear-filter"></a> <a class="remove hide"></a>
                                                <ul id="page_search_results"></ul>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="user-profile-grid" class="playlists playlists-grid">
                            @include('commons.playlist', ['playlists' => $profile->subscribed, 'element' => 'profile'])
                        </div>
                    </div>
                @else
                    <div class="empty-page following">
                        <div class="empty-inner">
                            @if (auth()->check() && auth()->user()->username == $profile->username)
                                <h2 data-translate-text="EMPTY_SUBSCRIBED_PLAYLISTS_OWNER">{{ __('web.EMPTY_SUBSCRIBED_PLAYLISTS_OWNER') }}</h2>
                                <p data-translate-text="EMPTY_SUBSCRIBED_PLAYLISTS_DESC_OWNER">{{ __('web.EMPTY_SUBSCRIBED_PLAYLISTS_DESC_OWNER') }}</p>
                            @else
                                <h2 data-translate-text="EMPTY_SUBSCRIBED_PLAYLISTS">{{ __('web.EMPTY_SUBSCRIBED_PLAYLISTS', ['name' => $profile->name]) }}</h2>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div id="column2" class="sm">
                <div class="content">
                    {!! Advert::get('sidebar') !!}
                </div>
            </div>
        </div>
    </div>
@endsection