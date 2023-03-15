<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
<div class="template-upload upload-info card">
    <form class="upload-edit-song-form ajax-form m-0" method="POST" action="{{ route('backend.ajax.song.edit')  }}" enctype="multipart/form-data">
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
                    <img class="img" />
                    <div class="edit-artwork edit-song-artwork">
                        <input class="edit-song-artwork-input" name="artwork" type="file" accept="image/*">
                        <span>Edit</span>
                    </div>
                </div>
                <div class="song-info-container">
                    <div class="control field">
                        <label for="title">
                            <span data-translate-text="NAME">Name:</span>
                        </label>
                        <input class="song-name-input form-control" name="title" type="text" autocomplete="off" value="{%=file.name%}" required>
                    </div>
                    <div class="control field multi-artists">
                        <label for="title">
                            <span data-translate-text="NAME">Artist(s):</span>
                        </label>
                        <select class="form-control multi-selector song-artists-input" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="">
                            <option value="" selected="selected" data-artwork="" data-title=""></option>
                        </select>
                    </div>
                    <div class="control field">
                        <label>
                            <span data-translate-text="GENRES">Genres:</span>
                        </label>
                        <select class="select2-active" name="genre[]" placeholder="Select genres" multiple autocomplete="off">
                            {!! genreSelection(0, 0) !!}}
                        </select>
                    </div>
                    <div class="control field">
                        <label>
                            <span data-translate-text="MOODS">Moods:</span>
                        </label>
                        <select class="select2-active" name="mood[]" placeholder="Select moods" multiple autocomplete="off">
                            {!! moodSelection(0, 0) !!}}
                        </select>
                    </div>
                    <div class="control field">
                        <label for="copyright">
                            <span data-translate-text="NAME">Copyright (Option):</span>
                        </label>
                        <input name="copyright" class="form-control" type="text" autocomplete="off">
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