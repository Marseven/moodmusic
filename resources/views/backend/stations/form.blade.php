@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.stations') }}">Stations</a></li>
        <li class="breadcrumb-item active">{{ isset($station) ? $station->title : ' Add new Station' }}</li>
    </ol>
    @if(isset($station))
        <div class="row col-lg-12 media-info mb-3 station">
            <div class="media">
                <img class="mr-3" src="{{ $station->artwork_url }}">
                <div class="media-body">
                    <h5 class="mt-0">{{ $station->title }}</h5>
                    <p>{{ $station->description }}</p>
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
                    <input class="form-control" type="text" name="title" value="{{ isset($station) && ! old('title') ? $station->title : old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select multiple="" class="form-control select2-active" name="category[]">
                        {!! radioCategorySelection(isset($station) && ! old('category') ? $station->category : old('category')) !!}
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
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5">{{ isset($station) && ! old('description') ? $station->description : old('description') }}</textarea>
                </div>
                <div class="form-group filter-country">
                    <label>Country/City</label>
                    <div class="form-inline">
                        <div class="filter-country">
                            {!! makeCountryDropDown('country_code', 'form-control select2-active filter-country-select', isset($station) && ! old('country_code') ? $station->country_code : old('country_code')) !!}
                        </div>
                        <div class="ml-3 @if(! isset($station) || ! $station->country_code) d-none @endif filter-city">
                            @if(isset($station) && ($station->country_code || $station->city_id))
                                {!! makeCityDropDown($station->country_code, 'city_id', 'form-control select2-active filter-city-select', isset($station) && ! old('city_id') ? $station->city_id : old('city_id')) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group filter-language @if(! isset($station) || ! $station->country_code) d-none @endif">
                    <label>Language</label>
                    <div class="form-inline filter-language-select">
                        @if(isset($station) && ($station->country_code || $station->language_id))
                            {!! makeCountryLanguageDropDown($station->country_code, 'language_id', 'form-control select2-active', isset($station) && ! old('language_id') ? $station->language_id : old('language_id')) !!}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>Stream url (only audio accepted)</label>
                    <input class="form-control" type="text" name="stream_url" value="{{ isset($station) && ! old('stream_url') ? $station->stream_url : old('stream_url') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection