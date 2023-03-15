@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('backend.email') }}">Email templates</a></li>
        <li class="breadcrumb-item active">{{ $email->description }}</li>

    </ol>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $email->description }}</h6>
        </div>
        <div class="card-body">
            <form role="form" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-4">Subject
                        <p class="small">The header with control information and other data.</p>
                    </label>
                    <div class="col-sm-8">
                        <input class="form-control" name="subject" value="{{ $email->subject }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Email body
                        <p class="small">The body of the message which includes the sender's text, as well as attachments and other component.</p>
                    </label>
                    <div class="col-sm-8">
                        <textarea class="form-control default editor" rows="6" name="content">{{ $email->content }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection