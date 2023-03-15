@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.metatags') }}">Metatags</a></li>
        <li class="breadcrumb-item active">For URL: {{ $metatag->url }}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4 py-3 border-left-info">
                    <div class="card-body card-small">
                        <p>Preview for <span class="badge badge-secondary">{{ url($metatag->url) }}</span></p>
<textarea disabled rows="10" class="form-control">
<title>{{ MetaTag::get('title') }}</title>
{!! MetaTag::tag('description') !!}
{!! MetaTag::tag('keywords') !!}
{!! MetaTag::get('image') ? MetaTag::tag('image') : '' !!}
{!! MetaTag::openGraph() !!}
{!! MetaTag::twitterCard() !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">URL
                        <p class="small">Specify URL of the website for which you want to assign meta tags.</p>
                    </label>
                    <div class="col-sm-8">
                        <input class="form-control" name="url" value="{{ $metatag->url }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Information</label>
                    <div class="col-sm-8">
                        <input class="form-control" value="{{ $metatag->info }}" name="info">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Page title</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="title" value="{{ $metatag->page_title }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Page description
                        <p class="small">Text that can be displayed on the search engine.</p>
                    </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="2" name="description">{{ $metatag->page_description }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Keywords</label>
                    <div class="col-sm-8">
                        {!! makeTagSelector('keywords[]', isset($metatag) && ! old('keywords') ? $metatag->page_keywords : old('keywords')) !!}
                    </div>
                </div>
                <div class="form-group row border-bottom">
                    <label class="col-sm-4">Auto general keywords
                        <p class="small">Automatically mass generate keywords (base on title and description) to maximise your search engine presence. .</p>
                    </label>
                    <div class="col-sm-8 col-9">
                        <label class="switch">
                            {!! makeCheckBox('auto_keyword', $metatag->auto_keyword) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Page Artwork
                        <p class="small mt-2">This will use for social share, if the page already contain an artwork this will overwrite the current one. For example song page will use this instead of song artwork.</p>
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group col-xs-12">
                            <input type="file" name="artwork" class="file-selector" accept="image/*">
                            <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                            <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                            <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection