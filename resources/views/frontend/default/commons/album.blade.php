@foreach ($albums as $index => $album)
    <script>var album_data_{{ $album->id }} = {!! json_encode($album) !!}</script>
    @if($element == "carousel")
        @if(config('settings.channel_grid_style'))
            <div class="module module-row station tall" data-toggle="contextmenu" data-trigger="right" data-type="album" data-id="{{ $album->id }}">
                <div class="img-container">
                    <img class="img" src="{{$album->artwork_url}}" alt="{!! $album->title !!}">
                    <a class="overlay-link" href="{{$album->permalink_url}}"></a>
                </div>
                <div class="metadata station">
                    <div class="title">
                        <a href="{{$album->permalink_url}}">{!! $album->title !!}</a>
                    </div>
                    <div class="description">
                        <span class="byline"><span data-translate-text="BY">{{ __('web.BY') }}</span> @foreach($album->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link artist" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
                    </div>
                </div>
            </div>
        @else
        <div class="module module-cell album block swiper-slide draggable" data-toggle="contextmenu" data-trigger="right" data-type="album" data-id="{{ $album->id }}">
            <div class="img-container">
                <img class="img" src="{{ $album->artwork_url }}">
                <a class="overlay-link" href="{{$album->permalink_url}}"></a>
                <div class="actions primary">
                    <a class="btn play play-lg play-scale play-object" data-type="album" data-id="{{ $album->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner">
                <a href="{{ $album->permalink_url }}" class="album-link title">{!! $album->title !!}</a>
                <span class="byline">by @foreach($album->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link artist" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
            </div>
        </div>
        @endif
    @elseif($element == "search")
        <div class="module module-row tall album" data-index="{{ $index }}">
            <div class="img-container">
                <img class="img" src="{{ $album->artwork_url }}" alt="{!! $album->title !!}">
            </div>
            <div class="metadata album">
                <a href="{{ $album->permalink_url }}" class="title album-link">{!! $album->title !!}</a>
                <div class="meta-inner">
                    <span data-translate-text="BY">by</span> @foreach($album->artists as $artist)<a class="meta-text artist-link" href="{{$artist->permalink_url}}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                </div>
            </div>
        </div>
    @elseif($element == "grid")
        <div class="module module-cell grid-item">
            <div class="img-container">
                <img class="img" src="{{ $album->artwork_url }}" alt="{!! $album->title !!}">
                <a class="overlay-link" href="{{ $album->permalink_url }}"></a>
                <div class="actions primary">
                    <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="album" data-id="{{ $album->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                    <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="album" data-id="{{ $album->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner album">
                <a href="{{ $album->permalink_url }}" class="headline title">{!! $album->title !!}</a>
                <span class="byline">by @foreach($album->artists as $artist)<a class="secondary-text" href="{{$artist->permalink_url}}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
            </div>
        </div>
    @elseif($element == "activity")
        @if (count($albums) > 1)
            <a href="{{ $album->permalink_url }}" class="feed-item-img small " data-toggle="contextmenu" data-trigger="right" data-type="album" data-id="{{ $album->id }}">
                <img src="{{ $album->artwork_url }}" class="row-feed-image">
            </a>
        @else
            <div class="feed-item">
                <a href="{{ $album->permalink_url }}" class="feed-item-img " data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{{ $album->id }}">
                    <img class="feed-img-medium" src="{{ $album->artwork_url }}" width="80" height="80">
                </a>
                <div class="inner">
                    <a href="{{ $album->permalink_url }}" class="item-title album-link">{!! $album->title !!}</a>
                    @foreach($album->artists as $artist)<a class="item-subtitle artist-link" href="{{ $artist->permalink_url }}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                    <a class="btn play play-object" data-type="album" data-id="{{ $album->id }}">
                        <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <span data-translate-text="PLAY_ALBUM">Play Album</span>
                    </a>
                </div>
            </div>
        @endif
    @endif
@endforeach