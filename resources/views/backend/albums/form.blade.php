@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('backend.albums') }}">Albums</a></li>
        <li class="breadcrumb-item active">
            @if(isset($album))
                <a href="{{ route('backend.albums.edit', ['id' => $album->id]) }}">{!! $album->title !!}</a> - @foreach($album->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
            @else
                Add New Album
            @endif
        </li>
    </ol>
    <div class="row">
        @if(isset($album))
            <div class="col-lg-12 media-info mb-3 album">
                <div class="media mb-3">
                    <img class="mr-3" src="{{ $album->artwork_url }}">
                    <div class="media-body">
                        <h5 class="m-0">{!! $album->title !!} - @foreach($album->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</h5>
                        <p>Songs: {{ $album->song_count }}</p>
                        <p class="m-0"><a href="{{ $album->permalink_url }}" class="btn btn-warning" target="_blank">Preview @if(! $album->approved) (only Moderator) @endif</a> <a href="{{ route('backend.albums.tracklist', ['id' => $album->id]) }}" class="btn btn-info">Tracks List</a> <a href="{{ route('backend.albums.upload', ['id' => $album->id]) }}" class="btn btn-success">Upload</a></p>
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
                                <div class="form-group multi-artists">
                                    <label>Artists</label>
                                    <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="">
                                        @if(isset($album))
                                            @foreach ($album->artists as $index => $artist)
                                                <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Album Name</label>
                                    <input name="name" class="form-control" value="{{ isset($album) && ! old('title') ? $album->title : old('title') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Artwork</label>
                                    <div class="input-group col-xs-12">
                                        <input type="file" name="artwork" class="file-selector" accept="image/*">
                                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                        <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('update-song-artwork') !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Also update artwork for all songs in this album</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" rows="3" name="description">{{ isset($album) && ! old('description') ? $album->description : old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Genre(s)</label>
                                    <select multiple="" class="form-control select2-active" name="genre[]">
                                        {!! genreSelection(explode(',', isset($album) && ! old('genre') ? $album->genre : old('genre'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mood(s)</label>
                                    <select multiple="" class="form-control select2-active" name="mood[]">
                                        {!! moodSelection(explode(',', isset($album) && ! old('mood') ? $album->mood : old('mood'))) !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Copyright</label>
                                    <input type="text" class="form-control" name="copyright" value="{{ isset($album) && ! old('copyright') ? $album->copyright : old('copyright') }}">
                                </div>
                                <div class="form-group">
                                    <label>Released At</label>
                                    <input type="text" class="form-control datepicker" name="released_at" value="{{ isset($album) && ! old('released_at') ? \Carbon\Carbon::parse($album->released_at)->format('m/d/Y') : old('released_at') }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Schedule Publish</label>
                                    <input type="text" class="form-control datepicker" name="created_at" value="{{ isset($album) && ! old('created_at') ? \Carbon\Carbon::parse($album->created_at)->format('m/d/Y') : old('created_at') }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('selling', isset($album) && ! old('selling') ? $album->selling : old('selling')) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Allow to sell this album</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name="price" value="{{ isset($album) && ! old('price') ? $album->price : old('price') }}">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="switch">
                                            {!! makeCheckBox('approved', isset($album) && ! old('approved') ? $album->approved : old('approved')) !!}
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="pl-6 col-form-label">Approve this album</label>
                                    </div>
                                </div>
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
                        <button type="submit" class="btn btn-primary">Save</button>
                        @if(isset($album) && ! $album->approved)
                            <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                        @endif
                        @if(Route::currentRouteName() == 'backend.albums.edit')
                            <input type="hidden" name="file_id" value="{{ $album->file_id }}">
                            @if(! $album->approved)
                                <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                            @endif
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <a class="btn btn-danger"  href="{{ route('backend.songs.delete', ['id' => $album->id]) }}" onclick="return confirm('Are you sure want to delete this song?')" data-toggle="tooltip" data-placement="left" title="Delete this song"><i class="fas fa-fw fa-trash"></i></a>
                        @endif
                        @if(Route::currentRouteName() == 'backend.albums.add')
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection