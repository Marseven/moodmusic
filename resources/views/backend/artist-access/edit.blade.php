@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.artist.access') }}">Artist Claim Requests</a></li>
        <li class="breadcrumb-item active">{{ $request->artist_name }}</li>
    </ol>
        <div class="main-section text-center">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12 profile-header"></div>
            </div>
            <div class="row user-detail">
                <div class="col-lg-12 col-sm-12 col-12">
                    @if($request->artist)
                        <img src="{{ $request->artist->artwork_url }}" class="rounded-circle img-thumbnail">
                    @else
                        <img src="{{ $request->user->artwork_url }}" class="rounded-circle img-thumbnail">
                    @endif
                    <h5>{{ $request->artist_name }}</h5>
                    <p class="text-muted">Artist</p>
                    <table class="mt-4 table table-striped">
                        <tbody>
                        <tr>
                            <td>Requested by</td>
                            <td>
                                <a href="{{ route('backend.users.edit', ['id' => $request->user->id]) }}">
                                    <img class="img-square-24" src="{{ $request->user->artwork_url }}">
                                    <span>{{ $request->user->name }}</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Requester's affiliation</td>
                            <td>
                                {{ $request->affiliation }}
                            </td>
                        </tr>
                        <tr>
                            <td>Message to admin</td>
                            <td>
                                {{ $request->message }}
                            </td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td>{{ $request->user->email }}</td>
                        </tr>
                        <tr>
                            <td>Phone contact</td>
                            <td>{{ $request->phone }} (ext: {{ $request->ext }})</td>
                        </tr>
                        <tr>
                            <td>Requested date</td>
                            <td>{{ $request->created_at }}</td>
                        </tr>
                        <tr>
                            <td>Facebook profile</td>
                            <td>
                                @if($request->user->connect->firstWhere('service', 'facebook') && isset($request->user->connect->firstWhere('service', 'facebook')->provider_artwork))
                                    <img class="img-square-24" src="{{ $request->user->connect->firstWhere('service', 'facebook')->provider_artwork }}">
                                    <span class="badge badge-success"> Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Twitter profile</td>
                            <td>
                                @if($request->user->connect->firstWhere('service', 'twitter') && isset($request->user->connect->firstWhere('service', 'facebook')->provider_artwork))
                                    <img class="img-square-24" src="{{ $request->user->connect->firstWhere('service', 'facebook')->provider_artwork }}">
                                    <span class="badge badge-success"> Verified</span>
                                @else
                                    <span class="badge badge-warning">Unverified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Passport uploaded</td>
                            <td>
                                @if($request->getFirstMediaUrl('passport'))
                                    <a href="{{ $request->getFirstMediaUrl('passport') }}" target="_blank">
                                        <img class="img-square-24" src="{{ $request->getFirstMediaUrl('passport') }}">
                                        <span class="badge badge-success"> uploaded</span>
                                    </a>
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Is approved?</td>
                            <td>
                                @if($request->approved)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <form role="form" method="post" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Reject</button>
                </form>
                <div class="m-5 collapse" id="collapseExample">
                    <form role="form" method="post" action="">
                        @csrf
                        <input type="hidden" name="reject" value="1">
                        <div class="form-group">
                            <label>Comment</label>
                            <textarea class="form-control" rows="3" name="comment"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Reject &amp; Send Email to the artist</button>
                    </form>
                </div>
            </div>
        </div>
@endsection