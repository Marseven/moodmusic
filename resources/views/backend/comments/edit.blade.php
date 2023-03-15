@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.comments') }}">Comments</a></li>
        <li class="breadcrumb-item active"> Edit </li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Content</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="content" row="3" required>{{ $comment->content }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Created at</label>
                    <div class="col-sm-4">
                        <input class="form-control datetimepicker-no-mask" name="created_at" value="{{ \Carbon\Carbon::parse(($comment->created_at))->format('Y/m/d H:i') }}" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Approve</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            {!! makeCheckBox('approved', $comment->approved) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
@endsection