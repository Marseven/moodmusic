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
                @if(count($playlist->subscribers))
                    <div class="row">
                        @include('commons.user', ['users' => $playlist->subscribers, 'element' => 'profile'])
                    </div>
                @else
                    <div class="empty-page followers">
                        <div class="empty-inner">
                            <h2 data-translate-text="PLAYLIST_EMPTY_SUBSCRIBER">{{ __('web.PLAYLIST_EMPTY_SUBSCRIBER') }}</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection