@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.categories') }}">Categories</a></li>
        <li class="breadcrumb-item active">{{ isset($category) ? $category->name : 'Add new category' }}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Name
                        <p class="small">The name is how it appears on your site.</p></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="name" value="{{ isset($category) ? $category->name : old('name') }}" required>
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Alternative name</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="alt_name" value="{{ isset($category) ? $category->alt_name : old('alt_name') }}">
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="2" name="description">{{ isset($category) ? $category->description : old('description') }}</textarea>
                    </div>
                </div>

                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Meta title</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="meta_title" value="{{ isset($category) ? $category->meta_title : old('meta_title') }}" placeholder="Option">
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3 col-form-label">Meta description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="2" name="meta_description" placeholder="Option">{{ isset($category) ? $category->meta_description : old('meta_description') }}</textarea>
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3 col-form-label">Meta keywords</label>
                    <div class="col-sm-9">
                        {!! makeTagSelector('meta_keywords[]',isset($category) ? $category->meta_keywords : old('meta_keywords')) !!}
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Criterion of the news sort</label>
                    <div class="col-sm-9">
                        {!! makeDropDown( array("0" => "Global settings", "1" => "By publication date", "2" => "By views", "3" => "Alphabetical", "4" => "By number of comments"), "news_sort", isset($category) ? $category->meta_description : old('news_sort') ) !!}
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Show news published in the sub-categories</label>
                    <div class="col-sm-9">
                        {!! makeDropDown( array("0" => "Global settings", "1" => "No", "2" => "Yes"), "show_sub", isset($category) ? $category->meta_description : old('show_sub') ) !!}
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Disable publishing on the homepage</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('disable_main', isset($category) ? $category->disable_main : old('disable_main')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Disable commenting in articles</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('disable_comments', isset($category) ? $category->disable_comments : old('disable_comments')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                    <label class="col-sm-3">Exclude from site search</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('disable_search', isset($category) ? $category->disable_search : old('disable_search')) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row border-bottom mb-4 pt-3 pb-3">
                    <label class="col-sm-3">Artwork (min width, height 300x300)</p>
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-12">
                            <input type="file" name="artwork" class="file-selector" accept="image/*">
                            <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                            <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                            <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection