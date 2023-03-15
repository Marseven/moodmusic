@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.services') }}">Plans</a></li>
        <li class="breadcrumb-item active">@if(isset($service)) {{ $service->title }} @else Add new plan @endif</li>
    </ol>

    <div class="row">
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="title" value="{{ isset($service) && ! old('title') ? $service->title : old('title') }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control editor" rows="10" name="description" required>{{ isset($service) && ! old('description') ? $service->description : old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-5">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon"><i class="fas fa-fw fa-money-check"></i></span>
                            <input type="text" class="form-control input-lg money" name="price" value="{{ isset($service) && ! old('price') ? $service->price : old('price') }}" placeholder="0.00" required>
                            <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-hand-holding-usd"></i> {{ __('currency.'. config('settings.currency', 'USD')) }}</button></span>
                        </div>
                        <small id="emailHelp" class="form-text text-muted">In {{ __('currency.'. config('settings.currency', 'USD')) }}, numeric only</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Trial</label>
                    <div class="col-sm-5">
                        <label class="switch">
                            {!! makeCheckBox('trial', isset($service) && ! old('trial') ? $service->trial : (old('trial') ? old('trial') : 1)) !!}
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Trial Period<small id="emailHelp" class="form-text text-muted"><span class="text-danger">Customers will not be charged before the end of their trial period.</span></small>
                    </label>
                    <div class="col-sm-5">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon"><i class="fas fa-fw fa-money-check"></i></span>
                            <input type="text" class="form-control input-lg number" name="trial_period" value="{{ isset($service) && ! old('trial_period') ? $service->trial_period : old('trial_period') }}" placeholder="0">
                        </div>
                        <small id="emailHelp" class="form-text text-muted">Trial period time, should be in day, week, month, or year.</small>
                    </div>
                    <div class="col-sm-5">
                        {!! makeDropDown( array("D" => "Day", "W" => "Week", "M" => "Month", "Y" => "Year"), "trial_period_format", isset($service) ? $service->trial_period_format : old('trial_period_format') ) !!}
                        <small id="emailHelp" class="form-text text-muted">Trial period type.</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Plan Period</label>
                    <div class="col-sm-5">
                        <div class="input-group col-xs-12">
                            <span class="input-group-addon"><i class="fas fa-fw fa-money-check"></i></span>
                            <input type="text" class="form-control input-lg number" name="plan_period" value="{{ isset($service) && ! old('plan_period') ? $service->plan_period : old('plan_period') }}" placeholder="0" required>
                        </div>
                        <small id="emailHelp" class="form-text text-muted">Trial period time, should be in day, week, month, or year.</small>
                    </div>
                    <div class="col-sm-5">
                        {!! makeDropDown( array("D" => "Day", "W" => "Week", "M" => "Month", "Y" => "Year"), "plan_period_format", isset($service) ? $service->plan_period_format : old('plan_period_format') ) !!}
                        <small id="emailHelp" class="form-text text-muted">Plan period type.</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Move user to this group after purchased<br><span class="small text-danger">The user will be moved automatically to this group after the payment has been made.</span></label>
                    <div class="col-sm-5">
                        {!! makeRolesDropDown('role_id', isset($service) && ! old('role_id') ? $service->role_id : old('role_id'), 'required') !!}
                        <small id="emailHelp" class="form-text text-muted">You can set permission for any groups by use admin <a href="{{ route('backend.roles') }}">Roles section</a></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Is active? </label>
                    <div class="col-sm-10">
                        <label class="switch">
                            {!! makeCheckBox('active', isset($service) && ! old('active') ? $service->active : (old('active') ? old('active') : 1)) !!}
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