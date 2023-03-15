@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Withdraws ({{ $withdraws->total() }})</li>
    </ol>
    <div class="row">
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
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Details</th>
                    <th class="desktop">Requested at</th>
                    <th width="200">Action</th>
                </tr>
                </thead>
                @foreach ($withdraws as $index => $withdraw )
                    @if($withdraw->user)
                        <tr>
                            <td><a href="{{ route('backend.users.edit', ['id' => $withdraw->user->id]) }}">{{ $withdraw->user->name }}</a></td>
                            <td><span class="text-success">{{ number_format($withdraw->amount, 2) }} {{ config('settings.currency', 'USD') }}</span></td>
                            <td>{{ ucwords($withdraw->user->payment_method) }}</td>
                            <td>
                                @if($withdraw->user->payment_method == 'paypal')
                                    {{ $withdraw->user->payment_paypal  }}
                                @else
                                    {{ $withdraw->user->payment_bank  }}
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($withdraw->created_at)->format('M j, Y') }}</td>
                            <td>
                                @if($withdraw->paid)
                                    <button class="btn btn-sm btn-success " data-action="mark-paid" data-id="{{ $withdraw->id }}" data-init="true">Paid</button>
                                @else
                                    <button class="btn btn-sm btn-secondary " data-action="mark-paid" data-id="{{ $withdraw->id }}">Mark as Paid</button>
                                @endif
                                <button class="btn btn-sm btn-danger" data-action="decline-request" data-id="{{ $withdraw->id }}">Decline</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <div class="pagination pagination-right">
                {{ $withdraws->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection