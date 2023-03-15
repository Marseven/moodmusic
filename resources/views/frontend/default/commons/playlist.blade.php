@foreach ($playlists as $index => $playlist)
    <script>var playlist_data_{{ $playlist->id }} = {!! json_encode($playlist) !!}</script>
    @if($element == "carousel")
        @if(config('settings.channel_grid_style'))
            <div class="module module-row station tall" data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $playlist->id }}">
                <div class="img-container">
                    <img class="img" src="{{$playlist->artwork_url}}" alt="{!! $playlist->title !!}">
                    <a class="overlay-link" href="{{$playlist->permalink_url}}"></a>
                    <div class="row-actions primary">
                        <a class="btn play play-lg play-object" data-type="playlist" data-id="{{$playlist->id}}">
                            <div></div>
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata station">
                    <div class="title">
                        <a href="{{$playlist->permalink_url}}">{!! $playlist->title !!}</a>
                    </div>
                    <div class="description">
                        @if(isset($playlist->user))
                            <span class="byline">by <a href="{{ route('frontend.user', ['username' => $playlist->user->username]) }}" class="playlist-link" title="{{ $playlist->user->name }}">{{ $playlist->user->name }}</a></span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="module module-cell playlist block swiper-slide draggable" data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $playlist->id }}">
                <div class="img-container" data-type="playlist" data-id="{{ $playlist->id }}">
                    <img class="img" src="{{ $playlist->artwork_url }}">
                    <a class="overlay-link" href="{{$playlist->permalink_url}}"></a>
                    <div class="actions primary">
                        <a class="btn play play-lg play-scale play-object" data-type="playlist" data-id="{{ $playlist->id }}">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="module-inner">
                    <a class="title" href="{{ $playlist->permalink_url }}" title="{!! $playlist->title !!}">{!! $playlist->title !!}</a>
                    @if(isset($playlist->user))
                        <span class="byline">by <a href="{{ route('frontend.user', ['username' => $playlist->user->username]) }}" class="playlist-link" title="{{ $playlist->user->name }}">{{ $playlist->user->name }}</a></span>
                    @endif
                </div>
            </div>
        @endif
    @elseif($element == "search")
        <div class="module module-row tall playlist grid-item" data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $playlist->id }}" data-index="{{ $index }}">
            <div class="img-container">
                <img class="img" src="{{ $playlist->artwork_url }}" alt="{!! $playlist->title !!}"></div>
            <div class="metadata playlist">
                <a href="{{ $playlist->permalink_url }}" class="title playlist-link" data-playlist-id="8991588">{!! $playlist->title !!}</a>
                @if(isset($playlist->user))
                    <div class="meta-inner">
                        <span data-translate-text="BY">by <a href="{{ route('frontend.user', ['username' => $playlist->user->name]) }}" class="meta-text">{{ $playlist->user->name }}</a></span>
                    </div>
                @endif
            </div>
            <div class="row-actions secondary">
                @if(isset($playlist->user) && (! auth()->check() || auth()->check() && auth()->user()->id != $playlist->user->id))
                    <a class="btn btn-favorite favorite @if($playlist->favorite) on @endif" data-type="playlist" data-id="{{ $playlist->id }}" data-title="{!! $playlist->title !!}" data-url="{{ $playlist->permalink_url }}" data-text-on="{{ __('web.PLAYLIST_UNSUBSCRIBE') }}" data-text-off="{{ __('web.PLAYLIST_SUBSCRIBE') }}">
                        <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        @if($playlist->favorite)
                            <span class="label desktop" data-translate-text="PLAYLIST_UNSUBSCRIBE">{{ __('web.PLAYLIST_UNSUBSCRIBE') }}</span>
                        @else
                            <span class="label desktop" data-translate-text="PLAYLIST_SUBSCRIBE"> {{ __('web.PLAYLIST_SUBSCRIBE') }} </span>
                        @endif
                    </a>
                @endif
            </div>
        </div>
    @elseif($element == "activity")
        @if (count($playlists) > 1)
            <a href="{{ $playlist->permalink_url }}" class="feed-item-img show-playlist-tooltip small playlist-link " data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $playlist->id }}">
                <img src="{{ $playlist->artwork_url }}" class="row-feed-image">
            </a>
        @else
            <div class="feed-item">
                <a href="{{ $playlist->permalink_url }}" class="feed-item-img show-playlist-tooltip playlist-link " data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $playlist->id }}">
                    <img class="feed-img-medium" src="{{ $playlist->artwork_url }}" width="80" height="80">
                </a>
                <div class="inner">
                    <a href="{{ $playlist->permalink_url }}" class="item-title playlist-link">{!! $playlist->title !!}</a>
                    @if(isset($playlist->user))
                        <a href="{{ route('frontend.user', ['username' => $playlist->user->username]) }}" class="item-subtitle artist-link">{{ $playlist->user->name }}</a>
                    @endif
                    <a class="btn play play-object" data-type="playlist" data-id="{{ $playlist->id }}">
                        <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <span data-translate-text="PLAY_PLAYLIST">{{ __('web.PLAY_PLAYLIST') }}</span>
                    </a>
                </div>
            </div>
        @endif
    @else
        <div class="module module-cell playlist small grid-item">
            <div class="img-container">
                <img class="img" src="{{ $playlist->artwork_url }}" alt="{!! $playlist->title !!}"/>
                <div class="actions primary">
                    <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="playlist" data-id="{{ $playlist->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                    <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="playlist" data-id="{{ $playlist->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                    <a class="btn btn-favorite favorite @if($playlist->favorite) on @endif" data-type="playlist" data-id="{{ $playlist->id }}" data-title="{!! $playlist->title !!}" data-url="{{ $playlist->permalink_url }}">
                        <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner playlist">
                <a href="{{ $playlist->permalink_url }}" class="headline title">{!! $playlist->title !!}</a>
                @if(isset($playlist->user))
                    <span class="byline">by <a href="{{ route('frontend.user', ['username' => $playlist->user->username]) }}" class="secondary-text">{{ $playlist->user->name }}</a></span>
                @endif
            </div>
        </div>
    @endif
@endforeach