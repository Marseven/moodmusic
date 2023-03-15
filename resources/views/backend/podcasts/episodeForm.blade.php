@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.podcasts') }}">Podcasts</a></li>
        <li class="breadcrumb-item active">{{ $podcast->title }}</li>
        <li class="breadcrumb-item active">{{ $episode->title }}</li>
    </ol>
    @if(isset($podcast))
        <div class="row col-lg-12 media-info mb-3 podcast">
            <div class="media">
                <img class="mr-3" src="{{ $podcast->artwork_url }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $podcast->title }}</h5>
                    <p>{{ $podcast->description }}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="template-upload upload-info card">
                <form class="upload-edit-song-form m-0" method="POST" action="{{ route('backend.ajax.podcast.episode.edit')  }}" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-info-content">
                        <div class="error hide">
                        </div>
                        <div class="upload-info-block">
                            <div class="song-info-container full">
                                <div class="control field">
                                    <label for="title">
                                        <span data-translate-text="FORM_TITLE">{{ __('web.FORM_EPISODE_TITLE') }}</span>
                                    </label>
                                    <input class="form-control" name="title" type="text" autocomplete="off" value="{{ $episode->title }}" required>
                                </div>
                                <div class="control field">
                                    <label for="copyright">
                                        <span data-translate-text="FORM_DESCRIPTION">{{ __('web.FORM_EPISODE_DESCRIPTION') }}</span>
                                    </label>
                                    <textarea class="form-control" name="description" cols="30" rows="5">{{ $episode->description }}</textarea>
                                </div>
                                <div class="control field">
                                    <label for="season">
                                        <span>Season</span>
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
                            </div>
                        </div>
                        <div class="upload-info-footer">
                            <input name="id" type="hidden" value="{{ $episode->id }}">
                            <button class="btn btn-primary save" type="submit" data-translate-text="SAVE">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection