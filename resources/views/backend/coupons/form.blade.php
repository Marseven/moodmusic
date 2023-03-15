@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.coupons') }}">Coupons</a></li>
        <li class="breadcrumb-item active">{{ isset($coupon) ? $coupon->code : 'Add new coupon' }}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="" class="form-horizontal">
                @csrf
                <div class="form-group">
                    <label>Code:</label>
                    <input class="form-control" type="text" name="code" value="{{ isset($coupon) && ! old('code') ? $coupon->code : old('code') }}" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" class="form-control" rows="5">{{ isset($coupon) && ! old('description') ? $coupon->description : old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Discount Type</label>
                    {!! makeDropDown(
                        array("percentage" => "Percentage", "fixed" => "Fixed price"),
                        "type",
                        null,
                        true
                    ) !!}
                </div>
                <div class="form-group">
                    <label>Coupon amount:</label>
                    <input class="form-control" type="number" name="amount" value="{{ isset($coupon) && ! old('amount') ? $coupon->amount : old('amount') }}" required>
                </div>
                <div class="form-group">
                    <label>Minimum spend (integer, in {{ config('settings.currency', 'USD') }}):</label>
                    <input class="form-control" type="number" name="minimum_spend" value="{{ isset($coupon) && ! old('minimum_spend') ? $coupon->minimum_spend : old('minimum_spend') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Maximum spend (integer, in {{ config('settings.currency', 'USD') }}):</label>
                    <input class="form-control" type="number" step="1" name="maximum_spend" value="{{ isset($coupon) && ! old('maximum_spend') ? $coupon->maximum_spend : old('maximum_spend') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Expiry date:</label>
                    <input class="form-control datetimepicker-with-form" type="text" name="expired_at" value="{{ isset($coupon) && ! old('expired_at') ? $coupon->expired_at : old('expired_at') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Usage limit:</label>
                    <input class="form-control" type="number" step="1" name="usage_limit" value="{{ isset($coupon) && ! old('usage_limit') ? $coupon->usage_limit : old('usage_limit') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="switch">
                            {!! makeCheckBox('approved', isset($coupon) && ! old('approved') ? $coupon->approved : old('approved') ) !!}
                            <span class="slider round"></span>
                        </label>
                        <label class="pl-6 col-form-label">Active this coupon</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection