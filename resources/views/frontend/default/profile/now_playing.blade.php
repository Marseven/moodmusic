@extends('index')
@section('content')
    @if(isset($profile))
        @include('profile.nav')
    @endif
    <div id="page-content">
        <div id="queue-container" @if(isset($profile)) data-queue-by-username="{{ $profile->username }}" @endif>
            <div class="container">
                @if(isset($profile))
                    <div class="page-header user main small">
                        <div class="img"><img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40"></div>
                        <div class="inner">
                            <div class="actions-primary">
                                <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg>
                                    <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                                </a>
                            </div>
                            <h1 title="{{ $profile->name }}" class="">{{ $profile->name }}<span class="subpage-header"> / Queue</span></h1>
                            @if(isset($profile->group->role_id))
                                <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="page-header user main small">
                        <div class="inner">
                            <h1 title="Queue" class="text-center">Queue</h1>
                        </div>
                    </div>
                @endif
                <div id="column1" class="full">
                    <h2 id="now-playing-header" data-translate-text="NOW_PLAYING_SONG" class="hide">{{ __('web.NOW_PLAYING_SONG') }}</h2>
                    <div id="now-playing-profile-card" class="song main medium hide">

                    </div>
                    <div id="grid-header-container" class="hide">
                        <h2 id="queue-songs-header" data-translate-text="NOW_PLAYING_SONGS_IN_QUEUE">{{ __('web.NOW_PLAYING_SONGS_IN_QUEUE') }}</h2>
                        <div id="now-playing-radio"><span class="radio-btn" data-translate-text="RADIO_OFF">{{ __('web.RADIO_OFF') }}</span></div>
                    </div>
                    <div id="now-playing-grid" class="hide"></div>
                    @if (! auth()->check() && isset($profile) || (auth()->check() && isset($profile) && auth()->user()->username != $profile->username))
                        <div id="no-playing-songs-copy" class="hide" data-translate-text="NOW_PLAYING_NO_SONGS_IN_QUEUE">{{ __('web.NOW_PLAYING_NO_SONGS_IN_QUEUE', ['user' => $profile->name]) }}</div>
                    @else
                        <div id="no-playing-songs-copy" class="hide" data-translate-text="NOW_PLAYING_NO_SONGS_IN_QUEUE_OWNER">{{ __('web.NOW_PLAYING_NO_SONGS_IN_QUEUE_OWNER') }}</div>
                    @endif
                    <div id="no-song-grid-block" class="hide">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if(isset($profile))
                                    @include('commons.song', ['songs' => $profile->suggest, 'element' => 'carousel'])
                                @else
                                    @include('commons.song', ['songs' => $suggest, 'element' => 'carousel'])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection