<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ $podcast->permalink_url }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.podcast') active @endif" data-translate-text="OVERVIEW">{{ __('web.OVERVIEW') }}<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.podcast.subscribers', ['id' => $podcast->id, 'slug' => str_slug($podcast->title)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.podcast.subscribers') active @endif" data-translate-text="SUBSCRIBERS">{{ __('web.SUBSCRIBERS') }}<div class="arrow"></div></a></li>
        </ul>
    </div>
</div>
{!! Advert::get('header') !!}