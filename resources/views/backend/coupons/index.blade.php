@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Coupons</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 py-3 border-left-info">
                <div class="card-body">
                    Create and edit coupons code
                </div>
            </div>
            <a href="{{ route('backend.coupons.add') }}" class="btn btn-primary">Add new coupon</a>
            <table class="mt-4 table table-striped">
                <thead>
                <tr>
                    <th>Code</th>
                    <th class="desktop">Type</th>
                    <th class="desktop">Discount amount</th>
                    <th class="desktop">Used</th>
                    <th class="desktop">Expired at</th>
                    <th class="desktop">Created at</th>
                    <th class="desktop">Is active</th>
                    <th class="th-3action">Action</th>
                </tr>
                </thead>
                @foreach ($coupons as $index => $coupon)
                    <tr>
                        <td><a href="{{ route('backend.coupons.edit', ['id' => $coupon->id]) }}">{{ $coupon->code }}</a></td>
                        <td class="desktop">{{ $coupon->type }}</td>
                        <td class="desktop">{{ $coupon->amount }}</td>
                        <td class="desktop">{{ $coupon->use_count }}</td>
                        <td class="desktop">
                            @if($coupon->expired_at)
                                {{ $coupon->expired_at }}
                            @else
                                <span class="badge badge-info badge-pill">never</span>
                            @endif
                        </td>
                        <td class="desktop">{{ timeElapsedString($coupon->created_at) }}</td>
                        <td class="desktop">
                            @if($coupon->approved)
                                <span class="badge badge-success badge-pill">active</span>
                            @else
                                <span class="badge badge-danger badge-pill">in-active</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('backend.coupons.edit', ['id' => $coupon->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                            <a href="{{ route('backend.coupons.delete', ['id' => $coupon->id]) }}" class="row-button delete" onclick="return confirm('Are you sure to delete this page page?')"><i class="fas fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection