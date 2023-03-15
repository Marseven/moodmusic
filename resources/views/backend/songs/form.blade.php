@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ url('/admin/songs') }}">Songs</a></li>
        @if(Route::currentRouteName() == 'backend.songs.edit')
            <li class="breadcrumb-item active">{!! $song->title !!} - @foreach($song->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</li>
        @endif
        @if(Route::currentRouteName() == 'backend.songs.add')
            <li class="breadcrumb-item active">Add New Song</li>
        @endif
    </ol>
    <div class="row">
        @if(Route::currentRouteName() == 'backend.songs.edit')
            <div class="col-lg-12 media-info mb-3 album">
                <div class="media mb-3">
                    <img class="mr-3" src="{{ $song->artwork_url }}">
                    <div class="media-body">
                        <h5 class="m-0">{!! $song->title !!} - @foreach($song->artists as $artist)<a href="{{ url('admin/artists/edit/' . $artist->id) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</h5>
                        <h5>
                            @if($song->mp3)
                                <span class="badge badge-pill badge-dark">MP3</span>
                            @endif
                            @if($song->hd)
                                <span class="badge badge-pill badge-danger">HD</span>
                            @endif
                            @if($song->hls)
                                <span class="badge badge-pill badge-warning">HLS</span>
                            @endif
                        </h5>
                        <p class="m-0"><a href="{{ $song->permalink_url }}" class="btn btn-warning" target="_blank">Preview @if(! $song->approved) (only Moderator) @endif</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 media-info mb-3 song">
                <iframe width="100%" height="60" frameborder="0" src="{{ asset('share/embed/dark/song/' . $song->id) }}"></iframe>
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
                                    <label>Track Name</label>
                                    <input class="form-control" name="title" value="{{ isset($song) && ! old('title') ? $song->title : old('title') }}" required>
                                </div>
                                <div class="form-group multi-artists">
                                    <label>Artist(s)</label>
                                    <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="" required>
                                        @if(Route::currentRouteName() == 'backend.songs.edit')
                                            @foreach ($song->artists as $index => $artist)
                                                <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group select2-artwork">
                                    <label>Album(s)</label>
                                    <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.album') }}" name="albumIds[]">
                                        @if(Route::currentRouteName() == 'backend.songs.edit')
                                            @if($song->album)
                                                <option value="{{ $song->album->id }}" selected="selected" data-artwork="{{ $song->album->artwork_url }}"  data-title="{{ $song->album->title }}">{{ $song->album->title }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Artwork File</label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="artwork" class="file-selector" accept="image/*">
                                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                        <span class="input-group-btn">
                                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-image"></i> Browse</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Audio File</label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="file" class="file-selector" accept="audio/*">
                                        <span class="input-group-addon"><i class="fas fa-fw fa-music"></i></span>
                                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Music File">
                                        <span class="input-group-btn">
                                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-music"></i> Browse</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="4" class="form-control" name="description">{{ isset($song) && ! old('description') ? $song->description : old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Lyrics</label>
                                    <textarea rows="10" class="form-control" name="lyrics">{{ (isset($lyric) && isset($lyric->id)) && ! old('lyrics') ? $lyric->lyrics : old('lyrics') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Genres</label>
                                    <select multiple="" class="form-control select2-active" name="genre[]">
                                        {!! genreSelection(explode(',', isset($song) && ! old('genre') ? $song->genre : old('genre'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Moods</label>
                                    <select multiple="" class="form-control select2-active" name="mood[]">
                                        {!! moodSelection(explode(',', isset($song) && ! old('mood') ? $song->mood : old('mood'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tags</label>
                                    {!! makeTagSelector('tags[]', isset($song) && ! old('tags') ? array_column($song->tags->toArray(), 'tag')  : old('tags')) !!}
                                </div>
                                <div class="form-group">
                                    <label>Released At</label>
                                    <input type="text" class="form-control datepicker" name="released_at" value="{{ \Carbon\Carbon::parse( isset($song) && ! old('released_at') ? $song->released_at : old('released_at'))->format('m/d/Y') }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Copyright</label>
                                    <input type="text" class="form-control" name="copyright" value="{{ isset($song) && ! old('copyright') ? $song->copyright : old('copyright') }}">
                                </div>
                                @if(isset($song->bpm))
                                    <div class="form-group">
                                        <label>BPM</label>
                                        <input type="text" class="form-control" name="bpm" value="{{ isset($song) && ! old('bpm') ? $song->bpm : old('bpm') }}">
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Youtube ID</label>
                                    <input type="text" class="form-control" name="youtube_id" value="{{ isset($song) && ! old('youtube') ? (isset($song->log->youtube) ? $song->log->youtube : '') : old('youtube') }}">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('selling', isset($song) && ! old('selling') ? $song->selling : old('selling')) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Allow to sell this song</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name="price" value="{{ isset($song) && ! old('price') ? $song->price : old('price') }}">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('allow_comments', isset($song) && ! old('allow_comments') ? $song->allow_comments : old('allow_comments') ) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Allow to comment</label>
                                    </div>
                                </div>
                                @if(Route::currentRouteName() == 'backend.songs.edit')
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="switch">
                                                {!! makeCheckBox('approved', isset($song) && ! old('approved') ? $song->approved : old('approved')) !!}
                                                <span class="slider round"></span>
                                            </label>
                                            <label class="pl-6 col-form-label">Approve this song</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div id="streamable" class="tab-pane fade">
                                <div class="alert alert-info">Note: You can configure additional song playable and downloadable parameters for different groups in this section.</div>
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
                        @if(Route::currentRouteName() == 'backend.songs.edit')
                            <input type="hidden" name="file_id" value="{{ $song->file_id }}">
                            <button type="submit" class="btn btn-primary">Save</button>
                            @if(! $song->approved)
                                <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                            @endif
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <a class="btn btn-danger"  href="{{ route('backend.songs.delete', ['id' => $song->id]) }}" onclick="return confirm('Are you sure want to delete this song?')" data-toggle="tooltip" data-placement="left" title="Delete this song"><i class="fas fa-fw fa-trash"></i></a>
                        @endif
                        @if(Route::currentRouteName() == 'backend.songs.add')
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        @if(Route::currentRouteName() == 'backend.songs.edit')
            <div class="col-lg-12">
                <div class="mt-5 collapse" id="collapseExample">
                    <form role="form" method="post" action="{{ route('backend.songs.edit.reject.post', ['id' => $song->id]) }}" enctype="multipart/form-data">
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