@if(Route::currentRouteName() == 'frontend.homepage')
    <div id="landing-hero" class="p-0">
        <div class="claim-hero">
            <div class="container claim-container">
                <div class="row">
                    <div class="col">
                        <div class="vertical-align">
                            <p class="claim-subtitle text-uppercase" data-translate-text="PREMIUM">{{ __('web.PREMIUM') }}</p>
                            <h1 class="claim-display-title" data-translate-text="LANDING_TITLE">{{ __('web.LANDING_TITLE') }}</h1>
                            @if(auth()->check())
                                @if(auth()->user()->group->role_id == config('settings.default_usergroup', 5))
                                    <p class="claim-h3 text-left text-white" data-translate-text="LANDING_DESC">{{ __('web.LANDING_DESC') }}</p>
                                    <a href="{{ route('frontend.settings.subscription') }}" class="button-white orange w-button" data-translate-text="LANDING_BUTTON_TEXT">{{ __('web.LANDING_BUTTON_TEXT') }}</a>
                                @else
                                    <p class="claim-h3 text-left text-white" data-translate-text="LANDING_DISCOVER_DESC">{{ __('web.LANDING_DISCOVER_DESC') }}</p>
                                    <a href="{{ route('frontend.discover') }}" class="button-white orange w-button" data-translate-text="LANDING_DISCOVER_BUTTON_TEXT">{{ __('web.LANDING_DISCOVER_BUTTON_TEXT') }}</a>
                                @endif
                            @else
                                <p class="claim-h3 text-left text-white" data-translate-text="LANDING_DESC">{{ __('web.LANDING_DESC') }}</p>
                                <a class="button-white orange w-button claim-artist-access" data-translate-text="LANDING_BUTTON_TEXT">{{ __('web.LANDING_BUTTON_TEXT') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="claim-column-right col">
                        <img src="{{ asset('skins/default/images/main-landing.png') }}" width="540" alt="{{ __('web.LANDING_TITLE') }}" class="claim-landing-image">
                    </div>
                </div>
            </div>
        </div>

        <div class="container landing-text-section">
            <p class="landing-section-title">{{ __('web.LANDING_ENHANCING') }}<br></p>
            <div class="d-flex justify-content-between flex-lg-row flex-column">
                <div class="media mb-lg-0 mb-5">
                    <div class="landing-media-icon mr-3">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             width="79.977px" height="79.977px" viewBox="0 0 79.977 79.977" style="enable-background:new 0 0 79.977 79.977;"
                             xml:space="preserve">
                            <path d="M66.535,13.693H52.589V6.468c0-1.484-1.203-2.688-2.688-2.688H30.075c-1.485,0-2.688,1.204-2.688,2.688v7.225H13.441
                                C6.018,13.693,0,19.712,0,27.134v29.21l0.022-0.002c0.077,0.144,8.041,14.229,19.854,19.509l-0.154,0.345L60.4,75.593
                                c11.383-5.086,18.959-18.447,19.576-19.563V27.134C79.976,19.712,73.958,13.693,66.535,13.693z M47.213,13.693h-14.45V9.157
                                h14.449L47.213,13.693L47.213,13.693z"/>
                                                <path d="M0,58.446v4.309c0,7.424,6.018,13.441,13.441,13.441h4.1C8.537,71.398,2.268,62.175,0,58.446z"/>
                                                <path d="M62.242,76.196h4.293c7.423,0,13.44-6.018,13.44-13.441v-4.08C77.441,62.741,71.136,71.671,62.242,76.196z"/>
                        </svg>
                    </div>
                    <div class="media-body pr-3">
                        <div class="landing-heading-small">{{ __('web.LANDING_ENHANCING_PARTNERS_TITLE') }}</div>
                        {{ __('web.LANDING_ENHANCING_PARTNERS_DESC') }}
                    </div>
                </div>
                <div class="media mb-lg-0 mb-5">
                    <div class="landing-media-icon mr-3">
                        <svg id="Layer_1" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m354.721 257.948h34.002v12h-34.002z"/><path d="m164.723 212.759h182.555a15.9 15.9 0 0 0 0-31.792h-182.555a15.9 15.9 0 0 0 0 31.792z"/><path d="m228.034 115.337v-95.089a85.243 85.243 0 0 0 -13 5.194v73.58a16.774 16.774 0 0 0 13 16.315z"/><path d="m344.721 102.345a85.826 85.826 0 0 0 -37.754-71.074v67.751a26.786 26.786 0 0 1 -26.755 26.755h-48.423a26.786 26.786 0 0 1 -26.755-26.755v-67.751a85.826 85.826 0 0 0 -37.754 71.074v68.622h177.441z"/><path d="m296.967 99.022v-73.58a85.243 85.243 0 0 0 -13-5.194v95.089a16.774 16.774 0 0 0 13-16.315z"/><path d="m273.967 17.854a85.85 85.85 0 0 0 -12.967-1.302v99.225h12.967z"/><path d="m251.348 442.208h9.3c86.058 0 156.071-70.013 156.071-156.07v-56.19a9 9 0 1 0 -18 0v56.19c0 76.132-61.939 138.07-138.071 138.07h-9.3c-76.132 0-138.071-61.938-138.071-138.07v-56.19a9 9 0 0 0 -18 0v56.19c0 86.062 70.013 156.07 156.071 156.07z"/><path d="m251 16.552a85.824 85.824 0 0 0 -12.966 1.3v97.923h12.966z"/><path d="m123.277 257.948h34.003v12h-34.003z"/><path d="m269.31 451.984q-4.3.222-8.658.224h-9.3q-4.356 0-8.657-.224v43.464h26.615z"/><path d="m253.127 377.468h5.747a85.944 85.944 0 0 0 85.847-85.847v-68.862h-177.441v68.862a85.944 85.944 0 0 0 85.847 85.847zm-72.248-133.148a5 5 0 0 1 10 0v8.063a5 5 0 0 1 -10 0zm0 26.031a5 5 0 0 1 10 0v22.149a58.715 58.715 0 0 0 58.649 58.65 5 5 0 0 1 0 10 68.727 68.727 0 0 1 -68.649-68.65z"/></svg>
                    </div>
                    <div class="media-body pr-3">
                        <div class="landing-heading-small">{{ __('web.LANDING_ENHANCING_ARTISTS_TITLE') }}</div>
                        {{ __('web.LANDING_ENHANCING_ARTISTS_DESC') }}
                    </div>
                </div>
                <div class="media">
                    <div class="landing-media-icon mr-3">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 512.018 512.018" style="enable-background:new 0 0 512.018 512.018;" xml:space="preserve">
                            <g>
                                <path d="M6.98,256.673c-5.504,2.027-8.341,8.128-6.336,13.653l6.699,18.432L58.35,237.75L6.98,256.673z"/>
                            </g>
                                                    <g>
                                                        <path d="M55.257,420.513l30.72,84.48c0.96,2.667,2.965,4.843,5.525,6.016c1.429,0.661,2.965,1.003,4.501,1.003
                                    c1.259,0,2.496-0.213,3.691-0.661l33.899-12.501L55.257,420.513z"/>
                                                    </g>
                                                    <g>
                                                        <path d="M511.364,348.385l-35.157-96.661l-84.373,84.373l41.813-15.403c5.483-2.091,11.669,0.768,13.696,6.315
                                    c2.048,5.525-0.789,11.669-6.315,13.696l-53.12,19.584c-1.216,0.448-2.453,0.661-3.691,0.661c-4.331,0-8.427-2.667-10.005-6.976
                                    c-0.021-0.064,0-0.128-0.021-0.192l-89.408,89.408l220.245-81.152C510.553,360.012,513.39,353.91,511.364,348.385z"/>
                                                    </g>
                                                    <g>
                                                        <path d="M508.889,173.793L338.222,3.126c-4.16-4.16-10.923-4.16-15.083,0l-320,320c-4.16,4.16-4.16,10.923,0,15.083
                                    l170.667,170.667c2.069,2.091,4.8,3.136,7.531,3.136c2.731,0,5.461-1.045,7.552-3.115l320-320
                                    C513.049,184.716,513.049,177.974,508.889,173.793z M124.889,295.564L82.222,338.23c-2.091,2.069-4.821,3.115-7.552,3.115
                                    c-2.731,0-5.461-1.045-7.552-3.115c-4.16-4.16-4.16-10.923,0-15.083l42.667-42.667c4.16-4.16,10.923-4.16,15.083,0
                                    C129.028,284.641,129.049,291.382,124.889,295.564z M309.102,309.11c-7.552,7.552-17.813,11.179-29.227,11.179
                                    c-18.859,0-40.896-9.877-59.328-28.331c-13.483-13.483-22.955-29.611-26.645-45.397c-4.096-17.6-0.725-32.917,9.493-43.157
                                    c10.219-10.24,25.536-13.611,43.157-9.493c15.787,3.691,31.915,13.141,45.397,26.645
                                    C321.582,250.166,329.134,289.078,309.102,309.11z M444.889,188.897l-42.667,42.667c-2.091,2.069-4.821,3.115-7.552,3.115
                                    s-5.461-1.045-7.552-3.115c-4.16-4.16-4.16-10.923,0-15.083l42.667-42.667c4.16-4.16,10.923-4.16,15.083,0
                                    C449.028,177.974,449.049,184.716,444.889,188.897z"/>
                                                    </g>
                        </svg>
                    </div>
                    <div class="media-body pr-3">
                        <div class="landing-heading-small">{{ __('web.LANDING_ENHANCING_PUBLISHERS_TITLE') }}</div>
                        {{ __('web.LANDING_ENHANCING_PUBLISHERS_DESC') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="pub-reach-section">
            <div class="mxm-container-1440 container">
                <div class="row">
                    <div class="d-flex flex-column description padd-top140 col-lg-6 col-12">
                        <h2 class="landing-heading-title white-text">{{ __('web.REACH_AUDIENCE_TITLE') }}</h2>
                        <p class="h3-20---regular white-text">{{ __('web.REACH_AUDIENCE_DESC') }}<br></p>
                    </div>
                    <div class="image-feature col-6 d-lg-block d-none"></div>
                </div>
            </div>
        </div>

        @if(Cache::has('trending_week'))
            <div class="va-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-12 align-items-center d-flex">
                            <div class="position-relative">
                                <h2 class="claim-h2 text-left" data-translate-text="LANDING_TRENDING_TITLE">{{ __('web.LANDING_TRENDING_TITLE') }}</h2>
                                <p class="claim-h3 text-left" data-translate-text="LANDING_TRENDING_DESC">{{ __('web.LANDING_TRENDING_DESC') }}</p>
                                <a href="{{ route('frontend.trending.week') }}" class="cta-link mt-3">
                                    <span>
                                        <i class="landing-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M6.5 13.5L5.5 12.5 9.9 8 5.5 3.5 6.5 2.5 12.1 8 6.5 13.5z"></path></svg>
                                        </i>
                                    </span>
                                    <span class="cta-text font-weight-bolder" data-translate-text="LANDING_TRENDING_BUTTON_TEXT">{{ __('web.LANDING_TRENDING_BUTTON_TEXT') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-7 col-12">
                            <div class="custom-grid-wrapper row">
                                @foreach(Cache::get('trending_week')->slice(0, 6) as $song)
                                    <a href="{{ $song->permalink_url }}" class="custom-grid text-dec-none col-lg-4 col-6">
                                        <div class="position-relative overflow-hidden">
                                            <div class="custom-grid-image block placeholder position-relative" style="padding-bottom:100%;">
                                                <img src="{{ $song->artwork_url }}" alt="{!! $song->title !!}" class="block position-absolute" />
                                            </div>
                                            <div class="custom-grid-cover ">
                                                <div class="position-center-content justify-content-center align-items-center text-center p-2">
                                                    <h5 class="mb-2">{!! $song->title !!}</h5>
                                                    <p class="mb-0">@foreach($song->artists as $artist){!! $artist->name !!}@if(!$loop->last), @endif @endforeach</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="va-section-footer secondary">
            <div class="container claim-container">
                <h2 class="claim-h2 mb-5" data-translate-text="WHY_US">{{ __('web.WHY_US') }}</h2>
                <div class="row">
                    <div class="card-info w-col col-lg-4 col-12">
                        <div class="position-relative d-flex justify-content-center mb-3">
                            <img src="{{ asset('skins/default/images/landing-collection.svg') }}" alt="" class="card-image">
                        </div>
                        <h3 class="claim-feature-h3 text-center" data-translate-text="WHY_US_1_T">{{ __('web.WHY_US_1_T') }}</h3>
                        <p class="claim-h3-regular text-secondary" data-translate-text="WHY_US_1_D">{{ __('web.WHY_US_1_D') }}</p>
                    </div>
                    <div class="card-info w-col col-lg-4 col-12">
                        <div class="position-relative d-flex justify-content-center mb-3">
                            <img src="{{ asset('skins/default/images/landing-pocket.svg') }}" alt="" class="card-image">
                        </div>
                        <h3 class="claim-feature-h3 text-center" data-translate-text="WHY_US_2_T">{{ __('web.WHY_US_2_T') }}</h3>
                        <p class="claim-h3-regular text-secondary" data-translate-text="WHY_US_2_D">{{ __('web.WHY_US_2_D') }}</p>
                    </div>
                    <div class="card-info w-col col-lg-4 col-12">
                        <div class="position-relative d-flex justify-content-center mb-3">
                            <img src="{{ asset('skins/default/images/landing-foryou.svg') }}" alt="" class="card-image">
                        </div>
                        <h3 class="claim-feature-h3 text-center" data-translate-text="WHY_US_3_T">{{ __('web.WHY_US_3_T') }}</h3>
                        <p class="claim-h3-regular text-secondary" data-translate-text="WHY_US_3_D">{{ __('web.WHY_US_3_D') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="va-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('skins/default/images/landing-community.svg') }}" alt="" class="card-image">
                    </div>
                    <div class="col-lg-6 col-12">
                        <h2 class="claim-h2-white padding-bottom-40px" data-translate-text="JOIN_US_TITLE">{{ __('web.JOIN_US_TITLE') }}</h2>
                        <p class="claim-h3" data-translate-text="JOIN_US_DESCRIPTION">{{ __('web.JOIN_US_DESCRIPTION') }}</p>
                        <div class="d-flex justify-content-center">
                            <a class="button-white w-button claim-artist-access button-red"data-translate-text="JOIN_US_BUTTON_TEXT">{{ __('web.JOIN_US_BUTTON_TEXT') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="va-section-footer secondary blue">
            <div class="container">
                <h2 class="claim-h2-white padding-bottom-40px text-white" data-translate-text="CLAIM_NOW_TEXT">{{ __('web.CLAIM_NOW_TEXT') }}</h2>
                <p class="claim-h3 text-white" data-translate-text="CLAIM_NOW_DESCRIPTION">{!! __('web.CLAIM_NOW_DESCRIPTION') !!}</p>
                <div class="d-flex justify-content-center">
                    <a class="button-white w-button claim-artist-access" data-translate-text="CLAIM_NOW_BUTTON_TEXT">{{ __('web.CLAIM_NOW_BUTTON_TEXT') }}</a>
                </div>
            </div>
        </div>
        @include('homepage.footer')
    </div>
@endif