@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Orders ({{ $orders->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                    </div>
                    <div class="mr-5 h2">{{ $stats->revenue }} {{ config('settings.currency', 'USD') }}</div>
                </div>
                <div class="card-footer text-white clearfix small z-1">
                    <span class="float-left">Total Revenue</span>
                    <span class="float-right">
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-secondary o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                    </div>
                    <div class="mr-5 h2">{{ $stats->commission }} {{ config('settings.currency', 'USD') }}</div>
                </div>
                <div class="card-footer text-white clearfix small z-1">
                    <span class="float-left">Artist's Commission</span>
                    <span class="float-right">
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                    </div>
                    <div class="mr-5 h2">{{ $stats->song->revenue }} {{ config('settings.currency', 'USD') }}</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('backend.songs') }}">
                    <span class="float-left">Song sales</span>
                    <span class="float-right">
                        {{ $stats->song->count }} items
                    </span>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                    </div>
                    <div class="mr-5 h2">{{ $stats->album->revenue }} {{ config('settings.currency', 'USD') }}</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="{{ route('backend.artists') }}">
                    <span class="float-left">Album sales</span>
                    <span class="float-right">
                        {{ $stats->album->count }} items
                    </span>
                </a>
            </div>
        </div>
        <div class="col-lg-12">
            <form mothod="GET" action="">
                <div class="form-group input-group">
                    <input type="text" class="form-control" name="q" value="{{ $term }}" placeholder="Enter customer name">
                    <span class="input-group-append">
			        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
			    </span>
                    <span class="input-group-append">
			        <button class="btn btn-primary"><i class="fa fa-filter"></i></button>
			    </span>
                </div>
            </form>
            <table class="table table-striped datatables table-hover">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Item</th>
                    <th>Payment</th>
                    <th class="desktop">Amount</th>
                    <th>Status</th>
                    <th width="150">Payment Date</th>
                </tr>
                </thead>
                @foreach ($orders as $index => $order )
                    @if($order->user)
                        <tr>
                            <td><a href="{{ route('backend.users.edit', ['id' => $order->user->id]) }}">{{ $order->user->name }}</a></td>
                            <td>
                                @if(isset($order->object))
                                    <a href="{{ $order->object->permalink_url  }}" target="_blank">{{ $order->object->title }}</a>
                                @endif
                            </td>
                            <td>{{ $order->payment }}</td>
                            <td><span class="text-success">{{ __('symbol.' . $order->currency) }}{{ number_format($order->amount, 2) }}</span></td>
                            <td>
                                <span class="badge badge-success">success</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->next_billing_date)->format('M j, Y') }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <div class="pagination pagination-right">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection