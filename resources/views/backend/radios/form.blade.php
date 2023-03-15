@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Radio</a></li>
        <li class="breadcrumb-item active">{{ isset($radio) ? $radio->name : 'Add new Radio category' }}</li>
    </ol>
    @if(isset($radio))
    <div class="row col-lg-12 media-info mb-3 radio">
        <div class="media">
            <img class="mr-3 wide" src="{{ $radio->artwork_url }}">
            <div class="media-body">
                <h5 class="mt-0">{{ $radio->name }}</h5>
                <p>{{ $radio->description }}</p>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ isset($radio) && ! old('name') ? $radio->name : old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>User-friendly URL</label>
                    <input class="form-control" type="text" name="alt_name" value="{{ isset($radio) && ! old('alt_name') ? $radio->alt_name : old('alt_name') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ isset($radio) && ! old('description') ? $radio->description : old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ isset($radio) && ! old('meta_title') ? $radio->meta_title : old('meta_title') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Meta description</label>
                    <textarea name="meta_description" class="form-control" rows="2" placeholder="Option">{{ isset($radio) && ! old('meta_description') ? $radio->meta_description : old('meta_description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta keywords</label>
                    {!! makeTagSelector('meta_keywords[]', isset($radio) && ! old('meta_keywords') ? $radio->meta_keywords : old('meta_keywords')) !!}
                </div>
                <div class="form-group">
                    <label>Edit artwork</label>
                    <div class="input-group col-xs-12">
                        <input type="file" name="artwork" class="file-selector" accept="image/*" {{ isset($radio) ?: 'required' }}>
                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save">
                    <button type="reset" class="btn btn-info">Reset</button>
                </div>
            </form>
        </div>
    </div>
@endsection