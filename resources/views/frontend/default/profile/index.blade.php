@extends('index')
@section('content')
    @include('profile.nav')
    <div id="page-content" class="bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $profile->artwork_url }}">
            </div>
            <div class="page-header user main ">
                <div class="img">
                    <img src="{{ $profile->artwork_url }}">
                    @if ($profile->activity_privacy || (auth()->check() && auth()->user()->username == $profile->username))
                        <a class="btn edit-profile" href="{{ route('frontend.settings') }}">
                            <span data-translate-text="EDIT_PROFILE">Edit Profile</span>
                        </a>
                    @endif
                </div>
                <div class="inner">
                    <h1 title="{{ $profile->name }}">{!! $profile->name !!}@if(isset($profile->group) && isset($profile->group->role_id) && isset($profile->group->role->name) && isset($profile->group->role->permissions))<span class="group-badge basic-tooltip" tooltip="{{ $profile->group->role->name }}">{!! $profile->group->role->permissions['group_badge'] !!}</span>@endif</h1>
                    <div class="actions-primary">
                        @if (! auth()->check() || (auth()->check() && auth()->user()->username != $profile->username))
                            <a class="btn btn-favorite favorite @if($profile->favorite) on @endif" data-type="user" data-id="{{ $profile->id }}" data-title="{{ $profile->name }}" data-url="{{ $profile->permalink_url }}" data-text-on="{{ __('web.UNFOLLOW') }}" data-text-off="{{ __('web.FOLLOW') }}">
                                <svg class="off" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="on" height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                @if($profile->favorite)
                                    <span class="label desktop" data-translate-text="UNFOLLOW">{{ __('web.UNFOLLOW') }}</span>
                                @else
                                    <span class="label desktop" data-translate-text="FOLLOW">{{ __('web.FOLLOW') }}</span>
                                @endif
                            </a>
                        @endif
                        @if ((auth()->check() && auth()->user()->username == $profile->username))
                            <a class="btn recent-options mobile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"/><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"/></svg>
                            </a>
                        @endif
                        <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg>
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                        <a class="btn share" data-type="user" data-id="{{ $profile->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            <span class="desktop" data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                        </a>
                    </div>
                    @if($profile->bio)
                        <div class="description">
                            <p class="text-lg-left text-center">{{ $profile->bio }}</p>
                        </div>
                    @endif
                    <ul class="stat-summary">
                        <li><a href="{{ route('frontend.user.favorites', ['username' => $profile->username]) }}"><span id="song-count" class="num">{{ $profile->favorite_count }}</span><span class="label" data-translate-text="SONGS">{{ __('web.SONGS') }}</span></a></li>
                        <li><a href="{{ route('frontend.user.playlists', ['username' => $profile->username]) }}"><span id="playlist-count" class="num">{{ $profile->playlist_count }}</span><span class="label" data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}</span></a></li>
                        <li><a href="{{ route('frontend.user.followers', ['username' => $profile->username]) }}"><span id="follower-count" class="num">{{ $profile->follower_count }}</span><span class="label" data-translate-text="FOLLOWERS">{{ __('web.FOLLOWERS') }}</span></a></li>
                    </ul>
                </div>
            </div>
            <div id="column1" class="">
                @if (! $profile->activity_privacy || (auth()->check() && auth()->user()->username == $profile->username))
                    <h2 id="now-playing-header" data-translate-text="NOW_PLAYING_SONG" class="hide small">{{ __('web.NOW_PLAYING_SONG') }}</h2>
                    <div id="now-playing-profile-card" class="song main medium small-img hide">
                        <div class="img">
                            <img src="">
                            <div class="card-actions primary">
                                <div class="button-process-container">
                                    <div class="buttonProgressBorder button-progress-active-border">
                                        <div class="button-progress-circle"></div>
                                    </div>
                                </div>
                                <a class="btn play-lg play-object" data-type="song">
                                    <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                    <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                    <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                                </a>
                            </div>
                        </div>
                        <div class="inner">
                            <div class="actions-primary">
                                <a class="btn add-song">
                                    <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                    <span data-translate-text="ADD_SONG">Add Song</span>
                                    <span class="caret"></span></a>
                                <a class="btn share">
                                    <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"></path><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"></path></svg>
                                    <span data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                                </a>
                            </div>
                            <h1></h1>
                            <div class="byline">Song by <span class="artist-link"></span> <span class="has-album" data-translate-text="ON">{{ __('web.ON') }}</span> <span class="album-link"></span></div>
                            <ul class="stat-summary">
                                <li><span id="fans-count" class="num">-</span><span class="label" data-translate-text="FANS">Fans</span></li>
                            </ul>
                        </div>
                    </div>
                    @if(count($profile->recent))
                        <div class="content">
                            <div class="current-user-listen sub-header">
                                <p id="current-user-song" class="hide"></p>
                            </div>
                            <div class="sub-header user-recent-listens">
                                <a class="btn btn-secondary btn-icon-only btn-rounded play-now" data-target="#songs-grid">
                                    <svg height="25" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 5v14l11-7z"/>
                                        <path d="M0 0h24v24H0z" fill="none"/>
                                    </svg>
                                </a>
                                <h2 data-translate-text="USER_RECENT_LISTENS">{{ __('web.USER_RECENT_LISTENS') }}</h2>
                                @if ((auth()->check() && auth()->user()->username == $profile->username))
                                    <a class="recent-options btn btn-small">
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"/><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"/></svg>
                                        </div>
                                        <span class="caret"></span>
                                    </a>
                                @endif
                            </div>
                            <div id="songs-grid">
                                @include('commons.song', ['songs' => $profile->recent, 'element' => 'genre'])
                            </div>
                        </div>
                    @else
                        <div class="empty-page following">
                            <div class="empty-inner">
                                @if (auth()->check() && auth()->user()->username == $profile->username)
                                    <h2 data-translate-text="EMPTY_LISTENS_OWNER">{{ __('web.EMPTY_LISTENS_OWNER') }}</h2>
                                    <p data-translate-text="EMPTY_LISTENS_DESC_OWNER">{{ __('web.EMPTY_LISTENS_DESC_OWNER') }}</p>
                                @else
                                    <h2 data-translate-text="EMPTY_LISTENS">{{ __('web.EMPTY_LISTENS', ['name' => $profile->name]) }}</h2>
                                    <p data-translate-text="EMPTY_LISTENS_DESC">{{ __('web.EMPTY_LISTENS_DESC') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                @if(count($profile->playlists))
                    <div id="top-playlists" class="content">
                        <div class="sub-header">
                            <h2 data-translate-text="PLAYLISTS">Playlists</h2>
                            <a class="view-more" href="/{{ $profile->username }}/playlists">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>

                            </a>
                        </div>
                        <div id="top-playlists-grid" class="playlists-grid row">
                            @include('commons.playlist', ['playlists' => $profile->playlists, 'element' => 'profile'])
                        </div>
                    </div>
                @endif
            </div>
            <div id="column2">
                {!! Advert::get('sidebar') !!}
                @if (! $profile->activity_privacy || (auth()->check() && auth()->user()->username == $profile->username))
                    <div class="content">
                        {!! Advert::get('sidebar') !!}
                        <div class="col2-tabs">
                            <a id="activity-tab" class="column2-tab show-activity active"><span data-translate-text="ACTIVITY">{{ __('web.ACTIVITY') }}</span></a>
                            <a id="comments-tab" class="column2-tab show-comments"><span data-translate-text="COMMENTS">{{ __('web.COMMENTS') }}</span></a>
                        </div>
                        <div id="activity">
                            <div id="small-activity-grid">
                                @include('commons.activity', ['activities' => $profile->activities, 'type' => 'small'])
                            </div>
                        </div>
                        <div id="comments" class="hide">
                            @if(config('settings.user_comments') && $profile->allow_comments)
                                @include('comments.index', ['comments' => $profile->comments, 'object' => (Object) ['id' => $profile->id, 'type' => 'App\Models\User', 'title' => $profile->name], 'mod' => (auth()->check() && auth()->user()->username == $profile->username)])
                            @else
                                <p class="text-center mt-5">Comments are turned off.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="content">
                        <div id="comment_widget">
                            @if(config('settings.user_comments') && $profile->allow_comments)
                                <h3 data-translate-text="COMMENTS">Comments</h3>
                                <div id="comments">
                                    @include('comments.index', ['comments' => $profile->comments, 'object' => (Object) ['id' => $profile->id, 'type' => 'App\Models\User', 'title' => $profile->name], 'mod' => (auth()->check() && auth()->user()->username == $profile->username)])
                                </div>
                            @else
                                <p class="text-center mt-5">Comments are turned off.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection