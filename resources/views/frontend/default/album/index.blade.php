@extends('index')
@section('content')
    @include('album.nav', ['album' => $album])
    {!! Advert::get('header') !!}
    <script>var album_data_{{ $album->id }} = {!! json_encode($album->makeHidden('songs')) !!}</script>
    <div id="page-content" class="bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $album->artwork_url }}" alt="{!! $album->title !!}">
            </div>
            <div class="page-header album main medium ">
                <div class="img"> <img id="page-cover-art" src="{{ $album->artwork_url }}" alt="{!! $album->title !!}"> </div>
                <div class="inner">
                    <h1 title="{!! $album->title !!}">{!! $album->title !!}</h1>
                        @if(!$album->visibility)<span class="private" data-translate-text="PRIVATE">{{ __('web.PRIVATE') }}</span>@endif
                    <div class="byline">
                        @if($album->explicit)
                            <span class="explicit">E</span>
                        @endif
                        <span>Album by @foreach($album->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
                    </div>
                    <div class="actions-primary">
                        <a class="btn play play-object desktop" data-type="album" data-id="{{ $album->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <span data-translate-text="PLAY_ALBUM">{{ __('web.PLAY_ALBUM') }}</span>
                        </a>

                        @if($album->selling && config('settings.module_store', true))
                            @if($album->purchased)
                                <a class="btn desktop" href="{{ route('frontend.user.purchased', auth()->user()->username) }}">
                                    <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                    </svg>
                                    <span>{{ __('web.DOWNLOAD') }}</span>
                                </a>
                            @else
                                <a class="btn desktop" data-action="buy" data-orderable-type="App\Models\Album" data-orderable-id="{{ $album->id }}">
                                    <svg height="26"  width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve"><path d="M307.286,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024c8.285,0,15.024-6.74,15.024-15.024C322.31,284.298,315.571,277.558,307.286,277.558z"/><path d="M187.186,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024s15.024-6.74,15.024-15.024C202.21,284.298,195.47,277.558,187.186,277.558z"/><path d="M512,97.433H63.541l-4.643-59.324H0V68.11h31.153l25.793,329.548h38.067c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.117,53.117c29.289,0,53.118-23.829,53.118-53.117c0-8.281-1.907-16.123-5.301-23.117h130.727c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.118,53.117c29.289,0,53.117-23.829,53.117-53.117c0-8.281-1.907-16.123-5.301-23.117h36.558L512,97.433z M352.311,292.583c0,24.827-20.199,45.025-45.025,45.025c-24.827,0-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.323,0.917,15.024,2.587v-62.661h-90.099v105.099c0,24.827-20.199,45.025-45.025,45.025s-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.322,0.917,15.024,2.587v-92.662h150.101V292.583z"/></svg>
                                    <span class="desktop" data-translate-text="Buy">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $album->price }}</span>
                                </a>
                            @endif
                        @endif

                        <a class="btn share" data-type="album" data-id="{{ $album->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            <span class="desktop" data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                        </a>

                        <!--
                        <a class="btn fav mobile" data-album-id="{{ $album->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
                        </a>
                        -->
                        @if($album->selling && config('settings.module_store', true))
                            @if($album->purchased)
                                <a class="btn mobile" href="{{ route('frontend.user.purchased', auth()->user()->username) }}">
                                    <span>{{ __('web.DOWNLOAD') }}</span>
                                </a>
                            @else
                                <a class="btn play mobile" data-action="buy" data-orderable-type="App\Models\Album" data-orderable-id="{{ $album->id }}">
                                    <span data-translate-text="Buy">Buy {{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $album->price }}</span>
                                </a>
                            @endif
                        @else
                            <a class="btn play play-object mobile" data-type="album" data-id="{{ $album->id }}">
                                <span data-translate-text="PLAY_ALBUM">{{ __('web.PLAY_ALBUM') }}</span>
                            </a>
                        @endif
                        <a class="btn options mobile" data-toggle="contextmenu" data-trigger="left" data-type="album" data-id="{{ $album->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                        </a>
                    </div>
                    @if($album->description)
                        <div class="description">
                            <p>{{ $album->description }}</p>
                        </div>
                    @endif
                    <ul class="stat-summary">
                        <li>
                            <span id="song-count" class="num">{{ $album->song_count }}</span>
                            <span class="label" data-translate-text="SONGS">Songs</span>
                        </li>

                        @if($album->released_at)
                            <li>
                                <span id="song-count" class="num">{{ \Carbon\Carbon::parse($album->released_at)->format('Y') }}</span>
                                <span class="label" data-translate-text="RELEASED">{{ __('web.RELEASED') }}</span>
                            </li>
                        @endif
                        @if(! $album->approved)
                            <li>
                                <span class="badge badge-warning">{{ __('web.PREVIEW') }}</span>
                            </li>
                        @endif
                        <li class="tags">
                            @if($album->genres && count($album->genres))
                                @foreach($album->genres as $index => $genre)
                                    <a class="genre-link" href="{{ $genre->permalink_url }}"><span class="tag">{{ $genre->name }}</span></a>
                                @endforeach
                            @else
                                @if(isset($album->artists) && count($album->artists))
                                    @foreach($album->artists->first()->genres as $index => $genre)
                                        <a class="genre-link" href="{{ $genre->permalink_url }}"><span class="tag">{{ $genre->name }}</span></a>
                                    @endforeach
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div id="column1">
                <div class="content">
                    @include('commons.toolbar.song', ['type' => 'album', 'id' => $album->id])
                    <div id="songs-grid" class="no-album-column profile">
                        @include('commons.song', ['songs' => $album->songs, 'element' => 'genre', 'number' => true])
                    </div>
                    <div class="show-more-songs-wrapper"> <a id="show-more-songs" class="hide" data-translate-text="SHOW_MORE_SONGS">{{ __('web.SHOW_MORE_SONGS') }}</a> </div>
                    <div id="extra-grid" class="no-album-column profile"></div>
                </div>
            </div>
            <div id="column2">
                <div class="content">
                    {!! Advert::get('sidebar') !!}
                    <div class="sub-header">
                        <h3 data-translate-text="COMMENTS">Comments</h3>
                    </div>
                    <div id="comments">
                        @if(config('settings.album_comments') && $album->allow_comments)
                            @include('comments.index', ['object' => (Object) ['id' => $album->id, 'type' => 'App\Models\Album', 'title' => $album->title]])
                        @else
                            <p class="text-center mt-5">Comments are turned off.</p>
                        @endif
                    </div>
                </div>
                @if(isset($artistTopSongs) && count($artistTopSongs))
                    <div id="topArtistSongs-digest">
                        <div class="sub-header">
                            <h3 data-translate-text="ARTIST_TOP_SONGS">Artist's Top Songs</h3><a href="{{ $artist->permalink_url }}" class="view-more" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a></div>
                        <ul class="snapshot">
                            @include('commons.song', ['songs' => $artistTopSongs, 'element' => 'snapshot'])
                        </ul>
                        <div class="divider"></div>
                    </div>
                @endif
                @if(isset($artists) && count($artists))
                    <div id="similarArtists-digest">
                        <div class="sub-header">
                            <h3 data-translate-text="SIMILAR_ARTISTS">Related Artists</h3>
                            <a href="{{ route('frontend.artist.similar', ['id' => $album->artists->first()->id, 'slug' => str_slug($album->artists->first()->name)]) }}" class="view-more" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a>
                        </div>
                        <ul class="snapshot">
                            @include('commons.artist', ['artists' => $artists, 'element' => 'search'])
                        </ul>
                    </div>
                @endif
            </div>
            <div id="events-digest"></div>
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection