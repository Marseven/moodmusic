@extends('index')
@section('content')
    @include('album.nav', ['album' => $album])
    <div id="page-content" class="artist">
        <div class="container">
            <div class="page-header artist main small desktop"> <a class="img "> <img id="page-cover-art" src="{{ $album->artwork_url }}" alt="{!! $album->title !!}">  </a>
                <div class="inner">
                    <h1 title="{!! $album->title !!}">{!! $album->title !!}<span class="subpage-header"> / Albums</span></h1>
                    <div class="byline">
                        <span class="label">Album by</span> @foreach($album->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</div>
                    <div class="actions-primary">
                        <a class="btn play play-object desktop" data-type="album" data-id="{{ $album->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <span data-translate-text="PLAY_ALBUM">Play Album</span>
                        </a>
                        <a class="btn share desktop" data-type="album" data-id="{{ $album->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            <span class="desktop" data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="column1" class="content full">
                @if(count($related))
                    <div id="grid-toolbar-container">
                        <div class="grid-toolbar">
                            <div class="grid-toolbar-inner">
                                <ul class="actions primary"> </ul>
                                <ul class="actions secondary">
                                    <li>
                                        <form class="search-bar"> <i class="icon search icon-search-m-gray-flat"></i> <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter"> <a class="icon ex icon-circle-ex-l-gray-flat clear-filter"></a> <a class="remove hide"></a>
                                            <ul id="page_search_results"></ul>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="grid" class="no-artist-column albums grid-albums-vertical">
                        @include('commons.album', ['albums' => $related, 'element' => 'grid'])
                    </div>
                @else
                    <div class="empty-page followers">
                        <div class="empty-inner">
                            <h2>{{ __('web.EMPTY_SIMILAR_ALBUMS') }}</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection