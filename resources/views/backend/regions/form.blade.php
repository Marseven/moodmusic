@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Radio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.country.languages') }}">Regions</a></li>
        <li class="breadcrumb-item active">{{ isset($region) ? $region->name : ' Add new region' }}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" value="{{ isset($region) && ! old('name') ? $region->name : old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Alternative name (for SEO URL)</label>
                    <input class="form-control" type="text" name="alt_name" value="{{ isset($region) && ! old('alt_name') ? $region->alt_name : old('alt_name') }}" required>
                    <p class="small text-info">Used to view all content in this category. This field is required. Only latin characters are allowed.</p>
                </div>
                <div class="form-group form-inline">
                    <label>Visible</label>
                    <div class="col-sm-8 col-3">
                        <label class="switch">
                            {!! makeCheckBox('visibility', isset($region->visibility) ?  $region->visibility : 0) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection