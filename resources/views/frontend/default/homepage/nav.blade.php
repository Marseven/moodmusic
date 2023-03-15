<div id="page-nav" class="home-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ route('frontend.homepage') }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.homepage') active @endif">
                    <span data-translate-text="HOME">{{ __('web.HOME') }}</span>
                    <div class="arrow"></div>
                </a>
            </li>
            <li><a href="{{ route('frontend.discover') }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.discover') active @endif"><span data-translate-text="DISCOVER">{{ __('web.DISCOVER') }}</span><div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.community') }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.community') active @endif"><span data-translate-text="COMMUNITY">{{ __('web.COMMUNITY') }}</span><div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.trending') }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.trending') active @endif"><span data-translate-text="TRENDING">{{ __('web.TRENDING') }}</span><div class="arrow"></div></a></li>
        </ul>
    </div>
</div>
{!! Advert::get('header') !!}