<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ route('frontend.search.song', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.song') active @endif" data-translate-text="SEARCH_OPTION_SONGS">{{ __('web.SEARCH_OPTION_SONGS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.artist', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.artist') active @endif" data-translate-text="SEARCH_OPTION_ARTISTS">{{ __('web.SEARCH_OPTION_ARTISTS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.album', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.album') active @endif" data-translate-text="SEARCH_OPTION_ALBUMS">{{ __('web.SEARCH_OPTION_ALBUMS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.playlist', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.playlist') active @endif" data-translate-text="SEARCH_OPTION_PLAYLISTS">{{ __('web.SEARCH_OPTION_PLAYLISTS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.podcast', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.podcast') active @endif" data-translate-text="SEARCH_OPTION_PODCASTS">{{ __('web.SEARCH_OPTION_PODCASTS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.station', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.station') active @endif" data-translate-text="SEARCH_OPTION_STATIONS">{{ __('web.SEARCH_OPTION_STATIONS') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.user', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.user') active @endif" data-translate-text="SEARCH_OPTION_PEOPLE">{{ __('web.SEARCH_OPTION_PEOPLE') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.search.event', ['slug' => $term]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.search.event') active @endif" data-translate-text="SEARCH_OPTION_EVENTS">{{ __('web.SEARCH_OPTION_EVENTS') }}<div class="arrow"></div></a></li>
        </ul>
    </div>
</div>