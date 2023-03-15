@extends('index')
@section('content')
    @include('podcast.channel.nav', ['channel' => $channel])
    <script>var channel_data_{{ $channel->id }} = {!! json_encode($channel->makeHidden('songs')->makeHidden('related')) !!}</script>
    <div id="page-content" class="bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $channel->artwork_url }}" alt="{{ $channel->title}}">
            </div>
            <div class="page-header channel main medium">
                <div class="img">
                    <img src="{{ $channel->artwork_url }}" alt="{{ $channel->title}}">
                    @if (auth()->check() && auth()->user()->id == $channel->user->id)
                        <a class="btn dropdown edit-channel-context-trigger" data-type="channel" data-id="{{ $channel->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="26" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"/><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"/></svg>
                            <span class="caret"></span>
                        </a>
                    @endif
                </div>
                <div class="inner">
                    <h1 title="{{ $channel->title }}">{{ $channel->title }}</h1>
                    @if(!$channel->visibility)<span class="private" data-translate-text="PRIVATE">{{ __('web.PRIVATE') }}</span>@endif
                    <div class="byline">
                        <span>Podcast by <a href="@{{ $channel->user->permalink_url }}" class="user-link">@{{ $channel->user->name }}</a></span> • <span><span data-translate-text="UPDATED">{{ __('web.UPDATED') }}:</span> {{ timeElapsedString($channel->updated_at) }}</span>
                    </div>
                    <div class="actions-primary">
                        @include('podcast.channel.actions')
                    </div>
                    <ul class="stat-summary">
                        <li>
                            <span class="num">{{ $channel->episodes->total() }}</span><span class="label">Songs</span>
                        </li>
                        <li>
                            <span class="num">@if(intval($channel->loves)){{$channel->loves}}@else - @endif</span><span class="label">Subscriber</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="column1">
                <div id="grid-toolbar-container">
                    <div class="grid-toolbar">
                        <div class="grid-toolbar-inner">
                            <ul class="actions primary">
                            </ul>
                            <ul class="actions secondary">
                                <li>
                                    <form class="search-bar">
                                        <svg class="icon search" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                        <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter">
                                        <a class="icon ex clear-filter">
                                            <svg height="16px" width="16px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531 0 68.382812 26.628906 132.667969 74.980469 181.019531 48.351562 48.351563 112.640625 74.980469 181.019531 74.980469s132.667969-26.628906 181.019531-74.980469c48.351563-48.351562 74.980469-112.636719 74.980469-181.019531 0-68.378906-26.628906-132.667969-74.980469-181.019531zm-70.292969 256.386719c9.761719 9.765624 9.761719 25.59375 0 35.355468-4.882812 4.882813-11.28125 7.324219-17.679687 7.324219s-12.796875-2.441406-17.679687-7.324219l-75.367188-75.367187-75.367188 75.371093c-4.882812 4.878907-11.28125 7.320313-17.679687 7.320313s-12.796875-2.441406-17.679687-7.320313c-9.761719-9.765624-9.761719-25.59375 0-35.355468l75.371093-75.371094-75.371093-75.367188c-9.761719-9.765624-9.761719-25.59375 0-35.355468 9.765624-9.765625 25.59375-9.765625 35.355468 0l75.371094 75.367187 75.367188-75.367187c9.765624-9.761719 25.59375-9.765625 35.355468 0 9.765625 9.761718 9.765625 25.589844 0 35.355468l-75.367187 75.367188zm0 0"/></svg>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="songs-grid" class="@if(auth()->check() && auth()->user()->id == $channel->user->id) channel-owner sortable @endif" data-type="channel" data-id="{{ $channel->id }}">
                    @include('commons.episode', ['episodes' => $channel->episodes])
                </div>
            </div>
            <div id="column2">
                <div class="content">
                    {!! Advert::get('sidebar') !!}
                    <div class="col2-tabs">
                        <a id="activity-tab" class="column2-tab show-activity active"><span data-translate-text="ACTIVITY">Activity</span></a>
                        <a id="comments-tab" class="column2-tab show-comments"><span data-translate-text="COMMENTS">{{ __('web.COMMENTS') }}</span></a>
                    </div>
                    <div id="activity">
                        <div id="small-activity-grid">
                            <div id="community" class="content">
                                @-include('commons.activity', ['activities' => $channel->activities, 'type' => 'full'])
                            </div>
                        </div>
                    </div>
                    <div id="comments" class="hide">
                        @if(config('settings.channel_comments') && $channel->allow_comments)
                            @include('comments.index', ['object' => (Object) ['id' => $channel->id, 'type' => 'App\Models\Playlist', 'title' => $channel->title]])
                        @else
                            <p class="text-center mt-5">Comments are turned off.</p>
                        @endif
                    </div>
                </div>
                @if(isset($channel->related) && count($channel->related))
                    <div class="content">
                        <div id="collaborators_digest"></div>
                        <div id="subscriber_digest"></div>
                        <div id="channel_digest">
                            <h3 data-translate-text="OTHER_PLAYLISTS">{{ __('web.OTHER_PLAYLISTS') }}</h3>
                            <ul class="snapshot">
                                @include('commons.channel', ['channels' => $channel->related, 'element' => 'search'])
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection