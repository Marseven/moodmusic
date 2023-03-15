@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.artists') }}">Artists</a></li>
        <li class="breadcrumb-item active">Create new artist</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group">
                    <label>Name </label>
                    <input class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label>Artwork</label>
                    <div class="input-group col-xs-12">
                        <input type="file" name="artwork" class="file-selector" accept="image/*" required>
                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Biography:</label>
                    <textarea class="form-control" rows="3" name="bio"></textarea>
                </div>
                <div class="form-group">
                    <label>Genre(s)</label>
                    <select multiple="" class="form-control select2-active" name="genre[]">
                        {!! genreSelection(0, 0) !!}
                    </select>
                </div>
                <div class="form-group">
                    <label>Mood(s)</label>
                    <select multiple="" class="form-control select2-active" name="mood[]">
                        {!! moodSelection(0, 0) !!}
                    </select>
                </div>
                <input type="hidden" name="doAdd" value="true">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection