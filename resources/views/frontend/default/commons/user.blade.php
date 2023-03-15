@foreach ($users as $index => $user)
    <script>var user_data_{{ $user->id }} = {!! json_encode(['id' => $user->id, 'username' => $user->username, 'name' => $user->name, 'follower_count' => $user->follower_count, 'artwork_url' => $user->artwork_url, 'permalink_url' => $user->permalink_url]) !!}</script>
    @if($element == "carousel")
        <div class="module module-cell playlist block swiper-slide draggable" data-toggle="contextmenu" data-trigger="right" data-type="user" data-id="{{ $user->id }}">
            <div class="img-container rounded-circle">
                <img class="img" src="{{ $user->artwork_url }}" alt="{{ $user->name }}">
                <a class="overlay-link" href="{{$user->permalink_url}}"></a>
                <div class="actions primary">
                    <a class="btn play play-lg play-scale play-object" data-type="user" data-id="{{ $user->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner">
                <a class="title text-center" href="{{ $user->permalink_url }}" title="{{ $user->name }}">{{ $user->name }}</a>
            </div>
        </div>
    @elseif ($element == "activity")
        @if (count($users) > 1)
            <a href="{{ $user->permalink_url }}" class="feed-item-img show-artist-tooltip small artist-link circle">
                <img src="{{ $user->artwork_url }}" alt="{{ $user->name }}" class="row-feed-image circle">
            </a>
        @else
            <div class="feed-item d-flex">
                <a href="{{ $user->permalink_url }}" class="feed-item-img show-artist-tooltip artist-link circle">
                    <img class="feed-img-medium circle" src="{{ $user->artwork_url }}" width="80" height="80">
                </a>
                <div class="inner align-self-center">
                    <a href="{{ $user->url }}" class="item-title artist-link" data-artist-id="{{ $user->id }}">{{ $user->name }}</a>
                    @if (! auth()->check() || (auth()->check() && auth()->user()->username != $user->username))
                        <a class="btn favorite @if($user->favorite) on btn-success @endif" data-type="artist" data-id="{{ $user->id }}" data-title="{{ $user->name }}" data-url="{{ $user->url }}">
                            <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            <span class="favorite-label" data-translate-text="FOLLOW">@if($user->favorite) Following @else Follow @endif</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @elseif ($element == "search")
        <div class="module module-row tall user" data-index="{{ $index }}">
            <div class="img-container">
                <img class="img" src="{{ $user->artwork_url }}" alt="{{ $user->name }}">
            </div>
            <div class="metadata user">
                <a href="{{ $user->permalink_url }}" class="title user-link">{{ $user->name }}</a>
            </div>
            <div class="row-actions secondary">
                <a class="btn favorite @if($user->favorite) on btn-success @endif" data-type="user" data-id="{{ $user->id }}" data-title="{{ $user->name }}" data-url="{{ $user->permalink_url }}">
                    <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <span class="favorite-label" data-translate-text="FOLLOW">@if($user->favorite) Following @else Follow @endif</span>
                </a>
            </div>
        </div>
    @elseif ($element == "grid")
        <div class="module module-cell playlist small grid-item">
            <div class="img-container">
                <img class="img" src="{{ $user->artwork_url }}" alt="{{ $user->name }}"/>
                <div class="actions primary">
                    <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="user" data-id="{{ $user->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                    <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="user" data-id="{{ $user->id }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    </a>
                    <a class="btn btn-secondary btn-icon-only btn-favorite" data-type="user" data-id="{{ $user->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </a>
                </div>
            </div>
            <div class="module-inner">
                <a href="{{ $user->permalink_url }}" class="headline">{{ $user->name }}</a>
            </div>
        </div>
    @else
        <div class="col-lg-6 col-12 mb-2">
            <div class="module-cell user">
                <a href="{{ $user->permalink_url }}" title="{{ $user->username }}" class="img-container">
                    <img class="img" src="{{ $user->artwork_url }}" alt="{{ $user->name }}">
                </a>
                <div class="module-inner">
                    <a href="/{{ $user->username }}" class="headline">{{ $user->name }}</a>
                    <ul class="metadata">
                        <li><a href="{{ route('frontend.user.favorites', ['username' => $user->username]) }}"><span id="song-count" class="num">{{ $user->favorite_count }}</span> <span class="label" data-translate-text="SONGS">{{ __('web.SONGS') }}</span></a></li>
                        <li><a href="{{ route('frontend.user.playlists', ['username' => $user->username]) }}"><span id="playlist-count" class="num">{{ $user->playlist_count }}</span> <span class="label" data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}</span></a></li>
                        <li><a href="{{ route('frontend.user.followers', ['username' => $user->username]) }}"><span id="follower-count" class="num">{{ $user->follower_count }}</span> <span class="label" data-translate-text="FOLLOWERS">{{ __('web.FOLLOWERS') }}</span></a></li>
                    </ul>
                </div>
                <div class="module-actions">
                    @if (! auth()->check() || (auth()->check() && auth()->user()->username != $user->username))
                        <a class="btn btn-favorite favorite @if($user->favorite) on @endif" data-type="user" data-id="{{ $user->id }}" data-title="{{ $user->name }}" data-url="{{ $user->permalink_url }}" data-text-on="{{ __('web.UNFOLLOW') }}" data-text-off="{{ __('web.FOLLOW') }}">
                            <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            @if($user->favorite)
                                <span class="label desktop" data-translate-text="UNFOLLOW">{{ __('web.UNFOLLOW') }}</span>
                            @else
                                <span class="label desktop" data-translate-text="FOLLOW">{{ __('web.FOLLOW') }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endforeach
