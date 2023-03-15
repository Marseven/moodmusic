@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Services({{ $total }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 py-3 border-left-info">
                <div class="card-body">
                    Manage registered users, edit their profiles and block their accounts
                </div>
            </div>
            <p>
                <a href="{{ route('backend.services.add') }}" class="btn btn-primary">Add new plan</a>
            </p>
            <form mothod="GET" action="{{ route('backend.services') }}">
                <div class="form-group input-group">
                    <input type="hidden" name="do" value="users">
                    <input type="text" class="form-control" name="q" value="{{ $term }}" placeholder="Enter subscription title">
                    <span class="input-group-append">
			        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
			        </button>
			    </span>
                </div>
            </form>
            <table class="table table-striped datatables table-hover">
                <colgroup>
                    <col class="span1">
                    <col class="span7">
                </colgroup>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Trial</th>
                    <th class="desktop">Period</th>
                    <th class="desktop">To Group</th>
                    <th class="desktop">Created At</th>
                    <th class="desktop">Updated At</th>
                    <th class="th-2action">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($services as $index => $item)
                    <tr>
                        <td><a href="{{ route('backend.services.edit', ['id' => $item->id]) }}">{{ $item->title }}</a></td>
                        <td>{{ number_format($item->price, 2) }} {{ config('settings.currency', 'USD') }}</td>
                        <td>
                            @if(! $item->trial)
                                <span class="badge badge-danger">No</span>
                            @else
                                <span class="badge badge-success">{{ $item->trial_period }}
                                    @switch($item->trial_period_format)
                                        @case('D')
                                        Day
                                        @break
                                        @case('W')
                                        Week
                                        @break
                                        @case('M')
                                        Month
                                        @break
                                        @case('Y')
                                        Year
                                        @break
                                    @endswitch
                            </span>
                            @endif
                        </td>

                        <td class="desktop">{{ $item->plan_period }}
                            @switch($item->plan_period_format)
                                @case('D')
                                Day
                                @break
                                @case('W')
                                Week
                                @break
                                @case('M')
                                Month
                                @break
                                @case('Y')
                                Year
                                @break
                            @endswitch
                        </td>
                        <td class="desktop">
                            @if(isset($item->role))
                                <a href="{{ route('backend.roles.edit', ['id' => $item->role_id]) }}">{{ $item->role->name }}</a>
                            @endif
                        </td>
                        <td class="desktop">{{ timeElapsedString($item->created_at) }}</td>
                        <td class="desktop">{{ timeElapsedString($item->updated_at) }}</td>
                        <td>
                            <a class="row-button edit" href="{{ route('backend.services.edit', ['id' => $item->id]) }}"><i class="fas fa-fw fa-edit"></i></a>
                            <a class="row-button delete" onclick="var r=confirm('By deleting this user, all song which linked to this user will be deleted too, Are you sure want to delete this user?');if (r==true){window.location='{{ route('backend.services.delete', ['id' => $item->id]) }}'}; return false;" href="{{ route('backend.services.delete', ['id' => $item->id]) }}"><i class="fas fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination pagination-right">
                {{ $services->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection