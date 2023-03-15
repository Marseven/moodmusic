@extends('index')
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content" class="artist">
        <div class="container">

            <div class="page-header artist main small desktop">
                <a class="img">
                    <img src="{{ $artist->artwork_url }}" alt="{!! $artist->name !!}">
                </a>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}<span class="subpage-header"> / Albums</span></h1>
                    <div class="byline">Create and manage your album on the website.</div>
                    <div class="actions-primary">
                        <a class="btn create-album">
                            <svg height="26" width="14" viewBox="0 0 511.334 511.334" xmlns="http://www.w3.org/2000/svg"><path d="m436.667 21c0-11.598-9.402-21-21-21h-394.667c-11.598 0-21 9.402-21 21v394.667c0 11.598 9.402 21 21 21s21-9.402 21-21v-373.667h373.667c11.598 0 21-9.402 21-21z"/><path d="m490.333 74.667h-394.666c-11.598 0-21 9.402-21 21v394.667c0 11.598 9.402 21 21 21h394.667c11.598 0 21-9.402 21-21v-394.667c-.001-11.598-9.402-21-21.001-21zm-21 394.667h-352.666v-352.667h352.667v352.667z"/><path d="m255.667 404.667c35.106 0 63.667-28.561 63.667-63.667 0-10.433 0-84.548 0-94.021l33.608 16.805c10.373 5.184 22.987.981 28.175-9.392 5.187-10.374.982-22.988-9.392-28.175l-64-32c-13.939-6.967-30.392 3.176-30.392 18.783v64.334h-21.667c-35.105 0-63.666 28.561-63.666 63.666 0 35.106 28.561 63.667 63.667 63.667zm0-85.333h21.667v21.666c0 11.947-9.72 21.667-21.667 21.667s-21.667-9.72-21.667-21.667c0-11.946 9.72-21.666 21.667-21.666z"/></svg>
                            <span data-translate-text="CREATE_ALBUM">Create Album</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="column1" class="content full">
                <h2 class="text-center">{{ __('web.LB_UPLOAD_ALBUM_SELECT_DESC') }}</h2>
                @if(count($artist->albums))
                    <div id="grid-toolbar-container">
                        <div class="grid-toolbar">
                            <div class="grid-toolbar-inner">
                                <ul class="actions primary"> </ul>
                                <ul class="actions secondary">
                                    <li>
                                        <form class="search-bar"> <i class="icon search icon-search-m-gray-flat"></i>
                                            <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter">
                                            <a class="icon ex icon-circle-ex-l-gray-flat clear-filter"></a> <a class="remove hide"></a>
                                            <ul id="page_search_results"></ul>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="grid" class="row">
                        @foreach($artist->albums as $album)
                            <script>var album_data_{{ $album->id }} = {!! json_encode($album) !!}</script>
                            <div class="module module-cell small grid-item">
                                <div class="img-container">
                                    <img class="img" src="{{ $album->artwork_url }}" alt="{!! $album->title !!}">
                                    <a class="overlay-link" href="{{ route('frontend.auth.user.artist.manager.albums.show', ['id' => $album->id]) }}"></a>
                                    <div class="actions primary">
                                        <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="album" data-id="{{ $album->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                        </a>
                                        <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="album" data-id="{{ $album->id }}">
                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="module-inner">
                                    <a href="{{ route('frontend.auth.user.artist.manager.albums.show', ['id' => $album->id]) }}" class="headline">{!! $album->title !!}</a>
                                    <span class="byline">by @foreach($album->artists as $artist)<a class="secondary-text" href="{{ route('frontend.auth.user.artist.manager.albums.show', ['id' => $album->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-page followers">
                        <div class="empty-inner">
                            <h2>{{ __('web.ARTIST_DIDNT_RELEASE_ANYTHING_YET', ['name' => $artist->name]) }}</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection