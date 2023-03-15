@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Requests ({{ $total_requests }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form mothod="GET" action="{{ route('backend.artists') }}">
                <div class="form-group input-group">
                    <input type="text" class="form-control" name="q" value="{{ $term }}" placeholder="Enter artist name">
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
                    <th>Artist Name</th>
                    <th>Requester</th>
                    <th>Affiliation</th>
                    <th class="desktop">Phone</th>
                    <th class="desktop">Facebook</th>
                    <th class="desktop">Twitter</th>
                    <th class="desktop">Passport</th>
                    <th class="th-2action" class="desktop">Approved</th>
                    <th class="th-2action">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($requests as $index => $request)
                    @if(isset( $request->user))
                        <tr>
                            @if($request->artist)
                                <td><a href="{{ route('backend.artists.edit', ['id' => $request->artist->id]) }}">{{ $request->artist_name }}</a></td>
                            @else
                                <td><span data-toggle="tooltip" data-placement="top" title="Artist is not in database">{{ $request->artist_name }}</span></td>
                            @endif
                            <td class="desktop">
                                <a href="{{ route('backend.users.edit', ['id' => $request->user->id]) }}">
                                    {{ $request->user->name }}
                                </a>
                            </td>
                            <td class="desktop"><a class="badge badge-info text-white" data-toggle="tooltip" data-placement="top" title="{{ $request->message }}">{{ $request->affiliation }} (?)</a></td>
                            <td class="desktop">{{ $request->phone }} (ext: {{ $request->ext }})</td>
                            <td class="desktop">
                                @if($request->user->connect->firstWhere('service', 'facebook'))
                                    <img class="img-square-24" src="{{ $request->user->connect->firstWhere('service', 'facebook')->provider_artwork }}">
                                    <span class="badge badge-success"> Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </td>
                            <td class="desktop">
                                @if($request->user->connect->firstWhere('service', 'twitter'))
                                    <img class="img-square-24" src="{{ $request->user->connect->firstWhere('service', 'twitter')->provider_artwork }}">
                                    <span class="badge badge-success"> Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </td>
                            <td class="desktop">
                                @if($request->getFirstMediaUrl('passport'))
                                    <span class="badge badge-success">Yes</span> (<a href="{{ $request->getFirstMediaUrl('passport') }}" target="_blank">see passport</a>)
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </td>
                            <td class="desktop">
                                @if($request->approved)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </td>
                            <td>
                                <a class="row-button edit" data-toggle="tooltip" data-placement="top" title="Check the request details" href="{{ route('backend.artist.access.edit', ['id' => $request->id]) }}"><i class="fas fa-fw fa-edit"></i></a>
                                <a class="row-button delete" data-toggle="tooltip" data-placement="top" title="Reject this request, an email will be automatically send to the author." onclick="return confirm('Are you sure want to reject this request?');" href="{{ route('backend.artist.access.reject', ['id' => $request->id]) }}"><i class="fas fa-eject"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="pagination pagination-right">
                {{ $requests->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection