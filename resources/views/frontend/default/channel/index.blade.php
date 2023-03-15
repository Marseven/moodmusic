@extends('index')
@section('pagination')
    @if($channel->object_type == 'song')
        @include('commons.song', ['songs' => $channel->objects, 'element' => 'genre'])
    @elseif($channel->object_type == 'artist')
        @include('commons.artist', ['artists' => $channel->objects, 'element' => 'collection'])
    @elseif($channel->object_type == 'album')
        @include('commons.album', ['albums' => $channel->objects, 'element' => 'grid'])
    @elseif($channel->object_type == 'playlist')
        @include('commons.playlist', ['playlists' => $channel->objects, 'element' => null])
    @elseif($channel->object_type == 'station')
        @include('commons.station', ['stations' => $channel->objects, 'element' => 'carousel'])
    @elseif($channel->object_type == 'user')
        @include('commons.user', ['users' => $channel->objects, 'element' => 'grid'])
    @elseif($channel->object_type == 'podcast')
        @include('commons.podcast', ['podcasts' => $channel->objects, 'element' => 'grid'])
    @elseif($channel->object_type == 'video')
        @include('commons.video', ['videos' => $channel->objects, 'element' => 'grid'])
    @endif
@stop
@section('content')
    {!! Advert::get('header') !!}
    <div id="page-content">
        <div class="container">
            <div class="page-header no-separator desktop">
                <div id="primary-actions">
                    <a class="btn play-station" data-type="channel" data-id="{{ $channel->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg><span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                    </a>
                </div>
                <h1>{{ $channel->title }}</h1>
                <div class="byline"><span>{{ $channel->description }}</span></div>
            </div>
            <div id="column1" class="full">
                @if($channel->object_type == 'song')
                    @include('commons.toolbar.song', ['type' => 'channel', 'id' => $channel->id])
                @elseif($channel->object_type == 'station')
                    @include('commons.toolbar.station')
                @endif
                <div @if($channel->object_type == 'song') id="songs-grid" @endif class="infinity-load-more items-sort-able @if($channel->object_type == 'playlist' || $channel->object_type == 'user') playlists-grid @endif @if($channel->object_type == 'album' || $channel->object_type == 'podcast') row media-row @endif">
                    @yield('pagination')
                </div>
            </div>
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection