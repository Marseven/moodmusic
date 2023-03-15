<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ $playlist->permalink_url }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.playlist') active @endif" data-translate-text="OVERVIEW">{{ __('web.OVERVIEW') }}<div class="arrow"></div></a></li>
            @if($playlist->visibility)
                <li><a href="{{ $playlist->permalink_url }}/subscribers" class="page-nav-link @if(Route::currentRouteName() == 'frontend.playlist.subscribers') active @endif" data-translate-text="SUBSCRIBERS">{{ __('web.SUBSCRIBERS') }}<div class="arrow"></div></a></li>
                <li><a href="{{ $playlist->permalink_url }}/collaborators" class="page-nav-link @if(Route::currentRouteName() == 'frontend.playlist.collaborators') active @endif" data-translate-text="PLAYLIST_COLLABORATORS">{{ __('web.PLAYLIST_COLLABORATORS') }}<div class="arrow"></div></a></li>
            @endif
        </ul>
    </div>
</div>
{!! Advert::get('header') !!}
