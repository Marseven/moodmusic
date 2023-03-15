<script id="template-upload" type="text/x-tmpl">
{% for (var g=0, file; file=o.files[g]; g++) { %}
    <div class="template-upload upload-info card">
        <form class="upload-edit-song-form m-0" method="POST" action="{{ route('frontend.auth.user.artist.manager.song.edit.post') }}" enctype="multipart/form-data">
            <div class="upload-info-content">
                <div class="error hide">
                </div>
                <div class="upload-info-progress-outer">
                    <div class="upload-info-progress progress"></div>
                </div>
                <div class="upload-info-file">
                    <span>Speed <span class="upload-info-bitrate"></span></span>
                    <span class="upload-info-extra"></span>

                </div>
                <div class="upload-info-block">
                    <div class="img-container">
                        <img class="img"/>
                        <div class="edit-artwork edit-song-artwork">
                            <input class="edit-song-artwork-input" name="artwork" type="file" accept="image/*">
                            <span>{{ __('web.EDIT') }}</span>
                        </div>
                    </div>
                    <div class="song-info-container">
                        <div class="control field">
                            <label for="title">
                                <span data-translate-text="FORM_TITLE">{{ __('web.FORM_TITLE') }}</span>
                            </label>
                            <input class="song-name-input" name="title" type="text" autocomplete="off" value="{%=file.name%}" required>
                        </div>
                        <div class="control field">
                            <label>
                                <span data-translate-text="FROM_DEFAULT_GENRE">{{ __('web.FROM_DEFAULT_GENRE') }}</span>
                            </label>
                            <select class="genre-selection" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                                {!! genreSelection(isset(auth()->user()->artist) ? explode(',', auth()->user()->artist->genre) : 0) !!}}
                            </select>
                        </div>
                        <div class="control field">
                            <label>
                                <span data-translate-text="FROM_DEFAULT_MOOD">{{ __('web.FROM_DEFAULT_MOOD') }}</span>
                            </label>
                            <select class="mood-selection" name="mood[]" multiple autocomplete="off">
                                 {!! moodSelection(isset(auth()->user()->artist) ? explode(',', auth()->user()->artist->mood) : 0) !!}}
                            </select>
                        </div>
                        <div class="control field">
                            <label for="created_at">
                                <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('web.FORM_SCHEDULE_PUBLISH') }}</span>
                            </label>
                            <input class="datepicker" name="created_at" type="text" placeholder="{{ __('web.IMMEDIATELY') }}" autocomplete="off">
                        </div>
                        <!--
                        <div class="control field">
                            <label for="copyright">
                                <span data-translate-text="FORM_COPYRIGHT">{{ __('web.FORM_COPYRIGHT') }}</span>
                            </label>
                            <input name="copyright" type="text" autocomplete="off" value="">
                        </div>
                        -->
                        <div class="control field bpm-control d-none">
                            <label for="bpm">
                                <span data-translate-text="FORM_BPM">{{ __('web.FORM_BPM') }}</span>
                            </label>
                            <input name="bpm" type="text" autocomplete="off" value="">
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
                        <div class="control field row mb-0">
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 visibility-check-box">
                                    <input class="hide custom-checkbox" type="checkbox" name="visibility" id="upload-visibility-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="cbx" for="upload-visibility-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-visibility-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.MAKE_PUBLIC') }}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 comments-check-box">
                                    <input class="hide custom-checkbox" type="checkbox" name="comments" id="upload-comments-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="cbx" for="upload-comments-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-comments-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.ALLOW_COMMENTS') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="control field row mb-0">
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 notification-check-box">
                                    <input class="hide custom-checkbox" type="checkbox" name="notification" id="upload-notification-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="cbx" for="upload-notification-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-notification-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.NOTIFY_MY_FANS') }}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 downloadable-check-box">
                                    <input class="hide custom-checkbox" type="checkbox" name="downloadable" id="upload-downloadable-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="cbx" for="upload-downloadable-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-downloadable-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="control field row mb-0">
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 explicit-check-box">
                                    <input class="hide custom-checkbox" type="checkbox" name="explicit" id="upload-explicit-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">
                                    <label class="cbx" for="upload-explicit-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-explicit-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.SONG_EXPLICIT') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="control field row mb-0">
                            <div class="col-12">
                                <div class="row ml-0 mr-0 mt-2 selling-check-box" data-toggle="collapse" href="#collapse-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" role="button" aria-expanded="false" aria-controls="collapse-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">
                                    <input class="hide custom-checkbox" type="checkbox" name="selling" id="upload-selling-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">
                                    <label class="cbx" for="upload-selling-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}"></label>
                                    <label class="lbl" for="upload-selling-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.SELL_THIS_SONG') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="control field collapse" id="collapse-id-{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">
                            <label for="created_at">
                                <span data-translate-text="FORM_PRICE">{{ __('web.FORM_PRICE') }} </span>
                            </label>
                            <input name="price" type="number" step="1" min="{{ \App\Models\Role::getValue('monetization_song_min_price') }}" max="{{ \App\Models\Role::getValue('monetization_song_max_price') }}" placeholder="{{ __('web.SELL_THIS_SONG_TIP') }}" autocomplete="off">
                        </div>
                        <div class="song-info-container-overlay">
                            <div class="wrapper no-margin">
                                <div class="wrapper-cell upload">
                                    <div class="text">
                                        <div class="text-line"> </div>
                                        <div class="text-line"></div>
                                    </div>
                                    <div class="text">
                                        <div class="text-line"> </div>
                                        <div class="text-line"></div>
                                    </div>
                                    <div class="text">
                                        <div class="text-line"> </div>
                                        <div class="text-line"></div>
                                    </div>
                                    <div class="text">
                                        <div class="text-line"> </div>
                                        <div class="text-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="upload-info-footer hide">
                <input name="id" type="hidden">
                <button class="btn btn-primary save" type="submit" data-translate-text="SAVE">{{ __('SAVE') }}</button>
                </div>
            </div>
        </form>
    </div>
{% } %}
</script>