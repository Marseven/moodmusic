@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item">System message</li>
        <li class="breadcrumb-item active">{{ $message }}</li>

    </ol>
    <div class="row">
        <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ $message }}</h6>
                    </div>
                    <div class="card-body">
                        <form role="form" method="post" action="">
                            @csrf
                            <p class="text-center">{!! $subMessage !!}</p>
                            <div class="form-group">
                                <label>End time for the ban</label>
                                <input class="form-control datepicker-no-mask" name="ban_end_at" value=""  autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Ban reason</label>
                                <textarea class="form-control editor" rows="5" name="ban_reason"></textarea>
                            </div>
                            <div class="d-flex justify-content-center">
                                @foreach($ids as $id)
                                    <input name="ids[]" type="hidden" value="{{ $id }}">
                                @endforeach
                                <input name="action" type="hidden" value="save_{{ $action }}">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>


        </div>
    </div>
@endsection