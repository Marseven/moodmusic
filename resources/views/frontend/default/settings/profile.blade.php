@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <form class="ajax-form" id="settings-profile-form" method="post" action="{{ url('auth/user/settings/profile') }}" enctype="multipart/form-data" novalidate>
                <div class="page-header ">
                    <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_PROFILE">{{ __('web.SETTINGS_TITLE_PROFILE') }}</span></h1>
                </div>
                <div id="column1" class="full settings">
                    <div class="user-picture-container content row">
                        <div class="fields col-lg-6 col-12">
                            <label class="control-label" data-translate-text="SETTINGS_MY_PROFILE_PICTURE">{!! __('web.SETTINGS_MY_PROFILE_PICTURE') !!}</label>
                            <div class="user-img-container"><img src="{{ auth()->user()->artwork_url }}" class="user-picture-preview"></div>
                            <div class="user-picture-right">
                                <div class="upload-button-container">
                                    <div id="upload-pic" class="button-upload-form">
                                        <a id="entity-art-browse" class="btn" data-translate-text="UPLOAD_PROFILE_IMAGE">{{ __('web.UPLOAD_PROFILE_IMAGE') }}</a>
                                        <input class="uploader invisible-input" id="upload-user-pic" name="artwork" accept="image/*" title="" type="file">
                                    </div>
                                    <span id="user-pic-filename"></span>
                                </div>
                                <p class="help-text" data-translate-text="SETTINGS_PICTURE_REQS">{!! __('web.SETTINGS_PICTURE_REQS') !!}</p>
                                <p><a id="user-picture-import-twitter" class="disabled btn hide" data-translate-text="SETTINGS_PICTURE_IMPORT_TWITTER">{{ __('web.SETTINGS_PICTURE_IMPORT_TWITTER') }}</a></p>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_PICTURE_TIP">{!! __('web.SETTINGS_PICTURE_TIP') !!}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" for="name" data-translate-text="FORM_NAME">{{ __('web.FORM_NAME') }}</label>
                                <input class="span4" name="name" maxlength="175" value="{{ auth()->user()->name }}" type="text" required>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_NAME_TIP">{{ __('web.SETTINGS_NAME_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" for="country" data-translate-text="FORM_COUNTRY">{{ __('web.FORM_COUNTRY') }}</label>
                                {!! makeCountryDropDown('country', 'span3 select2', auth()->user()->country) !!}
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_LOCATION_TIP">{{ __('web.SETTINGS_LOCATION_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control">
                                <label class="control-label" for="settings-bio">
                                    <span data-translate-text="FORM_SHORT_BIO">Short Bio:</span>
                                    <span class="help-text" data-translate-text="FORM_SHORT_BIO_LIMIT">{{ __('web.FORM_SHORT_BIO_LIMIT') }}</span>
                                </label>
                                <textarea type="text" class="span6" name="bio" maxlength="180">{{ auth()->user()->bio }}</textarea>
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_BIO_TIP">{{ __('web.SETTINGS_BIO_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <label class="control-label" data-translate-text="FORM_DOB">{{ __('web.FORM_DOB') }}</label>
                            <input name="birth" maxlength="175" type="text" class="datepicker" value="{{ \Carbon\Carbon::parse(auth()->user()->birth)->format('m/d/Y') }}" autocomplete="off">
                            <p class="help-block hide" data-translate-text="POPUP_SIGNUP_FORM_TOO_YOUNG">{{ __('web.POPUP_SIGNUP_FORM_TOO_YOUNG') }}</p>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_BIRTHDAY_TIP">{{ __('web.SETTINGS_BIRTHDAY_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <label class="control-label" data-translate-text="FORM_SEX_PROMPT">{{ __('web.FORM_SEX_PROMPT') }}</label>
                            {!! makeDropDown( array("M" => __('web.FORM_SEX_MAN'), "F" => __('web.FORM_SEX_WOMAN'), "O" => __('web.ARTIST_AFFILIATION_4')), "gender", auth()->user()->gender ) !!}
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_BIRTHDAY_TIP">{{ __('web.SETTINGS_BIRTHDAY_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="WEBSITE_URL">{{ __('web.WEBSITE_URL') }}</label>
                                <input class="span4" name="website_url" maxlength="175" value="{{ auth()->user()->website_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_WEBSITE_TIP">{{ __('web.SETTINGS_WEBSITE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="FACEBOOK_PROFILE_URL">{{ __('web.FACEBOOK_PROFILE_URL') }}</label>
                                <input class="span4" name="facebook_url" maxlength="175" value="{{ auth()->user()->facebook_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_FACEBOOK_PROFILE_URL_TIP">{{ __('web.SETTINGS_FACEBOOK_PROFILE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="TWITTER_PROFILE_URL">{{ __('web.TWITTER_PROFILE_URL') }}</label>
                                <input class="span4" name="twitter_url" maxlength="175" value="{{ auth()->user()->twitter_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_TWITTER_PROFILE_URL_TIP">{{ __('web.SETTINGS_TWITTER_PROFILE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="YOUTUBE_PROFILE_URL">{{ __('web.YOUTUBE_PROFILE_URL') }}</label>
                                <input class="span4" name="youtube_url" maxlength="175" value="{{ auth()->user()->youtube_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_YOUTUBE_PROFILE_URL_TIP">{{ __('web.SETTINGS_YOUTUBE_PROFILE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="INSTAGRAM_PROFILE_URL">{{ __('web.INSTAGRAM_PROFILE_URL') }}</label>
                                <input class="span4" name="instagram_url" maxlength="175" value="{{ auth()->user()->instagram_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_INSTAGRAM_PROFILE_URL_TIP">{{ __('web.SETTINGS_INSTAGRAM_PROFILE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div class="content row">
                        <div class="fields col-lg-6 col-12">
                            <div class="control single">
                                <label class="control-label" data-translate-text="SOUNDCLOUD_PROFILE_URL">{{ __('web.SOUNDCLOUD_PROFILE_URL') }}</label>
                                <input class="span4" name="soundcloud_url" maxlength="175" value="{{ auth()->user()->soundcloud_url }}" type="text">
                            </div>
                        </div>
                        <div class="description col-lg-6 col-12 desktop">
                            <p data-translate-text="SETTINGS_SOUNDCLOUD_PROFILE_URL_TIP">{{ __('web.SETTINGS_SOUNDCLOUD_PROFILE_URL_TIP') }}</p>
                        </div>
                    </div>
                    <div id="settings-deactivate-container" class="control-group">
                        <h2 data-translate-text="SETTINGS_NOTIFS_TITLE"></h2>
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