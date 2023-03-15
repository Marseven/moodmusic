@extends('index')
@section('content')
    <script>var song_data_{{ $song->id }} = {!! json_encode($song) !!}</script>
    <div id="page-content" class="bluring-helper">
        @if($song->pending)
            <div class="processing-overlay" data-translate-text="PROCESSING_AUDIO">{{ __('web.PROCESSING_AUDIO') }}</div>
        @endif
        <div class="container">
            <div class="blurimg">
                <img src="{{ $song->artwork_url }}" alt="{!! $song->title !!}">
            </div>
            <div class="page-header main medium song-header @if($song->mp3 && config('settings.waveform')) mb-0 pb-0 @endif">
                <div class="img song" data-id="{{ $song->id }}">
                    <img src="{{ $song->artwork_url }}">
                </div>
                <div class="inner">
                    <h1 title="{!! $song->title !!}">{!! $song->title !!}</h1>
                    @if(!$song->visibility)<span class="private" data-translate-text="PRIVATE">{{ __('web.PRIVATE') }}</span>@endif
                    <div class="byline">
                        @if($song->explicit)
                            <span class="explicit">E</span>
                        @endif
                        <span>{{ __('web.SONG_BYLINE') }} @foreach($song->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach @if($song->album_name) on <a href="{{ $song->album_url }}" class="album-link">{{ $song->album_name }}</a> @endif </span>
                    </div>
                    <div class="actions-container">
                        <div class="actions-primary">
                            @if($song->selling && config('settings.module_store', true))
                                @if($song->purchased)
                                    <a class="btn desktop" href="{{ route('frontend.user.purchased', auth()->user()->username) }}">
                                        <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                        </svg>
                                        <span>{{ __('web.DOWNLOAD') }}</span>
                                    </a>
                                @else
                                    <a class="btn desktop" data-type="song" data-action="buy" data-orderable-type="App\Models\Song" data-orderable-id="{{ $song->id }}">
                                        <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 4H4.80701L6.06367 7.35103L9.06367 15.3508L9.30701 15.9997H10H18H18.693L18.9363 15.3508L21.9363 7.35103L22.443 5.9999H21H7.69299L6.43633 2.64887L6.19299 2H5.5H2V4ZM10.693 13.9997L8.44301 7.9999H19.557L17.307 13.9997H10.693ZM8 20C8 18.8954 8.89543 18 10 18C11.1046 18 12 18.8954 12 20C12 21.1046 11.1046 22 10 22C8.89543 22 8 21.1046 8 20ZM16 20C16 18.8954 16.8954 18 18 18C19.1046 18 20 18.8954 20 20C20 21.1046 19.1046 22 18 22C16.8954 22 16 21.1046 16 20Z"></path></svg>
                                        <span>{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $song->price }}</span>
                                    </a>
                                @endif
                            @endif
                            <div class="btn-group desktop">
                                <a class="btn play play-object" data-type="song" data-id="{{ $song->id }}">
                                    <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg> <span data-translate-text="PLAY_SONG">{{ __('web.PLAY_SONG') }}</span>
                                </a>
                                <a class="btn play-menu" data-type="song" data-id="{{ $song->id }}" data-target=".song-header">
                                    <span class="caret"></span>
                                </a>
                            </div>
                            <div class="btn-group desktop">
                                <a class="btn add-song" data-type="song" data-id="{{ $song->id }}">
                                    <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/>
                                    </svg>
                                    <span data-translate-text="ADD_SONG">{{ __('web.ADD_SONG') }}</span>
                                    <span class="caret"></span>
                                </a>
                            </div>
                            @if(auth()->check() && auth()->user()->subscription)
                                @if(isset($song->access))
                                    @if($song->access && isset(groupPermission($song->access)[\App\Models\Role::groupId()]))
                                        @if(groupPermission($song->access)[\App\Models\Role::groupId()])
                                            <a class="btn desktop" target="_blank" href="{{ route('frontend.song.download', ['id' => $song->id]) }}">
                                                <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                                </svg>
                                                <span>{{ __('web.DOWNLOAD') }}</span>
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            @endif
                            @if(env('YT_DOWNLOAD_MODULE') && isset($song->log) && isset($song->log->youtube) && ! $song->mp3)
                                <a class="btn desktop @if(! auth()->check()) login @endif " @if(auth()->check()) href="{{ route('frontend.song.download.yt', ['id' => $song->id]) }}" target="_blank" @endif>
                                    <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                    </svg>
                                    <span>{{ __('web.DOWNLOAD') }}</span>
                                </a>
                            @endif
                            @if(! $song->selling && $song->mp3 && $song->allow_download && \App\Models\Role::getValue('option_download'))
                                <a class="btn desktop" data-action="download" data-type="song" data-id="{{ $song->id }}">
                                    <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                    </svg>
                                    <span>{{ __('web.DOWNLOAD') }}</span>
                                </a>
                            @endif
                            <!-- mobile button -->
                            @if($song->selling)
                                @if($song->purchased)
                                    <a class="btn mobile" href="{{ route('frontend.user.purchased', auth()->user()->username) }}">
                                        <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                        </svg>
                                    </a>
                                @else
                                    <a class="btn mobile" data-type="song" data-action="buy" data-orderable-type="App\Models\Song" data-orderable-id="{{ $song->id }}">
                                        <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 4H4.80701L6.06367 7.35103L9.06367 15.3508L9.30701 15.9997H10H18H18.693L18.9363 15.3508L21.9363 7.35103L22.443 5.9999H21H7.69299L6.43633 2.64887L6.19299 2H5.5H2V4ZM10.693 13.9997L8.44301 7.9999H19.557L17.307 13.9997H10.693ZM8 20C8 18.8954 8.89543 18 10 18C11.1046 18 12 18.8954 12 20C12 21.1046 11.1046 22 10 22C8.89543 22 8 21.1046 8 20ZM16 20C16 18.8954 16.8954 18 18 18C19.1046 18 20 18.8954 20 20C20 21.1046 19.1046 22 18 22C16.8954 22 16 21.1046 16 20Z"></path></svg>
                                    </a>
                                @endif
                            @else
                                <a class="btn fav favorite mobile @if(auth()->check() && $song->favorite) on @endif" data-type="song" data-id="{{$song->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
                                </a>
                            @endif
                            @if(env('YT_DOWNLOAD_MODULE') && isset($song->log) && isset($song->log->youtube))
                                <a class="btn mobile @if(! auth()->check()) login @endif " @if(auth()->check()) href="{{ route('frontend.song.download.yt', ['id' => $song->id]) }}" target="_blank" @endif>
                                    <svg width="16" height="26" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                                    </svg>
                                </a>
                            @endif
                            <a class="btn play play-object mobile" data-type="song" data-id="{{ $song->id }}">
                                <span data-translate-text="PLAY">{{ __('web.PLAY') }}</span>
                            </a>
                            @if(env('YT_DOWNLOAD_MODULE') && isset($song->log) && isset($song->log->youtube))
                                <a class="btn mobile share" data-type="song" data-id="{{ $song->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                                </a>
                            @endif
                            <a class="btn options mobile" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </a>
                        </div>
                        <div class="tags-container">
                            @if($song->tags)
                                @foreach($song->tags as $item)
                                    <a class="tag" href="{{ $item->permalink_url }}">#{{ $item->tag }}</a>
                                @endforeach
                            @endif

                        </div>
                    </div>
                    @if($song->description || $song->copyright)
                        <div class="description">
                            @if($song->copyright)
                                <p>© {{ $song->copyright }}</p>
                            @endif
                            <p>{{ $song->description }}</p>
                        </div>
                    @endif
                    <ul class="stat-summary">
                        <li>
                            <span id="fans-count" class="num">@if($song->collectors) {{ $song->collectors }} @else - @endif</span>
                            <span class="label" data-translate-text="FANS">{{ __('web.FANS') }}</span>
                        </li>
                        <li>
                            <span id="listen-count" class="num">@if($song->plays) {{ $song->plays }} @else - @endif</span>
                            <span class="label" data-translate-text="PLAYS">{{ __('web.PLAYS') }}</span>
                        </li>
                        @if($song->released_at)
                        <li>
                            <span id="released-at" class="num">{{ \Carbon\Carbon::parse($song->released_at)->format('M j, Y') }}</span>
                            <span class="label" data-translate-text="RELEASED">{{ __('web.RELEASED') }}</span>
                        </li>
                        @endif
                        <li class="tags">
                            @if($song->genres && count($song->genres))
                                @foreach($song->genres as $index => $genre)
                                    <a class="genre-link" href="{{ $genre->permalink_url }}"><span class="tag">{!! $genre->name !!}</span></a>
                                @endforeach
                            @else
                                @if(isset($song->artists) && count($song->artists))
                                    @foreach($song->artists->first()->genres as $index => $genre)
                                        <a class="genre-link" href="{{ $genre->permalink_url }}"><span class="tag">{!! $genre->name !!}</span></a>
                                    @endforeach
                                @endif
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            @if($song->mp3 && config('settings.waveform'))
                <div id="wave-form-container" data-id="{{ $song->id }}" class="track-waveform ready" @if(! $song->waveform) data-uri="{{ $song->getFirstMediaUrl('audio') }}" @endif>
                    <canvas id="waveform"></canvas>
                    <waveform data-id="{{ $song->id }}">
                        <canvas id="waveform-active-{{ $song->id }}"></canvas>
                    </waveform>
                    <canvas id="embed_display_waveform_visualizer" class="song-waveform-visualizer song-waveform-visualizer-{{ $song->id }} hide"></canvas>
                </div>
            @endif
            <div id="column1">
                <div class="content share-content">
                    <div class="sub-header">
                        <h2 data-translate-text="SHARE_SONG">{{ __('web.SHARE_SONG') }}</h2>
                    </div>
                    <div class="share-box">
                        <div class="share-box-content">
                            <div class="control">
                                <div class="floated-links">
                                    <label class="control-label" data-translate-text="EMBED">{{ __('web.EMBED') }}</label>
                                    <a class="copy-link" id="copy-song-embed" data-translate-text="SHARE_COPY">Copy</a>
                                </div>
                                <textarea class="select-all widget-code"><iframe width="100%" height="180" frameborder="0" src="{{ route('frontend.share.embed', ['theme' => 'dark', 'type' => 'song', 'id' => $song->id]) }}"></iframe></textarea>
                            </div>
                        </div>
                        <span class="third-party-btn-head" data-translate-text="SHARE_WITH_FRIENDS">{{ __('web.SHARE_WITH_FRIENDS') }}</span>
                        <a class="btn share-btn third-party twitter" target="_blank" href="https://twitter.com/intent/tweet?url={{ $song->permalink_url }}">
                            <svg class="icon" width="24" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510" xml:space="preserve"><path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z M400.35,186.15c-2.55,117.3-76.5,198.9-188.7,204C165.75,392.7,132.6,377.4,102,359.55c33.15,5.101,76.5-7.649,99.45-28.05c-33.15-2.55-53.55-20.4-63.75-48.45c10.2,2.55,20.4,0,28.05,0c-30.6-10.2-51-28.05-53.55-68.85c7.65,5.1,17.85,7.65,28.05,7.65c-22.95-12.75-38.25-61.2-20.4-91.8c33.15,35.7,73.95,66.3,140.25,71.4c-17.85-71.4,79.051-109.65,117.301-61.2c17.85-2.55,30.6-10.2,43.35-15.3c-5.1,17.85-15.3,28.05-28.05,38.25c12.75-2.55,25.5-5.1,35.7-10.2C425.85,165.75,413.1,175.95,400.35,186.15z"></path></svg>
                            <span class="text" data-translate-text="SHARE_ON_TWITTER">{{ __('web.SHARE_ON_TWITTER') }}</span></a>
                        <a class="btn share-btn third-party facebook" target="_blank" href="https://www.facebook.com/share.php?u={{ $song->permalink_url }}&ref=songShare">
                            <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"></path></svg>
                            <span class="text" data-translate-text="SHARE_ON_FACEBOOK">{{ __('web.SHARE_ON_FACEBOOK') }}</span>
                        </a>
                        <a class="btn share-btn more-menu share" data-type="song" data-id="{{ $song->id }}">
                            <span class="btn-text" data-translate-text="MORE">{{ __('web.MORE') }}</span>
                        </a>
                    </div>
                </div>
                <div class="lyric-container d-none" data-action="get-lyric" data-type="song" data-id="{{ $song->id }}">
                    <div class="sub-header">
                        <h2 data-translate-text="LYRIC">{{ __('web.LYRIC') }}</h2>
                    </div>
                    <div class="lyric-content"></div>
                </div>
                <div class="comments-container">
                    @if(config('settings.song_comments') && $song->allow_comments)
                        <div class="sub-header">
                            <h2 data-translate-text="COMMENTS">{{ __('web.COMMENTS') }}</h2>
                        </div>
                        <div id="comments">
                            @include('comments.index', ['object' => (Object) ['id' => $song->id, 'type' => 'App\Models\Song', 'title' => $song->title]])
                        </div>
                    @else
                        <p class="text-center">Comments are turned off.</p>
                    @endif
                </div>
            </div>
            <div id="column2">
                {!! Advert::get('sidebar') !!}
                <div id="artist-top-songs" class="content">
                    <div class="sub-header">
                        <h3 data-translate-text="ARTIST_TOP_SONGS">{{ __('web.ARTIST_TOP_SONGS') }}</h3>
                    </div>
                    <ul class="snapshot">
                        @if(isset($related))
                            @include('commons.song', ['songs' => $related->songs, 'element' => 'snapshot'])
                        @endif
                    </ul>
                    <div class="divider"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
