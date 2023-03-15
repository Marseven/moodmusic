@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ url('/admin/songs') }}">Songs</a></li>
        <li class="breadcrumb-item active"> {!! $song->title !!} - @foreach($song->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</li>
    </ol>
    <div class="row">
        <div class="col-lg-12 media-info mb-3 album">
            <div class="media mb-3">
                <img class="mr-3" src="{{ $song->artwork_url }}">
                <div class="media-body">
                    <h5 class="m-0">{!! $song->title !!} - @foreach($song->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</h5>
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
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group">
                    <label>Track Name</label>
                    <input class="form-control" name="title" value="{!! $song->title !!}" required>
                </div>
                <div class="form-group multi-artists">
                    <label>Artist(s)</label>
                    <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="">
                        @foreach ($song->artists as $index => $artist)
                            <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group select2-artwork">
                    <label>Album(s)</label>
                    <select class="form-control select-ajax" data-ajax--url="/api/search/album" name="albumIds[]">
                        @if($song->album)
                            <option value="{{ $song->album->id }}" selected="selected" data-artwork="{{ $song->album->artwork_url }}"  data-title="{{ $song->album->title }}">{{ $song->album->title }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>Edit artwork</label>
                    <div class="input-group col-xs-12">
                        <input type="file" name="artwork" class="file-selector" accept="image/*">
                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Genres</label>
                    <select multiple="" class="form-control select2-active" name="genre[]">
                        {!! genreSelection(explode(',', $song->genre)) !!}
                    </select>
                </div>
                <div class="form-group">
                    <label>Moods</label>
                    <select multiple="" class="form-control select2-active" name="mood[]">
                        {!! moodSelection(explode(',', $song->mood)) !!}
                    </select>
                </div>
                <div class="form-group">
                    <label>Released At</label>
                    <input type="text" class="form-control datepicker" name="released_at" value="{{ \Carbon\Carbon::parse($song->released_at)->format('m/d/Y') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Copyright</label>
                    <input type="text" class="form-control" name="copyright" value="{{ $song->copyright }}">
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="switch">
                            {!! makeCheckBox('allow_comments', $song->allow_comments ) !!}
                            <span class="slider round"></span>
                        </label>
                        <label class="pl-6 col-form-label">Allow to comment</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="switch">
                            {!! makeCheckBox('approved', $song->approved ) !!}
                            <span class="slider round"></span>
                        </label>
                        <label class="pl-6 col-form-label">Approve this song</label>
                    </div>
                </div>
                <input type="hidden" name="file_id" value="{{ $song->file_id }}">
                <button type="submit" class="btn btn-primary">Save</button>
                @if(! $song->approved)
                    <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                @endif
                <button type="reset" class="btn btn-secondary">Reset</button>
                <a class="btn btn-danger"  href="{{ route('backend.songs.delete', ['id' => $song->id]) }}" onclick="return confirm('Are you sure want to delete this song?')" data-toggle="tooltip" data-placement="left" title="Delete this song"><i class="fas fa-fw fa-trash"></i></a>
            </form>


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
    </div>
@endsection