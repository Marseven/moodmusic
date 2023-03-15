@section('lightbox-close')
    <a class="lightbox-close close">
        <svg  class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
    </a>
@stop
<div id="lightbox-overlay"></div>
<div id="lightbox-outer">
    <div class="lightbox lightbox-confirm hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <h2 class="title">{{ __('web.LB_START_RADIO_TITLE') }}</h2>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-content-block">
                    <p></p>
                </div>
            </div>
            <div class="lightbox-footer">
                <div class="left-btns"><a class="btn btn-secondary close" data-translate-text="CANCEL">{{ __('web.CANCEL') }}</a></div>
                <div class="right-btns"><a class="btn btn-primary submit">{{ __('web.OK') }}</a></div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-claimArtist hide">
        <div class="lbcontainer">
            <div id="claimArtistLightbox">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="CLAIM_ARTIST">{{ __('web.CLAIM_ARTIST') }}</h2>
                    @yield('lightbox-close')
                </div>
                <ul id="lightbox-stages-header" class="">
                    <li class="lightbox-stage active" rel="account">
                        <div class="circle">
                            <svg class="icon" height="24" viewBox="0 0 512 512" width="24" xmlns="http://www.w3.org/2000/svg"><g><path d="m459.669 82.906-196-81.377c-4.91-2.038-10.429-2.039-15.338 0l-196 81.377c-7.465 3.1-12.331 10.388-12.331 18.471v98.925c0 136.213 82.329 258.74 208.442 310.215 4.844 1.977 10.271 1.977 15.116 0 126.111-51.474 208.442-174.001 208.442-310.215v-98.925c0-8.083-4.865-15.371-12.331-18.471zm-27.669 117.396c0 115.795-68 222.392-176 269.974-105.114-46.311-176-151.041-176-269.974v-85.573l176-73.074 176 73.074zm-198.106 67.414 85.964-85.963c7.81-7.81 20.473-7.811 28.284 0s7.81 20.474-.001 28.284l-100.105 100.105c-7.812 7.812-20.475 7.809-28.284 0l-55.894-55.894c-7.811-7.811-7.811-20.474 0-28.284s20.474-7.811 28.284 0z"/></g></svg>
                        </div> {{ __('web.VERIFY_ACCOUNT') }}<div class="arrow"></div>
                    </li>
                    <li class="lightbox-stage" rel="info">
                        <div class="circle">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        </div> {{ __('web.INFORMATION') }}<div class="arrow"></div>
                    </li>
                    <li class="lightbox-stage" rel="done">
                        <div class="circle">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0zm0 0h24v24H0V0z"/><path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                        </div> {{ __('web.FINISHED') }}<div class="arrow"></div>
                    </li>
                </ul>
                <div class="lightbox-content">
                    <div class="lightbox-error error hide"></div>
                    <div class="lightbox-content-block">
                        <div id="artist-claiming-stage-account" class="claiming-stage">
                            <p data-translate-text="VERIFY_ACCOUNT_EMAIL_MESSAGE">{{ __('web.VERIFY_ACCOUNT_EMAIL_MESSAGE') }}</p>
                            <div class="control-group floated"><label class="control-label" for="artist-claiming-email" data-translate-text="FORM_EMAIL_ADDRESS">{{ __('web.FORM_EMAIL_ADDRESS') }}</label>
                                <div class="control"><input type="text" id="artist-claiming-email" value=""></div>
                            </div>
                            <div class="control-group floated"><label class="control-label" for="artist-claiming-fname" data-translate-text="FORM_FULL_NAME">{{ __('web.FORM_FULL_NAME') }}</label>
                                <div class="control"><input type="text" id="artist-claiming-fname" value=""></div>
                            </div>
                            <div class="control-group password-container hide"><label class="control-label" for="artist-claiming-password" data-translate-text="FORM_PASSWORD_REQUIRED">>{{ __('web.FORM_PASSWORD_REQUIRED') }}</label>
                                <div class="control"><input type="password" id="artist-claiming-password"></div>
                            </div>
                        </div>
                        <div id="artist-claiming-stage-info" class="claiming-stage hide">
                            <div id="artist-claiming-name-container" class="control-group">
                                <label class="control-label" for="artist-claiming-email" data-translate-text="FORM_ARTIST_OR_BAND">{{ __('web.FORM_ARTIST_OR_BAND') }}</label>
                                <form id="artist-claim-search-form" class="control artist-search-container ajax-form" method="GET" action="{{ url('api/search/artist') }}">
                                    <button type="submit" class="btn btn-secondary search" data-translate-text="SEARCH">{{ __('web.SEARH') }}</button>
                                    <div id="artist-name-container">
                                        <input type="text" name="q" value="" autocomplete="off">
                                    </div>
                                </form>
                                <div id="artist-search-loading" class="hide">
                                    <img class="page-loading" width="32" height="32">
                                </div>
                                <div id="window-claim-artist-selector" class="window-selector-container hide">
                                    <span id="selected-create-text" class="control-label d-block text-center mb-2" data-translate-text="LB_CLAIM_ARTIST_CHOOSE_PROFILE_TOP">{{ __('web.LB_CLAIM_ARTIST_CHOOSE_PROFILE_TOP') }}</span>
                                    <div class="window-selector window-selector-selected snapshot">
                                        <div class="module module-row tall artist">
                                            <div class="img-container">
                                                <img class="img" src="">
                                            </div>
                                            <div class="metadata">
                                                <span class="title artist-link"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="others hide">
                                        <span class="control-label text-center d-block text-center mb-2" data-translate-text="LB_CLAIM_ARTIST_CHOOSE_PROFILE_OTHERS">{{ __('web.LB_CLAIM_ARTIST_CHOOSE_PROFILE_OTHERS') }}</span>
                                        <div class="window-selector window-selector-others"></div>
                                    </div>
                                    <input type="hidden" name="artist_id"/>
                                    <input type="hidden" name="artist_name"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control"><label class="control-label" for="artist-claiming-phone" data-translate-text="FORM_PHONE">{{ __('web.FORM_PHONE') }}</label><input type="text" id="artist-claiming-phone" value=""></div>
                                <div class="control"><label class="control-label" for="artist-claiming-phone-ext" data-translate-text="FORM_PHONE_EXT">{{ __('web.FORM_PHONE_EXT') }}</label><input type="text" id="artist-claiming-phone-ext" value=""></div>
                                <div class="control"><label class="control-label" for="artist-claiming-affiliation" data-translate-text="FORM_ARTIST_AFFILIATION">{{ __('web.FORM_ARTIST_AFFILIATION') }}</label>
                                    <select id="artist-claiming-affiliation" class="span2">
                                        <option value="">{{ __('web.FORM_SELECT_AN_OPTION') }}</option>
                                        <option value="Artist/Band Member">{{ __('web.ARTIST_AFFILIATION_1') }}</option>
                                        <option value="Manager">{{ __('web.ARTIST_AFFILIATION_2') }}</option>
                                        <option value="Label">{{ __('web.ARTIST_AFFILIATION_3') }}</option>
                                        <option value="Other">{{ __('web.ARTIST_AFFILIATION_4') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message-container"><label class="control-label" for="artist-claiming-message" data-translate-text="EXPLAIN_ARTIST_IDENTITY">{{ __('web.EXPLAIN_ARTIST_IDENTITY') }}</label>
                                <div class="control"><textarea id="artist-claiming-message"></textarea></div>
                            </div>
                            <h3 data-translate-text="SPEED_UP_ARTIST_PROCESS">{{ __('web.SPEED_UP_ARTIST_PROCESS') }}</h3>
                            <div class="connect-container">
                                <div class="twitter-icon-container icon-container">
                                    <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568C480.224,136.96,497.728,118.496,512,97.248z"/></svg>
                                    <img class="hide"/>
                                </div>
                                <h3 class="icon-name">Twitter</h3>
                                <span class="icon-message text-secondary small" data-translate-text="TWITTER_ARTIST_VERIFY_INSTRUCTIONS">{{ __('web.TWITTER_ARTIST_VERIFY_INSTRUCTIONS') }}</span>
                                <a id="artist-twitter-connect" class="btn" data-translate-text="SETTINGS_NAV_THIRD_PARTY" data-action="social-login" data-service="twitter">{{ __('web.SETTINGS_NAV_THIRD_PARTY') }}</a>
                            </div>
                            <div class="connect-container facebook">
                                <div class="facebook-icon-container icon-container">
                                    <svg class="icon" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg>
                                    <img class="hide"/>
                                </div>
                                <h3 class="icon-name">Facebook</h3>
                                <div id="facebook-verify-connect">
                                    <span class="icon-message text-secondary small" data-translate-text="FACEBOOK_ARTIST_VERIFY_INSTRUCTIONS">{{ __('web.FACEBOOK_ARTIST_VERIFY_INSTRUCTIONS') }}</span>
                                    <a id="artist-facebook-connect" class="btn" data-translate-text="SETTINGS_NAV_THIRD_PARTY" data-action="social-login" data-service="facebook">{{ __('web.SETTINGS_NAV_THIRD_PARTY') }}</a>
                                </div>
                            </div>
                            <div class="connect-container passport">
                                <div class="passport-icon-container icon-container">
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M437.333,0h-42.667c35.346,0,64,28.654,64,64v384c0,35.346-28.654,64-64,64h42.667c35.346,0,64-28.654,64-64V64C501.333,28.654,472.68,0,437.333,0z"/>
                                        <path d="M119.616,309.333c14.802,28.855,40.851,50.326,72,59.349c-8.816-18.747-14.577-38.783-17.067-59.349H119.616z"/>
                                        <path d="M328.384,202.667c-14.799-28.849-40.839-50.319-71.979-59.349c8.815,18.747,14.577,38.783,17.067,59.349H328.384z"/>
                                        <path d="M191.552,143.317c-31.125,9.039-57.148,30.508-71.936,59.349h54.869C176.975,182.1,182.736,162.064,191.552,143.317z"/>
                                        <path d="M170.667,256c0-10.667,0.491-21.483,1.387-32h-60.843c-6.065,20.902-6.065,43.098,0,64h60.843C171.157,277.483,170.667,266.667,170.667,256z"/>
                                        <path d="M224,138.667c-7.957,0-21.333,22.379-27.968,64h55.936C245.333,161.045,231.957,138.667,224,138.667z"/>
                                        <path d="M224,373.333c7.957,0,21.333-22.379,27.968-64h-55.936C202.667,350.955,216.043,373.333,224,373.333z"/>
                                        <path d="M275.947,224c0.896,10.517,1.387,21.333,1.387,32s-0.491,21.483-1.387,32h60.843c6.066-20.902,6.066-43.098,0-64H275.947z"/>
                                        <path d="M437.333,448V64c0-35.346-28.654-64-64-64H32C20.218,0,10.667,9.551,10.667,21.333v469.333C10.667,502.449,20.218,512,32,512h341.333C408.68,512,437.333,483.346,437.333,448z M352,309.333C322.545,380.026,241.359,413.455,170.667,384c-33.762-14.068-60.599-40.904-74.667-74.667c-14.272-34.123-14.272-72.543,0-106.667C125.455,131.974,206.641,98.545,277.333,128c33.762,14.068,60.599,40.904,74.667,74.667C366.27,236.79,366.27,275.21,352,309.333z"/>
                                        <path d="M193.493,224c-0.875,9.941-1.493,20.437-1.493,32s0.619,22.059,1.493,32h61.013c0.875-9.941,1.493-20.437,1.493-32s-0.619-22.059-1.493-32H193.493z"/>
                                        <path d="M256.384,368.683c31.148-9.025,57.196-30.496,72-59.349h-54.869C271.005,329.903,265.221,349.939,256.384,368.683z"/>
                                    </svg>
                                </div>
                                <h3 class="icon-name" data-translate-text="PASSPORT">{{ __('web.PASSPORT') }}</h3>
                                <div id="passport-verify-connect">
                                    <span class="icon-message text-secondary small" data-translate-text="PASSPORT_TIP">{{ __('web.PASSPORT_TIP') }}</span>
                                    <div class="btn">
                                        <span data-translate-text="BROWSE">{{ __('web.BROWSE') }}</span>
                                        <form id="passport-form" enctype="multipart/form-data" method="post">
                                            <input name="passport" type="file">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="connect-container">
                                <input class="hide custom-checkbox" id="accept-terms" type="checkbox" name="accept-terms">
                                <label class="cbx float-left" for="accept-terms"></label>
                                <label class="lbl ml-4" for="accept-terms" data-translate-text="AGREE_ARTIST_TERMS">{{ __('web.AGREE_ARTIST_TERMS') }}</label>
                            </div>
                        </div>
                        <div id="artist-claiming-stage-message" class="hide">
                            <div class="claiming-success-badge">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0zm0 0h24v24H0V0z"></path><path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg>
                            </div>
                            <p class="claiming-success-message" data-translate-text="POPUP_CLAIM_ARTIST_SENT">{{ __('web.POPUP_CLAIM_ARTIST_SENT') }}</p>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <a class="btn btn-primary continue" data-translate-text="CONTINUE">{{ __('web.CONTINUE') }}</a>
                        <a class="btn btn-primary create hide" data-translate-text="CREATE_ARTIST">{{ __('web.CREATE_ARTIST') }}</a>
                        <a class="btn btn-primary finished hide" data-translate-text="CLOSE">{{ __('web.CLOSE') }}</a>
                        <a class="btn btn-primary submit hide" data-translate-text="ARTIST_CLAIM_SEND_REQUEST">{{ __('web.ARTIST_CLAIM_SEND_REQUEST') }}</a></div>
                    <div class="left">
                        <a class="btn btn-secondary back hide" data-translate-text="BACK">{{ __('web.BACK') }}</a>
                        <a class="btn btn-secondary close hide" data-translate-text="CLOSE">{{ __('web.CLOSE') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-radioClearQueue hide">
        <div class="lbcontainer">
            <form class="generic radioClearQueue">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="LB_START_RADIO_TITLE">{{ __('web.LB_START_RADIO_TITLE') }}</h2>
                    @yield('lightbox-close')

                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <p data-translate-text="LB_START_RADIO_MESSAGE">{{ __('web.LB_START_RADIO_MESSAGE') }}</p>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="left-btns"><a class="btn btn-secondary close" data-translate-text="CANCEL">{{ __('web.CANCEL') }}</a></div>
                    <div class="right-btns"><a class="btn btn-primary submit " data-translate-text="LB_START_RADIO_TITLE">{{ __('web.LB_START_RADIO_TITLE') }}</a></div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-login hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <h2 class="title" data-translate-text="SIGN_IN">{{ __('web.SIGN_IN') }}</h2>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-content-block">
                    @if(config('settings.social_login'))
                        <div class="lb-nav-outer">
                            <div class="lb-nav-container no-center">
                                <div class="row">
                                    @if(config('settings.facebook_login'))
                                        <div class="col">
                                            <a class="lb-facebook-login btn share-btn third-party facebook" data-action="social-login" data-service="facebook">
                                                <svg class="icon" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"></path></svg>
                                                <span class="text desktop" data-translate-text="SIGN_IN_FACEBOOK">{{ __('web.SIGN_IN_FACEBOOK') }}</span>
                                                <span class="text mobile" data-translate-text="FACEBOOK">{{ __('web.FACEBOOK') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if(config('settings.google_login'))
                                        <div class="col">
                                            <a class="lb-google-login btn share-btn third-party google" data-action="social-login" data-service="google">
                                                <svg class="icon icon-google-plus-white-active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-10.333 16.667c-2.581 0-4.667-2.087-4.667-4.667s2.086-4.667 4.667-4.667c1.26 0 2.313.46 3.127 1.22l-1.267 1.22c-.347-.333-.954-.72-1.86-.72-1.593 0-2.893 1.32-2.893 2.947s1.3 2.947 2.893 2.947c1.847 0 2.54-1.327 2.647-2.013h-2.647v-1.6h4.406c.041.233.074.467.074.773-.001 2.666-1.787 4.56-4.48 4.56zm11.333-4h-2v2h-1.334v-2h-2v-1.333h2v-2h1.334v2h2v1.333z"></path>
                                                </svg>
                                                <span class="text desktop" data-translate-text="SIGN_IN_GOOGLE">{{ __('web.SIGN_IN_GOOGLE') }}</span>
                                                <span class="text mobile" data-translate-text="GOOGLE">{{ __('web.GOOGLE') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if(config('settings.twitter_login'))
                                        <div class="col">
                                            <a class="lb-twitter-login btn share-btn third-party twitter" data-action="social-login" data-service="twitter">
                                                <svg class="icon icon-twitter-white-active" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510" xml:space="preserve"><path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z M400.35,186.15c-2.55,117.3-76.5,198.9-188.7,204C165.75,392.7,132.6,377.4,102,359.55c33.15,5.101,76.5-7.649,99.45-28.05c-33.15-2.55-53.55-20.4-63.75-48.45c10.2,2.55,20.4,0,28.05,0c-30.6-10.2-51-28.05-53.55-68.85c7.65,5.1,17.85,7.65,28.05,7.65c-22.95-12.75-38.25-61.2-20.4-91.8c33.15,35.7,73.95,66.3,140.25,71.4c-17.85-71.4,79.051-109.65,117.301-61.2c17.85-2.55,30.6-10.2,43.35-15.3c-5.1,17.85-15.3,28.05-28.05,38.25c12.75-2.55,25.5-5.1,35.7-10.2C425.85,165.75,413.1,175.95,400.35,186.15z"></path></svg>
                                                <span class="text desktop" data-translate-text="SIGN_IN_TWITTER">{{ __('web.SIGN_IN_TWITTER') }}</span>
                                                <span class="text mobile" data-translate-text="TWITTER">{{ __('web.TWITTER') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if(config('settings.apple_login'))
                                        <div class="col">
                                            <a class="lb-apple-login btn share-btn third-party apple" data-action="social-login" data-service="apple">
                                                <svg class="icon icon-apple-white-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"  width="24" height="24" xml:space="preserve">
                                                    <g>
                                                        <path d="M185.255,512c-76.201-0.439-139.233-155.991-139.233-235.21c0-129.404,97.075-157.734,134.487-157.734   c16.86,0,34.863,6.621,50.742,12.48c11.104,4.087,22.588,8.306,28.975,8.306c3.823,0,12.832-3.589,20.786-6.738   c16.963-6.753,38.071-15.146,62.651-15.146c0.044,0,0.103,0,0.146,0c18.354,0,74.004,4.028,107.461,54.272l7.837,11.777   l-11.279,8.511c-16.113,12.158-45.513,34.336-45.513,78.267c0,52.031,33.296,72.041,49.292,81.665   c7.061,4.248,14.37,8.628,14.37,18.208c0,6.255-49.922,140.566-122.417,140.566c-17.739,0-30.278-5.332-41.338-10.034   c-11.191-4.761-20.845-8.862-36.797-8.862c-8.086,0-18.311,3.823-29.136,7.881C221.496,505.73,204.752,512,185.753,512H185.255z"/>
                                                        <path d="M351.343,0c1.888,68.076-46.797,115.304-95.425,112.342C247.905,58.015,304.54,0,351.343,0z"/>
                                                    </g>
                                                </svg>
                                                <span class="text desktop" data-translate-text="SIGN_IN_APPLE">{{ __('web.SIGN_IN_APPLE') }}</span>
                                                <span class="text mobile" data-translate-text="APPLE">{{ __('web.APPLE') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    @if(config('settings.discord_login'))
                                        <div class="col">
                                            <a class="lb-apple-login btn share-btn third-party discord" data-action="social-login" data-service="discord">
                                                <svg class="icon icon-discord-white-active" height="24" viewBox="0 0 510.901 510.901" width="24" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m483.927 224.185c-15.602-49.641-25.629-72.626-30.219-81.911l-.002-.003c-3.69-7.601-13.042-23.957-13.414-24.607-1.31-1.599-33.786-40.463-112.059-69.209l-10.343 28.16c37.01 13.592 62.342 29.288 78.034 41.089-43.344-12.925-94.883-20.835-140.53-20.835-45.702 0-97.303 7.933-140.679 20.886 14.528-10.779 40.946-27.464 78.183-41.14l-10.343-28.16c-78.272 28.746-110.749 67.61-112.059 69.209 0 0-7.715 12.454-14.197 26.129-3.885 8.196-13.949 26.91-29.536 80.716-19.549 67.5-26.492 162.909-26.763 166.726 3.451 7.549 43.451 71.212 151.089 71.212l29.495-42.71c24.345 6.315 49.46 9.51 74.812 9.51 25.406 0 50.571-3.209 74.962-9.549l29.313 42.749c109.779 0 145.613-61.496 151.23-71.384-.434-4.545-11.306-117.023-26.974-166.878zm-28.73 181.831c-22.833 16.16-49.659 24.98-79.833 26.267l-15.133-22.069c20.871-7.865 40.895-18.099 59.712-30.624l-16.623-24.973c-20.989 13.971-43.649 24.781-67.368 32.266v.029s-39.276 12.335-80.555 12.335-80.445-12.299-80.445-12.299v-.031c-23.759-7.486-46.457-18.308-67.478-32.3l-16.624 24.972c18.85 12.547 38.912 22.795 59.823 30.665l-15.213 22.029c-30.187-1.281-57.024-10.102-79.865-26.268-12.734-9.013-20.938-18.15-24.881-23.069 1.74-20.548 8.86-94.827 24.869-150.091 12.015-41.475 20.309-60.884 24.837-70.255 0 0 116.109-35.732 174.977-35.732 58.82 0 130.587 14.613 174.584 35.546 4.757 10.838 13.226 32.259 25.327 70.765 12.767 40.623 22.29 126.948 24.665 149.897-4.001 4.971-12.168 14.016-24.776 22.94z"/></g><path d="m156.038 252.991h30v50h-30z"/><path d="m324.754 252.991h30v50h-30z"/></g></svg>
                                                <span class="text desktop" data-translate-text="SIGN_IN_DISCORD">{{ __('web.SIGN_IN_DISCORD') }}</span>
                                                <span class="text mobile" data-translate-text="DISCORD">{{ __('web.DISCORD') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="lightbox-error error hide"></div>
                    <div id="lightbox-login-form-goes-here">
                        <div class="error hide"></div>
                        <div class="positive hide">
                            <div class="message"></div>
                        </div>
                        <p id="login-msg"></p>
                        <form id="lightbox-login-form" class="vertical" method="post">
                            <div class="row">
                                @if(config('settings.authorization_method', 0) == 0)
                                    <div class="control-group col-lg-6 col-12">
                                        <label class="control-label" for="username" data-translate-text="FORM_USERNAME">{{ __('web.FORM_USERNAME') }}</label>
                                        <div class="controls">
                                            <input class="login-text" id="login-username" name="username" type="text" autocapitalize="none" autocorrect="off">
                                        </div>
                                        <a class="open-signup small desktop" data-translate-text="LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB">{{ __('web.LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB') }}</a>
                                    </div>
                                @else
                                    <div class="control-group col-lg-6 col-12">
                                        <label class="control-label" for="email" data-translate-text="FORM_EMAIL">{{ __('web.FORM_EMAIL') }}</label>
                                        <div class="controls">
                                            <input class="login-text" id="login-email" name="email" type="text">
                                        </div>
                                        <a class="open-signup small desktop" data-translate-text="LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB">{{ __('web.LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB') }}</a>
                                    </div>
                                @endif
                                <div class="control-group col-lg-6 col-12">
                                    <label class="control-label" for="password" data-translate-text="FORM_PASSWORD">{{ __('web.FORM_PASSWORD') }}</label>
                                    <div class="controls">
                                        <input class="login-text" id="login-password" name="password" type="password">
                                    </div>
                                    <a class="forgot small" data-translate-text="FORM_FORGOT_PASSWORD">{{ __('web.FORM_FORGOT_PASSWORD') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="lightbox-footer">
                <div class="right">
                    <a class="btn btn-primary submit" data-translate-text="SIGN_IN">{{ __('web.SIGN_IN') }}</a>
                </div>
                @if(! config('settings.disable_register'))
                    <div id="lightbox-footer-left" class="left mobile">
                        <a class="btn btn-secondary open-signup" data-translate-text="LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB">{{ __('web.LB_SIGNUP_LOGIN_DONT_HAVE_ACCOUNT_SUB') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-signup hide">
        <div class="lbcontainer">
            <form id="singup-form" method="POST" action="{{ route('frontend.auth.info.validate') }}">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="POPUP_SIGNUP_TITLE">{{ __('web.POPUP_SIGNUP_TITLE') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-error error hide"></div>
                    <div class="lightboxsettings.disable_register-content-block">
                        @if(config('settings.disable_register'))
                            <p class="mt-3 mb-3">{{ __('web.REGISTRATION_DISABLED') }}</p>
                        @else
                            @if(config('settings.social_login'))
                                <div class="lb-nav-outer">
                                    <div class="lb-nav-container no-center">
                                        <div class="row">
                                            @if(config('settings.facebook_login'))
                                                <div class="col">
                                                    <a class="lb-facebook-login btn share-btn third-party facebook" data-action="social-login" data-service="facebook">
                                                        <svg class="icon" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"></path></svg>
                                                        <span class="text desktop" data-translate-text="SIGN_IN_FACEBOOK">{{ __('web.SIGN_IN_FACEBOOK') }}</span>
                                                        <span class="text mobile" data-translate-text="FACEBOOK">{{ __('web.FACEBOOK') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(config('settings.google_login'))
                                                <div class="col">
                                                    <a class="lb-google-login btn share-btn third-party google" data-action="social-login" data-service="google">
                                                        <svg class="icon icon-google-plus-white-active" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-10.333 16.667c-2.581 0-4.667-2.087-4.667-4.667s2.086-4.667 4.667-4.667c1.26 0 2.313.46 3.127 1.22l-1.267 1.22c-.347-.333-.954-.72-1.86-.72-1.593 0-2.893 1.32-2.893 2.947s1.3 2.947 2.893 2.947c1.847 0 2.54-1.327 2.647-2.013h-2.647v-1.6h4.406c.041.233.074.467.074.773-.001 2.666-1.787 4.56-4.48 4.56zm11.333-4h-2v2h-1.334v-2h-2v-1.333h2v-2h1.334v2h2v1.333z"></path>
                                                        </svg>
                                                        <span class="text desktop" data-translate-text="SIGN_IN_GOOGLE">{{ __('web.SIGN_IN_GOOGLE') }}</span>
                                                        <span class="text mobile" data-translate-text="GOOGLE">{{ __('web.GOOGLE') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(config('settings.twitter_login'))
                                                <div class="col">
                                                    <a class="lb-twitter-login btn share-btn third-party twitter" data-action="social-login" data-service="twitter">
                                                        <svg class="icon icon-twitter-white-active" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510" xml:space="preserve"><path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z M400.35,186.15c-2.55,117.3-76.5,198.9-188.7,204C165.75,392.7,132.6,377.4,102,359.55c33.15,5.101,76.5-7.649,99.45-28.05c-33.15-2.55-53.55-20.4-63.75-48.45c10.2,2.55,20.4,0,28.05,0c-30.6-10.2-51-28.05-53.55-68.85c7.65,5.1,17.85,7.65,28.05,7.65c-22.95-12.75-38.25-61.2-20.4-91.8c33.15,35.7,73.95,66.3,140.25,71.4c-17.85-71.4,79.051-109.65,117.301-61.2c17.85-2.55,30.6-10.2,43.35-15.3c-5.1,17.85-15.3,28.05-28.05,38.25c12.75-2.55,25.5-5.1,35.7-10.2C425.85,165.75,413.1,175.95,400.35,186.15z"></path></svg>
                                                        <span class="text desktop" data-translate-text="SIGN_IN_TWITTER">{{ __('web.SIGN_IN_TWITTER') }}</span>
                                                        <span class="text mobile" data-translate-text="TWITTER">{{ __('web.TWITTER') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(config('settings.apple_login'))
                                                <div class="col">
                                                    <a class="lb-apple-login btn share-btn third-party apple" data-action="social-login" data-service="apple">
                                                        <svg class="icon icon-apple-white-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"  width="24" height="24" xml:space="preserve">
                                                            <g>
                                                                <path d="M185.255,512c-76.201-0.439-139.233-155.991-139.233-235.21c0-129.404,97.075-157.734,134.487-157.734   c16.86,0,34.863,6.621,50.742,12.48c11.104,4.087,22.588,8.306,28.975,8.306c3.823,0,12.832-3.589,20.786-6.738   c16.963-6.753,38.071-15.146,62.651-15.146c0.044,0,0.103,0,0.146,0c18.354,0,74.004,4.028,107.461,54.272l7.837,11.777   l-11.279,8.511c-16.113,12.158-45.513,34.336-45.513,78.267c0,52.031,33.296,72.041,49.292,81.665   c7.061,4.248,14.37,8.628,14.37,18.208c0,6.255-49.922,140.566-122.417,140.566c-17.739,0-30.278-5.332-41.338-10.034   c-11.191-4.761-20.845-8.862-36.797-8.862c-8.086,0-18.311,3.823-29.136,7.881C221.496,505.73,204.752,512,185.753,512H185.255z"/>
                                                                <path d="M351.343,0c1.888,68.076-46.797,115.304-95.425,112.342C247.905,58.015,304.54,0,351.343,0z"/>
                                                            </g>
                                                        </svg>
                                                        <span class="text desktop" data-translate-text="SIGN_IN_APPLE">{{ __('web.SIGN_IN_APPLE') }}</span>
                                                        <span class="text mobile" data-translate-text="APPLE">{{ __('web.APPLE') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(config('settings.discord_login'))
                                                <div class="col">
                                                    <a class="lb-apple-login btn share-btn third-party discord" data-action="social-login" data-service="discord">
                                                        <svg class="icon icon-discord-white-active" height="24" viewBox="0 0 510.901 510.901" width="24" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m483.927 224.185c-15.602-49.641-25.629-72.626-30.219-81.911l-.002-.003c-3.69-7.601-13.042-23.957-13.414-24.607-1.31-1.599-33.786-40.463-112.059-69.209l-10.343 28.16c37.01 13.592 62.342 29.288 78.034 41.089-43.344-12.925-94.883-20.835-140.53-20.835-45.702 0-97.303 7.933-140.679 20.886 14.528-10.779 40.946-27.464 78.183-41.14l-10.343-28.16c-78.272 28.746-110.749 67.61-112.059 69.209 0 0-7.715 12.454-14.197 26.129-3.885 8.196-13.949 26.91-29.536 80.716-19.549 67.5-26.492 162.909-26.763 166.726 3.451 7.549 43.451 71.212 151.089 71.212l29.495-42.71c24.345 6.315 49.46 9.51 74.812 9.51 25.406 0 50.571-3.209 74.962-9.549l29.313 42.749c109.779 0 145.613-61.496 151.23-71.384-.434-4.545-11.306-117.023-26.974-166.878zm-28.73 181.831c-22.833 16.16-49.659 24.98-79.833 26.267l-15.133-22.069c20.871-7.865 40.895-18.099 59.712-30.624l-16.623-24.973c-20.989 13.971-43.649 24.781-67.368 32.266v.029s-39.276 12.335-80.555 12.335-80.445-12.299-80.445-12.299v-.031c-23.759-7.486-46.457-18.308-67.478-32.3l-16.624 24.972c18.85 12.547 38.912 22.795 59.823 30.665l-15.213 22.029c-30.187-1.281-57.024-10.102-79.865-26.268-12.734-9.013-20.938-18.15-24.881-23.069 1.74-20.548 8.86-94.827 24.869-150.091 12.015-41.475 20.309-60.884 24.837-70.255 0 0 116.109-35.732 174.977-35.732 58.82 0 130.587 14.613 174.584 35.546 4.757 10.838 13.226 32.259 25.327 70.765 12.767 40.623 22.29 126.948 24.665 149.897-4.001 4.971-12.168 14.016-24.776 22.94z"/></g><path d="m156.038 252.991h30v50h-30z"/><path d="m324.754 252.991h30v50h-30z"/></g></svg>
                                                        <span class="text desktop" data-translate-text="SIGN_IN_DISCORD">{{ __('web.SIGN_IN_DISCORD') }}</span>
                                                        <span class="text mobile" data-translate-text="DISCORD">{{ __('web.DISCORD') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id="signup-stage-singup" class="signup-stage">
                                <div class="row">
                                    <div class="control control-group col-lg-6 col-12">
                                        <label class="control-label" for="signup-email" data-translate-text="FORM_EMAIL_ADDRESS">{{ __('web.FORM_EMAIL_ADDRESS') }}</label>
                                        <div class="controls"><input class="signup-text" id="signup-email" name="email" type="text"></div>
                                    </div>
                                    <div class="control control-group col-lg-6 col-12">
                                        <label class="control-label" for="signup-fname" data-translate-text="FORM_NAME">{{ __('web.FORM_NAME') }}</label>
                                        <div class="controls"><input class="signup-text" id="signup-fname" name="name" type="text"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="control control-group col-lg-6 col-12">
                                        <label class="control-label" for="signup-password1" data-translate-text="FORM_PASSWORD">{{ __('web.FORM_PASSWORD') }}</label>
                                        <div class="controls"><input class="signup-text" id="signup-password1" name="password" type="password"></div>
                                    </div>
                                    <div class="control control-group col-lg-6 col-12">
                                        <label class="control-label" for="signup-password2" data-translate-text="FORM_CONFIRM_PASSWORD">{{ __('web.FORM_CONFIRM_PASSWORD') }}</label>
                                        <div class="controls"><input class="signup-text" id="signup-password2" name="password_confirmation" type="password"></div>
                                    </div>
                                </div>
                                @if(config('settings.dob_signup'))
                                    <div class="row">
                                        <div class="control control-group col-lg-6 col-12">
                                            <label class="control-label" data-translate-text="FORM_DOB">Date of Birth:</label>
                                            <div class="controls">
                                                <select id="signup-dob-month" name="dob-month" class="span2">
                                                    @foreach(explode(',', __('web.MONTHS')) as $key => $value)
                                                        <option value="{{ $loop->iteration }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <select id="signup-dob-day" name="dob-day" class="span1">
                                                    @for ($i = 31; $i >= 1; $i--)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                    <option value="1">1</option>
                                                </select>
                                                <select id="signup-dob-year" name="dob-year" class="span2">
                                                    @for ($i = \Carbon\Carbon::now()->format('Y'); $i >= 1911; $i--)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control control-group col-lg-6 col-12">
                                            <label class="control-label" data-translate-text="COUNTRY">Country:</label>
                                            <div class="controls">
                                                <select name="country" class="span3 select2" style="display: none">@if(! config('settings.default_country'))<option disabled selected value></option>@endif
                                                    @foreach(\App\Models\Country::all() as $country)
                                                        <option value="{{ $country->code }}" @if(config('settings.default_country') == $country->code) selected="selected" @endif>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(config('settings.gender_signup'))
                                    <div class="row">
                                        <div class="control control-group col-lg-6 col-12">
                                            <label class="control-label" data-translate-text="FORM_SEX_PROMPT">I identify as:</label>
                                            <div class="controls radio-container d-flex">
                                                <div class="d-flex mr-3">
                                                    <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-f" value="F">
                                                    <label class="cbx" for="settings-gender-f"></label>
                                                    <label class="lbl" for="settings-gender-f" data-translate-text="FORM_SEX_WOMAN">{{ __('web.FORM_SEX_WOMAN') }}</label>
                                                </div>
                                                <div class="d-flex mr-3">
                                                    <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-m" value="M">
                                                    <label class="cbx" for="settings-gender-m"></label>
                                                    <label class="lbl" for="settings-gender-m" data-translate-text="FORM_SEX_MAN">{{ __('web.FORM_SEX_MAN') }}</label>
                                                </div>
                                                <div class="d-flex">
                                                    <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-o" value="O">
                                                    <label class="cbx" for="settings-gender-o"></label>
                                                    <label class="lbl" for="settings-gender-o" data-translate-text="FORM_SEX_OTHER">{{ __('web.FORM_SEX_OTHER') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <p class="tos small" data-translate-text="FORM_TOS_2">{!! __('web.FORM_TOS_2') !!}</p>
                            </div>
                            <div id="signup-stage-profile" class="signup-stage hide">
                                <div class="control-group custom-url">
                                    <label class="control-label" for="username" data-translate-text="LB_SIGNUP_FORM_URL">{{ __('web.LB_SIGNUP_FORM_URL') }}</label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on">{{ route('frontend.homepage') }}/</span>
                                            <input id="signup-username" name="username" class="signup-text" size="16" value="" type="text" autocomplete="off" maxlength="30" autocapitalize="none" autocorrect="off">
                                        </div>
                                    </div>
                                </div>
                                @if(config('settings.allow_artist_claim', 1))
                                    <p class="checkbox-container">
                                        <input class="hide custom-checkbox" type="checkbox" name="artist" id="im-artist">
                                        <label class="cbx" for="im-artist"></label>
                                        <label class="lbl" for="im-artist" data-translate-text="IM_AN_ARTIST_SIGNUP">{{ __('web.IM_AN_ARTIST_SIGNUP') }}</label>
                                    </p>
                                @endif
                            </div>
                            <div id="signup-stage-verify" class="signup-stage hide">
                                <h2 class="text-center"  data-translate-text="LB_VERIFY_ACCOUNT">{{ __('web.LB_VERIFY_ACCOUNT') }}</h2>
                                <p  data-translate-text="LB_VERIFY_ACCOUNT_DESCRIPTION">{{ __('web.LB_VERIFY_ACCOUNT_DESCRIPTION') }}</p>
                            </div>
                            <div id="signup-stage-complete" class="signup-stage hide">
                                <div class="url-callout">
                                    <p class="profile-url text-center"></p>
                                </div>
                                <div class="complete-todo">
                                    <svg class="todo-icon profile" height="512" viewBox="0 0 480.063 480.063" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m394.032 424.803v39.26c0 4.42-3.58 8-8 8h-292c-4.42 0-8-3.58-8-8v-39.26c0-41.19 33.39-74.56 74.59-74.57 14.56-.01 27.38-7.5 34.76-18.86 7.414-11.394 6.65-21.302 6.65-29.31l.15-.37c-35.9-14.86-61.15-50.23-61.15-91.5v-3.13c-14.255 0-25-11.265-25-24.54v-41.56c-.32-14.47.34-65.5 37.2-101.03 42.86-41.31 110.78-37.93 159.98-15.83 1.6.72 1.55 3.01-.07 3.68l-12.83 5.28c-1.92.79-1.51 3.62.55 3.84l6.23.67c29.83 3.19 57.54 19.39 74.72 46.35.46.73.33 1.84-.26 2.47-10.6 11.21-16.52 26.09-16.52 41.56v54.57c0 13.55-10.99 24.54-24.54 24.54h-1.46v3.13c0 41.27-25.25 76.64-61.15 91.5l.15.37c0 7.777-.827 17.82 6.65 29.31 7.38 11.36 20.2 18.85 34.76 18.86 41.2.01 74.59 33.38 74.59 74.57z" fill="#ffdfba"></path><path d="m394.032 424.803v39.26c0 4.418-3.582 8-8 8h-292c-4.418 0-8-3.582-8-8v-39.26c0-41.19 33.395-74.555 74.585-74.57 14.564-.005 27.387-7.504 34.765-18.86 25.754 22.002 63.531 22.015 89.3 0 7.377 11.356 20.201 18.855 34.765 18.86 41.19.015 74.585 33.38 74.585 74.57z" fill="#fe4f60"></path><path d="m381.807 83.928c.464.729.334 1.833-.259 2.461-10.597 11.218-16.517 26.093-16.517 41.564v54.57c0 12.388-9.333 24.54-26 24.54v-61.77c0-26.51-21.49-48-48-48h-102c-26.51 0-48 21.49-48 48v61.77c-14.255 0-25-11.265-25-24.54v-41.56c-.32-14.47.34-65.5 37.2-101.03 42.858-41.311 110.784-37.929 159.977-15.827 1.601.719 1.558 3.01-.065 3.678l-12.831 5.282c-1.918.79-1.514 3.617.548 3.838l6.232.669c29.834 3.187 57.537 19.387 74.715 46.355z" fill="#42434d"></path><path d="m339.032 210.193c0 54.696-44.348 99-99 99-51.492 0-99-40.031-99-102.13v-61.77c0-26.51 21.49-48 48-48h102c26.51 0 48 21.49 48 48z" fill="#ffebd2"></path><path d="m217.616 274.121c16.277 10.183 3.442 35.156-14.376 28.004-36.634-14.704-62.208-50.404-62.208-91.932v-64.9c0-10.084 3.11-19.442 8.423-27.168 6.514-9.473 21.577-5.288 21.577 7.168v64.9c0 36.51 19.192 66.79 46.584 83.928z" fill="#fff3e4"></path><path d="m279.162 318.483c-24.637 10.313-51.712 11.113-78.26 0 1.356-5.626 1.13-9.27 1.13-16.42l.15-.37c24.082 9.996 51.571 10.016 75.7 0l.15.37c0 7.153-.226 10.796 1.13 16.42z" fill="#ffd6a6"></path><path d="m200.913 374.39c-3.698 1.163-7.664 1.804-11.916 1.841-41.296.364-74.966 33.017-74.966 74.315v7.517c0 7.732-6.268 14-14 14h-6c-4.418 0-8-3.582-8-8v-39.26c0-41.191 33.395-74.555 74.585-74.57 14.564-.005 27.387-7.504 34.765-18.86 2.974 2.54 6.158 4.823 9.512 6.822 14.753 8.791 12.402 31.044-3.98 36.195z" fill="#ff6d7a"></path><path d="m279.15 374.39c3.698 1.163 7.664 1.804 11.916 1.841 41.296.364 74.966 33.017 74.966 74.315v7.517c0 7.732 6.268 14 14 14h6c4.418 0 8-3.582 8-8v-39.26c0-41.191-33.395-74.555-74.585-74.57-14.564-.005-27.387-7.504-34.765-18.86-2.974 2.54-6.158 4.823-9.512 6.822-14.753 8.791-12.402 31.044 3.98 36.195z" fill="#e84857"></path><path d="m313.142 27.783c-11.758 4.839-13.434 5.906-17.508 5.274-65.674-10.18-123.294 16.993-142.862 80.786v.01c-7.32 8.42-11.74 19.42-11.74 31.44v37.523c0 16.188-25 17.315-25-.293v-41.56c-.32-14.47.34-65.5 37.2-101.03 42.86-41.31 110.78-37.93 159.98-15.83 1.6.72 1.55 3.01-.07 3.68z" fill="#4d4e59"></path><path d="m402.032 424.806v47.257c0 4.418-3.582 8-8 8s-8-3.582-8-8v-47.257c0-36.795-29.775-66.572-66.573-66.571-17.411 0-33.208-8.87-42.259-23.728-2.298-3.773-1.103-8.696 2.671-10.994 3.773-2.299 8.695-1.103 10.994 2.671 6.122 10.051 16.811 16.051 28.594 16.051 45.637-.002 82.573 36.93 82.573 82.571zm-139.606-80.193c.941 4.317-1.796 8.579-6.113 9.52-21.054 4.587-42.467-.005-59.516-11.642-16.878 18.087-39.176 15.744-36.191 15.744-36.795-.001-66.573 29.773-66.573 66.571v47.257c0 4.418-3.582 8-8 8s-8-3.582-8-8v-47.257c0-45.636 36.929-82.571 82.571-82.571 18.462 0 33.429-14.875 33.429-33.342v-2.107c-34.919-16.697-59.429-51.784-60.923-92.643-14.37-3.455-25.077-16.317-25.077-31.62v-41.473c-.437-20.3 2.577-71.143 39.648-106.877 45.775-44.126 119.183-41.323 173.161-15.338 5.261 2.535 6.06 9.643 1.691 13.324 27.345 6.67 50.925 23.48 66.074 47.538.782 1.239 2.214 3.184 1.84 6.287-.232 1.931-.807 3.565-2.295 5.075-9.75 9.888-15.119 22.991-15.119 36.896v54.57c0 4.418-3.582 8-8 8s-8-3.582-8-8v-54.57c0-16.037 5.479-31.259 15.542-43.487-15.338-21.936-39.268-36.044-66.332-38.942l-14.061-1.506c-8.222-.88-9.835-12.207-2.194-15.352l6.395-2.633c-83.286-29.035-172.351 3.226-172.351 114.928v41.56c0 6.348 3.656 11.865 9 14.636v-51.863c0-30.878 25.122-56 56-56h102c30.878 0 56 25.12 56 55.997v65.503c0 69.574-67.988 122.42-137.17 102.053-.45 5.708-1.871 11.216-4.186 16.336 13.458 9.242 30.453 12.97 47.23 9.314 4.317-.94 8.579 1.797 9.52 6.114zm-22.394-43.425c50.178 0 91-40.822 91-91v-64.895c0-22.054-17.944-39.997-40-39.997h-102c-22.056 0-40 17.944-40 40v64.892c0 50.178 40.822 91 91 91zm81 137.875h-24c-4.418 0-8 3.582-8 8s3.582 8 8 8h24c4.418 0 8-3.582 8-8s-3.582-8-8-8z"></path></svg>                                <div class="todo-description">
                                        <h3 class="todo-header" data-translate-text="LB_SIGNUP_CUSTOMIZE_PROFILE">{{ __('web.LB_SIGNUP_CUSTOMIZE_PROFILE') }}</h3>
                                        <p class="todo-text" data-translate-text="LB_SIGNUP_CUSTOMIZE_PROFILE_SUBTEXT">{{ __('web.LB_SIGNUP_CUSTOMIZE_PROFILE_SUBTEXT') }}</p>
                                    </div>
                                    <a class="btn btn-secondary edit-profile" data-translate-text="EDIT_PROFILE">{{ __('web.EDIT_PROFILE') }}</a>
                                </div>
                                <div class="complete-todo">
                                    <svg class="todo-icon popular" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g id="XMLID_1159_"><g id="XMLID_2123_"><path id="XMLID_2121_" d="m189.412 153.557 40.002-124.408c8.199-25.5 44.263-25.544 52.524-.063l40.355 124.471h131.388c26.747 0 37.854 34.238 16.2 49.939l-106.078 76.914 40.723 124.129c8.347 25.442-20.787 46.669-42.447 30.926l-105.675-76.807-106.049 77.265c-21.632 15.761-50.782-5.408-42.49-30.855l40.653-124.755-106.354-76.784c-21.709-15.673-10.622-49.972 16.154-49.972z" fill="#ffcd69"></path><g id="XMLID_2119_"><path id="XMLID_871_" d="m134.105 451.353c-7.69 0-15.38-2.436-22.093-7.311-13.442-9.762-18.803-26.275-13.655-42.071l38.393-117.818-100.44-72.516c-13.475-9.729-18.878-26.233-13.766-42.047s19.154-26.032 35.774-26.032h123.805l37.772-117.47c5.089-15.827 19.121-26.067 35.747-26.088h.049c16.604 0 30.638 10.201 35.761 26.002l38.112 117.556h13.423c5.523 0 10 4.478 10 10s-4.477 10-10 10h-20.694c-4.334 0-8.176-2.793-9.513-6.916l-40.354-124.472c-2.909-8.974-10.55-12.17-16.739-12.17-.007 0-.014 0-.021 0-6.193.008-13.84 3.219-16.731 12.21l-40.003 124.409c-1.33 4.135-5.176 6.939-9.52 6.939h-131.094c-9.441 0-14.84 6.294-16.744 12.184-1.904 5.891-1.211 14.153 6.443 19.681l106.354 76.785c3.524 2.544 5.001 7.073 3.654 11.206l-40.653 124.755c-2.924 8.973 1.384 16.055 6.392 19.69 5.007 3.636 13.076 5.54 20.702-.016l106.049-77.266c3.505-2.555 8.259-2.559 11.768-.007l105.675 76.807c7.637 5.55 15.704 3.633 20.708-.012 5.003-3.646 9.301-10.737 6.358-19.708l-40.723-124.13c-1.354-4.13.113-8.662 3.632-11.213l106.078-76.914c7.635-5.536 8.317-13.794 6.409-19.676s-7.307-12.167-16.738-12.167h-20.694c-5.523 0-10-4.478-10-10s4.477-10 10-10h20.694c16.602 0 30.64 10.204 35.762 25.996 5.123 15.792-.252 32.293-13.693 42.038l-100.172 72.632 38.449 117.199c5.181 15.792-.151 32.32-13.584 42.107-13.432 9.786-30.799 9.797-44.244.024l-99.789-72.528-100.167 72.98c-6.722 4.899-14.43 7.348-22.139 7.348z"></path></g><g id="XMLID_2151_"><path id="XMLID_856_" d="m256 512c-5.523 0-10-4.478-10-10v-80.877c0-5.522 4.477-10 10-10s10 4.478 10 10v80.877c0 5.522-4.477 10-10 10z"></path></g><g id="XMLID_2150_"><path id="XMLID_855_" d="m499.933 334.776c-1.024 0-2.065-.159-3.092-.492l-76.918-24.992c-5.252-1.707-8.127-7.349-6.42-12.601 1.706-5.252 7.348-8.123 12.601-6.421l76.918 24.992c5.252 1.707 8.127 7.349 6.42 12.601-1.374 4.226-5.294 6.913-9.509 6.913z"></path></g><g id="XMLID_2152_"><path id="XMLID_854_" d="m359.211 113.447c-2.038 0-4.094-.621-5.87-1.911-4.468-3.246-5.458-9.5-2.212-13.968l47.538-65.431c3.247-4.468 9.499-5.459 13.968-2.212 4.468 3.246 5.458 9.5 2.212 13.968l-47.538 65.431c-1.956 2.694-5.006 4.123-8.098 4.123z"></path></g><g id="XMLID_2154_"><path id="XMLID_853_" d="m152.789 113.447c-3.093 0-6.142-1.43-8.099-4.123l-47.538-65.43c-3.246-4.468-2.255-10.722 2.212-13.968 4.469-3.248 10.722-2.256 13.968 2.212l47.538 65.431c3.246 4.468 2.255 10.722-2.212 13.968-1.774 1.289-3.831 1.91-5.869 1.91z"></path></g><g id="XMLID_2156_"><path id="XMLID_852_" d="m12.067 334.776c-4.216 0-8.135-2.687-9.509-6.913-1.706-5.252 1.168-10.894 6.42-12.601l76.918-24.992c5.254-1.706 10.894 1.168 12.601 6.421 1.707 5.252-1.168 10.894-6.42 12.601l-76.917 24.992c-1.027.333-2.069.492-3.093.492z"></path></g></g><g id="XMLID_1847_"><g id="XMLID_1848_"><path id="XMLID_851_" d="m387.99 163.56c-2.63 0-5.21-1.069-7.07-2.93-1.86-1.86-2.93-4.44-2.93-7.07s1.07-5.21 2.93-7.069c1.86-1.86 4.43-2.931 7.07-2.931 2.63 0 5.21 1.07 7.07 2.931 1.86 1.859 2.93 4.439 2.93 7.069s-1.07 5.21-2.93 7.07-4.44 2.93-7.07 2.93z"></path></g></g></g></svg>                                <div class="todo-description">
                                        <h3 class="todo-header" data-translate-text="LB_SIGNUP_ENJOY_POPULAR">{{ __('web.LB_SIGNUP_ENJOY_POPULAR') }}</h3>
                                        <p class="todo-text" data-translate-text="LB_SIGNUP_ENJOY_POPULAR_SUBTEXT">{{ __('web.LB_SIGNUP_ENJOY_POPULAR_SUBTEXT') }}</p>
                                    </div>
                                    <a href="{{ route('frontend.trending') }}" class="btn btn-secondary popular-page" data-translate-text="VIEW_POPULAR_PAGE">{{ __('web.VIEW_POPULAR_PAGE') }}</a>
                                </div>
                                @if(config('settings.allow_artist_claim', 1))
                                    <div class="complete-todo">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 457.5 457.5" xml:space="preserve" class="todo-icon popular">
                                            <path d="M300.15,222.7v5.2c0,39.6-31.8,71.9-71.4,71.9s-71.4-31.8-71.4-71.4v-5.7v-52.2v-52.1v-37
                                                c0-39.1,31.8-71.4,71.4-71.4c19.8,0,37.6,7.9,50.6,20.9s20.9,30.8,20.9,50.6v37v52v52.2H300.15z"></path>
                                            <path d="M309.75,227.9V81.4c0-21.8-8.2-42.3-23.6-57.6C270.85,8.5,250.65,0,228.85,0c-44.9,0-81.1,36.5-81.1,81.4v147
                                                c0,21.8,8.2,42.3,23.6,57.6c15.3,15.3,35.5,23.8,57.3,23.8C273.55,309.8,309.75,273.1,309.75,227.9z M167.45,232.5h37.8
                                                c5.5,0,10-4.5,10-10s-4.5-10-10-10h-37.5v-32h37.5c5.5,0,10-4.5,10-10s-4.5-10-10-10h-37.5v-32h37.5c5.5,0,10-4.5,10-10
                                                s-4.5-10-10-10h-37.5V81.4c0-30.5,21.9-55.8,51-60.6v43.4c0,5.5,4.5,10,10,10s10-4.5,10-10V20.8c12.7,2,24.1,7.9,33.3,17.1
                                                c11.6,11.6,17.7,27,17.7,43.5v27.1h-37.5c-5.5,0-10,4.5-10,10s4.5,10,10,10h37.5v32h-37.5c-5.5,0-10,4.5-10,10s4.5,10,10,10h37.5v32
                                                h-37.5c-5.5,0-10,4.5-10,10s4.5,10,10,10h37.8c-2.4,32-29,57.3-61.2,57.3c-16.5,0-31.9-6.4-43.5-18
                                                C174.65,261.2,168.45,247.5,167.45,232.5z"></path>
                                            <path d="M354.75,159.5c-5.5,0-10,4.5-10,10v58.9c0,31.1-12,60.3-33.9,82.2s-51.1,34-82.2,34s-60.2-12.1-82.1-34
                                                c-21.8-21.9-33.8-51.1-33.8-82.2v-58.9c0-5.5-4.5-10-10-10s-10,4.5-10,10v58.9c0,36.5,14.1,70.7,39.7,96.3
                                                c23.3,23.3,53.5,37.1,86.3,39.5v73.3h-46.8c-5.5,0-10,4.5-10,10s4.5,10,10,10h113.7c5.5,0,10-4.5,10-10s-4.5-10-10-10h-46.9v-73.3
                                                c32.7-2.3,63-16.1,86.3-39.5c25.7-25.7,39.7-59.9,39.7-96.4v-58.8C364.75,164,360.25,159.5,354.75,159.5z"></path>
                                            </svg>
                                        <div class="todo-description">
                                            <h3 class="todo-header" data-translate-text="LB_SIGNUP_MAKE_MUSIC">{{ __('web.LB_SIGNUP_MAKE_MUSIC') }}</h3>
                                            <p class="todo-text" data-translate-text="LB_SIGNUP_MAKE_MUSIC_SUBTEXT">{{ __('web.LB_SIGNUP_MAKE_MUSIC_SUBTEXT') }}</p>
                                        </div>
                                        <a class="btn btn-secondary create-artist" data-translate-text="CLAIM_ARTIST">{{ __('web.CLAIM_ARTIST') }}</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @if(! config('settings.disable_register'))
                    <div class="lightbox-footer">
                        <div class="right">
                            <a class="btn btn-primary close hide" data-translate-text="CLOSE">{{ __('web.CLOSE') }}</a>
                            <button class="btn btn-primary" type="submit" data-translate-text="SIGN_UP">{{ __('web.SIGN_UP') }}</button>
                        </div>
                        <div class="left"></div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-dob-update hide">
        <div class="lbcontainer">
            <form id="dob-update-form" class="ajax-form" method="POST" action="{{ route('frontend.auth.user.dob.update') }}">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="POPUP_UPDATE_DOB">{{ __('web.POPUP_UPDATE_DOB') }}</h2>
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-error error hide"></div>
                    @if(config('settings.dob_signup'))
                        <div class="row">
                            <div class="control control-group col-lg-12 col-12">
                                <label class="control-label" data-translate-text="FORM_DOB">{{ __('web.FORM_DOB') }}</label>
                                <div class="controls">
                                    <select id="signup-dob-month" name="dob-month" class="span2">
                                        @foreach(explode(',', __('web.MONTHS')) as $key => $value)
                                            <option value="{{ $loop->iteration }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <select id="signup-dob-day" name="dob-day" class="span1">
                                        @for ($i = 31; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                        <option value="1">1</option>
                                    </select>
                                    <select id="signup-dob-year" name="dob-year" class="span2">
                                        @for ($i = \Carbon\Carbon::now()->format('Y'); $i >= 1911; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="control control-group col-lg-16 col-12">
                                <label class="control-label" data-translate-text="COUNTRY">Country:</label>
                                <div class="controls">
                                    <select name="country" class="span3 select2" style="display: none"><option disabled selected value></option>
                                        @foreach(\App\Models\Country::all() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="control control-group col-lg-6 col-12">
                                <label class="control-label" data-translate-text="FORM_SEX_PROMPT">I identify as:</label>
                                <div class="controls radio-container d-flex">
                                    <div class="d-flex mr-3">
                                        <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-f2" value="F">
                                        <label class="cbx" for="settings-gender-f2"></label>
                                        <label class="lbl" for="settings-gender-f2" data-translate-text="FORM_SEX_WOMAN">{{ __('web.FORM_SEX_WOMAN') }}</label>
                                    </div>
                                    <div class="d-flex mr-3">
                                        <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-m2" value="M">
                                        <label class="cbx" for="settings-gender-m2"></label>
                                        <label class="lbl" for="settings-gender-m" data-translate-text="FORM_SEX_MAN">{{ __('web.FORM_SEX_MAN') }}</label>
                                    </div>
                                    <div class="d-flex">
                                        <input class="hide custom-checkbox" type="radio" name="gender" id="settings-gender-o2" value="O">
                                        <label class="cbx" for="settings-gender-o2"></label>
                                        <label class="lbl" for="settings-gender-o2" data-translate-text="FORM_SEX_OTHER">{{ __('web.FORM_SEX_OTHER') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary" type="submit" data-translate-text="SAVE">{{ __('web.SAVE') }}</button>
                    </div>
                    <div class="left"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-forget hide">
        <div class="lbcontainer">
            <form id="forgot-form" class="ajax-form" method="post" action="{{ route('frontend.account.send.request.reset.password') }}">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="LB_FORGET_TITLE">{{ __('web.LB_FORGET_TITLE') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide">
                            <div class="message"></div>
                        </div>
                        <p data-translate-text="LB_FORGET_SUBTITLE">{{ __('web.LB_FORGET_SUBTITLE') }}</p>
                        <div class="control-group">
                            <label class="control-label" for="username" data-translate-text="LB_FORGET_USERNAME">{{ __('web.LB_FORGET_USERNAME') }}</label>
                            <div class="controls"><input class="login-text" id="forget-email" name="email" type="text"></div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="left-btns"><a class="btn btn-secondary close" data-translate-text="CANCEL">{{ __('web.CANCEL') }}</a></div>
                    <div class="right-btns"><button type="submit" class="btn btn-primary" data-translate-text="SUBMIT">{{ __('web.SUBMIT') }}</button></div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-createPlaylist hide">
        <div class="lbcontainer">
            <div id="create-playlist">
                <form id="create-playlist-form" method="post" action="{{ route('frontend.auth.user.create.playlist') }}" enctype="multipart/form-data" novalidate>
                    <div class="lightbox-header">
                        <h2 class="title" data-translate-text="POPUP_PLAYLIST_METADATA_CREATE_NEW">{{ __('web.POPUP_PLAYLIST_METADATA_CREATE_NEW') }}</h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>
                            <div class="lightbox-with-artwork-block">
                                <div class="img-container">
                                    <img class="img" src="{{ asset('common/default/playlist.png') }}"/>
                                    <div class="artwork-select">
                                        <span>{{ __('web.EDIT') }}</span>
                                        <input class="input-playlist-artwork" name="artwork" accept="image/*" title="" type="file">
                                    </div>
                                </div>
                                <div class="input-container">
                                    <div class="control field">
                                        <label for="name">
                                            <span data-translate-text="NAME">Name:</span>
                                        </label>
                                        <input name="playlistName" type="text" required>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="GENRES">Genres:</span>
                                        </label>
                                        <select class="select2" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="MOODS">Moods:</span>
                                        </label>
                                        <select class="select2" name="mood[]" placeholder="Select moods" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="DESCRIPTION">Description:</span>
                                        </label>
                                        <textarea type="text" name="description" maxlength="180"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button class="btn btn-primary" type="submit" data-translate-text="POPUP_PLAYLIST_METADATA_CREATE">{{ __('web.POPUP_PLAYLIST_METADATA_CREATE') }}</button>
                        </div>
                        <div class="left">
                            <div class="row ml-0 mr-0 mt-2">
                                <input class="hide custom-checkbox" id="create-playlist-checkbox" type="checkbox" name="visibility" checked="checked">
                                <label class="cbx" for="create-playlist-checkbox"></label>
                                <label class="lbl" for="create-playlist-checkbox">{{ __('web.MAKE_PUBLIC') }}</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-rename hide">
        <div class="lbcontainer">
            <form id="edit-playlist-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.playlist.edit') }}" novalidate>
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="POPUP_PLAYLIST_META_TITLE">{{ __('web.POPUP_PLAYLIST_META_TITLE') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide response">
                            <div class="message"></div>
                        </div>
                        <div class="lightbox-with-artwork-block">
                            <div class="img-container">
                                <img class="img" src="{{ asset('common/default/playlist.png') }}"/>
                                <div class="artwork-select">
                                    <span>{{ __('web.EDIT') }}</span>
                                    <input class="input-playlist-artwork" name="artwork" accept="image/*" title="" type="file">
                                </div>
                            </div>
                            <div class="input-container">
                                <div class="control field">
                                    <label for="name">
                                        <span data-translate-text="NAME">{{ __('web.NAME') }}</span>
                                    </label>
                                    <input name="title" type="text" required>
                                    <input name="id" type="hidden">
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="GENRES">{{ __('web.GENRES') }}</span>
                                    </label>
                                    <select class="select2" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                                    </select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="MOODS">{{ __('web.MOODS') }}</span>
                                    </label>
                                    <select class="select2" name="mood[]" placeholder="Select moods" multiple autocomplete="off">
                                    </select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="DESCRIPTION">{{ __('web.DESCRIPTION') }}</span>
                                    </label>
                                    <textarea type="text" name="description" maxlength="180"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary" type="submit" data-translate-text="SAVE_CHANGES">{{ __('web.SAVE_CHANGES') }}</button>
                    </div>
                    <div class="left">
                        <div class="row ml-0 mr-0 mt-2">
                            <input class="hide custom-checkbox" id="edit-playlist-checkbox" type="checkbox" name="visibility">
                            <label class="cbx" for="edit-playlist-checkbox"></label>
                            <label class="lbl" for="edit-playlist-checkbox">{{ __('web.MAKE_PUBLIC') }}</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-delete hide">
        <div class="lbcontainer">
            <div id="deletePlaylist">
                <form class="ajax-form" method="post" action="/more.php?do=playlist&action=deletePlaylist" novalidate>
                    <div class="lightbox-header">
                        <h2 class="title" data-translate-text="POPUP_DELETE_PLAYLIST_TITLE">Delete Playlist?</h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide response">
                                <div class="message"></div>
                            </div>
                            <p data-translate-text="POPUP_DELETE_PLAYLIST_MESSAGE">{{ __('web.POPUP_DELETE_PLAYLIST_MESSAGE') }}</p>
                            <input name="id" type="hidden">
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right"><button class="btn btn-primary" type="submit" data-translate-text="DELETE">{{ __('web.DELETE') }}</button></div>
                        <div class="left"><a class="btn btn-secondary close" data-translate-text="CANCEL">{{ __('web.CANCEL') }}</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-share hide">
        <div class="lbcontainer">
            <form id="share-lightbox">
                <div class="lightbox-header lightbox-nav-container">
                    <ul class="lightbox-nav">
                        <li id="share-embed" class="share-svc active pl-3 pr-3" data-share-svc="embed" data-translate-text="LB_SITE_PLAYER">{{ __('web.LB_SHARE_EMBED') }}</li>
                        <li id="share-facebook" class="share-svc pl-3 pr-3" data-share-svc="facebook">Facebook</li>
                        <li id="share-twitter" class="share-svc pl-3 pr-3" data-share-svc="twitter">Twitter</li>
                        <li id="share-more" class="share-svc pl-3 pr-3" data-share-svc="more" data-translate-text="MORE">{{ __('web.MORE') }}</li>
                    </ul>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-error error hide"></div>
                    <div class="lightbox-content-block">
                        <div class="error hide"></div>
                        <div id="share-svc">
                            <div class="svc-box embed">
                                <div class="row">
                                    <div class="col-4 nav-widget active" data-widget="picture">
                                        <div class="nav-widget-block">Picture</div>
                                    </div>
                                    <div class="col-4 nav-widget" data-widget="classic">
                                        <div class="nav-widget-block">Classic</div>
                                    </div>
                                    <div class="col-4 nav-widget" data-widget="mini">
                                        <div class="nav-widget-block">Mini</div>
                                    </div>
                                </div>
                                <iframe id="svc-embed-iframe" frameborder="0"></iframe>
                                <div class="embed-options">
                                    <div class="embed-widget-theme dark" data-theme="dark">Dark</div>
                                    <div class="embed-widget-theme light" data-theme="light">Light</div>
                                </div>
                                <div class="embed-code-container"><label for="embed-code" data-translate-text="EMBED_CODE"></label>
                                    <input id="embed-code" name="embed-code" readonly="readonly">
                                </div>
                            </div>
                            <div class="svc-box facebook hide">
                                <p class="mb-0" data-translate-text="LB_SHARE_FACEBOOK_MSG">{!! __('web.LB_SHARE_FACEBOOK_MSG') !!}</p>
                            </div>
                            <div class="svc-box twitter hide">
                                <p class="label" data-translate-text="BROADCAST_TWEET">{{ __('web.BROADCAST_TWEET') }}</p><textarea id="share-message-tw"></textarea><span id="twitter-char-count" class="">45</span>
                            </div>
                            <div class="svc-box more hide">
                                <p data-translate-text="LB_SHARE_MORE_MSG">{{ __('web.LB_SHARE_MORE_MSG') }}</p>
                                <div class="share-more-option reddit">
                                    <div class="icon-container icon-reddit">
                                        <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m21.325 9.308c-.758 0-1.425.319-1.916.816-1.805-1.268-4.239-2.084-6.936-2.171l1.401-6.406 4.461 1.016c0 1.108.89 2.013 1.982 2.013 1.113 0 2.008-.929 2.008-2.038s-.889-2.038-2.007-2.038c-.779 0-1.451.477-1.786 1.129l-4.927-1.108c-.248-.067-.491.113-.557.365l-1.538 7.062c-2.676.113-5.084.928-6.895 2.197-.491-.518-1.184-.837-1.942-.837-2.812 0-3.733 3.829-1.158 5.138-.091.405-.132.837-.132 1.268 0 4.301 4.775 7.786 10.638 7.786 5.888 0 10.663-3.485 10.663-7.786 0-.431-.045-.883-.156-1.289 2.523-1.314 1.594-5.115-1.203-5.117zm-15.724 5.41c0-1.129.89-2.038 2.008-2.038 1.092 0 1.983.903 1.983 2.038 0 1.109-.89 2.013-1.983 2.013-1.113.005-2.008-.904-2.008-2.013zm10.839 4.798c-1.841 1.868-7.036 1.868-8.878 0-.203-.18-.203-.498 0-.703.177-.18.491-.18.668 0 1.406 1.463 6.07 1.488 7.537 0 .177-.18.491-.18.668 0 .207.206.207.524.005.703zm-.041-2.781c-1.092 0-1.982-.903-1.982-2.011 0-1.129.89-2.038 1.982-2.038 1.113 0 2.008.903 2.008 2.038-.005 1.103-.895 2.011-2.008 2.011z"/></svg>
                                    </div>
                                    <span class="title">Reddit</span>
                                    <a target="_blank" class="btn btn-secondary" data-translate-text="SHARE">{{ __('web.SHARE') }}</a>
                                </div>
                                <div class="share-more-option pinterest">
                                    <div class="icon-container icon-pinterest">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 40 40">
                                            <g>
                                                <path d="m37.3 20q0 4.7-2.3 8.6t-6.3 6.2-8.6 2.3q-2.4 0-4.8-0.7 1.3-2 1.7-3.6 0.2-0.8 1.2-4.7 0.5 0.8 1.7 1.5t2.5 0.6q2.7 0 4.8-1.5t3.3-4.2 1.2-6.1q0-2.5-1.4-4.7t-3.8-3.7-5.7-1.4q-2.4 0-4.4 0.7t-3.4 1.7-2.5 2.4-1.5 2.9-0.4 3q0 2.4 0.8 4.1t2.7 2.5q0.6 0.3 0.8-0.5 0.1-0.1 0.2-0.6t0.2-0.7q0.1-0.5-0.3-1-1.1-1.3-1.1-3.3 0-3.4 2.3-5.8t6.1-2.5q3.4 0 5.3 1.9t1.9 4.7q0 3.8-1.6 6.5t-3.9 2.6q-1.3 0-2.2-0.9t-0.5-2.4q0.2-0.8 0.6-2.1t0.7-2.3 0.2-1.6q0-1.2-0.6-1.9t-1.7-0.7q-1.4 0-2.3 1.2t-1 3.2q0 1.6 0.6 2.7l-2.2 9.4q-0.4 1.5-0.3 3.9-4.6-2-7.5-6.3t-2.8-9.4q0-4.7 2.3-8.6t6.2-6.2 8.6-2.3 8.6 2.3 6.3 6.2 2.3 8.6z"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="title">Pinterest</span>
                                    <a target="_blank" class="btn btn-secondary" data-translate-text="SHARE">{{ __('web.SHARE') }}</a>
                                </div>
                                <div class="share-more-option linkedin">
                                    <div class="icon-container icon-linkedin">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 40 40">
                                            <g>
                                                <path d="m13.3 31.7h-5v-16.7h5v16.7z m18.4 0h-5v-8.9c0-2.4-0.9-3.5-2.5-3.5-1.3 0-2.1 0.6-2.5 1.9v10.5h-5s0-15 0-16.7h3.9l0.3 3.3h0.1c1-1.6 2.7-2.8 4.9-2.8 1.7 0 3.1 0.5 4.2 1.7 1 1.2 1.6 2.8 1.6 5.1v9.4z m-18.3-20.9c0 1.4-1.1 2.5-2.6 2.5s-2.5-1.1-2.5-2.5 1.1-2.5 2.5-2.5 2.6 1.2 2.6 2.5z"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="title">LinkedIn</span>
                                    <a target="_blank" class="btn btn-secondary" data-translate-text="SHARE">{{ __('web.SHARE') }}</a>
                                </div>
                                <div class="share-more-option email">
                                    <div class="icon-container icon-email">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" preserveAspectRatio="xMidYMid meet" height="1em" width="1em" viewBox="0 0 40 40">
                                            <g>
                                                <path d="m33.4 13.4v-3.4l-13.4 8.4-13.4-8.4v3.4l13.4 8.2z m0-6.8q1.3 0 2.3 1.1t0.9 2.3v20q0 1.3-0.9 2.3t-2.3 1.1h-26.8q-1.3 0-2.3-1.1t-0.9-2.3v-20q0-1.3 0.9-2.3t2.3-1.1h26.8z"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <span class="title">Email</span>
                                    <a target="_blank" class="btn btn-secondary" data-translate-text="SHARE">{{ __('web.SHARE') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div id="share-link">
                        <div id="icon-background">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="34" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none"/>
                                <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
                            </svg>
                        </div>
                        <input id="share-lightbox-url" class="share-url" readonly="readonly">
                        <div class="copy-background">
                            <a class="btn" id="share-lightbox-copy" data-translate-text="SHARE_COPY">{{ __('web.SHARE_COPY') }}</a>
                        </div>
                    </div>
                    <div class="right-btns">
                        <a class="btn btn-primary submit" data-translate-text="SHARE">{{ __('web.SHARE') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-feedback hide">
        <div class="lbcontainer">
            <form id="feedback-form" class="ajax-form" method="post" action="{{ route('frontend.feedback') }}">
                <div class="lightbox-header">
                    <h2 class="title">Feedback</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide"></div>
                        <p data-translate-text="LB_FEEDBACK_MSG">{{ __('web.LB_FEEDBACK_MSG') }}</p>
                        <div class="field">
                            <label for="email" class="label" data-translate-text="LB_FEEDBACK_EMAIL">{{ __('web.LB_FEEDBACK_EMAIL') }}</label>
                            <input id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}" type="text">
                        </div>
                        <div class="field"><label for="feedback_feeling" class="label" data-translate-text="LB_FEEDBACK_FEELING">{{ __('web.LB_FEEDBACK_FEELING') }}</label><select id="feedback_feeling" class="feeling select2" name="feeling"><option value="">—</option><option>Angry</option><option>Confused</option><option>Happy</option><option>Amused</option><option>Impressed</option><option>Surprised</option><option>Disappointed</option><option>Frustrated</option><option>Curious</option><option>Indifferent</option></select></div>
                        <div class="field"><label for="feedback_about" class="label" data-translate-text="LB_FEEDBACK_ABOUT">{{ __('web.LB_FEEDBACK_ABOUT') }}</label><select id="feedback_about" class="about select2" name="about"><option value="">—</option><option value="Website">Website</option><option value="General">General Question</option><optgroup label="Mobile Devices"><option>iPhone</option><option>Android</option></optgroup></select></div>
                        <div class="field"><label for="comment" class="label" data-translate-text="LB_FEEDBACK_REPORT">{{ __('web.LB_FEEDBACK_REPORT') }}</label>
                            <div class="textarea_wrapper clear">
                                <div class="inner">
                                    <div class="cap"><textarea id="comment" type="text" name="comment"></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary submit" data-translate-text="SUBMIT" type="submit">{{ __('web.SUBMIT') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-payments hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <ul class="lightbox-nav lightbox-nav-container nav nav-tabs" role="tablist">
                    @if(config('settings.payment_stripe'))
                    <li class="nav-item lightbox-tab">
                        <button class="nav-link" data-toggle="tab" href="#stripe-payment" role="tab" aria-controls="stripe-payment" aria-selected="true">Stripe</button>
                    </li>
                    @endif
                    @if(config('settings.payment_paypal'))
                        <li class="nav-item lightbox-tab">
                            <button class="nav-link" data-toggle="tab" href="#paypal-payment" role="tab" aria-controls="paypal-payment" aria-selected="false">Paypal</button>
                        </li>
                    @endif
                    @if(is_array(config('payment.gateway')))
                        @foreach(config('payment.gateway') as $gateway)
                            <li class="nav-item lightbox-tab">
                                <button class="nav-link" data-toggle="tab" href="#{{ str_slug($gateway['name']) }}-payment" role="tab" aria-controls="{{ str_slug($gateway['name']) }}-payment" aria-selected="false">{{ $gateway['name'] }}</button>
                            </li>
                        @endforeach
                    @endif
                </ul>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-error error hide"></div>
                <div class="lightbox-content-block">
                    <div class="lightbox-trial alert alert-info hide">{!! __('web.PAYMENT_SUBSCRIPTION_TIP') !!}</div>
                    <div id="confirm-container" class="control-group">
                        <div class="header">
                            <p class="description" data-translate-text="DESCRIPTION">{{ __('web.FORM_DESCRIPTION') }}</p>
                            <p class="price" data-translate-text="AMOUNT">{{ __('web.FORM_AMOUNT') }}</p>
                        </div>
                        <div class="product">
                            <p class="description"></p>
                            <p class="price">0</p>
                        </div>
                        <div class="total">
                            <p class="description">{{ __('web.FORM_TOTAL') }}</p>
                            <p class="price"></p>
                        </div>
                    </div>
                    <p class="security-reassurance text-center">{{ __('web.PAYMENT_SECURITY_TITLE') }}</p>
                    <p class="text-secondary small mt-5">{{ __('web.PAYMENT_SECURITY_DESC') }}</p>

                    <div class="tab-content">
                        @if(config('settings.payment_stripe'))
                            <div class="control-group tab-pane fade" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment-tab">
                                <form id="payment-form" method="post">
                                    <div class="form-row">
                                        <label for="card-element" class="card-element-helper">
                                            {{ __('web.PAYMENT_STRIPE_TIP') }}
                                        </label>
                                        <div id="card-element">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert"></div>
                                    </div>
                                    <button id="stripe-get-token-submit" class="hide" type="submit"></button>
                                </form>
                                <form id="stripe-form" class="ajax-form stripe-local-form" method="post" action="{{ route('frontend.stripe.subscription.callback') }}">
                                    <input class="plan-id" name="planId" type="hidden">
                                    <input class="stripeToken" name="stripeToken" type="hidden">
                                </form>
                                <div class="d-flex justify-content-center align-items-center mt-3">
                                    <button id="stripe-form-submit-button" class="btn btn-primary">
                                        <span>{{ __('web.SUBMIT_PAYMENT') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if(config('settings.payment_paypal'))
                            <div class="control-group tab-pane fade" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment-tab">
                                <p class="text-center" data-translate-text="OPEN_PAYPAL_CTA">{{ __('web.PAYMENT_PAYPAL_TIP') }}</p>
                                <div class="row justify-content-center align-items-center">
                                    <a class="btn btn-secondary btn-paypal btn-payment mt-3" data-purchase-uri="frontend.paypal.purchase" data-subscription-uri="frontend.paypal.subscription" data-translate-text="OPEN_PAYPAL">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g id="surface723641"><path style=" stroke:none;fill-rule:nonzero;fill:rgb(8.235294%,39.607843%,75.294118%);fill-opacity:1;" d="M 9.351562 6.882812 C 9.40625 6.664062 9.59375 6.5 9.828125 6.5 L 16.566406 6.5 C 16.574219 6.5 16.582031 6.496094 16.589844 6.496094 C 16.449219 4.109375 14.445312 3 12.675781 3 L 5.9375 3 C 5.703125 3 5.511719 3.167969 5.460938 3.386719 L 2.515625 16.90625 L 2.519531 16.90625 C 2.515625 16.9375 2.5 16.96875 2.5 17.003906 C 2.5 17.28125 2.726562 17.5 3 17.5 L 7.035156 17.5 Z M 9.351562 6.882812 "/><path style=" stroke:none;fill-rule:nonzero;fill:rgb(1.176471%,60.784314%,89.803922%);fill-opacity:1;" d="M 16.589844 6.496094 C 16.617188 6.933594 16.589844 7.410156 16.476562 7.9375 C 15.835938 10.933594 13.519531 12.496094 10.660156 12.496094 C 10.660156 12.496094 8.925781 12.496094 8.503906 12.496094 C 8.242188 12.496094 8.121094 12.648438 8.0625 12.765625 L 7.191406 16.789062 L 7.039062 17.503906 L 6.40625 20.402344 L 6.414062 20.402344 C 6.40625 20.433594 6.394531 20.464844 6.394531 20.5 C 6.394531 20.777344 6.617188 21 6.894531 21 L 10.558594 21 L 10.566406 20.996094 C 10.800781 20.992188 10.988281 20.824219 11.039062 20.601562 L 11.046875 20.59375 L 11.953125 16.386719 C 11.953125 16.386719 12.015625 15.984375 12.4375 15.984375 C 12.859375 15.984375 14.527344 15.984375 14.527344 15.984375 C 17.390625 15.984375 19.726562 14.429688 20.367188 11.433594 C 21.089844 8.054688 18.679688 6.507812 16.589844 6.496094 Z M 16.589844 6.496094 "/><path style=" stroke:none;fill-rule:nonzero;fill:rgb(15.686275%,20.784314%,57.647059%);fill-opacity:1;" d="M 9.828125 6.5 C 9.59375 6.5 9.402344 6.664062 9.351562 6.882812 L 8.0625 12.765625 C 8.117188 12.648438 8.242188 12.496094 8.503906 12.496094 C 8.925781 12.496094 10.621094 12.496094 10.621094 12.496094 C 13.480469 12.496094 15.835938 10.9375 16.476562 7.9375 C 16.589844 7.410156 16.617188 6.933594 16.589844 6.496094 C 16.582031 6.496094 16.574219 6.5 16.566406 6.5 Z M 9.828125 6.5 "/></g></svg>
                                        <span>Open PayPal</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(is_array(config('payment.gateway')))
                            @foreach(config('payment.gateway') as $gateway)
                                <div class="control-group tab-pane fade" id="{{ str_slug($gateway['name']) }}-payment" role="tabpanel" aria-labelledby="{{ str_slug($gateway['name']) }}-payment-tab">
                                    <p class="text-center">{{ __('web.PAYMENT_GATEWAY_TIP', ['name' => $gateway['name']]) }}</p>
                                    <div class="row justify-content-center align-items-center">
                                        <a class="btn btn-payment mt-3" style="background: {{ $gateway['buttonColor'] }}" data-purchase-uri="{{ $gateway['purchaseLink'] }}" data-subscription-uri="{{ $gateway['subscriptionLink'] }}" target="_blank">
                                            {!! $gateway['buttonIcon']  !!}
                                            <span style="color: {{ $gateway['color'] }}">Pay with {{ $gateway['name'] }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-contact hide">
        <div class="lbcontainer">
            <form id="feedback-lightbox">
                <div class="lightbox-header">
                    <h2 class="title">Contact Support</h2>
                    @yield('lightbox-close')
                </div>
                <input type="hidden" name="feedbackType" value="">
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide"></div>
                        <p data-translate-text="LB_BILLING_FEEDBACK_MSG">{{ __('web.LB_BILLING_FEEDBACK_MSG') }}</p>
                        <div class="field">
                            <label for="email" class="label" data-translate-text="LB_FEEDBACK_EMAIL">{{ __('web.LB_FEEDBACK_EMAIL') }}</label>
                            <input type="text" name="email" value=""></div>
                        <div class="field">
                            <label for="feedback" class="label" data-translate-text="LB_BILLING_FEEDBACK_REPORT">{{ __('web.LB_BILLING_FEEDBACK_REPORT') }}</label>
                            <div class="textarea_wrapper clear">
                                <div class="top">
                                    <div class="cap"></div>
                                </div>
                                <div class="inner">
                                    <div class="cap"><textarea id="feedback" type="text" name="feedback"></textarea></div>
                                </div>
                                <div class="bottom">
                                    <div class="cap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right"><a class="btn btn-primary submit" data-translate-text="SUBMIT">{{ __('web.SUBMIT') }}</a></div>
                    <div class="left"></div>
                </div>
                <button class="hide" type="submit"></button>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-invite-collaborate hide">
        <div class="lbcontainer">
            <form id="invite-collaborators-lightbox">
                <div class="lightbox-header">
                    <h2 class="title">Invite Collaborators</h2>
                    @yield('lightbox-close')
                </div>
                <input type="hidden" name="feedbackType" value="">
                <div class="lightbox-content">
                    <div class="lightbox-error error hide"></div>
                    <div class="lightbox-content-block">
                        <div class="error hide"></div>
                        <div id="friends-can-collaborate">
                            <p class="invite-collaborate-loading" data-translate-text="PLEASE_WAIT">{{ __('web.PLEASE_WAIT') }}</p>
                            <div class="invite-to-collaborate hide">
                                <div class="img-container">
                                    <img class="img"/>
                                </div>
                                <span class="title"></span>
                                <a class="btn btn-primary invite-friend align-self-center" data-translate-text="INVITE">{{ __('web.INVITE') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-locale hide">
        <div class="lbcontainer">
            <div id="choose-locale">
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="LANGUAGE">{{ __('web.LANGUAGE') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block  separated-content">
                        <ul class="languages row">
                            @if(Cache::has('languages'))
                                @foreach(Cache::get('languages') as $code => $language)
                                    <li class="language col-4"><a class="@if($code == \Session::get('website_language', config('app.locale'))) active @endif" rel="{{ $code }}">{{ $language }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="clear noHeight"></div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right"></div>
                    <div class="left"><a class="btn btn-secondary close" data-translate-text="CLOSE">{{ __('web.CLOSE') }}</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-edit-song hide">
        <form id="edit-song-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.song.edit.post') }}" enctype="multipart/form-data" novalidate>
            <div class="lbcontainer">
                <div id="upload-song">
                    <div class="lightbox-header">
                        <h2 class="title"></h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>

                            <div class="img-container">
                                <img class="img"/>
                                <div class="edit-artwork edit-song-artwork" data-type="song" data-id="900">
                                    <span>{{ __('web.EDIT') }}</span>
                                    <input class="edit-artwork-input" name="artwork" type="file" accept="image/*">
                                </div>
                            </div>
                            <div class="song-info-container">
                                <div class="control field">
                                    <label for="name">
                                        <span data-translate-text="FORM_TITLE">{{ __('web.FORM_TITLE') }}</span>
                                    </label>
                                    <input name="title" type="text" required>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="FORM_GENRES">{{ __('web.FORM_GENRES') }}</span>
                                    </label>
                                    <select class="select2" name="genre[]" multiple></select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="FORM_MOODS">{{ __('web.FORM_MOODS') }}</span>
                                    </label>
                                    <select class="select2" name="mood[]" multiple></select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="TAGS">{{ __('web.FORM_TAGS') }}</span>
                                    </label>
                                    <select name="tag[]" class="select2-tags"  multiple="multiple"></select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="FORM_DESCRIPTION">{{ __('web.FORM_DESCRIPTION') }}</span>
                                    </label>
                                    <textarea type="text" name="description" maxlength="180"></textarea>
                                </div>
                                <div class="control field">
                                    <label for="name">
                                        <span data-translate-text="FORM_RELEASED_AT">{{ __('web.FORM_RELEASED_AT') }}</span>
                                    </label>
                                    <input class="datepicker" name="released_at" type="text" placeholder="None" autocomplete="off">
                                </div>
                                <div class="control field">
                                    <label for="name">
                                        <span data-translate-text="FORM_COPYRIGHT">{{ __('web.FORM_COPYRIGHT') }}</span>
                                    </label>
                                    <input name="copyright" type="text" placeholder="Option" autocomplete="off">
                                </div>
                                <div class="control field d-none bpm-control">
                                    <label for="name">
                                        <span data-translate-text="FORM_BPM">{{ __('web.FORM_BPM') }}</span>
                                    </label>
                                    <input name="bpm" type="text" placeholder="eg: 128" autocomplete="off">
                                </div>
                                <div class="control field attachment-control d-none">
                                    <label for="bpm">
                                        <span data-translate-text="FORM_ATTACHMENT">{{ __('web.FORM_ATTACHMENT') }}</span>
                                    </label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="attachment" class="file-selector" accept=".zip,.rar">
                                        <input type="text" class="form-control input-lg" disabled="" placeholder="{{ __('web.FORM_ATTACHMENT_TIP') }}">
                                        <span class="input-group-btn">
                                    <button class="browse btn btn-secondary input-lg" type="button">{{ __('web.BROWSE') }}</button>
                                </span>
                                    </div>
                                </div>
                                <div class="control field mb-0">
                                    <div class="row ml-0 mr-0 mt-2 explicit-check-box">
                                        <input class="hide custom-checkbox" type="checkbox" name="explicit" id="edit-song-explicit">
                                        <label class="cbx" for="edit-song-explicit"></label>
                                        <label class="lbl" for="edit-song-explicit">{{ __('web.SONG_EXPLICIT') }}</label>
                                    </div>
                                </div>
                                <div class="control field mb-0">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row ml-0 mr-0 mt-2 visibility-check-box">
                                                <input class="hide custom-checkbox" type="checkbox" name="visibility" id="edit-song-visibility">
                                                <label class="cbx" for="edit-song-visibility"></label>
                                                <label class="lbl" for="edit-song-visibility">{{ __('web.MAKE_PUBLIC') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row ml-0 mr-0 mt-2 comments-check-box">
                                                <input class="hide custom-checkbox" type="checkbox" name="comments" id="edit-song-comments">
                                                <label class="cbx" for="edit-song-comments"></label>
                                                <label class="lbl" for="edit-song-comments">{{ __('web.ALLOW_COMMENTS') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row ml-0 mr-0 mt-2 downloadable-check-box">
                                                <input class="hide custom-checkbox" type="checkbox" name="downloadable" id="edit-song-downloadable">
                                                <label class="cbx" for="edit-song-downloadable"></label>
                                                <label class="lbl" for="edit-song-downloadable">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row ml-0 mr-0 mt-2 selling-check-box" data-toggle="collapse" href="#edit-song-collapse-id" role="button" aria-expanded="false" aria-controls="edit-song-collapse-id">
                                                <input class="hide custom-checkbox" type="checkbox" name="selling" id="edit-song-selling">
                                                <label class="cbx" for="edit-song-selling"></label>
                                                <label class="lbl" for="edit-song-selling">{{ __('web.SELL_THIS_SONG') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field collapse" id="edit-song-collapse-id">
                                    <label for="created_at">
                                        <span data-translate-text="FORM_PRICE">{{ __('web.FORM_PRICE') }} </span>
                                    </label>
                                    <input name="price" type="number" step="1" min="{{ \App\Models\Role::getValue('monetization_song_min_price') }}" max="{{ \App\Models\Role::getValue('monetization_song_max_price') }}" placeholder="{{ __('web.SELL_THIS_SONG_TIP') }}" autocomplete="off">
                                </div>
                            </div>
                            <input name="id" type="hidden">
                            <input name="type" value="song" type="hidden">
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button id="edit-song-save-btn" class="btn btn-primary" type="submit" data-translate-text="SAVE">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="lightbox lightbox-create-album hide">
        <div class="lbcontainer">
            <div id="create-playlist">
                <form id="create-album-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.albums.create') }}" enctype="multipart/form-data" novalidate>
                    <div class="lightbox-header">
                        <h2 class="title" data-translate-text="POPUP_ALBUM_METADATA_CREATE_NEW">{{ __('web.POPUP_ALBUM_METADATA_CREATE_NEW') }}</h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>
                            <div class="lightbox-with-artwork-block">
                                <div class="img-container">
                                    <img class="img" src="{{ asset('common/default/album.png') }}" data-default-artwork="{{ asset('artworks/defaults/album.png') }}"/>
                                    <div class="control artwork-select">
                                        <span>{{ __('web.EDIT') }}</span>
                                        <input class="input-album-artwork" name="artwork" accept="image/*" title="" type="file">
                                    </div>
                                </div>
                                <div class="input-container">
                                    <div class="control field">
                                        <label for="title">
                                            <span data-translate-text="FORM_TITLE">{{ __('FORM_TITLE') }}</span>
                                        </label>
                                        <input name="title" type="text" required>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="{{ __('FORM_GENRE') }}">{{ __('FORM_GENRE') }}</span>
                                        </label>
                                        <select class="select2" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="FORM_MOOD">{{ __('FORM_MOOD') }}</span>
                                        </label>
                                        <select class="select2" name="mood[]" placeholder="Select moods" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label for="type">
                                            <span data-translate-text="FORM_TYPE">{{ __('web.FORM_TYPE') }}</span>
                                        </label>
                                        {!! makeDropDown(array(
                                            1 => __('web.ALBUM_TYPE_LP'),
                                            2 => __('web.ALBUM_TYPE_SINGLE'),
                                            3 => __('web.ALBUM_TYPE_EP'),
                                            4 => __('web.ALBUM_TYPE_COMPILATION'),
                                            5 => __('web.ALBUM_TYPE_SOUNDTRACK'),
                                            6 => __('web.ALBUM_TYPE_SPOKENWORD'),
                                            7 => __('web.ALBUM_TYPE_INTERVIEW'),
                                            8 => __('web.ALBUM_TYPE_LIVE'),
                                            9 => __('web.ALBUM_TYPE_REMIX'),
                                            10 => __('web.ALBUM_TYPE_OTHER'),
                                        ), 'type', null, true) !!}
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="FORM_DESCRIPTION">{{ __('FORM_DESCRIPTION') }}</span>
                                        </label>
                                        <textarea type="text" name="description" maxlength="180"></textarea>
                                    </div>
                                    <div class="control field">
                                        <label for="name">
                                            <span data-translate-text="FORM_RELEASED_AT">{{ __('FORM_RELEASED_AT') }}</span>
                                        </label>
                                        <input class="datepicker" name="released_at" type="text" placeholder="Today" autocomplete="off">
                                    </div>
                                    <div class="control field">
                                        <label for="created_at">
                                            <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('FORM_SCHEDULE_PUBLISH') }}</span>
                                        </label>
                                        <input class="datepicker" name="created_at" type="text" placeholder="Immediately" autocomplete="off">
                                    </div>
                                    <div class="control field">
                                        <label for="name">
                                            <span data-translate-text="FORM_COPYRIGHT">{{ __('FORM_COPYRIGHT') }}</span>
                                        </label>
                                        <input name="copyright" type="text" placeholder="Option" autocomplete="off">
                                    </div>
                                    <div class="control field mb-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row ml-0 mr-0 mt-2">
                                                    <input class="hide custom-checkbox" type="checkbox" name="visibility" id="create-album-visibility" checked>
                                                    <label class="cbx" for="create-album-visibility"></label>
                                                    <label class="lbl" for="create-album-visibility">{{ __('web.MAKE_PUBLIC') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row ml-0 mr-0 mt-2">
                                                    <input class="hide custom-checkbox" type="checkbox" name="comments" id="create-album-comments" checked>
                                                    <label class="cbx" for="create-album-comments"></label>
                                                    <label class="lbl" for="create-album-comments">{{ __('web.ALLOW_COMMENTS') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control field">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row ml-0 mr-0 mt-2" data-toggle="collapse" href="#create-album-collapse-id" role="button" aria-expanded="false" aria-controls="create-album-collapse-id">
                                                    <input class="hide custom-checkbox" type="checkbox" name="selling" id="create-album-selling">
                                                    <label class="cbx" for="create-album-selling"></label>
                                                    <label class="lbl" for="create-album-selling">{{ __('web.SELL_THIS_ALBUM') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control field collapse" id="create-album-collapse-id">
                                        <label for="created_at">
                                            <span data-translate-text="FORM_PRICE">{{ __('web.FORM_PRICE') }} </span>
                                        </label>
                                        <input name="price" type="number" step="1" min="{{ \App\Models\Role::getValue('monetization_album_min_price') }}" max="{{ \App\Models\Role::getValue('monetization_album_max_price') }}" placeholder="{{ __('web.SELL_THIS_SONG_TIP') }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button class="btn btn-primary" type="submit" data-translate-text="POPUP_ALBUM_METADATA_CREATE">{{ __('web.POPUP_ALBUM_METADATA_CREATE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-edit-album hide">
        <div class="lbcontainer">
            <div id="create-playlist">
                <form id="edit-album-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.albums.edit') }}" enctype="multipart/form-data" novalidate>
                    <div class="lightbox-header">
                        <h2 class="title"></h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>
                            <input name="id" type="hidden">
                            <div class="lightbox-with-artwork-block">
                                <div class="img-container">
                                    <img class="img" src="{{ asset('common/default/album.png') }}" data-default-artwork="{{ asset('artworks/defaults/album.png') }}"/>
                                    <div class="control artwork-select">
                                        <span>{{ __('web.EDIT') }}</span>
                                        <input class="edit-artwork-input" name="artwork" accept="image/*" title="" type="file">
                                    </div>
                                </div>
                                <div class="input-container">
                                    <div class="control field">
                                        <label for="title">
                                            <span data-translate-text="FORM_TITLE">{{ __('FORM_TITLE') }}</span>
                                        </label>
                                        <input name="title" type="text" required>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="{{ __('FORM_GENRE') }}">{{ __('FORM_GENRE') }}</span>
                                        </label>
                                        <select class="select2" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="FORM_MOOD">{{ __('FORM_MOOD') }}</span>
                                        </label>
                                        <select class="select2" name="mood[]" placeholder="Select moods" multiple autocomplete="off">
                                        </select>
                                    </div>
                                    <div class="control field">
                                        <label for="type">
                                            <span data-translate-text="FORM_TYPE">{{ __('web.FORM_TYPE') }}</span>
                                        </label>
                                        {!! makeDropDown(array(
                                            1 => __('web.ALBUM_TYPE_LP'),
                                            2 => __('web.ALBUM_TYPE_SINGLE'),
                                            3 => __('web.ALBUM_TYPE_EP'),
                                            4 => __('web.ALBUM_TYPE_COMPILATION'),
                                            5 => __('web.ALBUM_TYPE_SOUNDTRACK'),
                                            6 => __('web.ALBUM_TYPE_SPOKENWORD'),
                                            7 => __('web.ALBUM_TYPE_INTERVIEW'),
                                            8 => __('web.ALBUM_TYPE_LIVE'),
                                            9 => __('web.ALBUM_TYPE_REMIX'),
                                            10 => __('web.ALBUM_TYPE_OTHER'),
                                        ), 'type', null, true) !!}
                                    </div>
                                    <div class="control field">
                                        <label>
                                            <span data-translate-text="FORM_DESCRIPTION">{{ __('FORM_DESCRIPTION') }}</span>
                                        </label>
                                        <textarea type="text" name="description" maxlength="180"></textarea>
                                    </div>
                                    <div class="control field">
                                        <label for="name">
                                            <span data-translate-text="FORM_RELEASED_AT">{{ __('FORM_RELEASED_AT') }}</span>
                                        </label>
                                        <input class="datepicker" name="released_at" type="text" placeholder="Today" autocomplete="off">
                                    </div>
                                    <div class="control field">
                                        <label for="created_at">
                                            <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('FORM_SCHEDULE_PUBLISH') }}</span>
                                        </label>
                                        <input class="datepicker" name="created_at" type="text" placeholder="Immediately" autocomplete="off">
                                    </div>
                                    <div class="control field">
                                        <label for="name">
                                            <span data-translate-text="FORM_COPYRIGHT">{{ __('FORM_COPYRIGHT') }}</span>
                                        </label>
                                        <input name="copyright" type="text" placeholder="Option" autocomplete="off">
                                    </div>
                                    <div class="control field mb-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row ml-0 mr-0 mt-2">
                                                    <input class="hide custom-checkbox" type="checkbox" name="visibility" id="edit-album-visibility">
                                                    <label class="cbx" for="edit-album-visibility"></label>
                                                    <label class="lbl" for="edit-album-visibility">{{ __('web.MAKE_PUBLIC') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row ml-0 mr-0 mt-2">
                                                    <input class="hide custom-checkbox" type="checkbox" name="comments" id="edit-album-comments">
                                                    <label class="cbx" for="edit-album-comments"></label>
                                                    <label class="lbl" for="edit-album-comments">{{ __('web.ALLOW_COMMENTS') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control field">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row ml-0 mr-0 mt-2" data-toggle="collapse" href="#edit-album-collapse-id" role="button" aria-expanded="false" aria-controls="edit-album-collapse-id">
                                                    <input class="hide custom-checkbox" type="checkbox" name="selling" id="edit-album-selling">
                                                    <label class="cbx" for="edit-album-selling"></label>
                                                    <label class="lbl" for="edit-album-selling">{{ __('web.SELL_THIS_ALBUM') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control field collapse" id="edit-album-collapse-id">
                                        <label for="created_at">
                                            <span data-translate-text="FORM_PRICE">{{ __('web.FORM_PRICE') }} </span>
                                        </label>
                                        <input name="price" type="number" step="1" min="{{ \App\Models\Role::getValue('monetization_album_min_price') }}" max="{{ \App\Models\Role::getValue('monetization_album_max_price') }}" placeholder="{{ __('web.SELL_THIS_SONG_TIP') }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button class="btn btn-primary" type="submit" data-translate-text="SAVE">{{ __('web.SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-create-event hide">
        <div class="lbcontainer">
            <form id="create-event-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.event.create') }}" enctype="multipart/form-data" novalidate>
                <div class="lightbox-header">
                    <h2 class="title">{{ __('web.CREATE_EVENT') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide">
                            <div class="message"></div>
                        </div>
                        <div class="control field">
                            <label for="title">
                                <span data-translate-text="FORM_TITLE">{{ __('web.FORM_TITLE') }}</span>
                            </label>
                            <input name="title" type="text" required>
                        </div>
                        <div class="control field">
                            <label for="location">
                                <span data-translate-text="{{ __('web.FORM_LOCATION') }}">{{ __('web.FORM_LOCATION') }}</span>
                            </label>
                            <input name="location" type="text" required>
                        </div>
                        <div class="control field">
                            <label for="link">
                                <span data-translate-text="FORM_OUTSIDE_LINK">{{ __('web.FORM_OUTSIDE_LINK') }}</span>
                            </label>
                            <input name="link" type="text">
                        </div>
                        <div class="control field">
                            <label for="started_at">
                                <span data-translate-text="{{ __('web.FORM_STARTING_AT') }}">{{ __('web.FORM_STARTING_AT') }}</span>
                            </label>
                            <input class="datepicker" name="started_at" type="text" placeholder="{{ __('web.IMMEDIATELY') }}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary" type="submit" data-translate-text="CREATE">{{ __('web.CREATE') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-edit-event hide">
        <div class="lbcontainer">
            <form id="edit-event-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.event.edit') }}" enctype="multipart/form-data" novalidate>
                <div class="lightbox-header">
                    <h2 class="title">{{ __('web.EDIT_EVENT') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide">
                            <div class="message"></div>
                        </div>
                        <div class="control field">
                            <label for="title">
                                <span data-translate-text="FORM_TITLE">>{{ __('web.FORM_TITLE') }}</span>
                            </label>
                            <input name="title" type="text" value="" required>
                        </div>
                        <div class="control field">
                            <label for="location">
                                <span data-translate-text="FORM_LOCATION">>{{ __('web.FORM_LOCATION') }}</span>
                            </label>
                            <input name="location" type="text" required>
                        </div>
                        <div class="control field">
                            <label for="link">
                                <span data-translate-text="FORM_OUTSIDE_LINK">{{ __('web.FORM_OUTSIDE_LINK') }}</span>
                            </label>
                            <input name="link" type="text">
                        </div>
                        <div class="control field">
                            <label for="started_at">
                                <span data-translate-text="{{ __('web.FORM_STARTING_AT') }}">{{ __('web.FORM_STARTING_AT') }}</span>
                            </label>
                            <input class="datepicker" name="started_at" type="text" placeholder="Immediately" autocomplete="off" required>
                        </div>
                        <input name="id" type="hidden" required>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary" type="submit" data-translate-text="SAVE_CHANGES">{{ __('web.SAVE_CHANGES') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-vipOnlyFeature hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <h2 class="title" data-translate-text="LB_VIP_ONLY_TITLE">{{ __('web.LB_VIP_ONLY_TITLE') }}</h2>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-content-block">
                    <h3 data-translate-text="LB_VIP_ONLY_SUBTITLE">{{ __('web.LB_VIP_ONLY_SUBTITLE') }}</h3>
                    <p data-translate-text="LB_VIP_ONLY_MSG">{{ __('web.LB_VIP_ONLY_MSG') }}</p>
                </div>
            </div>
            <div class="lightbox-footer">
                <div class="left-btns before-login">
                    <a class="btn btn-secondary login" data-translate-text="LOGIN">{{ __('web.LOGIN') }}</a>
                </div>
                <div class="right after-login after-login hide">
                    <a class="btn btn-primary" data-translate-text="SUBSCRIBE" href="{{ route('frontend.settings.subscription') }}">{{ __('web.SUBSCRIBE') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-purchaseOnlyFeature hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <h2 class="title" data-translate-text="LB_PURCHASE_ONLY_TITLE">{{ __('web.LB_PURCHASE_ONLY_TITLE') }}</h2>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-content-block">
                    <h3 data-translate-text="LB_PURCHASE_ONLY_SUBTITLE">{{ __('web.LB_PURCHASE_ONLY_SUBTITLE') }}</h3>
                    <p data-translate-text="LB_PURCHASE_ONLY_MSG">{{ __('web.LB_PURCHASE_ONLY_MSG') }}</p>
                </div>
            </div>
            <div class="lightbox-footer">
                <div class="left-btns before-login">
                    <a class="btn btn-secondary login" data-translate-text="LOGIN">{{ __('web.LOGIN') }}</a>
                </div>
                <div class="right after-login after-login hide">
                    <a class="btn btn-secondary close" data-translate-text="CLOSE">{{ __('web.CLOSE') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-download hide">
        <div class="lbcontainer">
            <div class="lightbox-header">
                <h2 class="title" data-translate-text="DOWNLOAD">{{ __('web.DOWNLOAD') }}</h2>
                @yield('lightbox-close')
            </div>
            <div class="lightbox-content">
                <div class="lightbox-content-block">
                    <div class="download-tip">
                        <h1 data-translate-text="DOWNLOAD_TIP_TITLE">{{ __('web.DOWNLOAD_TIP_TITLE') }}</h1>
                        <a class="download-tip-learn" data-translate-text="SUBSCRIBE_NOW">{{ __('web.SUBSCRIBE') }}</a>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-block btn-download standard-download">
                                <span data-translate-text="STANDARD">{{ __('web.STANDARD') }}</span>
                                <span>{{ config('settings.audio_default_bitrate', 128) }} Kbps</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-block btn-download hq-download">
                                <span data-translate-text="HIGH_QUALITY">{{ __('web.HIGH_QUALITY') }}</span>
                                <span>{{ config('settings.audio_hd_bitrate', 320) }} Kbps</span>
                                <label data-translate-text="NOT_AVAILABLE">{{ __('web.NOT_AVAILABLE') }}</label>
                                <span class="download-badge bg-danger" data-translate-text="SUBSCRIPTION">{{ __('web.SUBSCRIPTION') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-import-postcast-rss hide">
        <div class="lbcontainer">
            <div id="create-playlist">
                <form id="import-podcast-rss-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.podcasts.import') }}" enctype="multipart/form-data" novalidate>
                    <div class="lightbox-header">
                        <h2 class="title" data-translate-text="ADD_PODCAST_FEED_URL">{{ __('web.ADD_PODCAST_FEED_URL') }}</h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>
                            <div class="alert alert-info hide">This will take a while, please hold on...</div>
                            <div class="control field">
                                <label for="title">
                                    <span data-translate-text="ENTER_PODCAST_FEED_URL">{{ __('web.ENTER_PODCAST_FEED_URL') }}</span>
                                </label>
                                <input name="rss_url" type="text" required autocomplete="off">
                            </div>
                            <div class="control field">
                                <label>
                                    <span data-translate-text="{{ __('FORM_COUNTRY') }}">{{ __('FORM_COUNTRY') }}</span>
                                </label>
                                {!! makeCountryDropDown('country', 'select2 podcast-import-country-select2', null) !!}
                            </div>
                            <div class="control field podcast-import-language-container d-none">
                                <label>
                                    <span data-translate-text="{{ __('FORM_LANGUAGE') }}">{{ __('FORM_LANGUAGE') }}</span>
                                </label>
                                <select class="select2" name="language" placeholder="Select language"></select>
                            </div>
                            <div class="control field">
                                <label for="created_at">
                                    <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('FORM_SCHEDULE_PUBLISH') }}</span>
                                </label>
                                <input class="datepicker" name="created_at" type="text" placeholder="Immediately" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button class="btn btn-primary" type="submit" data-translate-text="IMPORT">{{ __('web.IMPORT') }}</button>
                        </div>
                        <div class="left">
                            <div class="row ml-0 mr-0 mt-2">
                                <input class="hide custom-checkbox" id="import-rss-checkbox" type="checkbox" name="visibility" checked>
                                <label class="cbx" for="import-rss-checkbox"></label>
                                <label class="lbl" for="import-rss-checkbox" data-translate-text="MAKE_PUBLIC">{{ __('MAKE_PUBLIC') }}</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lightbox lightbox-create-show hide">
        <div class="lbcontainer">
            <form id="create-podcast-show" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.podcasts.create') }}" enctype="multipart/form-data" novalidate>
                <div class="lightbox-header">
                    <h2 class="title" data-translate-text="CREATE_NEW_SHOW">{{ __('web.CREATE_NEW_SHOW') }}</h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide">
                            <div class="message"></div>
                        </div>
                        <input name="id" type="hidden">
                        <div class="lightbox-with-artwork-block">
                            <div class="img-container">
                                <img class="img" src="{{ asset('common/default/podcast.png') }}" data-default-artwork="{{ asset('artworks/defaults/podcast.png') }}"/>
                                <div class="control artwork-select">
                                    <span>{{ __('web.EDIT') }}</span>
                                    <input class="edit-artwork-input" name="artwork" accept="image/*" title="" type="file">
                                </div>
                            </div>
                            <div class="input-container">
                                <div class="control field">
                                    <label for="title">
                                        <span data-translate-text="FORM_TITLE">{{ __('FORM_TITLE') }}</span>
                                    </label>
                                    <input name="title" type="text" required>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="FORM_DESCRIPTION">{{ __('FORM_DESCRIPTION') }}</span>
                                    </label>
                                    <textarea type="text" name="description" maxlength="1000"></textarea>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_CATEGORY') }}">{{ __('FORM_CATEGORY') }}</span>
                                    </label>
                                    <select class="select2" name="category[]" placeholder="Select categories" multiple autocomplete="off"></select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_COUNTRY') }}">{{ __('FORM_COUNTRY') }}</span>
                                    </label>
                                    <select class="select2 podcast-country-select2" name="country" placeholder="Select country"></select>
                                </div>
                                <div class="control field podcast-language-container d-none">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_LANGUAGE') }}">{{ __('FORM_LANGUAGE') }}</span>
                                    </label>
                                    <select class="select2 podcast-language-select2" name="language" placeholder="Select language"></select>
                                </div>
                                <div class="control field">
                                    <label for="created_at">
                                        <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('FORM_SCHEDULE_PUBLISH') }}</span>
                                    </label>
                                    <input class="datepicker" name="created_at" type="text" placeholder="Immediately" autocomplete="off">
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 visibility-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="visibility" id="create-podcast-visibility" checked>
                                            <label class="cbx" for="create-podcast-visibility"></label>
                                            <label class="lbl" for="create-podcast-visibility">{{ __('web.MAKE_PUBLIC') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 comments-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="allow_comments" id="create-podcast-comments" checked>
                                            <label class="cbx" for="create-podcast-comments"></label>
                                            <label class="lbl" for="create-podcast-comments">{{ __('web.ALLOW_COMMENTS') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="notification" id="create-podcast-notification" checked>
                                            <label class="cbx" for="create-podcast-notification"></label>
                                            <label class="lbl" for="create-podcast-notification">{{ __('web.NOTIFY_MY_FANS') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="allow_download" id="create-podcast-downloadable" checked>
                                            <label class="cbx" for="create-podcast-downloadable"></label>
                                            <label class="lbl" for="create-podcast-downloadable">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-12">
                                        <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="explicit" id="create-podcast-explicit">
                                            <label class="cbx" for="create-podcast-explicit"></label>
                                            <label class="lbl" for="create-podcast-explicit" data-translate-text="PODCAST_EXPLICIT">{{ __('web.PODCAST_EXPLICIT') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <button class="btn btn-primary" type="submit" data-translate-text="CREATE">{{ __('web.CREATE') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-edit-show hide">
        <div class="lbcontainer">
            <form id="edit-podcast-show-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.podcasts.edit') }}" enctype="multipart/form-data" novalidate>
                <div class="lightbox-header">
                    <h2 class="title"></h2>
                    @yield('lightbox-close')
                </div>
                <div class="lightbox-content">
                    <div class="lightbox-content-block">
                        <div class="error hide">
                            <div class="message"></div>
                        </div>
                        <input name="id" type="hidden">
                        <div class="lightbox-with-artwork-block">
                            <div class="img-container">
                                <img class="img" src="{{ asset('common/default/podcast.png') }}" data-default-artwork="{{ asset('artworks/defaults/podcast.png') }}"/>
                                <div class="control artwork-select">
                                    <span>{{ __('web.EDIT') }}</span>
                                    <input class="edit-artwork-input" name="artwork" accept="image/*" title="" type="file">
                                </div>
                            </div>
                            <div class="input-container">
                                <div class="control field">
                                    <label for="title">
                                        <span data-translate-text="FORM_TITLE">{{ __('FORM_TITLE') }}</span>
                                    </label>
                                    <input name="title" type="text" required>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="FORM_DESCRIPTION">{{ __('FORM_DESCRIPTION') }}</span>
                                    </label>
                                    <textarea type="text" name="description" maxlength="1000"></textarea>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_CATEGORY') }}">{{ __('FORM_CATEGORY') }}</span>
                                    </label>
                                    <select class="select2" name="category[]" placeholder="Select categories" multiple autocomplete="off"></select>
                                </div>
                                <div class="control field">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_COUNTRY') }}">{{ __('FORM_COUNTRY') }}</span>
                                    </label>
                                    <select class="select2 podcast-edit-country-select2" name="country" placeholder="Select country"></select>
                                </div>
                                <div class="control field podcast-edit-language-container d-none">
                                    <label>
                                        <span data-translate-text="{{ __('FORM_LANGUAGE') }}">{{ __('FORM_LANGUAGE') }}</span>
                                    </label>
                                    <select class="select2 podcast-edit-language-select2" name="language" placeholder="Select language"></select>
                                </div>
                                <div class="control field">
                                    <label for="created_at">
                                        <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('FORM_SCHEDULE_PUBLISH') }}</span>
                                    </label>
                                    <input class="datepicker" name="created_at" type="text" placeholder="Immediately" autocomplete="off">
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 visibility-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="visibility" id="edit-podcast-visibility">
                                            <label class="cbx" for="edit-podcast-visibility"></label>
                                            <label class="lbl" for="edit-podcast-visibility">{{ __('web.MAKE_PUBLIC') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 comments-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="allow_comments" id="edit-podcast-comments">
                                            <label class="cbx" for="edit-podcast-comments"></label>
                                            <label class="lbl" for="edit-podcast-comments">{{ __('web.ALLOW_COMMENTS') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-6">
                                        <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="allow_download" id="edit-podcast-downloadable">
                                            <label class="cbx" for="edit-podcast-downloadable"></label>
                                            <label class="lbl" for="edit-podcast-downloadable">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="control field row mb-0">
                                    <div class="col-12">
                                        <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                            <input class="hide custom-checkbox" type="checkbox" name="explicit" id="edit-podcast-explicit">
                                            <label class="cbx" for="edit-podcast-explicit"></label>
                                            <label class="lbl" for="edit-podcast-explicit" data-translate-text="PODCAST_EXPLICIT">{{ __('web.PODCAST_EXPLICIT') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lightbox-footer">
                    <div class="right">
                        <input name="id" type="hidden">
                        <button class="btn btn-primary" type="submit" data-translate-text="SAVE">{{ __('web.SAVE') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lightbox lightbox-edit-episode hide">
        <form id="edit-episode-form" class="ajax-form" method="post" action="{{ route('frontend.auth.user.artist.manager.episode.edit.post') }}" enctype="multipart/form-data" novalidate>
            <div class="lbcontainer">
                <div id="upload-song">
                    <div class="lightbox-header">
                        <h2 class="title">{{ __('EDIT_EPISODE') }}</h2>
                        @yield('lightbox-close')
                    </div>
                    <div class="lightbox-content">
                        <div class="lightbox-content-block">
                            <div class="error hide">
                                <div class="message"></div>
                            </div>
                            <div class="control field">
                                <label for="title">
                                    <span data-translate-text="FORM_TITLE">{{ __('web.FORM_EPISODE_TITLE') }}</span>
                                </label>
                                <input class="song-name-input form-control" name="title" type="text" autocomplete="off" required>
                            </div>
                            <div class="control field">
                                <label for="copyright">
                                    <span data-translate-text="FORM_DESCRIPTION">{{ __('web.FORM_EPISODE_DESCRIPTION') }}</span>
                                </label>
                                <textarea class="form-control" name="description" cols="30" rows="5"></textarea>
                            </div>
                            <div class="control field">
                                <label for="season">
                                    <span data-translate-text="EPISODE_SEASON_NUMBER_FORM">{{ __('web.EPISODE_SEASON_NUMBER_FORM') }}</span>
                                </label>
                                <input class="form-control" name="season" type="text" placeholder="{{ __('web.EPISODE_SEASON_NUMBER_TIP') }}" autocomplete="off">
                            </div>
                            <div class="control field">
                                <label for="number">
                                    <span data-translate-text="EPISODE_NUMBER_FORM">{{ __('web.EPISODE_NUMBER_FORM') }}</span>
                                </label>
                                <input class="form-control" name="number" type="text" placeholder="{{ __('web.EPISODE_NUMBER_TIP') }}" autocomplete="off">
                            </div>
                            <div class="control field">
                                <label for="type">
                                    <span data-translate-text="FORM_TYPE">{{ __('web.FORM_TYPE') }}</span>
                                </label>
                                {!! makeDropDown(array(
                                    1 => __('web.EPISODE_TYPE_FULL'),
                                    2 => __('web.EPISODE_TYPE_TRAILER'),
                                    3 => __('web.EPISODE_TYPE_BONUS'),
                                ), 'type', null, false) !!}
                            </div>
                            <div class="control field row mb-0">
                                <div class="col-6">
                                    <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                        <input class="hide custom-checkbox" type="checkbox" name="visibility" id="edit-episode-visibility">
                                        <label class="cbx" for="edit-episode-visibility"></label>
                                        <label class="lbl" for="edit-episode-visibility" data-translate-text="MAKE_PUBLIC">{{ __('web.MAKE_PUBLIC') }}</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                        <input class="hide custom-checkbox" type="checkbox" name="allow_comments" id="edit-episode-comments">
                                        <label class="cbx" for="edit-episode-comments"></label>
                                        <label class="lbl" for="edit-episode-comments" data-translate-text="ALLOW_COMMENTS">{{ __('web.ALLOW_COMMENTS') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="control field row mb-0">
                                <div class="col-6">
                                    <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                        <input class="hide custom-checkbox" type="checkbox" name="downloadable" id="edit-episode-download">
                                        <label class="cbx" for="edit-episode-download"></label>
                                        <label class="lbl" for="edit-episode-download" data-translate-text="ALLOW_DOWNLOAD">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                        <input class="hide custom-checkbox" type="checkbox" name="explicit" id="edit-episode-explicit">
                                        <label class="cbx" for="edit-episode-explicit"></label>
                                        <label class="lbl" for="edit-episode-explicit" data-translate-text="PODCAST_EXPLICIT">{{ __('web.PODCAST_EXPLICIT') }}</label>
                                    </div>
                                </div>
                            </div>
                            <input name="id" type="hidden">
                        </div>
                    </div>
                    <div class="lightbox-footer">
                        <div class="right">
                            <button id="edit-song-save-btn" class="btn btn-primary" type="submit" data-translate-text="SAVE">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if(is_array(config('modules.views.lightbox')))
        @foreach(config('modules.views.lightbox') as $lightbox)
            @include($lightbox)
        @endforeach
    @endif
</div>
