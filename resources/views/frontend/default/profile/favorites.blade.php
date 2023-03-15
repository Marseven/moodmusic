@extends('index')
@section('pagination')
    @include('commons.song', ['songs' => $profile->loved, 'element' => 'collection'])
@stop
@section('content')
    @include('profile.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header user main  small">
                <div class="img">
                    <img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40">
                </div>
                <div class="inner">
                    <div class="actions-primary">
                        @if (! auth()->check() || (auth()->check() && auth()->user()->username != $profile->username))
                            <a class="btn favorite @if($profile->favorite) btn-success @endif" data-id="{{ $profile->id }}" data-type="1" data-title="{{ $profile->name }}" data-url="{{ $profile->url }}">
                                <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                @if($profile->favorite)
                                    <span class="favorite-label" data-translate-text="UNFOLLOW">{{ __('web.UNFOLLOW') }}</span>
                                @else
                                    <span class="favorite-label" data-translate-text="FOLLOW">{{ __('web.FOLLOW') }}</span>
                                @endif
                            </a>
                        @endif
                        <a class="btn share" data-type="user" data-id="{{ $profile->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            <span class="desktop" data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                        </a>
                        <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}">
                            <i class="icon icon-station-gray"></i>
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                    </div>
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / {{ __('web.FAVORITES') }}</span></h1>
                   @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            @if (count($profile->loved))
                <div class="content">
                    <div id="column1" class="full">
                        @include('commons.toolbar.song', ['type' => 'favorites', 'id' => $profile->id])
                        <div id="songs-grid" class="infinity-load-more">
                            @yield('pagination')
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-page following">
                    <div class="empty-inner">
                        @if (auth()->check() && auth()->user()->username == $profile->username)
                            <h2 data-translate-text="EMPTY_COLLECTION_OWNER">{{ __('web.EMPTY_COLLECTION_OWNER') }}</h2>
                            <p data-translate-text="EMPTY_COLLECTION_DESC_OWNER">{{ __('web.EMPTY_COLLECTION_DESC_OWNER') }}</p>
                        @else
                            <h2 data-translate-text="EMPTY_COLLECTION">{{ __('web.EMPTY_COLLECTION', ['name' => $profile->name]) }}</h2>
                            <p data-translate-text="EMPTY_COLLECTION_DESC">{{ __('web.EMPTY_COLLECTION_DESC') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection