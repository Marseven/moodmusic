@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.users') }}">Users</a></li>
        <li class="breadcrumb-item active">Add new user</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" action="" enctype="multipart/form-data" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="username" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Artwork</label>
                    <div class="col-sm-10">
                        <div class="input-group col-xs-12">
                            <input type="file" name="artwork" class="file-selector" accept="image/*">
                            <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                            <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                            <span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button></span>
                        </div>
                        <small id="emailHelp" class="form-text text-muted">Min width, height 300x300, Image will be automatically crop and resize to 300/176 pixel.</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email </label>
                    <div class="col-sm-10">
                        <input class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Do not receive emails</label>
                    <div class="col-sm-10 col-3">
                        <label class="switch"><input type="checkbox" name="blockEmail]"><span class="slider round"></span></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password </label>
                    <div class="col-sm-10">
                        <input class="form-control" name="password" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Banned</label>
                    <div class="col-sm-10 col-3">
                        <label class="switch"><input type="checkbox" name="bannedUser]"><span class="slider round"></span></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Restrict to publish</label>
                    <div class="col-sm-10">
                        <select multiple="" class="form-control select2-active" name="publishRestrict[]">
                            <option name="music">Music</option>
                            <option name="music">Comment</option>
                            <option name="music">Playlist</option>
                        </select>
                    </div>
                </div>
                @if(\App\Models\Role::getValue('admin_roles'))
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Group</label>
                        <div class="col-sm-10">
                            {!! makeRolesDropDown('role', null, 'required') !!}
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Remove avatar?</label>
                    <div class="col-sm-10 col-3">
                        <label class="switch"><input type="checkbox" name="removeArtwork]"><span class="slider round"></span></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Delete all comments?</label>
                    <div class="col-sm-10 col-3">
                        <label class="switch"><input type="checkbox" name="deleteComments]"><span class="slider round"></span></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Block by IP</label>
                    <div class="col-sm-10">
                        <textarea class="form-control editor" rows="2" name="blockIps"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">About me</label>
                    <div class="col-sm-10">
                        <textarea class="form-control editor" rows="2" name="about"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
                <button type="reset" class="btn btn-info">Reset</button>
            </form>
        </div>
    </div>
@endsection