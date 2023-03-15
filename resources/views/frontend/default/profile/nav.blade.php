<script>var user_data_{{ $profile->id }} = {!! json_encode(['id' => $profile->id, 'username' => $profile->username, 'name' => $profile->name, 'follower_count' => $profile->follower_count, 'artwork_url' => $profile->artwork_url, 'permalink_url' => $profile->permalink_url]) !!}</script>
<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ route('frontend.user', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user') active @endif" data-translate-text="OVERVIEW">{{ __('web.OVERVIEW') }}<div class="arrow"></div></a></li>
            @if (! $profile->activity_privacy || (auth()->check() && auth()->user()->username == $profile->username))
                <li><a href="{{ route('frontend.user.feed', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.feed') active @endif" data-translate-text="NEWS_FEED">{{ __('web.NEWS_FEED') }}<div class="arrow"></div></a></li>
            @endif
            <li><a href="{{ route('frontend.user.collection', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.collection' || Route::currentRouteName() == 'frontend.user.favorites') active @endif" data-translate-text="COLLECTION">{{ __('web.COLLECTION') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.user.playlists', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.playlists' || Route::currentRouteName() == 'frontend.user.subscribed') active @endif" data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.user.followers', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.followers') active @endif" data-translate-text="USER_FOLLOWERS">{{ __('web.USER_FOLLOWERS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.user.following', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.following') active @endif" data-translate-text="USER_FOLLOWING">{{ __('web.USER_FOLLOWING') }}<div class="arrow"></div></a></li>
            @if (auth()->check() && auth()->user()->username == $profile->username)
                <li><a href="{{ route('frontend.user.notifications', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.notifications') active @endif" data-translate-text="NOTIFICATIONS">{{ __('web.NOTIFICATIONS') }}<div class="arrow"></div></a></li>
            @endif
            @if (! $profile->activity_privacy || (auth()->check() && auth()->user()->username == $profile->username))
                <li><a href="{{ route('frontend.user.now_playing', ['username' => $profile->username]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.user.now_playing') active @endif" data-translate-text="QUEUE">{{ __('web.QUEUE') }}<div class="arrow"></div></a></li>
            @endif
        </ul>
    </div>
</div>