@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <form class="ajax-form" method="post" action="{{ url('auth/user/settings/password') }}" novalidate>
                <div class="page-header ">
                    <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_PASSWORD">{{ __('web.SETTINGS_TITLE_PASSWORD') }}</span></h1>
                </div>
                <div id="column1" class="full settings">
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" for="old-password" data-translate-text="SETTINGS_CURRENT_PASSWORD">{{ __('web.SETTINGS_CURRENT_PASSWORD') }}</label>
                                <input name="old-password" class="span4" type="password" required>
                            </div>
                            <div class="control single">
                                <label class="control-label" for="password" data-translate-text="FORM_NEW_PASSWORD">{{ __('web.FORM_NEW_PASSWORD') }}</label>
                                <input name="password" class="span4" type="password" required>
                            </div>
                            <div class="control single">
                                <label class="control-label" for="password_confirmation" data-translate-text="FORM_CONFIRM_PASSWORD">{{ __('web.FORM_CONFIRM_PASSWORD') }}</label>
                                <input name="password_confirmation" class="span4" type="password" required>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_PASSWORD_TIP">Your password should be at least 5 characters and not a dictionary word or common name. You should change your password once a year.</p>
                        </div>
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