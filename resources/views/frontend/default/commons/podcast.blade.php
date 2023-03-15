@foreach ($podcasts as $index => $podcast)
    <script>var podcast_data_{{ $podcast->id }} = {!! json_encode($podcast) !!}</script>
    @if($element == "carousel")
        @if(config('settings.channel_grid_style'))
            <div class="module module-row station tall" data-toggle="contextmenu" data-trigger="right" data-type="podcast" data-id="{{ $podcast->id }}">
                <div class="img-container">
                    <img class="img" src="{{$podcast->artwork_url}}" alt="{!! $podcast->title !!}">
                    <a class="overlay-link" href="{{$podcast->permalink_url}}"></a>
                    <div class="row-actions primary">
                        <a class="btn play play-lg play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                            <div></div>
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata station">
                    <div class="title">
                        <a href="{{ $podcast->permalink_url }}" class="podcast-link title">{!! $podcast->title !!}</a>
                    </div>
                    <div class="description">
                        <span class="byline">by <a href="{{$podcast->artist->permalink_url}}" class="artist-link artist" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a></span>
                    </div>
                </div>
            </div>
        @else
            <div class="module module-cell playlist block swiper-slide draggable" data-toggle="contextmenu" data-trigger="right" data-type="podcast" data-id="{{ $podcast->id }}">
                <div class="img-container">
                    <img class="img" src="{{ $podcast->artwork_url }}">
                    <a class="overlay-link" href="{{$podcast->permalink_url}}"></a>
                    <div class="actions primary">
                        <a class="btn play play-lg play-scale play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="module-inner">
                    <a href="{{ $podcast->permalink_url }}" class="podcast-link title">{!! $podcast->title !!}</a>
                    @if(isset($podcast->artist))
                        <span class="byline">by <a href="{{$podcast->artist->permalink_url}}" class="artist-link artist" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a></span>
                    @endif
                </div>
            </div>
        @endif
    @elseif($element == "search")
        <div class="module module-row tall album" data-index="{{ $index }}">
            <div class="img-container">
                <img class="img" src="{{ $podcast->artwork_url }}" alt="{!! $podcast->title !!}">
            </div>
            <div class="metadata album">
                <a href="{{ $podcast->permalink_url }}" class="title podcast-link">{!! $podcast->title !!}</a>
                <div class="meta-inner">
                    @if(isset($podcast->artist))
                        <span class="byline">by <a href="{{$podcast->artist->permalink_url}}" class="artist-link artist" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a></span>
                    @endif
                </div>
            </div>
        </div>
    @elseif($element == "grid")
        <div class="module module-cell grid-item">
            <div class="img-container">
                <img class="img" src="{{ $podcast->artwork_url }}" alt="{!! $podcast->title !!}">
                <a class="overlay-link" href="{{ $podcast->permalink_url }}"></a>
                <div class="actions primary">
                    <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="podcast" data-id="{{ $podcast->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                    <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner podcast">
                <a href="{{ $podcast->permalink_url }}" class="headline title">{{ $podcast->title }}</a>
                @if(isset($podcast->artist))
                    <span class="byline">by <a href="{{$podcast->artist->permalink_url}}" class="artist-link artist" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a></span>
                @endif
            </div>
        </div>
    @elseif($element == "genre")
        <div class="module module-row station tall" data-index="{{ $index }}">
            <div class="img-container">
                <img class="img" src="{{$podcast->artwork_url}}" alt="{{$podcast->title}}">
                <a class="overlay-link" href="{{ $podcast->permalink_url }}"></a>
                <div class="row-actions primary">
                    <a class="btn play-lg play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                        <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </a>
                </div>
            </div>
            <div class="metadata station">
                <div class="title">
                    <a href="{{ $podcast->permalink_url }}">{{ $podcast->title }}</a>
                </div>
                <div class="description">
                    @if(isset($podcast->artist))
                        <span class="byline">by <a href="{{$podcast->artist->permalink_url}}" class="artist-link artist" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a></span>
                    @endif
                </div>
            </div>
        </div>
    @elseif($element == "activity")
        @if (count($podcasts) > 1)
            <a href="{{ $podcast->permalink_url }}" class="feed-item-img small " data-toggle="contextmenu" data-trigger="right" data-type="podcast" data-id="{{ $podcast->id }}">
                <img src="{{ $podcast->artwork_url }}" class="row-feed-image">
            </a>
        @else
            <div class="feed-item">
                <a href="{{ $podcast->permalink_url }}" class="feed-item-img " data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $podcast->id }}">
                    <img class="feed-img-medium" src="{{ $podcast->artwork_url }}" width="80" height="80">
                </a>
                <div class="inner">
                    <a href="{{ $podcast->permalink_url }}" class="item-title podcast-link">{{ $podcast->title }}</a>
                    @if(isset($podcast->artist))
                        <a href="{{$podcast->artist->permalink_url}}" class="item-subtitle artist-link" title="{{ $podcast->artist->name }}">{{$podcast->artist->name}}</a>
                    @endif
                    <a class="btn play play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                        <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <span data-translate-text="PLAY_ALBUM">Play Album</span>
                    </a>
                </div>
            </div>
        @endif
    @endif
@endforeach