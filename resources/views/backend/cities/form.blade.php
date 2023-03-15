@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.cities') }}">Cities</a></li>
        <li class="breadcrumb-item active">{{ isset($city) ? $city->name : ' Add new City' }}</li>
    </ol>
    @if(isset($city))
        <div class="row col-lg-12 media-info mb-3 country">
            <div class="media">
                <img class="mr-3" src="{{ $city->artwork_url }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $city->name }}</h5>
                    <p>{{ $city->continent }}</p>
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
                    <input class="form-control" type="text" name="name" value="{{ isset($city) && ! old('name') ? $city->name : old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    {!! makeCountryDropDown('country_code', 'form-control select2-active', isset($city) ?  $city->country->code : null) !!}
                </div>
                <div class="form-group form-inline">
                    <label>Fixed</label>
                    <div class="col-sm-8 col-3">
                        <label class="switch">
                            {!! makeCheckBox('fixed', isset($city->fixed) ?  $city->fixed : 0) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Artwork</label>
                    <div class="input-group col-xs-12">
                        <input type="file" name="artwork" class="file-selector" accept="image/*">
                        <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                        <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                            <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection