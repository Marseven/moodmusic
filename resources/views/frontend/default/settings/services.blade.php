@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header ">
                <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_CONNECT">{{ __('web.SETTINGS_TITLE_CONNECT') }}</span></h1>
            </div>
            <div id="column1" class="full settings">
                <div class="content-wrapper row">
                    <div class="fields col-lg-8 col-12">
                        @if(config('settings.facebook_login'))
                            <div class="content connect-container">
                                <div class="connected-info">
                                    <div class="connected-element-container facebook-icon-container">
                                        <svg class="icon @if(auth()->user()->connect->firstWhere('service', 'facebook')) hide @endif" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg>
                                        @if(auth()->user()->connect->firstWhere('service', 'facebook'))
                                            <img src="{{ auth()->user()->connect->firstWhere('service', 'facebook')->provider_artwork }}">
                                        @endif
                                    </div>
                                    <div class="connected-element">
                                        <h2>Facebook</h2>
                                        @if(auth()->user()->connect->firstWhere('service', 'facebook'))
                                            <p class="text-secondary desktop" data-translate-text="FACEBOOK_SERVICE_MSG">Connected as {{ auth()->user()->connect->firstWhere('service', 'facebook')->provider_name }}</p>
                                        @else
                                            <p class="text-secondary desktop" data-translate-text="FACEBOOK_SERVICE_MSG">{{ __('web.FACEBOOK_SERVICE_MSG') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group">
                                    @if(auth()->user()->connect->firstWhere('service', 'facebook'))
                                        <a class="btn" data-action="remove-service" data-service="facebook">
                                            <span data-translate-text="SERVICE_AUTHORIZE_DELETE">{{ __('web.SERVICE_AUTHORIZE_DELETE') }}</span>
                                        </a>
                                    @else
                                        <a class="btn thirdparty-connect" data-action="social-login" data-service="facebook">
                                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                            <span data-translate-text="FACEBOOK_SERVICE_LOGIN">{{ __('web.FACEBOOK_SERVICE_LOGIN') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if(config('settings.twitter_login'))
                            <div class="content connect-container">
                                <div class="connected-info">
                                    <div class="connected-element-container twitter-icon-container">
                                        <svg class="icon @if(auth()->user()->connect->firstWhere('service', 'twitter')) hide @endif" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568C480.224,136.96,497.728,118.496,512,97.248z"/></svg>
                                    @if(auth()->user()->connect->firstWhere('service', 'twitter'))
                                            <img src="{{ auth()->user()->connect->firstWhere('service', 'twitter')->provider_artwork }}">
                                        @endif
                                    </div>
                                    <div class="connected-element">
                                        <h2>Twitter</h2>
                                        @if(auth()->user()->connect->firstWhere('service', 'twitter'))
                                            <p class="text-secondary desktop">Connected as {{ auth()->user()->connect->firstWhere('service', 'twitter')->provider_name }}</p>
                                        @else
                                            <p class="text-secondary desktop" data-translate-text="TWITTER_SERVICE_MSG">{{ __('web.TWITTER_SERVICE_MSG') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group">
                                    @if(auth()->user()->connect->firstWhere('service', 'twitter'))
                                        <a class="btn" data-action="remove-service" data-service="twitter">
                                            <span data-translate-text="SERVICE_AUTHORIZE_DELETE">{{ __('web.SERVICE_AUTHORIZE_DELETE') }}</span>
                                        </a>
                                    @else
                                        <a class="btn thirdparty-connect" data-action="social-login" data-service="twitter">
                                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                            <span data-translate-text="TWITTER_SERVICE_LOGIN">{{ __('web.TWITTER_SERVICE_LOGIN') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if(config('settings.google_login'))
                            <div class="content connect-container">
                                <div class="connected-info">
                                    <div class="connected-element-container google-icon-container">
                                        <svg class="icon @if(auth()->user()->connect->firstWhere('service', 'google')) hide @endif" height="16" viewBox="0 0 512 512" width="16" xmlns="http://www.w3.org/2000/svg"><g><path d="m120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308h-86.308c-34.255 44.488-52.823 98.707-52.823 155.785s18.568 111.297 52.823 155.785h86.308v-86.308c-12.142-20.347-19.131-44.11-19.131-69.477z"/><path d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216c-20.525 12.186-44.388 19.039-69.569 19.039z"/><path d="m139.131 325.477-86.308 86.308c6.782 8.808 14.167 17.243 22.158 25.235 48.352 48.351 112.639 74.98 181.019 74.98v-120c-49.624 0-93.117-26.72-116.869-66.523z"/><path d="m512 256c0-15.575-1.41-31.179-4.192-46.377l-2.251-12.299h-249.557v120h121.452c-11.794 23.461-29.928 42.602-51.884 55.638l86.216 86.216c8.808-6.782 17.243-14.167 25.235-22.158 48.352-48.353 74.981-112.64 74.981-181.02z"/><path d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606c-48.352-48.352-112.639-74.981-181.02-74.981l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"/><path d="m256 120v-120c-68.38 0-132.667 26.629-181.02 74.98-7.991 7.991-15.376 16.426-22.158 25.235l86.308 86.308c23.753-39.803 67.246-66.523 116.87-66.523z"/></g></svg>
                                        @if(auth()->user()->connect->firstWhere('service', 'google'))
                                            <img src="{{ auth()->user()->connect->firstWhere('service', 'google')->provider_artwork }}">
                                        @endif
                                    </div>
                                    <div class="connected-element">
                                        <h2>Google</h2>
                                        @if(auth()->user()->connect->firstWhere('service', 'google'))
                                            <p class="text-secondary desktop">Connected as {{ auth()->user()->connect->firstWhere('service', 'google')->provider_name }}</p>
                                        @else
                                            <p class="text-secondary desktop" data-translate-text="GOOGLE_SERVICE_MSG">{{ __('web.GOOGLE_SERVICE_MSG') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group">
                                    @if(auth()->user()->connect->firstWhere('service', 'google'))
                                        <a class="btn" data-action="remove-service" data-service="google">
                                            <span data-translate-text="SERVICE_AUTHORIZE_DELETE">{{ __('web.SERVICE_AUTHORIZE_DELETE') }}</span>
                                        </a>
                                    @else
                                        <a class="btn thirdparty-connect" data-action="social-login" data-service="google">
                                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                            <span data-translate-text="GOOGLE_SERVICE_LOGIN">{{ __('web.GOOGLE_SERVICE_LOGIN') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="description col-lg-4 col-12 desktop">
                        <p data-translate-text="SETTINGS_CONNECT_TIP">{{ __('web.SETTINGS_CONNECT_TIP') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection