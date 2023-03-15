@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit your profile</h6>
                </div>
                <div class="card-body">
                    <form role="form" action="" enctype="multipart/form-data" method="post">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input class="form-control" name="username" value="{{ $user->username }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Name</label>
                            <div class="col-sm-8">
                                <input class="form-control" name="name" value="{{ $user->name }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Avatar</label>
                            <div class="col-sm-8">
                                <div class="input-group col-xs-12">
                                    <input type="file" name="artwork" class="file-selector" accept="image/*">
                                    <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                    <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                    <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Min width, height 300x300, Image will be automatically cropped and resized to 300x300 pixel.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Email </label>
                            <div class="col-sm-8">
                                <input class="form-control" name="email"  value="{{ $user->email }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Do not receive emails</label>
                            <div class="col-sm-8 col-3">
                                <label class="switch"><input type="checkbox" name="blockEmail"><span class="slider round"></span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Password </label>
                            <div class="col-sm-8">
                                <input class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Remove avatar?</label>
                            <div class="col-sm-8 col-3">
                                <label class="switch"><input type="checkbox" name="removeArtwork"><span class="slider round"></span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Delete all comments?</label>
                            <div class="col-sm-8">
                                <label class="switch"><input type="checkbox" name="deleteComments"><span class="slider round"></span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">About me</label>
                            <div class="col-sm-8">
                                <textarea class="form-control editor" rows="2" name="about"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="doEdit" value="true">
                        <button type="submit" class="btn btn-primary">Send</button>
                        <button type="reset" class="btn btn-info">Reset</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5 main-section text-center">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12 profile-header"></div>
            </div>
            <div class="row user-detail">
                <div class="col-lg-12 col-sm-12 col-12">
                    <img src="{{ $user->artwork_url }}" class="rounded-circle img-thumbnail">
                    <h5>{{ $user->name }}</h5>
                    <p>{{ $user->email }}</p>
                    <table class="mt-4 table table-striped">
                        <tr>
                            <td>Username</td>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td>E-Mail</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Registered</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                        <tr>
                            <td>Recent Activity</td>
                            <td>{{ $user->last_activity }}</td>
                        </tr>
                        <tr>
                            <td>IP</td>
                            <td>{{ $user->logged_ip }}</td>
                        </tr>
                        <tr>
                            <td>Group</td>
                            <td>{{ \App\Models\Role::getValue('group_name') }}</td>
                        </tr>
                        <tr>
                            <td>Badge</td>
                            <td>{!! \App\Models\Role::getValue('group_badge') !!}</td>
                        </tr>
                        @if($user->artist)
                            <tr>
                                <td>Artist/Band</td>
                                <td><a href="{{ route('backend.artists.edit', ['id' => $user->artist->id]) }}">{{ $user->artist->name }}</a></td>
                            </tr>
                        @endif
                        <tr>
                            <td>Songs</td>
                            <td>{{ DB::table('songs')->where('user_id', $user->id)->count() }}</td>
                        </tr>
                        <tr>
                            <td>Albums</td>
                            <td>{{ DB::table('albums')->where('user_id', $user->id)->count() }}</td>
                        </tr>
                        <tr>
                            <td>Playlists</td>
                            <td>{{ DB::table('playlists')->where('user_id', $user->id)->count() }}</td>
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td>{{ DB::table('comments')->where('user_id', $user->id)->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row user-social-detail">
                <div class="col-lg-12 col-sm-12 col-12">
                    @if($user->connect->firstWhere('service', 'facebook'))
                        <a href="https://facebook.com/profile.php?id={{ $user->connect->firstWhere('service', 'facebook')->provider_id }}" target="_blank"><i class="fab fa-facebook-square"></i></a>
                    @endif
                    @if($user->connect->firstWhere('service', 'twitter'))
                        <a href="https://facebook.com/profile.php?id={{ $user->connect->firstWhere('service', 'twitter')->provider_id }}"><i class="fab fa-twitter-square"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection