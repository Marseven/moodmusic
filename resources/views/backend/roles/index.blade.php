@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">User Groups</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form method="post" action="">
                @csrf
                <div class="form-group input-group">
                    <input type="text" class="form-control" name="name" placeholder="Create new group and role" autocomplete="off">
                    <span class="input-group-append">
			        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Start</button>
			    </span>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="th-2action">ID</th>
                    <th>Group</th>
                    <th class="desktop th-4action">Member(s)</th>
                    <th class="th-2action">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $index => $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td><a href="{{ route('backend.roles.edit', ['id' => $role->id]) }}">{{ $role->name }}</a> @if(isset($role->permissions) && isset($role->permissions['admin_access'])) <span class="text-danger">(has access to the Administration Panel)</span> @endif</td>
                        @if($role->id != 6)
                            <td class="desktop">{{ DB::table('role_users')->where('role_id', $role->id)->count()  }}</td>
                        @else
                            <td class="desktop">-</td>
                        @endif
                        <td>
                            <a href="{{ route('backend.roles.edit', ['id' => $role->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                            @if($role->id != 1 && $role->id != 5 && $role->id != 6)
                                <a href="{{ route('backend.roles.delete', ['id' => $role->id]) }}" class="row-button delete" onclick="return confirm('Are you sure?')"><i class="fas fa-fw fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection