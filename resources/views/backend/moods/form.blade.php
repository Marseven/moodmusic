@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.moods') }}">Moods</a></li>
        <li class="breadcrumb-item active">{{ isset($mood) ? $mood->name : 'Add new mood'}}</li>
    </ol>
    @if(isset($mood))
        <div class="row col-lg-12 media-info mb-3 mood">
            <div class="media">
                <img class="wide mr-3" src="{{ $mood->artwork_url }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $mood->name }}</h5>

                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ isset($mood) && ! old('name') ? $mood->name : old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>User-friendly URL</label>
                    <input class="form-control" type="text" name="alt_name" value="{{ isset($mood) && ! old('alt_name') ? $mood->alt_name : old('alt_name') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" value="{{ isset($mood) && ! old('description') ? $mood->description : old('description') }}">
                </div>
                <div class="form-group">
                    <label>Meta title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ isset($mood) && ! old('meta_title') ? $mood->meta_title : old('meta_title') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Meta description</label>
                    <textarea name="meta_description" class="form-control" rows="2" placeholder="Option">{{ isset($mood) && ! old('meta_description') ? $mood->meta_description : old('meta_description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta keywords</label>
                    {!! makeTagSelector('meta_keywords[]', isset($mood) && ! old('meta_keywords') ? $mood->meta_keywords : old('meta_keywords')) !!}
                </div>
                <div class="form-group">
                    <label>Artwork (min width, height 300x300, Image will be automatically crop and resize to 300/176 pixel)</label>
                    <div class="input-group col-xs-12">
                        <input type="file" name="artwork" class="file-selector" accept="image/*" {{ isset($mood) ?: 'required' }}>
                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
@endsection