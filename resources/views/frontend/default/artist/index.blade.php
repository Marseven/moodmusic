@extends('index')
@section('pagination')
    @include('commons.song', ['songs' => $artist->songs, 'element' => 'genre'])
@stop
@section('content')
    {!! Advert::get('header') !!}
    @include('artist.nav', ['artist' => $artist])
    <div id="page-content" class="artist bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $artist->artwork_url }}" alt="{!! $artist->name !!}">
            </div>
            <div class="page-header artist main">
                <a class="img ">
                    <img src="{{ $artist->artwork_url }}" alt="{{ $artist->name}}">
                </a>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}@if($artist->verified)<span class="verified-badge basic-tooltip" tooltip="We confirmed this is the authentic artist profile for this public figure." ><svg height="16pt" viewBox="0 0 512 512" width="16pt" xmlns="http://www.w3.org/2000/svg"><path d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm0 0" fill="#2196f3"/><path d="m385.75 201.75-138.667969 138.664062c-4.160156 4.160157-9.621093 6.253907-15.082031 6.253907s-10.921875-2.09375-15.082031-6.253907l-69.332031-69.332031c-8.34375-8.339843-8.34375-21.824219 0-30.164062 8.339843-8.34375 21.820312-8.34375 30.164062 0l54.25 54.25 123.585938-123.582031c8.339843-8.34375 21.820312-8.34375 30.164062 0 8.339844 8.339843 8.339844 21.820312 0 30.164062zm0 0" fill="#fafafa"/></svg></span>@endif</h1>
                    <div class="byline">
                        <span class="label">Artist</span>
                        @if($artist->facebook)
                            <a class="artist-thirdparty-icon" href="{{ $artist->website }}" target="_blank">
                                <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 460.298 460.297" style="enable-background:new 0 0 460.298 460.297;"
                                     xml:space="preserve">
                                        <path d="M230.149,120.939L65.986,256.274c0,0.191-0.048,0.472-0.144,0.855c-0.094,0.38-0.144,0.656-0.144,0.852v137.041
                                            c0,4.948,1.809,9.236,5.426,12.847c3.616,3.613,7.898,5.431,12.847,5.431h109.63V303.664h73.097v109.64h109.629
                                            c4.948,0,9.236-1.814,12.847-5.435c3.617-3.607,5.432-7.898,5.432-12.847V257.981c0-0.76-0.104-1.334-0.288-1.707L230.149,120.939
                                            z"/>
                                                                    <path d="M457.122,225.438L394.6,173.476V56.989c0-2.663-0.856-4.853-2.574-6.567c-1.704-1.712-3.894-2.568-6.563-2.568h-54.816
                                            c-2.666,0-4.855,0.856-6.57,2.568c-1.711,1.714-2.566,3.905-2.566,6.567v55.673l-69.662-58.245
                                            c-6.084-4.949-13.318-7.423-21.694-7.423c-8.375,0-15.608,2.474-21.698,7.423L3.172,225.438c-1.903,1.52-2.946,3.566-3.14,6.136
                                            c-0.193,2.568,0.472,4.811,1.997,6.713l17.701,21.128c1.525,1.712,3.521,2.759,5.996,3.142c2.285,0.192,4.57-0.476,6.855-1.998
                                            L230.149,95.817l197.57,164.741c1.526,1.328,3.521,1.991,5.996,1.991h0.858c2.471-0.376,4.463-1.43,5.996-3.138l17.703-21.125
                                            c1.522-1.906,2.189-4.145,1.991-6.716C460.068,229.007,459.021,226.961,457.122,225.438z"/>
                                </svg>
                            </a>
                        @endif
                        @if($artist->facebook)
                            <a class="artist-thirdparty-icon" href="{{ $artist->facebook }}" target="_blank">
                                <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"/></svg>
                            </a>
                        @endif
                        @if($artist->twitter)
                            <a class="artist-thirdparty-icon" href="{{ $artist->twitter }}" target="_blank">
                                <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510" xml:space="preserve"><path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z M400.35,186.15c-2.55,117.3-76.5,198.9-188.7,204C165.75,392.7,132.6,377.4,102,359.55c33.15,5.101,76.5-7.649,99.45-28.05c-33.15-2.55-53.55-20.4-63.75-48.45c10.2,2.55,20.4,0,28.05,0c-30.6-10.2-51-28.05-53.55-68.85c7.65,5.1,17.85,7.65,28.05,7.65c-22.95-12.75-38.25-61.2-20.4-91.8c33.15,35.7,73.95,66.3,140.25,71.4c-17.85-71.4,79.051-109.65,117.301-61.2c17.85-2.55,30.6-10.2,43.35-15.3c-5.1,17.85-15.3,28.05-28.05,38.25c12.75-2.55,25.5-5.1,35.7-10.2C425.85,165.75,413.1,175.95,400.35,186.15z"/></svg>
                            </a>
                        @endif
                    </div>
                    <div class="actions-primary">
                        @include('artist.actions')
                    </div>
                    @if($artist->description)
                        <div class="description">
                            <p>{{ $artist->description }}</p>
                        </div>
                    @endif
                    <ul class="stat-summary">
                        <li>
                            <span id="song-count" class="num">{{ $artist->song_count }}</span>
                            <span class="label" data-translate-text="SONGS">Songs</span>
                        </li>
                        <li>
                            <a href="{{ route('frontend.artist.albums', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}">
                                <span id="album-count" class="num">{{ $artist->album_count }}</span>
                                <span class="label" data-translate-text="ALBUMS">Albums</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.artist.followers', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}">
                                <span id="follower-count" class="num">@if(intval($artist->loves)){{$artist->loves}}@else - @endif</span>
                                <span class="label" data-translate-text="Fans">Fans</span>
                            </a>
                        </li>
                        <li class="tags">
                            @foreach($artist->genres as $index => $genre)
                                <a class="genre-link" href="{{ $genre->permalink_url }}"><span class="tag">{!! $genre->name !!}</span></a>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
            <div id="column1" class="content ">
                @if(count($artist->albums))
                    <div id="artist-albums-container">
                        <div id="artist-albums-header" class="sub-header">
                            <h2 class="short">
                                <span data-translate-text="FULL_ALBUMS">{{ __('web.FULL_ALBUMS') }}</span> / <span data-translate-text="SINGLES_EPS">{{ __('web.SINGLES_EPS') }}</span></h2>
                            <a class="view-more" href="{{ route('frontend.artist.albums', ['id' => $artist->id, 'slug' => str_slug($artist->name)]) }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                        <div id="artist-albums" class="no-artist-column albums grid-albums-vertical row">
                            @include('commons.album', ['albums' => $artist->albums, 'element' => 'grid'])
                        </div>
                    </div>
                @endif
                @if(count($artist->podcasts))
                    <div id="artist-albums-container">
                        <div id="artist-albums-header" class="sub-header">
                            <h2 class="short">
                                <span data-translate-text="TOP_PODCASTS">{{ __('web.TOP_PODCASTS') }}</span></h2>
                            <a class="view-more" href="{{ route('frontend.artist.podcasts', ['id' => $artist->id, 'slug' => str_slug($artist->name)]) }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                        <div id="artist-albums" class="no-artist-column albums grid-albums-vertical row">
                            @include('commons.podcast', ['podcasts' => $artist->podcasts, 'element' => 'grid'])
                        </div>
                    </div>
                @endif
                @if(count($artist->songs))
                    @include('commons.toolbar.song', ['type' => 'artist', 'id' => $artist->id])
                    <div id="songs-grid" class="no-artist-column profile infinity-load-more" data-total-page="{{ ceil($artist->song_count/20) }}">
                        @yield('pagination')
                    </div>
                @endif
            </div>
            <div id="column2">
                <div class="content">
                    {!! Advert::get('sidebar') !!}
                    <div class="content">
                        <div class="col2-tabs">
                            <a id="comments-tab" class="column2-tab show-comments active"><span data-translate-text="COMMENTS">{{ __('web.COMMENTS') }}</span></a>
                            <a id="activity-tab" class="column2-tab show-activity"><span data-translate-text="ACTIVITY">{{ __('web.ACTIVITY') }}</span></a>
                        </div>
                        <div id="comments">
                            @if(config('settings.artist_comments') && $artist->allow_comments)
                                @include('comments.index', ['object' => (Object) ['id' => $artist->id, 'type' => 'App\Models\Artist', 'title' => $artist->name]])
                            @else
                                <p class="text-center mt-5">Comments are turned off.</p>
                            @endif
                        </div>
                        <div id="activity" class="hide">
                            <div id="small-activity-grid">
                                @include('commons.activity', ['activities' => $artist->activities, 'type' => 'small'])
                            </div>
                        </div>
                    </div>
                    @if(isset($artist->similar) && count($artist->similar))
                        <div id="similarArtists-digest">
                            <div class="sub-header">
                                <h3 data-translate-text="SIMILAR_ARTISTS">Related Artists</h3>
                                <a href="{{ route('frontend.artist.similar', ['id' => $artist->id, str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="view-more" data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</a>
                            </div>
                            <ul class="snapshot">
                                @include('commons.artist', ['artists' => $artist->similar, 'element' => 'search'])
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection
