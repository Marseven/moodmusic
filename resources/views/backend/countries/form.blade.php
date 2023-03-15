@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.countries') }}">Countries</a></li>
        <li class="breadcrumb-item active">{{ isset($country) ? $country->name : ' Add new Country' }}</li>
    </ol>
    @if(isset($country))
        <div class="row col-lg-12 media-info mb-3 country">
            <div class="media">
                <img class="mr-3 img-160-90" src="{{ $country->artwork_url }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $country->name }}</h5>
                    <p>{{ $country->continent }}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>3 Chars Country Code (<a class="text-info" href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3" target="_blank">more info</a>)</label>
                    <input class="form-control" type="text" name="code" value="{{ isset($country) && ! old('code') ? $country->code : old('code') }}" required>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ isset($country) && ! old('name') ? $country->name : old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Region</label>
                    {!! makeRegionDropDown("region_id", 'select2-active', isset($country) && ! old('region_id') ? $country->region_id : old('region_id')) !!}
                </div>
                <div class="form-group">
                    <label>Local Name</label>
                    <input class="form-control" type="text" name="local_name" value="{{ isset($country) && ! old('local_name') ? $country->local_name : old('local_name') }}">
                </div>
                <div class="form-group">
                    <label>Government Form</label>
                    <input class="form-control" type="text" name="government_form" value="{{ isset($country) && ! old('government_form') ? $country->government_form : old('government_form') }}">
                </div>
                <div class="form-group form-inline">
                    <label>Fixed</label>
                    <div class="col-sm-8 col-3">
                        <label class="switch">
                            {!! makeCheckBox('fixed', isset($country->fixed) ?  $country->fixed : 0) !!}
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