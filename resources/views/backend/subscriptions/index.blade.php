@extends('backend.index')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('backend.dashboard') }}">Control Panel</a>
    </li>
    <li class="breadcrumb-item active">Subscriptions ({{ $total }})</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">A plan’s billing cycle defines the recurring frequency with which the subscriber is charged.</div>
        <form mothod="GET" action="">
            <div class="form-group input-group">
                <input type="text" class="form-control" name="term" value="{{ $term }}" placeholder="Enter customer name">
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
                <th>Subscriber</th>
                <th>Gate</th>
                <th>Status</th>
                <th class="desktop">Plan</th>
                <th class="desktop">Billing</th>
                <th class="desktop">Cycles</th>
                <th>Last Payment</th>
                <th>Next billing</th>
                <th class="desktop">Updated</th>
                <th class="desktop">Action</th>
            </tr>
            </thead>
            @foreach ($subscriptions as $index => $order )
                @if($order->user)
                    <tr>
                        <td><a href="{{ route('backend.users.edit', ['id' => $order->user->id]) }}">{{ $order->user->name }}</a></td>
                        <td>{{ ucwords($order->gate) }}</td>
                        <td>
                            @if(\Carbon\Carbon::parse($order->trial_end)->gt(\Carbon\Carbon::now()))
                                <span class="badge badge-info">Trial ends {{ \Carbon\Carbon::parse($order->trial_end)->format('F j') }}</span>
                            @elseif(\Carbon\Carbon::parse($order->next_billing_date)->gt(\Carbon\Carbon::now()))
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">in-Active</span>
                            @endif
                        </td>
                        <td class="desktop">
                            @if(isset($order->service))
                                <a href="{{ route('backend.services.edit', ['id' => $order->service->id]) }}">{{ $order->service->title }}</a>
                            @endif
                        </td>
                        <td><span class="badge badge-secondary">Auto</span></td>
                        <td><span class="badge badge-success">{{ $order->cycles }}</span></td>
                        <td><span class="text-success">{{ __('symbol.' . $order->currency) }}{{ number_format($order->amount) }}</span> {{ \Carbon\Carbon::parse($order->last_payment_date)->format('M j') }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->next_billing_date)->format('M j') }}</td>
                        <td class="desktop">{{ timeElapsedShortString($order->updated_at) }}</td>
                        <td>
                            <a class="btn btn-danger btn-sm text-white">Cancel & Refund</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
        <div class="pagination pagination-right">
            {{ $subscriptions->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection