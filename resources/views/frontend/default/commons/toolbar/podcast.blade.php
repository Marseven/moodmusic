<div id="grid-toolbar-container">
    <div class="grid-toolbar">
        <div class="grid-toolbar-inner">
            <ul class="actions secondary">
                @if(Route::currentRouteName() == 'frontend.podcasts.browse.by.region' || Route::currentRouteName() == 'frontend.podcasts.browse.category')
                    <li>
                        {!! makeCountryDropDown('country', 'toolbar-country-filter-select2', request()->input('country') ? request()->input('country') : null) !!}
                    </li>
                @endif
                <li class="toolbar-filter-language">
                    @if(Route::currentRouteName() == 'frontend.podcasts.browse.by.country' || request()->input('language_id'))
                        {!! makeCountryLanguageDropDown(request()->input('country') ? request()->input('country') : (isset($browse->country) ? $browse->country->code : null), 'language', 'toolbar-filter-language-select2', request()->input('language_id') ? request()->input('language_id') : null) !!}
                    @endif
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