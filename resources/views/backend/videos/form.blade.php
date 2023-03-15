@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.videos.overview') }}">Videos</a></li>
        <li class="breadcrumb-item active">
            @if(isset($video))
                {!! $video->title !!} - @foreach($video->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{{$artist->name}}">{{$artist->name}}</a>@if(!$loop->last), @endif @endforeach
            @else
                Add New Video
            @endif
        </li>
    </ol>
    <div class="row">
        @if(isset($video))
            <div class="col-lg-12 media-info mb-3 album">
                <div class="media mb-3">
                    <img class="mr-3" src="{{ $video->artwork_url }}" style="height: auto">
                    <div class="media-body">
                        <h5 class="m-0">{!! $video->title !!} - @foreach($video->artists as $artist)<a href="{{ url('admin/artists/edit/' . $artist->id) }}" title="{{$artist->name}}">{{$artist->name}}</a>@if(!$loop->last), @endif @endforeach</h5>
                        <h5>
                            @if($video->mp3)
                                <span class="badge badge-pill badge-dark">MP3</span>
                            @endif
                            @if($video->hd)
                                <span class="badge badge-pill badge-danger">HD</span>
                            @endif
                            @if($video->hls)
                                <span class="badge badge-pill badge-warning">HLS</span>
                            @endif
                        </h5>
                        <p class="m-0"><a href="{{ $video->permalink_url }}" class="btn btn-warning" target="_blank">Preview @if(! $video->approved) (only Moderator) @endif</a></p>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                <div class="card">
                    <div class="card-header p-0 position-relative">
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="pill"><i class="fas fa-fw fa-newspaper"></i> Overview</a></li>
                            <li class="nav-item"><a href="#streamable" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-lock"></i> Advanced</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content mt-2" id="myTabContent">
                            <div id="overview" class="tab-pane fade show active">
                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input class="form-control" name="title" value="{{ isset($video) ? $video->title : old('title') }}" required>
                                </div>
                                <div class="form-group multi-artists">
                                    <label>Artist(s)</label>
                                    <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="">
                                        @if(isset($video))
                                            @foreach ($video->artists as $index => $artist)
                                                <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group multi-artists">
                                    <label>Song</label>
                                    <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.song') }}" name="song_id">
                                        @if(isset($video))
                                            @foreach ($video->artists as $index => $artist)
                                                <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Video's artwork (big size, ex: 1920x1080)</label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="artwork" class="file-selector" accept="image/*" @if(! isset($video)) required @endif>
                                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                        <span class="input-group-btn">
                                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-image"></i> Browse</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Addition file (for download purpose)</label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="file" class="file-selector">
                                        <span class="input-group-addon"><i class="fas fa-fw fa-file"></i></span>
                                        <input type="text" class="form-control input-lg" disabled placeholder="Upload File">
                                        <span class="input-group-btn">
                                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="4" class="form-control" name="description">{{ isset($video) ? $video->description : old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Genres</label>
                                    <select multiple="" class="form-control select2-active" name="genre[]">
                                        {!! genreSelection(explode(',', isset($video) && ! old('genre') ? $video->genre : old('genre'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Moods</label>
                                    <select multiple="" class="form-control select2-active" name="mood[]">
                                        {!! moodSelection(explode(',', isset($video) && ! old('mood') ? $video->mood : old('mood'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Released At</label>
                                    <input type="text" class="form-control datepicker" name="released_at" value="{{ isset($video) ? \Carbon\Carbon::parse($video->released_at)->format('m/d/Y') : old('released_at') }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Stream URL (higher priority than Youtube)</label>
                                    <input type="text" class="form-control" name="stream_url" value="{{ isset($video) ? $video->stream_url : old('stream_url') }}">
                                </div>
                                <div class="form-group">
                                    <label>Youtube ID</label>
                                    <input type="text" class="form-control" name="youtube_id" value="{{ isset($video) ? $video->youtube_id : old('youtube_id') }}">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('selling', isset($video) && ! old('selling') ? $video->approved : (old('selling') ? old('selling') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Allow to sell this video</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name="price" value="{{ isset($video) ? $video->price : old('price') }}" placeholder="ex: 0.99">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('allow_comments', isset($video) && ! old('allow_comments') ? $video->allow_comments : (old('allow_comments') ? old('allow_comments') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Allow to comment</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('approved', isset($video) && ! old('approved') ? $video->approved : (old('approved') ? old('approved') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Approve this video</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('visibility', isset($video) && ! old('visibility') ? $video->approved : (old('visibility') ? old('visibility') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">This is not a private video</label>
                                    </div>
                                </div>
                            </div>
                            <div id="streamable" class="tab-pane fade">
                                <div class="alert alert-info">Note: You can configure additional video playable and downloadable parameters for different groups in this section.</div>
                                @if(cache()->has('usergroup'))
                                    @foreach(cache()->get('usergroup') as $group)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">{{ $group->name }}</label>
                                            <div class="col-sm-9">
                                                {!! makeDropDown([
                                                        0 => 'Group Settings',
                                                        1 => 'Playable',
                                                        2 => 'Playable And Downloadable',
                                                        3 => 'Play And Download Denied'
                                                    ], 'group_extra[' . $group->id . ']', isset($options) && isset($options[$group->id]) ? $options[$group->id] : 0)
                                                !!}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if(isset($video))
                            <input type="hidden" name="file_id" value="{{ $video->file_id }}">
                        @endif
                        <button type="submit" class="btn btn-primary">Save</button>
                        @if(isset($video) && ! $video->approved)
                            <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                        @endif
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        @if(isset($video))
                            <a class="btn btn-danger"  href="{{ route('backend.videos.delete', ['id' => $video->id]) }}" onclick="return confirm('Are you sure want to delete this song?')" data-toggle="tooltip" data-placement="left" title="Delete this song"><i class="fas fa-fw fa-trash"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        @if(isset($video))
            <div class="col-lg-12">
                <div class="mt-5 collapse" id="collapseExample">
                    <form role="form" method="post" action="{{ route('backend.songs.edit.reject.post', ['id' => $video->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" rows="3" name="comment"></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Reject & Send Email to the artist</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection