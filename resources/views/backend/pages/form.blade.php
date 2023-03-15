@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.pages') }}">Pages</a></li>
        <li class="breadcrumb-item active">{{ isset($page) ? $page->title : 'Add new page' }}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal">
                @csrf
                <div class="form-group">
                    <label>Title:</label>
                    <input class="form-control" type="text" name="title" value="{{ isset($page) && ! old('title') ? $page->title : old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>User-friendly URL:</label>
                    <input class="form-control" type="text" name="alt_name" value="{{ isset($page) && ! old('alt_name') ? $page->alt_name : old('alt_name') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Content:</label>
                    <textarea name="content" class="form-control default editor" rows="5">{{ isset($page) && ! old('content') ? $page->content : old('content') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Meta title:</label>
                    <input class="form-control" type="text" name="meta_title" value="{{ isset($page) && ! old('meta_title') ? $page->meta_title : old('meta_title') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Meta description:</label>
                    <input class="form-control" type="text" name="meta_description" value="{{ isset($page) && ! old('meta_description') ? $page->meta_description : old('meta_description') }}" placeholder="Option">
                </div>
                <div class="form-group">
                    <label>Meta keywords:</label>
                    <input class="form-control" type="text" name="meta_keywords" value="{{ isset($page) && ! old('meta_keywords') ? $page->meta_keywords : old('meta_keywords') }}" placeholder="Option">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection