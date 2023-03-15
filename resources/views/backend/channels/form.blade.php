@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.channels.overview') }}">Channels</a></li>
        <li class="breadcrumb-item active">@if(isset($channel)) Edit @else Add new channel @endif</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Title</label>
                    <div class="col-sm-9">
                        <input name="title" class="form-control" value="{{ isset($channel) && ! old('title') ? $channel->title : old('title') }}"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <input name="description" class="form-control" value="{{ isset($channel) && ! old('description') ? $channel->description : old('description') }}"/>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-9">
                        {!!  makeChannelDropDown(
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
                                                        ),
                                                        "object_type",
                                                        isset($channel) && ! old('object_type') ? $channel->object_type : old('object_type')
                                                    )
                                                !!}
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "song") d-none @endif" data-type="song">
                    <label>Select a Song</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.song') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects) && $channel->object_ids && count($channel->objects) && $channel->object_type == "song")
                                @foreach ($channel->objects as $index => $song)
                                    <option value="{{ $song->id }}" selected="selected" data-artwork="{{ $song->artwork_url }}">{!! $song->title !!}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "artist") d-none @endif" data-type="artist">
                    <label>Select an Artist</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects) && $channel->object_type == "artist")
                                @foreach ($channel->objects as $index => $artist)
                                    <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}">{!! $artist->name !!}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "album") d-none @endif" data-type="album">
                    <label>Select album(s)</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.album') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects) && $channel->object_ids && count($channel->objects) && $channel->object_type == "album")
                                @foreach ($channel->objects as $index => $album)
                                    <option value="{{ $album->id }}" selected="selected" data-artwork="{{ $album->artwork_url }}">{!! $album->title !!}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "station") d-none @endif" data-type="station">
                    <label>Select a Station</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.station') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects)  && $channel->object_type == "station")
                                @foreach ($channel->objects as $index => $station)
                                    <option value="{{ $station->id }}" selected="selected" data-artwork="{{ $station->artwork_url }}">{{ $station->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "playlist") d-none @endif" data-type="playlist">
                    <label>Select a Playlist</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.playlist') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects) && $channel->object_type == "playlist")
                                @foreach ($channel->objects as $index => $playlist)
                                    <option value="{{ $playlist->id }}" selected="selected" data-artwork="{{ $playlist->artwork_url }}">{!! $playlist->title !!}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "podcast") d-none @endif" data-type="podcast">
                    <label>Select a Podcast show</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.podcast') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects)  && $channel->object_type == "podcast")
                                @foreach ($channel->objects as $index => $podcast)
                                    <option value="{{ $podcast->id }}" selected="selected" data-artwork="{{ $podcast->artwork_url }}">{{ $podcast->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "user") d-none @endif" data-type="user">
                    <label>Select a User</label>
                    <div class="multi-artists">
                        <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.user') }}" name="object_ids[]" multiple="">
                            @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects)  && $channel->object_type == "user")
                                @foreach ($channel->objects as $index => $user)
                                    <option value="{{ $user->id }}" selected="selected" data-artwork="{{ $user->artwork_url }}">{{ $user->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @if(env('VIDEO_MODULE') == 'true')
                    <div class="form-group slide-show-selector @if(!isset($channel) || $channel->object_type != "video") d-none @endif" data-type="video">
                        <label>Select a Video</label>
                        <div class="multi-artists">
                            <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.video') }}" name="object_ids[]" multiple="">
                                @if(isset($channel->objects)  && $channel->object_ids && count($channel->objects)  && $channel->object_type == "video")
                                    @foreach ($channel->objects as $index => $video)
                                        <option value="{{ $video->id }}" selected="selected" data-artwork="{{ $video->artwork_url }}">{{ $video->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                @endif
                <div class="card mb-4 py-3 border-left-info">
                    <div class="card-body card-small">
                        Move item (drag & drop) to re-arrange
                    </div>
                </div>
                <div class="card mb-4 py-3 border-left-warning">
                    <div class="card-body card-small">
                        Fact: Leave the field empty to automatically load the latest item of the object type. For example you can create a channel that automatically get just released album.
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Auto-Populating (only work if you leave the selecting field empty.</label>
                    <div class="col-sm-9">
                        {!!  makeDropDown(
                                                    array(
                                                            "latest" => "Latest item (base on released date or created date)",
                                                            "topDay" => "Top attraction of the day",
                                                            "topWeek" => "Top attraction of the week",
                                                            "topMonth" => "Top attraction of the month",
                                                            "topYear" => "Top attraction of the year",
                                                        ),
                                                        "attraction",
                                                        isset($channel) && ! old('attraction') ? $channel->attraction : old('attraction')
                                                    )
                                                !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Meta Title</label>
                    <div class="col-sm-9">
                        <input name="meta_title" class="form-control" value="{{ isset($channel) && ! old('title') ? $channel->title : old('title') }}"/>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Meta description</label>
                    <div class="col-sm-9">
                        <input name="meta_description" class="form-control" value="{{ isset($channel) && ! old('title') ? $channel->title : old('title') }}"/>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the home page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_home', isset($channel) && ! old('allow_home') ? $channel->allow_home : old('allow_home')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the discover page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_discover', isset($channel) && ! old('allow_discover') ? $channel->allow_discover : old('allow_discover')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the radio page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_radio', isset($channel) && ! old('allow_radio') ? $channel->allow_radio : old('allow_radio')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the community page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_community', isset($channel) && ! old('allow_community') ? $channel->allow_community : old('allow_community')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the podcasts page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_podcasts', isset($channel) && ! old('allow_podcasts') ? $channel->allow_podcasts : old('allow_podcasts')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the trending page section</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('allow_trending', isset($channel) && ! old('allow_trending') ? $channel->allow_trending : old('allow_trending')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                @if(env('VIDEO_MODULE') == 'true')
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Publish on the video page section</label>
                        <div class="col-sm-9">
                            <label class="switch">
                                {!! makeCheckBox('allow_videos', isset($channel) && ! old('allow_videos') ? $channel->allow_videos : old('allow_videos')) !!}
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the genres page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="genre[]">
                            {!! genreSelection(explode(',', isset($channel) && ! old('genre') ? $channel->genre : implode(',', (old('genre') ? old('genre') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the moods page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="mood[]">
                            {!! moodSelection(explode(',', isset($channel) && ! old('mood') ? $channel->mood : implode(',', (old('mood') ? old('mood') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the radio category page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="radio[]">
                            {!! radioCategorySelection(explode(',', isset($channel) && ! old('radio') ? $channel->radio : implode(',', array()) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish on the podcast category page section</label>
                    <div class="col-sm-9">
                        <select multiple="" class="form-control select2-active" name="podcast[]">
                            {!! podcastCategorySelection(explode(',', isset($channel) && ! old('podcast') ? $channel->podcast : implode(',', (old('podcast') ? old('podcast') : [])) )) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Publish</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('visibility', isset($channel) && ! old('visibility') ? $channel->visibility : old('visibility')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection