@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <form id="settings-account-form" class="ajax-form" method="post" action="{{ url('auth/user/settings/account') }}" novalidate>
                <div class="page-header ">
                    <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_ACCOUNT">{{ __('web.SETTINGS_TITLE_ACCOUNT') }}</span></h1>
                </div>
                <div id="column1" class="full settings">
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="controls">
                                <label class="control-label" for="username" data-translate-text="SETTINGS_USERNAME_TITLE">{{ __('web.SETTINGS_USERNAME_TITLE') }}</label>
                                <input name="username" class="span4" value="{{ auth()->user()->username }}" type="text" required>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_USERNAME_TIP"><a href="{{ route('frontend.user', ['username' => auth()->user()->username]) }}" target="_blank">{{ route('frontend.user', ['username' => auth()->user()->username]) }}</a>. {{ __('web.SETTINGS_USERNAME_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control"><label class="control-label" for="email" data-translate-text="EMAIL}">{{ __('web.EMAIL') }}</label>
                                <input name="email" class="span4" value="{{ auth()->user()->email }}" type="text" required>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_EMAIL_TIP">{!! __('web.SETTINGS_EMAIL_TIP') !!}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control">
                                <label class="control-label" for="password" data-translate-text="SETTINGS_CURRENT_PASSWORD">{{ __('web.SETTINGS_CURRENT_PASSWORD') }}</label>
                                <input name="password" class="span4" type="password" required>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p class="bold" data-translate-text="SETTINGS_CURRENT_PASSWORD_TIP">{{ __('web.SETTINGS_CURRENT_PASSWORD_TIP') }}</p>
                        </div>
                    </div>
                    <div id="settings-third-party-auth" class="content hide">
                        <p class="centered-bold-tip" data-translate-text="SETTINGS_VERIFY_AUTH_TIP">Third-party authentication will be required to make the above changes.</p>
                    </div>
                </div>
                <div id="page-column-footer">
                    <div id="primary-actions-footer">
                        <button class="btn save" type="submit">
                            <svg height="24" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                            <span data-translate-text="SAVE_CHANGES">{{ __('web.SAVE_CHANGES') }}</span>
                        </button>
                        <a class="btn cancel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                <path d="M0 0h24v24H0z" fill="none"/>
                            </svg>
                            <span data-translate-text="CANCEL">{{ __('web.CANCEL') }}</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection