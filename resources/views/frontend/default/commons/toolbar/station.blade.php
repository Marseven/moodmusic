<div id="grid-toolbar-container">
    <div class="grid-toolbar">
        <div class="grid-toolbar-inner">
            <ul class="actions secondary">
                @if(Route::currentRouteName() == 'frontend.radio.browse.by.region' || Route::currentRouteName() == 'frontend.radio.browse.category')
                    <li class="desktop">
                        {!! makeCountryDropDown('country', 'toolbar-country-filter-select2', request()->input('country') ? request()->input('country') : null) !!}
                    </li>
                @endif
                <li class="toolbar-filter-city desktop">
                    @if(Route::currentRouteName() == 'frontend.radio.browse.by.country' || request()->input('city_id'))
                        {!! makeCityDropDown(request()->input('country') ? request()->input('country') : (isset($browse->country) ? $browse->country->code : null), 'city', 'toolbar-filter-city-select2', request()->input('city_id') ? request()->input('city_id') : null) !!}
                    @endif
                </li>
                <li class="toolbar-filter-language desktop">
                    @if(Route::currentRouteName() == 'frontend.radio.browse.by.country' || request()->input('language_id'))
                        {!! makeCountryLanguageDropDown(request()->input('country') ? request()->input('country') : (isset($browse->country) ? $browse->country->code : null), 'language', 'toolbar-filter-language-select2', request()->input('language_id') ? request()->input('language_id') : null) !!}
                    @endif
                </li>
                <li>
                    <div class="btn-group first view-options">
                        <a class="btn view-selector active" data-view="grid" data-target="#stations-grid">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 271.673 271.673" xml:space="preserve">
                                <path d="M114.939,0H10.449C4.678,0,0,4.678,0,10.449v104.49c0,5.771,4.678,10.449,10.449,10.449h104.49c5.771,0,10.449-4.678,10.449-10.449V10.449C125.388,4.678,120.71,0,114.939,0z"/>
                                <path d="M261.224,0h-104.49c-5.771,0-10.449,4.678-10.449,10.449v104.49c0,5.771,4.678,10.449,10.449,10.449h104.49c5.771,0,10.449-4.678,10.449-10.449V10.449C271.673,4.678,266.995,0,261.224,0z"/>
                                <path d="M114.939,146.286H10.449C4.678,146.286,0,150.964,0,156.735v104.49c0,5.771,4.678,10.449,10.449,10.449h104.49c5.771,0,10.449-4.678,10.449-10.449v-104.49C125.388,150.964,120.71,146.286,114.939,146.286z"/>
                                <path d="M261.224,146.286h-104.49c-5.771,0-10.449,4.678-10.449,10.449v104.49c0,5.771,4.678,10.449,10.449,10.449h104.49c5.771,0,10.449-4.678,10.449-10.449v-104.49C271.673,150.964,266.995,146.286,261.224,146.286z"/>
                            </svg>
                        </a>
                        <a class="btn view-selector" data-view="list" data-target="#stations-grid">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 124 124" xml:space="preserve">
                                <g>
                                    <path d="M112,6H12C5.4,6,0,11.4,0,18s5.4,12,12,12h100c6.6,0,12-5.4,12-12S118.6,6,112,6z"/>
                                    <path d="M112,50H12C5.4,50,0,55.4,0,62c0,6.6,5.4,12,12,12h100c6.6,0,12-5.4,12-12C124,55.4,118.6,50,112,50z"/>
                                    <path d="M112,94H12c-6.6,0-12,5.4-12,12s5.4,12,12,12h100c6.6,0,12-5.4,12-12S118.6,94,112,94z"/>
                                </g>
                            </svg>
                        </a>
                    </div>
                </li>
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