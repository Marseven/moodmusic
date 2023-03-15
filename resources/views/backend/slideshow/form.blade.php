@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.slideshow.overview') }}">Slideshow</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-6">
                        {!! makeSlideShowDropDown(
                                                    env('VIDEO_MODULE') == 'true' ? array(
                                                            "song" => "Song",
                                                            "album" => "Album",
                                                            "artist" => "Artist",
                                                            "station" => "Station",
                                                            "playlist" => "Playlist",
                                                            "podcast" => "Podcast",
                                                            "user" => "User",
                                                            "video" => "Video"
                                                        ) : array(
                                                            "song" => "Song",
                                                            "album" => "Album",
                                                            "artist" => "Artist",
                                                            "station" => "Station",
                                                            "playlist" => "Playlist",
                                                            "podcast" => "Podcast",
                                                            "user" => "User",
	                                                    ), "object_type", isset($slide) && ! old('object_type') ? $slide->object_type : old('object_type')
	                                              )
	                    !!}
                    </div>
                </div>

                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "song" ) d-none @endif" data-type="song">
                    <label class="col-sm-3 col-form-label">Select a Song</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.song') }}" name="object_id">
                            @if(isset($slide) && $song && count(get_object_vars(($song))))
                                <option value="{{ $song->id }}" selected="selected" data-artwork="{{ $song->artwork_url }}">{!! $song->title !!}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "artist" ) d-none @endif" data-type="artist">
                    <label class="col-sm-3 col-form-label">Select an Artist</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.artist') }}" name="object_id">
                            @if(isset($slide) && $artist && count(get_object_vars(($artist))))
                                <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}">{!! $artist->name !!}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "album" ) d-none @endif" data-type="album">
                    <label class="col-sm-3 col-form-label">Select Album</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="/api/search/album" name="object_id">
                            @if(isset($slide) && $album && count(get_object_vars(($album))))
                                <option value="{{ $album->id }}" selected="selected" data-artwork="{{ $album->artwork_url }}">{!! $album->title !!}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "station" ) d-none @endif" data-type="station">
                    <label class="col-sm-3 col-form-label">Select a Station</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.station') }}" name="object_id">
                            @if(isset($slide) && $station && count(get_object_vars(($station))))
                                <option value="{{ $station->id }}" selected="selected" data-artwork="{{ $station->artwork_url }}">{{ $station->title }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "playlist" ) d-none @endif" data-type="playlist">
                    <label class="col-sm-3 col-form-label">Select a Playlist</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.playlist') }}" name="object_id">
                            @if(isset($slide) && $playlist && count(get_object_vars(($playlist))))
                                <option value="{{ $playlist->id }}" selected="selected" data-artwork="{{ $playlist->artwork_url }}">{!! $playlist->title !!}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "podcast" ) d-none @endif" data-type="podcast">
                    <label class="col-sm-3 col-form-label">Select a Podcast</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.podcast') }}" name="object_id">
                            @if(isset($slide) && $podcast && count(get_object_vars(($podcast))))
                                <option value="{{ $podcast->id }}" selected="selected" data-artwork="{{ $podcast->artwork_url }}">{{ $podcast->title }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "user" ) d-none @endif" data-type="user">
                    <label class="col-sm-3 col-form-label">Select a User</label>
                    <div class="col-sm-6">
                        <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.user') }}" name="object_id">
                            @if(isset($slide) && $user && count(get_object_vars(($user))))
                                <option value="{{ $user->id }}" selected="selected" data-artwork="{{ $user->artwork_url }}">{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                @if(env('VIDEO_MODULE') == 'true')
                    <div class="form-group row slide-show-selector @if(! isset($slide) || $slide->object_type != "video" ) d-none @endif" data-type="video">
                        <label class="col-sm-3 col-form-label">Select a Video</label>
                        <div class="col-sm-6">
                            <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.video') }}" name="object_id">
                                @if(isset($slide) && $video && count(get_object_vars(($video))))
                                    <option value="{{ $video->id }}" selected="selected" data-artwork="{{ $video->artwork_url }}">{{ $video->title }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                @endif
                @if(isset($slide))
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Current Artwork</label>
                        <div class="col-sm-9">
                            <img class="container_photo" src="{{ $slide->artwork_url }}"/>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">New Artwork (min width, height 300x300, Image will be automatically crop and resize to 300/176 pixel)</label>
                    <div class="col-sm-9">
                        <input type="file" name="artwork" class="file-selector" accept="image/*">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                            <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                            <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Title</label>
                    <div class="col-sm-9">
                        <input name="title" class="form-control" value="{{ isset($slide) && ! old('title') ? $slide->title : old('title') }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Title link</label>
                    <div class="col-sm-9">
                        <input name="title_link" class="form-control" value="{{ isset($slide) && ! old('title_link') ? $slide->title_link : old('title_link') }}" placeholder="Optional">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control" rows="2">{{ isset($slide) && ! old('description') ? $slide->description : old('description') }}</textarea>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Visibility</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('visibility', isset($slide) && ! old('visibility') ? $slide->visibility : old('visibility')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the home page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_home', isset($slide) && ! old('allow_home') ? $slide->allow_home : old('allow_home')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the discover page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_discover', isset($slide) && ! old('allow_discover') ? $slide->allow_discover : old('allow_discover')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the radio page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_radio', isset($slide) && ! old('allow_radio') ? $slide->allow_radio : old('allow_radio')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the community page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_community', isset($slide) && ! old('allow_community') ? $slide->allow_community : old('allow_community')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the trending page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_trending', isset($slide) && ! old('allow_trending') ? $slide->allow_trending : old('allow_trending')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the podcasts page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_podcasts', isset($slide) && ! old('allow_podcasts') ? $slide->allow_podcasts : old('allow_podcasts')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                @if(env('VIDEO_MODULE') == 'true')
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Publish on the video page section</label>
                        <div class="col-sm-9">
                            <label class="switch">
                                {!! makeCheckBox('allow_videos', isset($slide) && ! old('allow_videos') ? $slide->allow_videos : old('allow_videos')) !!}
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the genres page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="genre[]">
                            {!! genreSelection(explode(',', isset($slide) && ! old('genre') ? $slide->genre : implode(',', (old('genre') ? old('genre') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the moods page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="mood[]">
                            {!! moodSelection(explode(',', isset($slide) && ! old('mood') ? $slide->mood : implode(',', (old('mood') ? old('mood') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the radio category page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="radio[]">
                            {!! radioCategorySelection(explode(',', isset($slide) && ! old('radio') ? $slide->radio : implode(',', array()) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the podcast category page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="podcast[]">
                            {!! podcastCategorySelection(explode(',', isset($slide) && ! old('podcast') ? $slide->podcast : implode(',', (old('podcast') ? old('podcast') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection