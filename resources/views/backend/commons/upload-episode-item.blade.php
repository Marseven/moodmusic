<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload upload-info card">
        <form class="upload-edit-song-form m-0" method="POST" action="{{ route('backend.ajax.podcast.episode.edit')  }}" enctype="multipart/form-data">
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
                    <div class="song-info-container full">
                        <div class="control field">
                            <label for="title">
                                <span data-translate-text="FORM_TITLE">{{ __('web.FORM_EPISODE_TITLE') }}</span>
                            </label>
                            <input class="song-name-input form-control" name="title" type="text" autocomplete="off" value="{%=file.name%}" required>
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
                            ), 'type', null, true) !!}
                        </div>
                        <div class="control field">
                            <label for="created_at">
                                <span data-translate-text="FORM_SCHEDULE_PUBLISH">{{ __('web.FORM_SCHEDULE_PUBLISH') }}</span>
                            </label>
                            <input class="form-control datepicker" name="created_at" type="text" placeholder="{{ __('web.IMMEDIATELY') }}" autocomplete="off">
                        </div>
                        <div class="control field row mb-0">
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="visibility" type="checkbox" class="form-check-input" id="checkbox1{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="form-check-label" for="checkbox1{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.MAKE_PUBLIC') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="allow_comments" type="checkbox" class="form-check-input" id="checkbox2{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="form-check-label" for="checkbox2{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.ALLOW_COMMENTS') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="control field row mb-0">
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="allow_download" type="checkbox" class="form-check-input" id="checkbox3{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}" checked>
                                    <label class="form-check-label" for="checkbox3{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.ALLOW_DOWNLOAD') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input name="explicit" type="checkbox" class="form-check-input" id="checkbox4{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">
                                    <label class="form-check-label" for="checkbox4{%=btoa(unescape(encodeURIComponent(file.name))).replace(/=/g, '')%}">{{ __('web.PODCAST_EXPLICIT') }}</label>
                                </div>
                            </div>
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

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    Download
{% } %}
</script>