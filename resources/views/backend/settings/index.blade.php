@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ config('settings.admin_path') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Configure script (use the navigation to access sections)</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 py-3 border-left-info">
                <div class="card-body">
                    Configure General Script Settings, displaying of news and comments, and security system of the script
                </div>
            </div>
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item"><a class="nav-link active" href="#config" data-toggle="pill"><i class="fas fa-fw fa-cog"></i> General</a></li>
                <li class="nav-item"><a href="#modules" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-cubes"></i> {{ __('backend.tabs_gr_modules') }}</a></li>
                <li class="nav-item"><a href="#security" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-lock"></i> Security</a></li>
                <li class="nav-item"><a href="#music" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-music"></i> Music</a></li>
                @if(env('IMPORT_MUSIC_MODULE'))
                    <li class="nav-item"><a href="#import" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-file-import"></i> Import</a></li>
                @endif
                <li class="nav-item"><a href="#podcast" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-rss"></i> Podcast</a></li>
                <li class="nav-item"><a href="#upload" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-cloud"></i> Storage</a></li>
                <li class="nav-item"><a href="#post" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-newspaper"></i> Blog</a></li>
                <li class="nav-item"><a href="#email" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-mail-bulk"></i> E-mail</a></li>
                <li class="nav-item"><a href="#thirdparty" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-key"></i> Authorization</a></li>
                <li class="nav-item"><a href="#comment" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-comment"></i> Comments</a></li>
                <li class="nav-item"><a href="#visitors" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-user"></i> Visitors</a></li>
                <li class="nav-item"><a href="#payment" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-credit-card"></i> Payments</a></li>
            </ul>
            <form method="post" action="">
                @csrf
                <div class="tab-content mt-4" id="myTabContent">
                    <div id="config" class="tab-pane fade show active">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Website URL
                                                <p class="small mb-0">Your website url, can only be changed by editing .env file.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="utf-8" value="{{ env('APP_URL') }}" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Force SSL & HTTPS Redirect.
                                                <p class="small mb-0">Force SSL helps you redirect insecure HTTP traffic to secure HTTPS one without touching any code. Just activate Force SSL and everything will be configured for you and SSL enabled. The entire site will move to HTTPS using your SSL certificate.<br><span class="text-danger"><strong>Only enable this option after you get the domain's ssl certificate installed.</strong></span></p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[force_https]', config('settings.force_https')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Show landing page on the first time user visiting the website.
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[landing]', config('settings.landing')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Show landing even user is logged, the <span class="font-italic text-muted">"Show landing page on the first time user visiting the website."</span> must be enabled to use this feature.
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[logged_landing]', config('settings.logged_landing')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Name of the website
                                                <p class="small mb-0">The name will be displayed at the top of your browser.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[site_title]" value="{{ config('settings.site_title') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Short name of the website
                                                <p class="small mb-0">Set the short name of the website to be published on Navigator and other place.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[short_title]" value="{{ config('settings.short_title') }}" required>
                                            </div>
                                        </div>
                                        -->
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Language
                                                <p class="small mb-0">Select the default language that will be used in the frontend system.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown($languages, "save_con[locale]", config('settings.locale') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Currency
                                                <p class="small mb-0">Select the default currency that will be used in the system.<br><span class="text-danger">Not all currency are supported by Paypal subscription, find out more <a href="https://developer.paypal.com/docs/business/checkout/reference/javascript-sdk/#currency" target="_blank">here</a>. If you choose to use the currency which not supported by Paypal, the system will automatically convert it to USD by the real-time rate taken from <a href="https://exchangeratesapi.io" target="_blank">https://exchangeratesapi.io</a>.</span></p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(trans('currency'), "save_con[currency]", config('settings.currency') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Language encoding
                                                <p class="small mb-0">Specify which character-encoding has been used.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="utf-8" name="save_con[charset]" value="{{ config('settings.charset') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Reaction
                                                <p class="small mb-0">Increase user engagement with powerful features for comments and more!. Reactions is built for user-engagement. It is designed with one purpose in mind and that is it to keep your users on your pages and posts for longer. <span class="text-info">Each comma is a new reactive option.</span></p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="ex: like,wow,love" name="save_con[reactions]" value="{{ config('settings.reactions') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Google Analytics Tracking Code
                                                <p class="small mb-0">The analytics information you'll get by using the tracking code is strictly confidential and is for your own eyes only!</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="ex: UA-113452478-1" name="save_con[analytic_tracking_code]" value="{{ config('settings.analytic_tracking_code') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Deep link scheme (for mobile app only)
                                                <p class="small mb-0">When a clicked link or programmatic request invokes a web URI intent, the Android, iOS system tries each of the following actions, in sequential order, until the request succeeds.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="ex: musicengine" name="save_con[deeplink_scheme]" value="{{ config('settings.deeplink_scheme', 'musicengine') }}">
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Website description
                                                <p class="small mb-0">A short description of your site. Keep it under 200 characters!</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[description]" value="{{ config('settings.description') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Meta keywords
                                                <p class="small mb-0">Use comma to separate keywords.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" rows="2" name="save_con[keyword]" required>{{ config('settings.keyword') }}</textarea>
                                            </div>
                                        </div>
                                        -->
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Skin Template
                                                <p class="small mb-0">Set the default theme for your site.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown($skins, "save_con[skin]", config('settings.skin') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Color scheme
                                                <p class="small mb-0">Set color scheme of the theme, it can be dark or light.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array(0 => 'Light Mode', 1 => 'Dark Mode'), "save_con[dark_mode]", config('settings.dark_mode', true) ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Shut down the website
                                                <p class="small mb-0">Turn a site into the offline state to conduct technical work.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">

                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[site_offline]', config('settings.site_offline')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The reason of the site shutdown:
                                                <p class="small mb-0">The message to show in shutdown mode of the site.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" rows="6" name="save_con[offline_reason]">{{ config('settings.offline_reason') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable monetization, allow to pay artist per stream system (for song and podcast's episode).</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[monetization]', config('settings.monetization')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Integrate Google analytics to admin panel.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[google_analytics]', config('settings.google_analytics')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        -->
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable dark mode on admin panel</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[admin_dark_mode]', config('settings.admin_dark_mode')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="modules" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Enable Youtube player for songs which not have any tagged audio.
                                                <p class="small mb-0 text-danger">Disable this option will turn off the ability to playing songs which imported from spotify.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_youtube]', config('settings.module_youtube', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">{{ __('backend.opt_sys_mod_com') }}
                                                <p class="small mb-0">{{ __('backend.opt_sys_mod_comd') }}</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_community]', config('settings.module_community', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">{{ __('backend.opt_sys_mod_pod') }}
                                                <p class="small mb-0">{{ __('backend.opt_sys_mod_podd') }}</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_podcast]', config('settings.module_podcast', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">{{ __('backend.opt_sys_mod_store') }}
                                                <p class="small mb-0">{{ __('backend.opt_sys_mod_stored') }}</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_store]', config('settings.module_store', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">{{ __('backend.opt_sys_mod_radio') }}
                                                <p class="small mb-0">{{ __('backend.opt_sys_mod_radiod') }}</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_radio]', config('settings.module_radio', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">{{ __('backend.opt_sys_mod_blog') }}
                                                <p class="small mb-0">{{ __('backend.opt_sys_mod_blog') }}</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[module_blog]', config('settings.module_blog', true)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="security" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">File of Administration Panel
                                                <p class="small mb-0">You can change the the Administration Panel location. By default it is "admin".</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="admin_path" value="{{ env('APP_ADMIN_PATH', 'admin') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Method of authorization in Administration Panel
                                                <p class="small mb-0">When extended authorization method is used in Administration Panel, the password will be required in each new session in the Administration Panel. It guarantees the safety of the Administration Panel in case if your Cookies are stolen. Warning! This authorization method will work only if your PHP is installed as the Apache module. Therefore it is recommend to clarify the PHP operating mode on your host provider before the activation.</p></label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Standard method", "1" => "Advanced method"), "save_con[extra_login]", config('settings.extra_login') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Block embedding website into iframes
                                                <p class="small mb-0">When this setting is enabled, the engine automatically blocks displaying your website if it is embedded into an iframe on another website, thereby preventing such attacks as clickjacking.</p>
                                            </label>

                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[block_iframes]', config('settings.block_iframes')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">IP list of users allowed to login to Administration Panel of the script
                                                <p class="small mb-0">You can block specific IP addresses to authorize to the Administration Panel. Attention: Be careful when change this setting. Access to the Administration Panel will be available only for the specified IP addresses. You can specify several addresses (one per line). You can specify either the full IP address and the mask, for example: 192.48.25.71 or 129.42.*.* If you don't need to set any IP restrictions, live this field blank.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" rows="4" name="save_con[admin_allowed_ip]">{{ config('settings.admin_allowed_ip') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Maximum number of failed authorizations
                                                <p class="small mb-0">Set the maximum number of incorrect login and password entries. After exceeding this limit the user will be automatically blocked by IP for 20 minutes. This step helps to prevent the guessing of user account’s password by hackers. If you do not want to configure this setting, then leave it blank.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[login_log]" value="{{ config('settings.login_log', 5) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Time out (in minutes) after several attempts of password entries.
                                                <p class="small mb-0">Specify the period of time during which the possibility of authorization for the visitor will be blocked by IP after the specified number of incorrect password entries. This timeout is specified in minutes. We do not recommend to make this value less than 20 minutes.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[login_ban_timeout]" value="{{ config('settings.login_ban_timeout', 20) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">IP change monitor
                                                <p class="small mb-0">Medium Level - automatically resets login for all users with access to the Administration Panel when they change the IP address. High Level - automatically resets login for totally all users when they change the IP address.</p></label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "None", "1" => "Medium Level", "2" => "High Level"), "save_con[extra_login]", config('settings.log_hash') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Reset the authorization key every time you enter?
                                                <p class="small mb-0">If 'Enabled', then authorization key will be reset every time a user logs in to the website. This will make it impossible to login using the same username using more than one device.</p></label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[log_hash]', config('settings.log_hash')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Enable Google Captcha (v3)
                                                <p class="small mb-0">Set the security code type to be used on the website. You can use a security code of the reCAPTCHA (v3 only) service.</p></label>
                                            <div class="col-sm-4">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[captcha]', config('settings.captcha')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">reCAPTCHA (v3) public key:
                                                <p class="small mb-0">You can get the key here: http://www.google.com/recaptcha Note: It is highly recommended to register on this service and generate the unique pair of keys with a permission to use it only on your domain. The use of a standard pair of keys does not give a proper protection from the spam robots.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[recaptcha_public_key]" value="{{ config('settings.recaptcha_public_key') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">reCAPTCHA (v3) secret key:
                                                <p class="small mb-0">Secret key for communication between your site and reCAPTCHA.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[recaptcha_secret_key]" value="{{ config('settings.recaptcha_secret_key') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="upload" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">From now on Save the audio (mp3, hls) at
                                                <p class="small mb-0">Select the location which system will store audio.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown($storage, "save_con[storage_audio_location]", config('settings.storage_audio_location', 'public') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">From now on Save the artwork (song, album, cover, user profile picture ...) at
                                                <p class="small mb-0">Select the location which system will store all artwork.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown($storage, "save_con[storage_artwork_location]", config('settings.storage_artwork_location', 'public') ) !!}
                                            </div>
                                        </div>
                                        <div class="alert alert-info" role="alert">
                                            Amazon S3 Settings
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Bucket Name
                                                <p class="small mb-0">Your Amazon S3 bucket name</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="amazon_s3_bucket_name" value="{{ env('AWS_BUCKET') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Access Key ID
                                                <p class="small mb-0">Key to access your bucket</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('AWS_ACCESS_KEY_ID') }}" name="amazon_s3_key_id">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Secret
                                                <p class="small mb-0">Secret key to access your bucket</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('AWS_SECRET_ACCESS_KEY') }}" name="amazon_s3_secret">
                                            </div>

                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Amazon S3 Gateway
                                                <p class="small mb-0">To reduce data latency in your applications, Amazon S3 Service offer a regional endpoint to make your requests. An endpoint is a URL that is the entry point for a web service.</p>
                                            </label>
                                            <div class="col-sm-4">

                                                <input class="form-control" value="{{ env('AWS_DEFAULT_REGION') }}" name="amazon_s3_region" placeholder="us-east-1">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Amazon S3 URL
                                                <p class="small mb-0">The url of your Amazone S3.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('AWS_URL') }}" name="amazon_s3_url">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Amazon S3 Signed Time
                                                <p class="small mb-0">Grant time-limited permission to download the objects, in minutes.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ intval(config('settings.s3_signed_time', 5)) }}" name="save_con[s3_signed_time]">
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-3" role="alert">
                                            Firebase real time database settings. The script using Firebase to handle user online status and current playing song. Firebase for real time database is FREE for starting (100 Simultaneous connections). Firebase offer 1GB free, and the system just using few bytes for each user.
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Api Key
                                                <p class="small mb-0">Your Firebase API key</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[firebase_api_key]" value="{{ config('settings.firebase_api_key') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Auth Domain
                                                <p class="small mb-0">Your Firebase Auth Domain</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[firebase_auth_domain]" value="{{ config('settings.firebase_auth_domain') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Database Url
                                                <p class="small mb-0">Your Firebase Database Url</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[firebase_database_url]" value="{{ config('settings.firebase_database_url') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="post" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Number of posts per page
                                                <p class="small mb-0">Number of the post articles that will be displayed per page.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[num_post_per_page]" value="{{ config('settings.num_post_per_page') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Post publication restriction after registering
                                                <p class="small mb-0">Enter the number of days after registration during which users are not allowed to publish post on the website. If you don’t want to set this limitation set 0.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.post_restriction') }}" name="save_con[post_restriction]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Time format for post:
                                                <p class="small mb-0">Help on the operation of functions</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.post_time_format') }}" name="save_con[post_time_format]">
                                            </div>

                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Show post pages navigation
                                                <p class="small mb-0">Choose the type of post pages navigation. You can disable navigation or show it at the top or bottom, or at the top and bottom at the same time.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Disabled", "1" => "Full page number", "2" => "Older and Newer"), "save_con[post_navigation]", config('settings.post_navigation') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Sort order for posts
                                                <p class="small mb-0">Select the sort order for posts.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Descending", "1" => "Ascending"), "save_con[post_sort_order]", config('settings.post_sort_order') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Sort criterion in a catalog
                                                <p class="small mb-0">Select the criterion for post in a catalog.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "By publication date", "1" => "By views", "2" => "Alphabetical", "3" => "By number of comments"), "save_con[post_sort_order]", config('settings.post_sort_order') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Automatic generation of 'description' and 'Keywords' meta tags for publication.
                                                <p class="small mb-0">You can enable auto-complete of 'description' and 'Keywords' meta tags for publication. If these fields have not been filled during the article publication on the website, the script will automatically create them.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[post_auto_meta]', config('settings.post_auto_meta')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Show post on the website, publication date of which has not yet come.
                                                <p class="small mb-0">If you enable this setting, news articles will be shown on the website, publication date of which has not yet come. If you disable this feature, news articles will appear as their publication date will come.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[post_without_scheduling]', config('settings.post_without_scheduling')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Send e-mail notification as new article is posted.
                                                <p class="small mb-0">If 'Enabled', the corresponding notification will be sent to the e-mail address specified in settings as article which is awaiting for moderation is posted on the website.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[post_email_notification]', config('settings.post_email_notification')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Show post published in the sub-categories
                                                <p class="small mb-0">If 'Enabled' and the parent category is viewed, then post of its subcategories will be also whown. Otherwise, you will need to specify several categories during the publication of the news article.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[post_show_sub]', config('settings.post_show_sub')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The maximum allowable size of the original image
                                                <p class="small mb-0">There are two ways to use this setting:<br>
                                                    - The first one: You need to enter the allowable dimensions in pixels of any side of the original image. For example: 800.
                                                    <br>
                                                    - The second one: You specify the width and height of the original image in the format Width x Height. For example: 800x600
                                                    <br>
                                                    If the size is greater, the original image will be automatically reduced to the specified size, otherwise the original image size will not be changed. You can set 0 if you want remain the original image.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_max_up_side]" value="{{ config('settings.image_max_up_side', 0) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The default settings for the original image
                                                <p class="small mb-0">If you set the maximum size of the original image in the settings above, you can specify which side will be used to perform original image control by default.</p></label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "By The Bigger Side", "1" => "By Height", "2" => "By Width"), "save_con[extra_login]", config('settings.log_hash') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Allow watermarks:
                                                <p class="small mb-0">When you upload or copy the image to the server, a watermark will be applied on this image.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[image_allow_watermark]', config('settings.image_allow_watermark')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The overlapping area for watermark
                                                <p class="small mb-0">Specify the area on the original image to apply the watermark.</p></label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Upper left corner", "1" => "Upper right corner", "2" => "Lower left corner", "3" => "Lower right corner"), "save_con[image_watermark_seite]", config('settings.image_watermark_seite', 3) ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The minimum image size for applying a watermark:
                                                <p class="small mb-0">The minimum size of any side of the image for applying a watermark.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_watermark_minimum]" value="{{ config('settings.image_watermark_minimum', 500) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Size of a thumbnail:
                                                <p class="small mb-0">There are two ways to use this setting:
                                                    <br>
                                                    - The first one: You specify the maximum size of any side of the uploadable image in pixels. Thumbnail will be created as this size will be exceeded. For example: 400
                                                    <br>
                                                    - The second one: You specify the width and height of the thubmnail in the format Width x Height. For example: 100x100.
                                                    <br>
                                                    You can set 0 if you do not want to create a thumbnails for the uploaded images.
                                                    <br>
                                                </p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_max_thumbnail]" value="{{ config('settings.image_max_thumbnail', 800) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Default settings for the thumbnails of the uploaded images
                                                <p class="small mb-0">Specify the side which will be used to create an image thumbnail by default.</p></label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "By the bigger side", "1" => "By Width", "2" => "By height"), "save_con[image_t_seite]", config('settings.image_t_seite', 0) ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Compression quality for .jpg image:
                                                <p class="small mb-0">Compression quality for uploaded images whith JPEG extension.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_jpeg_quality]" value="{{ config('settings.image_jpeg_quality', 90) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Auto-clear images:
                                                <p class="small mb-0">Enter the number of days. The image will be removed after this period if it has been uploaded to the server for a publication that has not been published.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_auto_clear]" value="{{ config('settings.image_auto_clear', 2) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="import" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Import songs, artists, album from Spotify then use Youtube to ripoff musis file
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[automate]', config('settings.automate')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Library will use to download youtube file.
                                                <p class="small mb-0 text-danger">You can choose between PHP library or <a href="https://youtube-dl.org/" target="_blank">youtube-dl</a>.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                {!! makeDropDown(array("0" => "php (not stable)", "1" => "youtube-dl (recommended)"), "save_con[import_youtube_library]", config('settings.import_youtube_library', 0) ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Path of youtube-dl @if(isset($youtube_dl)) {!! $youtube_dl  !!} @endif
                                                <p class="small mb-0">If you are going to use youtube-dl, you can special the path to the library in this form.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') }}" name="save_con[youtube_dl_path]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="music" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Import songs, artists, album from Spotify
                                                <p class="small mb-0 text-danger">Use Spotify API to import all music data, you will need to upload the mp3 files for imported songs later, other wise the player will try to play it by use Youtube API. <br> <strong>NOTE: BY ENABLING THIS FEATURE, THE SYSTEM WILL IMPORT A HUGE OF MUSIC DATA INTO YOUR DATABASE, DO NOT ENABLE IT IF YOU DON'T NEED AUTOMATE FUNCTION.</strong></p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[automate]', config('settings.automate')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Hide Youtube video player
                                                <p class="small mb-0 text-danger">If Spotify is enable and youtube is using to play the music, you can choose to hide the video by default.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[hide_youtube_player]', config('settings.hide_youtube_player')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow artist to claim there profile
                                                <p class="small mb-0">You can allow artist to claim thier profile on your site, this also allow them to upload/manage their songs/albums on the site.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[allow_artist_claim]', config('settings.allow_artist_claim', 1)) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Number of songs per swiper
                                                <p class="small mb-0">Number of songs that will be displayed per channel swiper.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.num_song_per_swiper') }}" name="save_con[num_song_per_swiper]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Number of songs per page
                                                <p class="small mb-0">Number of songs that will be displayed per page (like artist, profile page).</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.num_song_per_page') }}" name="save_con[num_song_per_page]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Number of related songs
                                                <p class="small mb-0">Set the number of related song to be shown in the song page.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.num_related_song') }}" name="save_con[num_related_song]">
                                            </div>
                                        </div>
                                        @if(env('CHANNEL_STYLE') == 'true')
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Channel style
                                                <p class="small mb-0">Choose the display style of channels.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown( array(0 => "Swiper", 1 => 'Grid'), "save_con[channel_grid_style]", config('settings.channel_grid_style') ) !!}
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Music publication restriction after registering
                                                <p class="small mb-0">Enter the number of days after registration during which users are not allowed to uploading music on the website. If you don’t want to set this limitation set 0.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.day_upload_restriction') }}" name="save_con[day_upload_restriction]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Send e-mail notification to as new song/album is uploaded/created
                                                <p class="small mb-0">If 'Enabled', the corresponding notification will be sent to the e-mail address specified in settings as music media which is awaiting for moderation is posted on the website.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[upload_notification]', config('settings.upload_notification')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Minimum character number in share content
                                                <p class="small mb-0">Set the minimum number of characters for the share content to be posted on the website. If you don’t want to set restrictions on the minimum number of characters, enter 0.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.share_min_chars') }}" name="save_con[share_min_chars]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Maximum number of characters in the share content
                                                <p class="small mb-0">Set the maximum number of characters that the user can use to write a share content.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.share_max_chars') }}" name="save_con[share_max_chars]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the largest audio file size allowed to upload (in kilobytes)
                                                <p class="small mb-0">Enter the maximum file size that is allowed to be uploaded. Size should be specified in kilobytes. For example, you should specify 2048 to limit the file size by 2 megabytes. If you want to remove the restriction, then enter 0 in the settings.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[max_audio_file_size]" value="{{ config('settings.max_audio_file_size') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the minimum mp3 bitrate allowed to upload
                                                <p class="small mb-0">Like 128 Kpbs etc...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[min_audio_bitrate]" value="{{ config('settings.min_audio_bitrate') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the maximum mp3 bitrate allowed to upload
                                                <p class="small mb-0">Like 320 Kpbs etc...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[max_audio_bitrate]" value="{{ config('settings.max_audio_bitrate') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the minimum duration of audio file in second allowed to upload
                                                <p class="small mb-0">Like 300 etc (in second)...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[min_audio_duration]" value="{{ config('settings.min_audio_duration') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the maximum duration of audio file in second allowed to upload
                                                <p class="small mb-0">Like 3000 etc (in second)...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[max_audio_duration]" value="{{ config('settings.max_audio_duration') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Flooding protection when you uploading song
                                                <p class="small mb-0">Set the number of seconds during which the user can not re-upload song. To turn off the protection and control, enter 0.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[audio_upload_flood]" value="{{ config('settings.audio_upload_flood') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Size of a album art:
                                                <p class="small mb-0">Your music collection's album art can be stored in a variety of sizes:
                                                    <br>
                                                    Historically the most common sizes for artwork have been 300x300 pixels and, laterly, 500x500. These are the sizes most music information databases store album artwork at. However, with newer, larger screen displays these images are beginning to look rather inadequate.
                                                    <br>
                                                    - Personal MP3 players e.g. iPod, smartphones - aim for small/medium size images of around 300x300 pixels. The higher resolution players are around 800 pixels in one dimension, so 300x300 leaves just under half the screen for the artwork.
                                                    <br>
                                                    - Tablets e.g. iPad - Aim for medium/large images of 500x500 and larger.
                                                    <br>
                                                    - Computer based software e.g. iTunes, Winamp - depending on the size of your player window, aim for medium/large images of 500x500 are larger.
                                                    <br>
                                                    - Large TVs - Aim for large images of 900x900 pixels and larger. Again, this depends on the resolution of your TV's software and the screen real estate afforded to your artwork.
                                                    <br>
                                                    <span class="text-info">This is the setting for music section, such as song, album, artist, playlist artwork.</span>
                                                </p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_artwork_max]" value="{{ config('settings.image_artwork_max', 500) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Size of a album artwork conversion:
                                                <p class="small mb-0">Base on purpose, the system will automatically create some of conversion from the main album art, in this setting you can specially set for each of them.<br>
                                                    <span class="text-info">This is the setting for music section, such as song, album, artist, playlist artwork.</span>
                                                </p>
                                            </label>
                                            <div class="col-sm-4">
                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_sm]" value="{{ config('settings.image_artwork_sm', 60) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Small</span>
                                                    </span>
                                                </div>
                                                <br>

                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_md]" value="{{ config('settings.image_artwork_md', 120) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Medium</span>
                                                    </span>
                                                </div>
                                                <br>

                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_lg]" value="{{ config('settings.image_artwork_lg', 300) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Large</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Compression quality for .jpg image:
                                                <p class="small mb-0">Compression quality for album art which uploaded in JPEG extension.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_jpeg_quality]" value="{{ config('settings.image_jpeg_quality', 90) }}">
                                            </div>
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Direct stream (only mp3)
                                                <p class="small mb-0 text-danger">This is the fastest way to steam a audio file (without protecting the file, script will stream the file directly by it's url).</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[direct_stream]', config('settings.direct_stream')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable waveform
                                                <p class="small mb-0 text-danger">Generate a waveform of the audio when possible.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[waveform]', config('settings.waveform')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-5">
                                            These following features require a working FFMpeg install. You will need both FFMpeg and FFProbe binaries to use it. Be sure that these binaries can be located with system PATH to get the benefit of the binary detection, otherwise you should have to explicitly give the binaries path on load.
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Use FFMpeg to handle the audio.
                                                <p class="small mb-0 text-danger">FFMPEG can be use to convert the audio files into various bitrate and create hls version of the audio.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[ffmpeg]', config('settings.ffmpeg')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to upload all kind of audios, such as M4A, MP3, WAV, AIFF, FLAC, AAC, OGG, MP2, MP4 and WMA. The system will get it converted  to mp3 file type automatically.
                                                <p class="small mb-0 text-danger">If not enabled, only mp3 file type can be uploaded.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_all_types]', config('settings.audio_all_types')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable HLS streaming
                                                <p class="small mb-0">HTTP Live Streaming (HLS) is a protocol that segments media files for optimization during streaming. HLS enables media players to play segments with the highest quality resolution that is supported by their network connection during playback. In HLS Encryption the audio files are encrypted using a secure AES-128 algorithm. The AES-128 is the only publicly available security algorithm that is used by the NSA for encrypting its top-secret classified information.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_stream_hls]', config('settings.audio_stream_hls')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable HLS Encryption for protecting audio files from being stolen.
                                                <div class="alert alert-danger mb-0">HLS AES-128 encryption refers to music streams using HLS streaming protocol wherein the music files are encrypted using the AES-128 algorithms. <a class="badge badge-secondary" href="https://www.vdocipher.com/blog/2017/08/hls-streaming-hls-encryption-secure-hls-drm/" target="_blank">Click here</a> for more info. PLEASE READ THE WIKI BE CAREFUL BEFORE ENABLE THIS OPTION, THE HLS CONVERSION MIGHT FAILED IF THE CONFIG WAS WRONG.</div>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_hls_drm]', config('settings.audio_hls_drm')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Keep a copy of the mp3 file after the HLS has been generated.
                                                <p class="small mb-0">The system will store versions of the mp3, and it will be used for backup, download, or given stream on browsers which are not supporting HLS streaming.
                                                    <br>
                                                    <span class="text-danger">Note: The download feature will not be working if you disable this option while enabling HLS streaming.</span>
                                                </p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_mp3_backup]', config('settings.audio_mp3_backup')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to store a original FLAC/WAV file type of the audio
                                                <p class="small mb-0">FLAC/WAV is a musical file format that offers bit-perfect copies of CDs but at half the size. This can be use for post purchased download. This option only works when the artist uploading a FLAC/WAV file.
                                                    <br>
                                                    <span class="text-danger">Note: Option "Allow to upload all kind of audios" must be enabled..</span>
                                                </p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_mp3_backup]', config('settings.audio_mp3_backup')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the default bitrate will be stored
                                                <p class="small mb-0">This feature required FFMPEG module being enabled. Like 128 Kbps...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[audio_default_bitrate]" value="{{ config('settings.audio_default_bitrate') }}" placeholder="Eg: 128">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to storage high definition audio
                                                <p class="small mb-0">The system will store a high bitrate version of the audio.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_hd]', config('settings.audio_hd')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the high definition bitrate will be stored (HD version of the audio)
                                                <p class="small mb-0">Like 320 Kbps...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[audio_hd_bitrate]" value="{{ config('settings.audio_hd_bitrate') }}" placeholder="Eg: 320">
                                            </div>
                                        </div>

                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Create short preview of the audio
                                                <p class="small mb-0">This feature help to create short preview for each uploading songs. This can be use to given free user preview the song before subscription or buying the song.
                                                </p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[audio_mp3_preview]', config('settings.audio_mp3_preview')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the preview duration in seconds
                                                <p class="small mb-0">For example: 30...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[audio_preview_duration]" value="{{ config('settings.audio_preview_duration', 30) }}" placeholder="Eg: 128">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the preview  bitrate will be stored
                                                <p class="small mb-0">For example: 128Kbps...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[audio_preview_bitrate]" value="{{ config('settings.audio_preview_bitrate', 128) }}" placeholder="Eg: 128">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="podcast" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Podcast publication restriction after registering
                                                <p class="small mb-0">Enter the number of days after registration during which users are not allowed to adding podcast on the website. If you don’t want to set this limitation set 0.</p></label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.podcast_day_upload_restriction') }}" name="save_con[podcast_day_upload_restriction]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Send e-mail notification to as new show/episode is created/uploaded
                                                <p class="small mb-0">If 'Enabled', the corresponding notification will be sent to the e-mail address specified in settings as podcast media which is awaiting for moderation is posted on the website.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[podcast_upload_notification]', config('settings.podcast_upload_notification')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the largest audio file size allowed to upload (in kilobytes)
                                                <p class="small mb-0">Enter the maximum file size that is allowed to be uploaded. Size should be specified in kilobytes. For example, you should specify 2048 to limit the file size by 2 megabytes. If you want to remove the restriction, then enter 0 in the settings.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_max_audio_file_size]" value="{{ config('settings.podcast_max_audio_file_size') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the minimum mp3 bitrate allowed to upload
                                                <p class="small mb-0">Like 128 Kpbs etc...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_min_audio_bitrate]" value="{{ config('settings.podcast_min_audio_bitrate') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the maximum mp3 bitrate allowed to upload
                                                <p class="small mb-0">Like 320 Kpbs etc...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_max_audio_bitrate]" value="{{ config('settings.podcast_max_audio_bitrate') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the minimum duration of audio file in second allowed to upload
                                                <p class="small mb-0">Like 300 etc (in second)...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_min_audio_duration]" value="{{ config('settings.podcast_min_audio_duration') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the maximum duration of audio file in second allowed to upload
                                                <p class="small mb-0">For example enter 7200 will limit the podcast's episode at 2 hours</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_max_audio_duration]" value="{{ config('settings.podcast_max_audio_duration') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Flooding protection when you uploading episode
                                                <p class="small mb-0">Set the number of seconds during which the user can not re-upload episodes. To turn off the protection and control, enter 0.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_audio_upload_flood]" value="{{ config('settings.podcast_audio_upload_flood') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Size of a album art:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_artwork_max]" value="{{ config('settings.image_artwork_max', 500) }}">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Size of a album artwork conversion:
                                                <p class="small mb-0">Base on purpose, the system will automatically create some of conversion from the main album art, in this setting you can specially set for each of them.<br>
                                                    <span class="text-info">This is the setting for podcast artwork only.</span>
                                                </p>
                                            </label>
                                            <div class="col-sm-4">
                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_sm]" value="{{ config('settings.podcast_image_artwork_sm', 60) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Small</span>
                                                    </span>
                                                </div>
                                                <br>

                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_md]" value="{{ config('settings.podcast_image_artwork_md', 120) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Medium</span>
                                                    </span>
                                                </div>
                                                <br>

                                                <div class="input-group col-xs-12 position-relative">
                                                    <input name="save_con[image_artwork_lg]" value="{{ config('settings.podcast_image_artwork_lg', 300) }}" class="form-control" type="text">
                                                    <span class="input-group-btn">
                                                        <span class="browse btn btn-secondary input-lg">Large</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Compression quality for .jpg image:
                                                <p class="small mb-0">Compression quality for album art which uploaded in JPEG extension.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_image_jpeg_quality]" value="{{ config('settings.podcast_image_jpeg_quality', 90) }}">
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-5">
                                            These following features require a working FFMpeg install. You will need both FFMpeg and FFProbe binaries to use it. Be sure that these binaries can be located with system PATH to get the benefit of the binary detection, otherwise you should have to explicitly give the binaries path on load.
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Use FFMpeg to handle the audio.
                                                <p class="small mb-0 text-danger">FFMPEG can be use to convert the audio files into various bitrate and create hls version of the audio.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[podcast_ffmpeg]', config('settings.podcast_ffmpeg')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to upload all kind of audios, such as M4A, MP3, WAV, AIFF, FLAC, AAC, OGG, MP2, MP4 and WMA. The system will get it converted  to mp3 file type automatically.
                                                <p class="small mb-0 text-danger">If not enabled, only mp3 file type can be uploaded.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[podcast_audio_all_types]', config('settings.podcast_audio_all_types')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable HLS streaming
                                                <p class="small mb-0">HTTP Live Streaming (HLS) is a protocol that segments media files for optimization during streaming. HLS enables media players to play segments with the highest quality resolution that is supported by their network connection during playback. In HLS Encryption the audio files are encrypted using a secure AES-128 algorithm. The AES-128 is the only publicly available security algorithm that is used by the NSA for encrypting its top-secret classified information.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[podcast_audio_stream_hls]', config('settings.podcast_audio_stream_hls')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable HLS Encryption for protect streaming audio from being stolen.
                                                <p class="small mb-0 text-danger">HLS AES-128 encryption refers to music streams using HLS streaming protocol wherein the music files are encrypted using the AES-128 algorithms. <a href="https://www.vdocipher.com/blog/2017/08/hls-streaming-hls-encryption-secure-hls-drm/" target="_blank">Click here</a> for more info.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[podcast_audio_hls_drm]', config('settings.podcast_audio_hls_drm')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Define the mp3 bitrate of the episode wthat will be stored
                                                <p class="small mb-0">This feature required FFMPEG module being enabled. 96kbps mono is the ideal balance between audio quality and file size for podcasts without music, and 128bkps stereo is great for podcasts with music.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[podcast_audio_default_bitrate]" value="{{ config('settings.podcast_audio_default_bitrate') }}" placeholder="Eg: 128">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="email" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">System e-mail address of the administrator:
                                                <p class="small mb-0">Enter your e-mail address of the website administrator. Service script messages will be sent on behalf of this address, for example notifications about the new personal message, etc. Also, this address will be used by the site administrators to get system notifications, for example, new comments notification, etc...</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.admin_mail', 'admin@admin.com') }}" name="save_con[admin_mail]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Sender's e-mail title for the outgoing letters
                                                <p class="small mb-0">You can specify a title for the outgoig letters which will be shown in the sender's mail. For examle, you can specify your website's short name.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.mail_title') }}" name="save_con[mail_title]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail driver
                                                <p class="small mb-0">Define what site e-mail system is used. PHP Mail is preferable.<br>
                                                    <span class="text-info">To do the same as mail() PHP function does, in most cases you should pick PHP Mail option. Host, user, password, port and encryption are not needed. At this point, you may check if it already works, but sometimes the next step is also needed. So you have to Set a new "sendmail" option in config/mail.php. You can check sendmail_path at phpinfo(), but it's usually this one <strong>"/usr/sbin/sendmail -t -i"</strong></span></p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown( array("sendmail" => "PHP Mail()", "smtp" => "SMTP"), "mail_driver", env('MAIL_DRIVER', 'smtp') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail host
                                                <p class="small mb-0">Default — localhost. For gmail please enter smtp.gmail.com</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('MAIL_HOST', 'localhost') }}" name="mail_host">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail port
                                                <p class="small mb-0">Usually — 25. For Gmail it is 587</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" placeholder="25" value="{{ env('MAIL_PORT') }}" name="mail_port">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail username
                                                <p class="small mb-0">Not required if 'localhost' is used. Enter youremail@gmail.com if you use Gmail</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('MAIL_USERNAME') }}" name="mail_username">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail password
                                                <p class="small mb-0">Not required if 'localhost' is used. Create app password if you are using your Gmail as a SMTP server</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ env('MAIL_PASSWORD') }}" name="mail_password">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Mail Secure
                                                <p class="small mb-0">Select TLS if use Gmail.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown( array("ssl" => "SSL", "tls" => "TLS"), "mail_encryption", env('MAIL_ENCRYPTION') ) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Enable MailChimp Subscribe
                                                <p class="small mb-0">MailChimp Subscribe is a highly customizable, free signup form builder. All users enabled <span class="text-info">"Keep me informed of cool new features and updates."</span> option will be automatically inserted to your mailchimp's subscribers list.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">
                                                    {!! makeCheckBox('save_con[mailchimp]', config('settings.mailchimp')) !!}
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="thirdparty" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Youtube API Key
                                        <p class="small mb-0">Use an youtube api key to stream music from youtube.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="save_con[youtube_api_key]" value="{{ config('settings.youtube_api_key') }}">
                                    </div>
                                </div>

                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 col-9 mb-0">Enable authorization using Facebook network
                                        <p class="small mb-0">You can enable or disable authorization using Facebook network support.</p>
                                    </label>
                                    <div class="col-sm-4 col-3">
                                        <label class="switch">{!! makeCheckBox('save_con[facebook_login]', config('settings.facebook_login')) !!}<span class="slider round"></span></label>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Facebook APP ID
                                        <p class="small mb-0">Specify your application ID in Facebook network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="facebook_app_id" value="{{ env('FACEBOOK_APP_ID') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Facebook APP Secret
                                        <p class="small mb-0">Specify the secure key of your application in Facebook network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="facebook_app_secret" value="{{ env('FACEBOOK_APP_SECRET') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Facebook APP Redirect
                                        <p class="small mb-0">This should be your site url.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="facebook_app_callback_url" value="{{ env('FACEBOOK_APP_CALLBACK_URL') ? env('FACEBOOK_APP_CALLBACK_URL') : route('frontend.auth.login.socialite.callback', ['service' => 'facebook']) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 col-9 mb-0">Enable authorization using Google
                                        <p class="small mb-0">You can enable or disable authorization using Google support.</p>
                                    </label>
                                    <div class="col-sm-4 col-3">
                                        <label class="switch">{!! makeCheckBox('save_con[google_login]', config('settings.google_login')) !!}<span class="slider round"></span></label>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Google Client ID
                                        <p class="small mb-0">Specify your application ID in Google.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="google_client_id" value="{{ env('GOOGLE_CLIENT_ID') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Google Client Secret
                                        <p class="small mb-0">Specify the secure key of your application in Google.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="google_client_secret" value="{{ env('GOOGLE_CLIENT_SECRET') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Google Oauth Redirect
                                        <p class="small mb-0">This should be your site url.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="google_app_callback_url" value="{{ env('GOOGLE_CLIENT_CALLBACK_URL') ? env('GOOGLE_CLIENT_CALLBACK_URL') : route('frontend.auth.login.socialite.callback', ['service' => 'google']) }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 col-9 mb-0">Enable authorization using Twitter
                                        <p class="small mb-0">You can enable or disable authorization using Google support.</p>
                                    </label>
                                    <div class="col-sm-4 col-3">
                                        <label class="switch">{!! makeCheckBox('save_con[twitter_login]', config('settings.twitter_login')) !!}<span class="slider round"></span></label>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Twitter APP ID
                                        <p class="small mb-0">Specify your application ID in Twitter network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="twitter_app_id" value="{{ env('TWITTER_APP_ID') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Twitter APP Secret
                                        <p class="small mb-0">Specify the secure key of your application in Twitter network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="twitter_app_secret" value="{{ env('TWITTER_APP_SECRET') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Twitter APP Redirect url
                                        <p class="small mb-0">This should be your site url.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="twitter_app_callback_url" value="{{ env('TWITTER_APP_CALLBACK_URL') ? env('TWITTER_APP_CALLBACK_URL') : route('frontend.auth.login.socialite.callback', ['service' => 'twitter']) }}">
                                    </div>
                                </div>

                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 col-9 mb-0">Enable authorization using Apple
                                        <p class="small mb-0">You can enable or disable authorization using Apple support.</p>
                                    </label>
                                    <div class="col-sm-4 col-3">
                                        <label class="switch">{!! makeCheckBox('save_con[apple_login]', config('settings.apple_login')) !!}<span class="slider round"></span></label>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Apple Client Id
                                        <p class="small mb-0">Specify your application ID in Apple network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="apple_app_id" value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_ID') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Apple Apple Service Secret
                                        <p class="small mb-0">Specify the secure key of your application in Apple Apple network.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="apple_app_secret" value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_SECRET') }}">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Apple APP Redirect url
                                        <p class="small mb-0">This should be your site url.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="apple_app_callback_url" value="{{ env('SIGN_IN_WITH_APPLE_REDIRECT') ? env('SIGN_IN_WITH_APPLE_REDIRECT') : route('frontend.auth.login.socialite.callback', ['service' => 'apple']) }}">
                                    </div>
                                </div>
                                @if(env('DISCORD_LOGIN'))
                                    <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                        <label class="col-sm-8 col-9 mb-0">Enable authorization using Discord
                                            <p class="small mb-0">You can enable or disable authorization using Apple support.</p>
                                        </label>
                                        <div class="col-sm-4 col-3">
                                            <label class="switch">{!! makeCheckBox('save_con[discord_login]', config('settings.discord_login')) !!}<span class="slider round"></span></label>
                                        </div>
                                    </div>
                                    <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                        <label class="col-sm-8 mb-0">Discord Client Id
                                            <p class="small mb-0">Specify your application ID in Discord network.</p>
                                        </label>
                                        <div class="col-sm-4">
                                            <input class="form-control" name="discord_app_id" value="{{ env('DISCORD_CLIENT_ID') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                        <label class="col-sm-8 mb-0">Discord Client Service Secret
                                            <p class="small mb-0">Specify the secure key of your application in Apple Apple network.</p>
                                        </label>
                                        <div class="col-sm-4">
                                            <input class="form-control" name="discord_app_secret" value="{{ env('DISCORD_CLIENT_SECRET') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                        <label class="col-sm-8 mb-0">Discord APP Redirect url
                                            <p class="small mb-0">This should be your site url.</p>
                                        </label>
                                        <div class="col-sm-4">
                                            <input class="form-control" name="discord_callback_url" value="{{ env('DISCORD_REDIRECT') ? env('DISCORD_REDIRECT') : route('frontend.auth.login.socialite.callback', ['service' => 'discord']) }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div id="comment" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Songs
                                                <p class="small mb-0">Enable or disable comments for songs.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[song_comments]', config('settings.song_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Playlists
                                                <p class="small mb-0">Enable or disable comments for playlists.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[playlist_comments]', config('settings.playlist_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Albums
                                                <p class="small mb-0">Enable or disable comments for albums.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[album_comments]', config('settings.album_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Podcast
                                                <p class="small mb-0">Enable or disable comments for podcast.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[podcast_comments]', config('settings.podcast_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Artists
                                                <p class="small mb-0">Enable or disable comments for artists.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[artist_comments]', config('settings.artist_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Playlists
                                                <p class="small mb-0">Enable or disable comments for playlists.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[playlist_comments]', config('settings.playlist_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment User profile
                                                <p class="small mb-0">Enable or disable comments for user profile.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[user_comments]', config('settings.user_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to comment Activities
                                                <p class="small mb-0">Enable or disable comments for activities.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[activity_comments]', config('settings.activity_comments')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Comments publication restriction after registering
                                                <p class="small mb-0">Enter the number of days after registration during which users are not allowed to publish comments on the website. If you don’t want to set this limitation set 0.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.allow_comments_after') }}" name="save_con[allow_comments_after]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow users to subscribe to comments
                                                <p class="small mb-0">Enables or disables a subscription for comments. If this feature is enabled, user will be able to subscribe to the comments of object (song,artist, etc...) after posting a comment there. He will be notified through e-mail as new comments to this article will be posted.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[subcribe_comment]', config('settings.subcribe_comment')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow comments combination
                                                <p class="small mb-0">Enable or disable combination of comments posted one by one by the same user. If this setting is enabled, all the comments added by a user to the news article during the day will be combined in one comment. It happens if all the comments are added one by one and there are no comment from other users between them.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[combination_comment]', config('settings.combination_comment')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Minimum character number for comments
                                                <p class="small mb-0">Set the minimum number of characters for the comment to be posted on the website. If you don’t want to set restrictions on the minimum number of characters, enter 0.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.comment_min_chars') }}" name="save_con[comment_min_chars]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Maximum number of characters in the comments
                                                <p class="small mb-0">Set the maximum number of characters that the user can use to write a comment.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.comment_max_chars') }}" name="save_con[comment_max_chars]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Number of comments per page
                                                <p class="small mb-0">Specify the number of comments displayed per page.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.comments_per_page') }}" name="save_con[comments_per_page]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Sort order for comments
                                                <p class="small mb-0">Select the sort order for comments</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Ascending", "1" => "Descending"), "save_con[comment_order]", config('settings.comment_order')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Flooding Protection
                                                <p class="small mb-0">Set how many comment user can post per minute, leave 0 = can't post the comment.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.comment_flood') }}" name="save_con[comment_flood]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow indexing of links in the comments by search engines
                                                <p class="small mb-0">If 'Disabled', then search engines will not be allowed to follow the links that will be posted by your users in comments.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[comment_index]', config('settings.comment_index')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Send e-mail notification to the Administrator as new comment is posted
                                                <p class="small mb-0">If 'Enabled', the corresponding notification will be sent to the e-mail address specified in settings as comment is posted on the website.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[comment_notif_admin]', config('settings.comment_notif_admin')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="images" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="visitors" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Authorization method
                                                <p class="small mb-0">You can choose the method of authorization on the website. There are two ways: either using login or using E-mail address.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Use Username", "1" => "Use Email address"), "save_con[authorization_method]", config('settings.authorization_method')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Register a new users in the group
                                                <p class="small mb-0">Select a group where new users will be placed after the registration.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeRolesDropDown("save_con[default_usergroup]", config('settings.default_usergroup', 5)) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Registration method
                                                <p class="small mb-0">Letter with the account activation will not be sent if the "simplified" registration system is used.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                {!! makeDropDown(array("0" => "Simplified", "1" => "Advanced"), "save_con[registration_method]", config('settings.registration_method')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Requires addition information when signing up (such as DOB, location)
                                                <p class="small mb-0">If 'Enabled', then users will need to add addition information during sign up.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[dob_signup]', config('settings.dob_signup')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Default country for signup form
                                                <p class="small mb-0">If 'Requires addition information when signing up' is enabled, you can select the default country which will be automatically before hand.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                {!! makeCountryDropDown('save_con[default_country]', 'form-control select2-active',  config('settings.default_country')) !!}
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Requires gender when signing up
                                                <p class="small mb-0">If 'Enabled', then users will need to add select their gender during sign up.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[gender_signup]', config('settings.gender_signup')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Age restrictions
                                                <p class="small mb-0">If "Requires addition information when signing up" is enabled, you can restrict user's age can sign up to the site. Set 0 to disable this option.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <input class="form-control" value="{{ intval(config('settings.age_restriction')) }}" name="save_con[age_restriction]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Allow to login using social networks
                                                <p class="small mb-0">If 'Enabled', then users will be able to login to your website using social networks and they do not need to be specially registered on your site. You can configure social networks settings in the 'Authorization' tab.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[social_login]', config('settings.social_login')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Disable registration
                                                <p class="small mb-0">If you enable this setting, the internal system of registration will be disabled and only users who have accounts in networks could log into your website.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[disable_register]', config('settings.disable_register')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Security code
                                                <p class="small mb-0">Security code is displayed during the registration to prevent auto-registration.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[register_captcha]', config('settings.register_captcha')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">The maximum number of registered users
                                                <p class="small mb-0">0 if there is no limit</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.users_max') }}" name="save_con[users_max]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Automatically delete registered users
                                                <p class="small mb-0">The number of days missing on the website. The user will be deleted in the end of this period. 0 = no limit.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.delete_inactive_user') }}" name="save_con[delete_inactive_user]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Minimum size of the avatars uploaded to the user profiles:
                                                <p class="small mb-0">Enter the minimum size of the avatars uploaded to the user profiles (in pixels). Value 0 removes the size limit, whereas -1 value sets global avatar uploading restriction.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="save_con[image_avatar_size]" value="{{ config('settings.image_avatar_size', 300) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="payment" class="tab-pane fade">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable Paypal
                                                <p class="small mb-0">PayPal Holdings, Inc. is an American company operating a worldwide online payments system that supports online money transfers and serves as an electronic alternative to traditional paper methods like checks and money orders.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[payment_paypal]', config('settings.payment_paypal')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Paypal Sandbox
                                                <p class="small mb-0">The PayPal Sandbox is a self-contained, virtual testing environment that mimics the live PayPal production environment. It provides a shielded space where you can initiate and watch your application process the requests you make to the PayPal APIs without touching any live PayPal accounts.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[payment_paypal_sandbox]', config('settings.payment_paypal_sandbox')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Enable Stripe
                                                <p class="small mb-0">Stripe is an American technology company based in San Francisco, California Its software allows individuals and businesses to make and receive payments over the Internet. Stripe provides the technical, fraud prevention, and banking infrastructure required to operate online payment systems. Find out more <a href="https://stripe.com/" target="_blank">here</a></p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[payment_stripe]', config('settings.payment_stripe')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 col-9 mb-0">Stripe Test Mode
                                                <p class="small mb-0">Stripe has a <a href="https://stripe.com/docs/keys#test-live-modes" target="_blank">test mode</a> you should use for testing.
                                                    It operates separately from live mode, so you can make changes without affecting your live data.
                                                    Stripe also provides <a href="https://stripe.com/docs/testing" target="_blank">credit card numbers and bank accounts</a> you can use
                                                    to test your integration.</p>
                                            </label>
                                            <div class="col-sm-4 col-3">
                                                <label class="switch">{!! makeCheckBox('save_con[payment_stripe_test_mode]', config('settings.payment_stripe_test_mode')) !!}<span class="slider round"></span></label>
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Stripe Public key
                                                <p class="small mb-0">API keys are meant solely to identify your account with Stripe, they aren’t secret.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.payment_stripe_publishable_key') }}" name="save_con[payment_stripe_publishable_key]">
                                            </div>
                                        </div>
                                        <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                            <label class="col-sm-8 mb-0">Stripe Testing secret API key
                                                <p class="small mb-0">This use for testing only, if you want to make stripe live, please config the secret key in .env file. API keys should be kept confidential and only stored on your own servers. Your account’s secret API key can perform any API request to Stripe without restriction.</p>
                                            </label>
                                            <div class="col-sm-4">
                                                <input class="form-control" value="{{ config('settings.payment_stripe_test_key') }}" name="save_con[payment_stripe_test_key]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 clearfix">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-info">Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection
