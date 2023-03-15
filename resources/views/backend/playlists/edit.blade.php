@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('backend.playlists') }}">Playlists</a>
        </li>
        <li class="breadcrumb-item active">{!! $playlist->title !!}</li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <div class="row col-lg-12 media-info mb-3 playlist">
        <div class="media">
            <img class="mr-3" src="{{ $playlist->artwork_url }}">
            <div class="media-body">
                <h5 class="mt-0">{!! $playlist->title !!}</h5>
                <p>Created by: {{ $playlist->user ? $playlist->user->name : 'Unknown' }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="title" value="{!! $playlist->title !!}" required>
                </div>
                <div class="form-group">
                    <label>Artwork (min width, height 300x300)</label>
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
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5">{{ $playlist->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Genres</label>
                    <select multiple="" class="form-control select2-active" name="genre[]">
                        {!! genreSelection(explode(',', $playlist->genre)) !!}
                    </select>
                </div>
                <div class="form-group">
                    <label>Mood(s)</label>
                    <select multiple="" class="form-control select2-active" name="mood[]">
                        {!! moodSelection(explode(',', $playlist->mood)) !!}
                    </select>
                </div>
                <input type="submit" class="btn btn-primary" value="Save">
            </form>
        </div>
    </div>
@endsection