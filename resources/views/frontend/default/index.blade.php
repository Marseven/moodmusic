<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::tag('description') !!}
    {!! MetaTag::tag('keywords') !!}
    {!! MetaTag::get('image') ? MetaTag::tag('image') : '' !!}
    {!! MetaTag::openGraph() !!}
    {!! MetaTag::twitterCard() !!}

    <meta http-equiv="Content-type" content="text/html;charset={{ config('settings.charset', 'utf-8') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1,IE=9,10">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="google-signin-client_id" content="{{ config('settings.google_client_id') }}">
    <link id="favicon" rel="icon" href="{{ asset('skins/default/images/favicon.png') }}" type="image/png" sizes="16x16 32x32">
    <link rel="stylesheet" href="{{ asset('skins/default/css/bootstrap.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/swiper.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/select2.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/jqueryui.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/app.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/cart.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/comments.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/reaction.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/jquery-contextmenu.css?version=' . env('APP_VERSION')) }}">
    <link rel="stylesheet" href="{{ asset('skins/default/css/loading.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/theme.css?version=' . env('APP_VERSION')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('skins/default/css/custom.css?version=' . env('APP_VERSION')) }}" type="text/css">

    @if(is_array(config('modules.css')))
        @foreach(config('modules.css') as $css)
            <link rel="stylesheet" href="{{ asset($css) }}?version={{ env('APP_VERSION') }}" type="text/css">
        @endforeach
    @endif

    @if(config('settings.captcha'))
        <meta name="recaptcha-key" content="{{ config('settings.recaptcha_public_key') }}" />
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('settings.recaptcha_public_key') }}"></script>
    @endif
    <script type="text/javascript">
        var GLOBAL = {
            youtube_player: {{ intval(config('settings.module_youtube', true)) }},
            disabled_registration: {{ intval(config('settings.disable_register')) }},
            dob_signup: {{ intval(config('settings.dob_signup')) }},
            hide_video_player: {{ intval(config('settings.hide_youtube_player')) }},
            logged_landing: {{ intval(config('settings.logged_landing')) }},
            live_meta_data: {{ intval(env('FEED_LIVE_METADATA', 0)) }},
            track_skip_limit: {{ intval(\App\Models\Role::getValue('option_track_skip_limit')) }},
            ad_support: {{ intval(\App\Models\Role::getValue('ad_support')) }},
            ad_frequency: {{ intval(\App\Models\Role::getValue('ad_frequency')) }},
            hd_stream: {{ intval(\App\Models\Role::getValue('option_hd_stream')) }},
            allow_artist_claim: {{ intval(config('settings.allow_artist_claim', 1)) }}
        };
    </script>
    <script src="{{ asset('js/core.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
</head>
<body class="@if((isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'true') || (config('settings.dark_mode', true) && ! isset($_COOKIE['darkMode'])))  dark-theme @endif @if(env('MEDIA_AD_MODULE') == 'true') media-ad-enabled @endif">
<div id="fb-root"></div>
<div id="header-container">
    <div id="logo" class="desktop">
        <a href="{{ route('frontend.homepage') }}" class="logo-link"></a>
    </div>
    <div id="header-search-container" class="desktop">
        <form id="header-search">
            <span class="prediction"></span>
            <input class="search" name="q" value="" autocomplete="off" type="text" placeholder="{{ __('web.SEARCH_FOR_MUSIC') }}">
            <svg class="icon search" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
        </form>
        <div class="tooltip suggest hide">
            <div class="search-suggest-content-scroll">
                <div id="search-suggest-content-container"></div>
            </div>
        </div>
    </div>
    <div id="header-user-assets" class="session desktop">
        <div id="header-account-group" class="user-asset hide">
            <a id="profile-button" class="">
                <img class="profile-img" width="16" height="16">
                <span class="caret"></span>
            </a>
        </div>
        @if(! config('settings.disable_register'))
            <a id="header-signup-btn" class="create-account" data-translate-text="BECOME_A_MEMBER">{{ __('web.BECOME_A_MEMBER') }}</a>
        @endif
        <div id="account-buttons" class="user-asset">
            <div class="btn-group no-border-left">
                <a id="settings-button" class="btn">
                    <svg height="29" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/>
                    </svg>
                </a>
                <a id="upload-button" href="{{ route('frontend.auth.upload') }}" class="btn upload-music hide">
                    <svg height="29" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                    </svg>
                </a>
                <a id="notification-button" class="btn hide">
                    <span id="header-notification-pill" class="notification-pill hide">
                        <span id="header-notification-count" class="notification-count">0</span>
                    </span>
                    <svg height="29" viewBox="0 0 24 24" width="15" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                    </svg>
                </a>
                @if(config('settings.module_store', true))
                    <a id="cart-button" data-action="show-cart" class="btn hide">
                        <span class="header-cart-notification-pill notification-pill hide">
                            <span class="notification-count">0</span>
                        </span>
                        <svg height="29" width="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M307.286,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024c8.285,0,15.024-6.74,15.024-15.024C322.31,284.298,315.571,277.558,307.286,277.558z"/><path d="M187.186,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024s15.024-6.74,15.024-15.024C202.21,284.298,195.47,277.558,187.186,277.558z"/><path d="M512,97.433H63.541l-4.643-59.324H0V68.11h31.153l25.793,329.548h38.067c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.117,53.117c29.289,0,53.118-23.829,53.118-53.117c0-8.281-1.907-16.123-5.301-23.117h130.727c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.118,53.117c29.289,0,53.117-23.829,53.117-53.117c0-8.281-1.907-16.123-5.301-23.117h36.558L512,97.433z M352.311,292.583c0,24.827-20.199,45.025-45.025,45.025c-24.827,0-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.323,0.917,15.024,2.587v-62.661h-90.099v105.099c0,24.827-20.199,45.025-45.025,45.025s-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.322,0.917,15.024,2.587v-92.662h150.101V292.583z"/></svg>
                    </a>
                @endif
            </div>
        </div>
        <a id="header-login-btn" class="login" data-translate-text="SIGN_IN">{{ __('web.SIGN_IN') }}</a>
    </div>

    <!-- mobile nav  -->
    <div id="header-nav-btn" class="mobile">
        <svg class="menu hide" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24.75 24.75" xml:space="preserve"><path d="M0,3.875c0-1.104,0.896-2,2-2h20.75c1.104,0,2,0.896,2,2s-0.896,2-2,2H2C0.896,5.875,0,4.979,0,3.875z M22.75,10.375H2c-1.104,0-2,0.896-2,2c0,1.104,0.896,2,2,2h20.75c1.104,0,2-0.896,2-2C24.75,11.271,23.855,10.375,22.75,10.375z M22.75,18.875H2c-1.104,0-2,0.896-2,2s0.896,2,2,2h20.75c1.104,0,2-0.896,2-2S23.855,18.875,22.75,18.875z"></path></svg>
        <svg class="back hide" width="24" height="24" viewBox="0 0 24 24"><path d="M19.7 11H7.5l5.6-5.6L11.7 4l-8 8 8 8 1.4-1.4L7.5 13h12.2z"></path></svg>
    </div>
    <div id="header-nav-logo" class="mobile">
    </div>
    <div id="header-user-menu" class="mobile">
        <svg class="un-auth" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
        <img class="user-auth hide">
    </div>
    @if(config('settings.module_store', true))
        <div id="header-cart-menu" data-action="show-cart" class="mobile">
            <span class="header-cart-notification-pill notification-pill hide">
                <span class="notification-count">0</span>
            </span>
            <svg height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M307.286,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024c8.285,0,15.024-6.74,15.024-15.024C322.31,284.298,315.571,277.558,307.286,277.558z"/><path d="M187.186,277.558c-8.284,0-15.024,6.74-15.024,15.024c0,8.284,6.74,15.024,15.024,15.024s15.024-6.74,15.024-15.024C202.21,284.298,195.47,277.558,187.186,277.558z"/><path d="M512,97.433H63.541l-4.643-59.324H0V68.11h31.153l25.793,329.548h38.067c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.117,53.117c29.289,0,53.118-23.829,53.118-53.117c0-8.281-1.907-16.123-5.301-23.117h130.727c-3.394,6.992-5.301,14.835-5.301,23.117c0,29.289,23.829,53.117,53.118,53.117c29.289,0,53.117-23.829,53.117-53.117c0-8.281-1.907-16.123-5.301-23.117h36.558L512,97.433z M352.311,292.583c0,24.827-20.199,45.025-45.025,45.025c-24.827,0-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.323,0.917,15.024,2.587v-62.661h-90.099v105.099c0,24.827-20.199,45.025-45.025,45.025s-45.025-20.199-45.025-45.025c0-24.828,20.199-45.025,45.025-45.025c5.267,0,10.322,0.917,15.024,2.587v-92.662h150.101V292.583z"/></svg>
        </div>
    @endif
    <div id="header-settings-menu" class="mobile">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"/><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"/></svg>
    </div>
    <div id="header-search-menu" class="mobile">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
    </div>
    <div id="header-nav-title" class="mobile"></div>
</div>

<!-- ajax cart, version 1.2.0 -->
<div class="cart">
    <div role="menu" class="dropdown-menu dropdown-menu--xs-full">
        <div class="container">
            <a class="icon-close cart__close" data-action="cart-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                <span>Close</span>
            </a>
            <div class="cart__top" data-translate-text="RECENT_ADDED_ITEMS">{{ __('web.RECENT_ADDED_ITEMS') }}</div>

            <ul id="cart-items"></ul>
            <div class="cart__bottom">
                <div class="cart__total">{{ __('web.SUBTOTAL') }} <span>{{ config('settings.currency', 'USD') }} 0.00</span></div>
                <a class="btn btn-primary btn-checkout" href="{{ route('frontend.cart') }}" data-translate-text="CHECKOUT">{{ __('web.CHECKOUT') }}</a>
                <!--
                    <a class="btn btn--ys btn-secondary" href="{{ route('frontend.cart') }}">View Cart</a>
                -->
            </div>
        </div>
    </div>
</div>

<!-- mobile search and side menu  and login box-->
<div id="sticky_header" class="mobile">
    <div class="sticky_wrapper">
        <div class="left_menu">
            <a class="sticky-menu-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24.75 24.75" xml:space="preserve"><path d="M0,3.875c0-1.104,0.896-2,2-2h20.75c1.104,0,2,0.896,2,2s-0.896,2-2,2H2C0.896,5.875,0,4.979,0,3.875z M22.75,10.375H2c-1.104,0-2,0.896-2,2c0,1.104,0.896,2,2,2h20.75c1.104,0,2-0.896,2-2C24.75,11.271,23.855,10.375,22.75,10.375z M22.75,18.875H2c-1.104,0-2,0.896-2,2s0.896,2,2,2h20.75c1.104,0,2-0.896,2-2S23.855,18.875,22.75,18.875z"></path></svg>
            </a>
        </div>
        <div class="sticky_search">
            <input type="text" id="sticky_search" placeholder="Search songs, artists, playlists.." autocomplete="off">
            <span class="s_icon">
                    <svg class="icon" viewBox="0 0 13.141 14.398" xmlns="http://www.w3.org/2000/svg"><path data-name="Path 144" d="M12.634 14.3a.4.4 0 0 1-.3-.129l-3.58-3.926-.223.172a5.152 5.152 0 0 1-3.068 1.029A5.546 5.546 0 0 1 .1 5.762 5.513 5.513 0 0 1 5.463.1a5.513 5.513 0 0 1 5.363 5.662 5.889 5.889 0 0 1-1.26 3.646l-.183.236 3.535 3.882a.486.486 0 0 1 0 .643.391.391 0 0 1-.284.131zM5.463 1a4.657 4.657 0 0 0-4.51 4.762 4.643 4.643 0 0 0 4.51 4.761 4.644 4.644 0 0 0 4.51-4.761A4.644 4.644 0 0 0 5.463 1z"></path></svg>
                </span>
        </div>
        <div class="right_menu">
            <a id="static-header-user-menu" class="login_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
            </a>
        </div>
    </div>
</div>

<aside id="sideMenu">
    <div id="side-logo" class="mobile">
        <a href="{{ route('frontend.homepage') }}" data-action="sidebar-dismiss">
            <img src="{{ asset('skins/default/images/favicon.png') }}">
            <span data-translate-text="HOME">{{ __('web.HOME') }}</span>
        </a>
    </div>
    <ul id="aside_ul">
        <li class="side-menu-home desktop">
            <a href="{{ route('frontend.homepage') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                <span data-translate-text="HOME">{{ __('web.HOME') }}</span>
            </a>
        </li>
        <li class="side-menu-discover">
            <a href="{{ route('frontend.discover') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 10c-3.86 0-7 3.14-7 7h2c0-2.76 2.24-5 5-5s5 2.24 5 5h2c0-3.86-3.14-7-7-7zm0-4C5.93 6 1 10.93 1 17h2c0-4.96 4.04-9 9-9s9 4.04 9 9h2c0-6.07-4.93-11-11-11z"/></svg>
                <span data-translate-text="DISCOVER">{{ __('web.DISCOVER') }}</span>
            </a>
        </li>
        @if(config('settings.module_community', true))
            <li class="side-menu-community">
                <a href="{{ route('frontend.community') }}">
                    <svg height="24" width="24" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m128.636 362.74-.908-.055c-7.128-.432-14.002-1.741-20.53-3.796l-45.624 78.997c-4.18 7.237-1.637 16.493 5.653 20.582l44.91 25.187c7.164 4.018 16.229 1.53 20.336-5.582l66.61-115.333z"/><g><path d="m381.991 28.656-135.527 82.972v238.94l135.698 80.615c10.002 5.942 22.671-1.264 22.671-12.895v-376.84c0-11.721-12.844-18.913-22.842-12.792z"/><g><path d="m48.53 280.042v-100.761c0-5.774.625-11.399 1.782-16.826h-21.816c-15.738 0-28.496 12.754-28.496 28.487v77.439c0 15.733 12.758 28.487 28.496 28.487h21.808c-1.153-5.442-1.774-11.067-1.774-16.826z"/><path d="m216.454 332.74h-86.908c-28.17-1.708-51.006-24.537-51.006-52.698v-100.76c0-28.161 22.836-50.989 51.006-49.28h86.908z"/></g></g></g><g><path d="m470.72 352.291c-3.84 0-7.68-1.465-10.61-4.394l-30.269-30.259c-5.86-5.857-5.86-15.355 0-21.213 5.859-5.857 15.361-5.857 21.22 0l30.269 30.259c5.86 5.857 5.86 15.355 0 21.213-2.93 2.929-6.77 4.394-10.61 4.394z"/><path d="m440.45 172.291c-3.841 0-7.68-1.464-10.61-4.394-5.86-5.857-5.86-15.355 0-21.213l30.269-30.259c5.86-5.858 15.36-5.858 21.22 0 5.859 5.857 5.859 15.355 0 21.213l-30.269 30.259c-2.929 2.929-6.77 4.394-10.61 4.394z"/><path d="m496.995 247.161h-42.807c-8.287 0-15.005-6.716-15.005-15s6.718-15 15.005-15h42.807c8.287 0 15.005 6.716 15.005 15s-6.718 15-15.005 15z"/></g></g></svg>
                    <span data-translate-text="COMMUNITY">{{ __('web.COMMUNITY') }}</span>
                </a>
            </li>
        @endif
        @if(config('settings.module_podcast', true))
            <li class="side-menu-podcasts">
                <a href="{{ route('frontend.podcasts') }}">
                    <svg height="24" viewBox="0 0 48 58" width="24" xmlns="http://www.w3.org/2000/svg"><circle id="Oval" cx="25" cy="25" r="7"/><path id="Shape" d="m41 25c.0043835 3.8369583-1.381053 7.5456651-3.9 10.44-.8816497-1.0158408-1.9600185-1.8424768-3.17-2.43 4.3150268-4.8070778 4.0504913-12.1673491-.5984984-16.6522513-4.6489898-4.4849021-12.0140134-4.4849021-16.6630032 0-4.6489897 4.4849022-4.9135252 11.8451735-.5984984 16.6522513-1.2099815.5875232-2.2883503 1.4141592-3.17 2.43-4.63398704-5.3707457-5.17622072-13.1522935-1.3318892-19.1138252 3.8443314-5.9615317 11.1563967-8.67818511 17.9606345-6.67291902 6.8042379 2.00526612 11.4743714 8.25317422 11.4712547 15.34674422z"/><path id="Shape" d="m49 25c-.0020711 7.5935583-3.6000328 14.7375524-9.7 19.26l.03-.14c.3603606-1.6531373.2915082-3.3710057-.2-4.99 5.7270739-5.7172575 7.4444796-14.3223043 4.3508714-21.8000042-3.0936081-7.47769995-10.3885041-12.35425664-18.4808714-12.35425664s-15.38726325 4.87655669-18.48087142 12.35425664c-3.09360816 7.4776999-1.37620243 16.0827467 4.35087142 21.8000042-.4915082 1.6189943-.5603606 3.3368627-.2 4.99l.03.14c-9.07806131-6.7351009-12.22594977-18.8917939-7.5576155-29.1864253 4.66833428-10.29463137 15.887313-15.93634235 26.9351117-13.54491516 11.0477988 2.39142719 18.9293761 12.16768026 18.9225038 23.47134046z"/><path id="Shape" d="m29.556 36h-9.112c-1.8151552-.0000102-3.5327417.8217028-4.6717465 2.2350168s-1.576974 3.2662842-1.1912535 5.0399832l3.077 14.15c.1998936.9191918 1.0133242 1.5748485 1.954 1.575h10.776c.9406758-.0001515 1.7541064-.6558082 1.954-1.575l3.077-14.15c.3857205-1.773699-.0522487-3.6266692-1.1912535-5.0399832s-2.8565913-2.235027-4.6717465-2.2350168zm-11.026 9.242-.5-2c-.0959435-.3498693.0051249-.72426.2641012-.9783171.2589762-.2540571.635236-.3479281.9832013-.2452935.3479652.1026346.6130526.385675.6926975.7396106l.5 2c.1334945.5356925-.1923791 1.0782184-.728 1.212-.0791715.0196698-.1604221.0297422-.242.03-.4589705-.0001268-.8589292-.3126718-.97-.758zm2.712 6.728c-.0791715.0196698-.1604221.0297422-.242.03-.4585975-.0005854-.8580197-.3130332-.969-.758l-.531-2.117c-.1024987-.3519028-.0042666-.731682.2560049-.9897522.2602716-.2580703.6408715-.353073.991891-.2475891s.616204.3945496.6911041.7533413l.53 2.117c.1335558.5353845-.1917847 1.0777678-.727 1.212zm9.258-2.845-.529 2.117c-.111161.44569-.5116567.7583324-.971.758-.0819126-.0001775-.1635037-.0102505-.243-.03-.5356209-.1337816-.8614945-.6763075-.728-1.212l.53-2.117c.0749001-.3587917.3400846-.6478574.6911041-.7533413s.7316194-.0104812.991891.2475891c.2602715.2580702.3585036.6378494.2560049.9897522zm1.471-5.883-.5 2c-.111161.44569-.5116567.7583324-.971.758-.0819126-.0001775-.1635037-.0102505-.243-.03-.5356209-.1337816-.8614945-.6763075-.728-1.212l.5-2c.1436734-.5239217.6793531-.8373021 1.2064607-.7057969.5271075.1315052.8528058.6597861.7335393 1.1897969z"/></svg>
                    <span data-translate-text="PODCASTS">{{ __('web.PODCASTS') }}</span>
                </a>
            </li>
        @endif
        <li class="side-menu-trending">
            <a href="{{ route('frontend.trending') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                <span data-translate-text="TRENDING">{{ __('web.TRENDING') }}</span>
            </a>
        </li>
        @if(config('settings.module_store', true))
            <li class="side-menu-store">
                <a href="{{ route('frontend.store') }}">
                    <svg width="24" height="24" viewBox="0 0 510.538 510.538" xmlns="http://www.w3.org/2000/svg"><path d="m178.269 393.07c-8.271 0-15 6.729-15 15s6.729 15 15 15c8.154 0 14.797-6.543 14.983-14.653v-.694c-.186-8.11-6.829-14.653-14.983-14.653z"/><circle cx="304.289" cy="375.668" r="15"/><path d="m460.269 121.538h-85v75c0 8.284-6.716 15-15 15s-15-6.716-15-15v-75h-180v75c0 8.284-6.716 15-15 15s-15-6.716-15-15v-75h-85c-8.284 0-15 6.716-15 15v329c0 24.813 20.187 45 45 45h350c24.813 0 45-20.187 45-45v-329c0-8.284-6.716-15-15-15zm-110.98 254.13c0 24.813-20.187 45-45 45s-45-20.187-45-45 20.187-45 45-45c5.259 0 10.305.915 15 2.58v-58.55l-96.036 23.743s-.051 112.453-.127 113.063c-1.762 23.213-21.2 41.566-44.857 41.566-24.813 0-45-20.187-45-45s20.187-45 45-45c5.252 0 10.293.913 14.983 2.574v-78.946c0-6.897 4.704-12.906 11.4-14.562l126.036-31.16c4.479-1.107 9.215-.092 12.846 2.75 3.632 2.843 5.754 7.199 5.754 11.811.001.002.001 119.886.001 120.131z"/><path d="m165.269 120c0-49.626 40.374-90 90-90s90 40.374 90 90v1.539h30v-1.539c0-66.168-53.832-120-120-120s-120 53.832-120 120v1.539h30z"/></svg>
                    <span data-translate-text="STORE">{{ __('web.STORE') }}</span>
                </a>
            </li>
        @endif
        @if(config('settings.module_radio', true))
            <li class="side-menu-radio">
                <a href="{{ route('frontend.radio') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3.24 6.15C2.51 6.43 2 7.17 2 8v12c0 1.1.89 2 2 2h16c1.11 0 2-.9 2-2V8c0-1.11-.89-2-2-2H8.3l8.26-3.34L15.88 1 3.24 6.15zM7 20c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-8h-2v-2h-2v2H4V8h16v4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    <span data-translate-text="RADIO">{{ __('web.RADIO') }}</span>
                </a>
            </li>
        @endif
        @if(config('settings.module_blog', true))
            <li class="side-menu-blog">
                <a href="{{ route('frontend.blog') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/></svg>
                    <span data-translate-text="HOME_BLOG">{{ __('web.HOME_BLOG') }}</span>
                </a>
            </li>
        @endif
        @if(is_array(config('modules.menu')))
            @foreach(config('modules.menu') as $menu)
                <li class="side-menu-{{ $menu['short_name'] }}">
                    <a href="{{ route($menu['route']) }}">
                        {!! $menu['icon'] !!}
                        <span data-translate-text="{{ $menu['text'] }}">{{ __('web.' . $menu['text']) }}</span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
    <div id="sidebar" class="scrollable">
        <div id="sidebar-playlists">
            <div id="sidebar-playlists-title" class="sidebar-title">
                <span data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}</span>
            </div>
            <div id="sidebar-no-playlists" class="hide sidebar-empty">
                <p class="label" data-translate-text="SIDEBAR_NO_PLAYLISTS">{{ __('web.SIDEBAR_NO_PLAYLISTS') }}</p>
                <a class="btn btn-secondary new-playlist create-playlist" data-translate-text="CREATE_A_PLAYLIST">{{ __('web.CREATE_PLAYLIST') }}</a>
            </div>
            <div id="playlists-failed" class="hide">
                <p data-translate-text="POPUP_ERROR_LOAD_PLAYLISTS">{{ __('web.POPUP_ERROR_LOAD_PLAYLISTS') }}</p>
            </div>
            <div id="sidebar-have-playlists">
                <div class="sidebar-item">
                    <a class="sidebar-link create-playlist" data-action="sidebar-dismiss">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14 10H2v2h12v-2zm0-4H2v2h12V6zm4 8v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM2 16h8v-2H2v2z"/>
                        </svg>
                        <span data-translate-text="CREATE_PLAYLIST">{{ __('web.CREATE_PLAYLIST') }}</span>
                    </a>
                </div>
                <a class="collapser" data-target="#sidebar-playlists-grid">
                    <span class="caret"></span>
                    <span class="title" data-translate-text="YOURS">{{ __('web.YOURS') }}</span>
                </a>
                <div id="sidebar-playlists-grid" class="collapsable">
                    <!-- sidebar playlist item --->
                    <div class="sidebar-playlist hide">
                        <div class="inner">
                            <div class="icon playlist">
                                <div class="img-container">
                                    <img>
                                </div>
                            </div>
                            <a class="btn play play-object" data-type="playlist">
                                <div class="icon play">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                        <path d="M0 0h24v24H0z" fill="none"/>
                                    </svg>
                                </div>
                            </a>
                            <a class="playlist-link">
                                <span class="name"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <a class="collapser" data-target="#sidebar-collab-playlists-grid">
                    <span class="caret"></span>
                    <span class="title" data-translate-text="COLLABORATIVE">{{ __('web.COLLABORATIVE') }}</span>
                </a>
                <div id="sidebar-collab-playlists-grid" class="collapsable"></div>
                <a class="collapser" data-target="#sidebar-subbed-playlists-grid">
                    <span class="caret"></span>
                    <span class="title" data-translate-text="SUBSCRIBED">{{ __('web.SUBSCRIBED') }}</span>
                </a>
                <div id="sidebar-subbed-playlists-grid" class="collapsable"></div>
            </div>
        </div>
    </div>
</aside>
<div class="contact-sidebar">
    <div id="sidebar-community">
        <div class="sidebar-title">
            <span class="community-link" data-translate-text="COMMUNITY">{{ __('web.COMMUNITY') }}</span>
            <span class="drag-handle"></span>
        </div>
        <div id="chat-disconnected" class="hide">
            <p data-translate-text="MANATEE_DISCONNECTED">{{ __('web.MANATEE_DISCONNECTED') }}</p> <a id="manatee-reconnect" class="btn" data-translate-text="MANATEE_RECONNECT">{{ __('web.MANATEE_RECONNECT') }}</a> </div>
        <div id="friends-failed" class="hide">
            <p data-translate-text="POPUP_ERROR_LOAD_FRIENDS">{{ __('web.POPUP_ERROR_LOAD_FRIENDS') }}</p>
        </div>
        <div id="sidebar-friends" class="friend-list hide">

        </div>
        <div id="sidebar-no-friends" class="sidebar-empty">
            <p class="label" data-translate-text="SIDEBAR_NO_FRIENDS">{{ __('web.SIDEBAR_NO_FRIENDS') }}</p>
            <a class="btn btn-secondary share share-profile" data-translate-text="SHARE_YOUR_PROFILE" data-type="user">{{ __('web.SHARE_YOUR_PROFILE') }}</a>
        </div>
        <div id="sidebar-invite-cta" class="hide">
            <p data-translate-text="INVITE_YOUR_FRIENDS">{{ __('web.INVITE_YOUR_FRIENDS') }}</p>
            <a class="btn invite-friends share" data-type="user" data-translate-text="INVITE_FRIENDS">{{ __('web.INVITE_FRIENDS') }}</a>
        </div>
    </div>

    <div id="sidebar-offline-msg" class="hide"> <span data-translate-text="OFFLINE_MSG">{{ __('web.OFFLINE_MSG') }}</span><br> <a id="sidebar-go-online" data-translate-text="GO_ONLINE">{{ __('web.GO_ONLINE') }}</a> <i id="close-offline-msg" class="icon ex icon-ex-l-gray-flat"></i> </div>
    <div id="sidebar-filter-container" class="hide">
        <form class="search-bar">
            <svg class="icon search" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
            <span data-translate-text="FILTER" class="placeholder">{{ __('web.FILTER') }}</span>
            <input type="text" autocomplete="off" value="" name="q" id="sidebar-filter" class="filter">
            <a class="clear-filter">
                <svg class="icon" height="16px" width="16px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531 0 68.382812 26.628906 132.667969 74.980469 181.019531 48.351562 48.351563 112.640625 74.980469 181.019531 74.980469s132.667969-26.628906 181.019531-74.980469c48.351563-48.351562 74.980469-112.636719 74.980469-181.019531 0-68.378906-26.628906-132.667969-74.980469-181.019531zm-70.292969 256.386719c9.761719 9.765624 9.761719 25.59375 0 35.355468-4.882812 4.882813-11.28125 7.324219-17.679687 7.324219s-12.796875-2.441406-17.679687-7.324219l-75.367188-75.367187-75.367188 75.371093c-4.882812 4.878907-11.28125 7.320313-17.679687 7.320313s-12.796875-2.441406-17.679687-7.320313c-9.761719-9.765624-9.761719-25.59375 0-35.355468l75.371093-75.371094-75.371093-75.367188c-9.761719-9.765624-9.761719-25.59375 0-35.355468 9.765624-9.765625 25.59375-9.765625 35.355468 0l75.371094 75.367187 75.367188-75.367187c9.765624-9.761719 25.59375-9.765625 35.355468 0 9.765625 9.761718 9.765625 25.589844 0 35.355468l-75.367187 75.367188zm0 0"></path></svg>
            </a>
        </form>
        <a id="hide-sidebar-filter">
            <i class="icon ex icon-ex-l-gray-flat"></i>
        </a>
    </div>
    <div id="sidebar-utility">
        <a id="filter-toggle" class="sidebar-util">
            <svg class="icon search" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
        </a>
        <a class="new-playlist sidebar-util create-playlist">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        </a>
        <a id="sidebar-settings" class="sidebar-util">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"></path><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"></path></svg>
        </a>
        <a id="toggle-sidebar" class="sidebar-util last">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8V4l8 8-8 8v-4H4V8z"/></svg>
        </a>
    </div>
</div>
<div id="user-settings-menu" class="mobile">
    <div class="user-section">
        <div class="inner-us">
            <a class="back-arrow ripple-wrap" data-icon="arrowBack">
                <svg width="26" height="26" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M19.7 11H7.5l5.6-5.6L11.7 4l-8 8 8 8 1.4-1.4L7.5 13h12.2z"></path>
                </svg>
            </a>

            <div class="user-subscription-helper after-login hide">
                <div class="user-subscription-text" data-translate-text="USER_SUBSCRIBED_DESCRIPTION">{{ __('web.USER_SUBSCRIBED_DESCRIPTION') }}</div>
                <a href="{{ route('frontend.settings.subscription') }}" class="user-subscription-button" data-translate-text="SUBSCRIBE">{{ __('web.SUBSCRIBE') }}</a>
            </div>

            <div class="user-auth user-info hide">
                <div class="info-profile">
                    <p class="info-name"></p>
                    <a class="info-link">{{ __('web.VIEW_PROFILE') }}</a>
                </div>
                <a class="info-artwork">
                    <img>
                </a>
            </div>
            <div class="setting-wrap separate">
                <ul class="user_options">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <a href="{{ route('frontend.homepage') }}" data-translate-text="HOME">{{ __('web.HOME') }}</a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z"/></svg>
                        <a class="show-lightbox" data-lightbox="lightbox-locale" data-translate-text="LANGUAGE">{{ __('web.LANGUAGE') }}</a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        <a class="show-lightbox" data-lightbox="lightbox-feedback" data-translate-text="FEEDBACK">{{ __('web.FEEDBACK') }}</a>
                    </li>
                    <li>
                        <span class="th-ic _ic" data-icon="theme_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path d="M9 2c-1.05 0-2.05.16-3 .46 4.06 1.27 7 5.06 7 9.54 0 4.48-2.94 8.27-7 9.54.95.3 1.95.46 3 .46 5.52 0 10-4.48 10-10S14.52 2 9 2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        </span>
                        <span data-translate-text="BLACK_THEME">{{ __('web.DARK_MODE') }}</span>
                        <label class="switch"><input type="checkbox" class="themeSwitch" @if((isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] == 'true') || (config('settings.dark_mode', true) && ! isset($_COOKIE['darkMode']))) checked="checked" @endif><span class="slider round"></span></label>
                    </li>
                </ul>
            </div>
            <div class="user-auth hide user-setting-wrap separate">
                <ul class="user_options">
                    <li>
                        <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"></path>
                        </svg>
                        <a href="{{ route('frontend.auth.upload') }}">
                            <span>{{ __('web.UPLOAD') }}</span>
                        </a>
                    </li>
                    <li id="artist-management-link" class="hide">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/></svg>
                        <a href="{{ route('frontend.auth.user.artist.manager') }}">
                            <span>{{ __('web.CONTEXT_ARTIST_MANAGER') }}</span>
                        </a>
                    </li>
                    <li>
                        <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"></path>
                        </svg>
                        <a class="auth-notifications-link">
                            <span>{{ __('web.NOTIFICATIONS') }}</span>
                        </a>
                        <span class="header-notification-count">0</span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 5h-3v5.5c0 1.38-1.12 2.5-2.5 2.5S10 13.88 10 12.5s1.12-2.5 2.5-2.5c.57 0 1.08.19 1.5.51V5h4v2zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6z"/></svg>
                        <a class="auth-my-music-link">{{ __('web.MY_MUSIC') }}</a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" xml:space="preserve"><path d="M497,128.533H15c-8.284,0-15,6.716-15,15V497c0,8.284,6.716,15,15,15h482c8.284,0,15-6.716,15-15V143.533C512,135.249,505.284,128.533,497,128.533z M340.637,332.748l-120.5,80.334c-2.51,1.672-5.411,2.519-8.321,2.519c-2.427,0-4.859-0.588-7.077-1.774c-4.877-2.611-7.922-7.693-7.922-13.226V239.934c0-5.532,3.045-10.615,7.922-13.225c4.879-2.611,10.797-2.324,15.398,0.744l120.5,80.334c4.173,2.781,6.68,7.465,6.68,12.48S344.81,329.967,340.637,332.748z"/><path d="M448.801,64.268h-385.6c-8.284,0-15,6.716-15,15s6.716,15,15,15h385.6c8.284,0,15-6.716,15-15S457.085,64.268,448.801,64.268z"/><path d="M400.6,0H111.4c-8.284,0-15,6.716-15,15s6.716,15,15,15h289.2c8.284,0,15-6.716,15-15S408.884,0,400.6,0z"/></svg>
                        <a class="auth-my-playlists-link">{{ __('web.MY_PLAYLISTS') }}</a>
                    </li>
                    <li>
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 435.104 435.104" xml:space="preserve"><circle cx="154.112" cy="377.684" r="52.736"/><path d="M323.072,324.436L323.072,324.436c-29.267-2.88-55.327,18.51-58.207,47.777c-2.88,29.267,18.51,55.327,47.777,58.207c3.468,0.341,6.962,0.341,10.43,0c29.267-2.88,50.657-28.94,47.777-58.207C368.361,346.928,348.356,326.924,323.072,324.436z"/><path d="M431.616,123.732c-2.62-3.923-7.059-6.239-11.776-6.144h-58.368v-1.024C361.476,54.637,311.278,4.432,249.351,4.428C187.425,4.424,137.22,54.622,137.216,116.549c0,0.005,0,0.01,0,0.015v1.024h-43.52L78.848,50.004C77.199,43.129,71.07,38.268,64,38.228H0v30.72h51.712l47.616,218.624c1.257,7.188,7.552,12.397,14.848,12.288h267.776c7.07-0.041,13.198-4.901,14.848-11.776l37.888-151.552C435.799,132.019,434.654,127.248,431.616,123.732z M249.344,197.972c-44.96,0-81.408-36.448-81.408-81.408s36.448-81.408,81.408-81.408s81.408,36.448,81.408,81.408C330.473,161.408,294.188,197.692,249.344,197.972z"/><path d="M237.056,118.1l-28.16-28.672l-22.016,21.504l38.912,39.424c2.836,2.894,6.7,4.55,10.752,4.608c3.999,0.196,7.897-1.289,10.752-4.096l64.512-60.928l-20.992-22.528L237.056,118.1z"/></svg>
                        <a class="auth-my-purchased-link">{{ __('web.PURCHASED') }}</a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path fill="none" d="M0 0h20v20H0V0z"></path><path d="M15.95 10.78c.03-.25.05-.51.05-.78s-.02-.53-.06-.78l1.69-1.32c.15-.12.19-.34.1-.51l-1.6-2.77c-.1-.18-.31-.24-.49-.18l-1.99.8c-.42-.32-.86-.58-1.35-.78L12 2.34c-.03-.2-.2-.34-.4-.34H8.4c-.2 0-.36.14-.39.34l-.3 2.12c-.49.2-.94.47-1.35.78l-1.99-.8c-.18-.07-.39 0-.49.18l-1.6 2.77c-.1.18-.06.39.1.51l1.69 1.32c-.04.25-.07.52-.07.78s.02.53.06.78L2.37 12.1c-.15.12-.19.34-.1.51l1.6 2.77c.1.18.31.24.49.18l1.99-.8c.42.32.86.58 1.35.78l.3 2.12c.04.2.2.34.4.34h3.2c.2 0 .37-.14.39-.34l.3-2.12c.49-.2.94-.47 1.35-.78l1.99.8c.18.07.39 0 .49-.18l1.6-2.77c.1-.18.06-.39-.1-.51l-1.67-1.32zM10 13c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3z"></path></svg>
                        <a href="{{ route('frontend.settings') }}">{{ __('web.SETTINGS') }}</a>
                    </li>
                </ul>
            </div>
            <div class="user-auth hide logout-wrap separate">
                <ul class="user_options">
                    <li class="lgout ripple-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
                        <a id="mobile-user-sign-out">{{ __('web.SIGN_OUT') }}</a>
                    </li>
                </ul>
            </div>
            <div class="un-auth reg-wrap separate">
                <p>{{ __('web.LOGIN_TIP') }}</p>
                <div id="mobile-reg-btn" class="reg_btn red_btn">{{ __('web.LOGIN_REGISTER') }}</div>
            </div>
        </div>
    </div>
</div>

@if(config('settings.landing'))
    @if(auth()->check())
        @if(config('settings.logged_landing'))
            @include('landing.index')
        @endif
    @else
        @include('landing.index')
    @endif
@endif

<div id="main" @if(config('settings.landing') && ! auth()->check() && Route::currentRouteName() == 'frontend.homepage') class="d-none" @endif>
    <div id="page">
        @yield('content')
    </div>
</div>

<div id="notifications"></div>
<!-- notification show up when click to world button -->
<div class="tooltip header-notifications hide">
    <div class="header-notifications-scroll">
        <div id="notifications-container"></div>
    </div>
    <a id="see-more-notifications" class="see-all">
        <span class="label" data-translate-text="HEADER_NOTIFICATION_MORE">{{ __('web.HEADER_NOTIFICATION_MORE') }}</span><span class="caret"></span>
    </a>
</div>
<!-- emoji box -->
<div class="tooltip emoji-tooltip hide">
    <div class="emoji">
        <div class="content emojis-scroll"></div>
    </div>
</div>

@if(env('MEDIA_AD_MODULE') == 'true')
    <div class="next-generation d-flex justify-content-center">
        <div class="next-video justify-content-center align-items-center d-none">
            <div class="video-wrap position-relative">
                <video id="next-video-source" controls autoplay></video>
                <div class="next-video-close">Skip</div>
            </div>
        </div>
        <div class="next-html d-none">
            <div class="next-html-close d-flex justify-content-center align-items-center">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
            </div>
            <div class="next-html-content"></div>
        </div>
    </div>
@endif

@include('loading')
@include('lightbox')
@include('jstemplate')

<!-- Third-Party javascript -->
<script>
    var payment_stripe_publishable_key = '{{ config('settings.payment_stripe_publishable_key') }}';
    var youtube_api_key = '{{ config('settings.youtube_api_key') }}';
    var youtube_search_endpoint = '{{ route('frontend.song.stream.youtube', ['id' => 'SONG_ID']) }}';
</script>
<script src="https://www.gstatic.com/firebasejs/6.1.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.1.0/firebase-database.js"></script>
<script type="text/javascript">
    // Firebase Config
    var config = {
        apiKey: "{{ config('settings.firebase_api_key') }}",
        authDomain: "{{ config('settings.firebase_auth_domain') }}",
        databaseURL: "{{ config('settings.firebase_database_url') }}",
    };
    firebase.initializeApp(config);
</script>
@if(config('settings.payment_stripe'))
    <script src="https://js.stripe.com/v3/" crossorigin="anonymous"></script>
@endif

<!-- polyfill for IE 11 only -->
<script type="text/javascript">
    if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) {
        document.write('<script type="text/javascript" src="{{ asset('js/ie/functions.js') }}"><\/script><script type="text/javascript" src="{{ asset('js/ie/polyfill.min.js') }}"><\/script>');
    }
</script>

<script src="{{ asset('js/utils.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/history.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/route.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/common.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/account.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/comment.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/share.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/nowplaying.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/contextmenu.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/player.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/artist.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/payment.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/artistCore.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/uploadApp.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('js/cart.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('skins/default/js/custom.js?version=' . env('APP_VERSION')) }}" type="text/javascript"></script>
<script src="{{ asset('embed/embed.js?skin=embedplayer10&icon_set=radius&version=' . env('APP_VERSION')) }}" type="text/javascript"></script>

@if(is_array(config('modules.js')))
    @foreach(config('modules.js') as $javascript)
        <script src="{{ asset($javascript) }}?version={{ env('APP_VERSION') }}" type="text/javascript"></script>
    @endforeach
@endif

@if(config('settings.analytic_tracking_code'))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ config('settings.analytic_tracking_code') }}', 'auto');
        ga('send', 'pageview');

    </script>
@endif
</body>
</html>