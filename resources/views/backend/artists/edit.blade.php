@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.artists') }}">Artists</a></li>
        <li class="breadcrumb-item active">{!! $artist->name !!}</li>
    </ol>
    <div class="row col-lg-12 media-info mb-3 artist">
        <div class="media">
            <img class="mr-3" src="{{ $artist->artwork_url }}">
            <div class="media-body">
                <h5 class="mt-0">{!! $artist->name !!}</h5>
                <p>Songs: {{ $artist->song_count }} (approved)</p>
                <p>Albums: {{ $artist->album_count }} (approved)</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs nav-justified">
                <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="pill">Edit</a></li>
                <li class="nav-item"><a href="#tab2" class="nav-link"  data-toggle="pill">Songs</a></li>
                <li class="nav-item"><a href="#tab3" class="nav-link"  data-toggle="pill">Albums</a></li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div id="tab1" class="tab-pane fade show active">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" action="" enctype="multipart/form-data" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label>Name </label>
                                            <input class="form-control" name="name" value="{!! $artist->name !!}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Artwork</label>
                                            <div class="input-group col-xs-12 position-relative">
                                                <input type="file" name="artwork" class="file-selector" accept="image/*">
                                                <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                                <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                                <span class="input-group-btn">
                                                    <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" name="update-song-artwork">
                                                <label class="custom-control-label" for="customCheck">Also update artwork for all songs by this artist (which not in any album)</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Biography:</label>
                                            <textarea class="form-control" rows="3" name="bio">{{ $artist->bio }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Genre(s)</label>
                                            <select multiple="" class="form-control select2-active" name="genre[]">
                                                {!! genreSelection(explode(',', $artist->genre)) !!}
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Mood(s)</label>
                                            <select multiple="" class="form-control select2-active" name="mood[]">
                                                {!! moodSelection(explode(',', $artist->mood)) !!}
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                        <button type="reset" class="btn btn-info">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped datatables table-hover">
                                        <colgroup>
                                            <col class="span1">
                                            <col class="span7">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th class="th-image"></th>
                                            <th>Title <span title="Double click to edit" data-toggle="tooltip" data-placement="top">(?)</span></th>
                                            <th class="desktop">Artist(s)</th>
                                            <th class="desktop">Album</th>
                                            <th class="desktop">Likes</th>
                                            <th class="desktop">Plays</th>
                                            <th class="desktop">Approved</th>
                                            <th class="desktop">Extra</th>
                                            <th class="desktop">Disk</th>
                                            <th class="th-2action">Action</th>
                                            <th class="th-1action"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @include('backend.commons.song', ['songs' => $artist->songs])
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab3" class="tab-pane fade">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-striped datatables table-hover">
                                        <colgroup>
                                            <col class="span1">
                                            <col class="span7">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th class="th-image"></th>
                                            <th>Name</th>
                                            <th>Artist</th>
                                            <th class="desktop">Approved</th>
                                            <th class="th-3action">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @include('backend.commons.album', ['albums' => $artist->albums])
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection