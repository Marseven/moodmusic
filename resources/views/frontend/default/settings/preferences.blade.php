@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <form id="settings-preferences-form" class="ajax-form" method="post" action="{{ url('auth/user/settings/preferences') }}" novalidate>
                <div class="page-header ">
                    <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_PREFERENCES">{{ __('web.SETTINGS_TITLE_PREFERENCES') }}</span></h1>
                </div>
                <div id="column1" class="full settings">
                    <div id="settings-prefs-container" class="control-group preferences-group">
                        <h2 data-translate-text="SETTINGS_LOCAL_SETTINGS_TITLE">Application Settings</h2>
                        <ul class="controls">
                            <li>
                                <input class="hide custom-checkbox" id="hd_streaming" type="checkbox" name="hd_streaming" @if(auth()->user()->hd_streaming) checked="checked" @endif>
                                <label class="cbx" for="hd_streaming"></label>
                                <label class="lbl" for="hd_streaming" data-translate-text="SETTINGS_HD_STREAMING">{{ __('web.SETTINGS_HD_STREAMING') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="restore_queue" type="checkbox" name="restore_queue" @if(auth()->user()->restore_queue) checked="checked" @endif>
                                <label class="cbx" for="restore_queue"></label>
                                <label class="lbl" for="restore_queue" data-translate-text="SETTINGS_RESTORE_QUEUE">{{ __('web.SETTINGS_RESTORE_QUEUE') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="persist_shuffle" type="checkbox" name="persist_shuffle" @if(auth()->user()->persist_shuffle) checked="checked" @endif>
                                <label class="cbx" for="persist_shuffle"></label>
                                <label class="lbl" for="persist_shuffle" data-translate-text="SETTINGS_PERSIST_SHUFFLE">{{ __('web.SETTINGS_PERSIST_SHUFFLE') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="play_pause_fade" type="checkbox" name="play_pause_fade" @if(auth()->user()->play_pause_fade) checked="checked" @endif>
                                <label class="cbx" for="play_pause_fade"></label>
                                <label class="lbl" for="play_pause_fade" data-translate-text="SETTINGS_FADEIN">{{ __('web.SETTINGS_FADEIN') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="disablePlayerShortcuts" type="checkbox" name="disablePlayerShortcuts" @if(auth()->user()->disablePlayerShortcuts) checked="checked" @endif>
                                <label class="cbx" for="disablePlayerShortcuts"></label>
                                <label class="lbl" for="disablePlayerShortcuts" data-translate-text="SETTINGS_DISABLE_PLAYER_KEYBOARD_SHORTCUTS">{{ __('web.SETTINGS_DISABLE_PLAYER_KEYBOARD_SHORTCUTS') }}</label>
                            </li>
                            <li class="crossfade hide">
                                <label for="crossfade_amount" value="1" data-translate-text="SETTINGS_CROSSFADE_FINEPRINT">{{ __('web.SETTINGS_CROSSFADE_FINEPRINT') }}</label>
                                <select name="crossfade_amount"><option value="3">3 seconds</option><option value="4">4 seconds</option><option value="5" selected="">5 seconds</option><option value="6">6 seconds</option><option value="7">7 seconds</option><option value="8">8 seconds</option></select>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="allow_comments" type="checkbox" name="allow_comments" @if(auth()->user()->allow_comments) checked="checked" @endif>
                                <label class="cbx" for="allow_comments"></label>
                                <label class="lbl" for="allow_comments" data-translate-text="SETTINGS_ALLOW_PROFILE_COMMENT_PROFILE">{{ __('web.SETTINGS_ALLOW_PROFILE_COMMENT_PROFILE') }}</label>
                            </li>
                        </ul>
                    </div>
                    <div id="settings-activity-container" class="control-group preferences-group">
                        <h2 data-translate-text="SETTINGS_ACTIVITY_TITLE">{{ __('web.SETTINGS_ACTIVITY_TITLE') }}</h2>
                        <ul id="user-activity_privacy">
                            <li>
                                <input class="hide custom-checkbox" id="activity-privacy-on" type="radio" name="activity_privacy" @if(auth()->user()->activity_privacy == 0) checked="checked" @endif value="0">
                                <label class="cbx radio" for="activity-privacy-on"></label>
                                <label for="activity-privacy-on" data-translate-text="SETTINGS_PRIVACY_RECORD">{!! __('web.SETTINGS_PRIVACY_RECORD') !!}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="activity-privacy-off" type="radio" name="activity_privacy" @if(auth()->user()->activity_privacy == 1) checked="checked" @endif value="1">
                                <label class="cbx radio" for="activity-privacy-on"></label>
                                <label for="activity-privacy-off" data-translate-text="SETTINGS_PRIVACY_OFF">{!! __('web.SETTINGS_PRIVACY_OFF') !!}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="activity-privacy-session" type="radio" name="activity_privacy" @if(auth()->user()->activity_privacy == 2) checked="checked" @endif value="2">
                                <label class="cbx radio" for="activity-privacy-on"></label>
                                <label for="activity-privacy-session" data-translate-text="SETTINGS_PRIVACY_INCOGNITO">{!! __('web.SETTINGS_PRIVACY_INCOGNITO') !!}</label>
                            </li>
                        </ul>
                    </div>
                    <div id="settings-notification-container" class="control-group preferences-group">
                        <h2 data-translate-text="SETTINGS_NOTIFICATION_TITLE">{{ __('web.SETTINGS_NOTIFICATION_TITLE') }}</h2>
                        <ul id="user-notification-prefs">
                            <li>
                                <input class="hide custom-checkbox" id="notif_follower" type="checkbox" name="notif_follower" @if(auth()->user()->notif_follower) checked="checked" @endif>
                                <label class="cbx" for="notif_follower"></label>
                                <label class="lbl" for="notif_follower" data-translate-text="SETTINGS_NOTIFICATION_FOLLOWS">{{ __('web.SETTINGS_NOTIFICATION_FOLLOWS') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="notif_playlist" type="checkbox" name="notif_playlist" @if(auth()->user()->notif_playlist) checked="checked" @endif>
                                <label class="cbx" for="notif_playlist"></label>
                                <label class="lbl" for="notif_playlist" data-translate-text="SETTINGS_NOTIFICATION_PLAYLIST">{{ __('web.SETTINGS_NOTIFICATION_PLAYLIST') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="notif_shares" type="checkbox" name="notif_shares" @if(auth()->user()->notif_shares) checked="checked" @endif>
                                <label class="cbx" for="notif_shares"></label>
                                <label class="lbl" for="notif_shares" data-translate-text="SETTINGS_NOTIFICATION_SHARE">{{ __('web.SETTINGS_NOTIFICATION_SHARE') }}</label>
                            </li>
                            <li>
                                <input class="hide custom-checkbox" id="notif_features" type="checkbox" name="notif_features" @if(auth()->user()->notif_features) checked="checked" @endif>
                                <label class="cbx" for="notif_features"></label>
                                <label class="lbl" for="notif_features" data-translate-text="SETTINGS_NOTIFICATION_FEATURE">{{ __('web.SETTINGS_NOTIFICATION_FEATURE') }}</label>
                            </li>
                        </ul>
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