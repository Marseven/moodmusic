@extends('index')
@section('content')
    @include('playlist.nav', ['playlist' => $playlist])
    <div id="page-content">
        <div class="container">
            <div class="page-header playlist main small desktop">
                <a class="img ">
                    <img src="{{ $playlist->artwork_url }}" alt="{{ $playlist->title}}">

                </a>
                <div class="inner">
                    <h1 title="{!! $playlist->title !!}">{!! $playlist->title !!}<span class="subpage-header"> / Playlists</span></h1>
                    <div class="byline">
                        <span class="label">Playlist</span>
                    </div>
                    <div class="actions-primary">
                        @include('playlist.actions')
                    </div>
                </div>
            </div>
            <div id="column1" class="full">
                @if(count($playlist->collaborators))
                    <div class="row">
                        @include('commons.user', ['users' => $playlist->collaborators, 'element' => 'profile'])
                    </div>
                @else
                    <div class="empty-page">
                        <div class="empty-inner">
                            <div class="msg">
                                @if(! auth()->check() || auth()->check() && auth()->user()->username != $playlist->username)
                                    <h2 data-translate-text="PLAYLIST_EMPTY_COLLABORATORS">{{ __('web.PLAYLIST_EMPTY_COLLABORATORS') }}</h2>
                                @else
                                    <h2 data-tranlsate-text="PLAYLIST_EMPTY_COLLABORATORS_OWNER">{{ __('web.PLAYLIST_EMPTY_COLLABORATORS_OWNER') }}</h2>
                                    <p data-tranlsate-text="PLAYLIST_EMPTY_COLLABORATORS_DESC_OWNER">{{ __('web.PLAYLIST_EMPTY_COLLABORATORS_DESC_OWNER') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection