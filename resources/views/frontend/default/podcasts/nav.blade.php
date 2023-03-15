<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ $album->permalink_url }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.album') active @endif" data-translate-text="OVERVIEW">{{ __('web.OVERVIEW') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.album.related', ['id' => $album->id, 'slug' => str_slug($album->title)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.album.related') active @endif" data-translate-text="RELATED_ALBUMS">{{ __('web.RELATED_ALBUMS') }}<div class="arrow"></div></a></li>
        </ul>
    </div>
</div>