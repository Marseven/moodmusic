@foreach ($users as $index => $user)
    <tr @if(isset($user->group) && isset($user->group->role) &&  ($user->group->role->id == 1 && auth()->user()->group->role->id != 1)) class="overlay-permission" @endif @if($user->ban) class="overlay-banned" @endif>
        <td class="td-image"><div class="play" data-id="{{ $user->id }}" data-type="user"><img src="{{ $user->artwork_url }}"/></div></td>
        <td><a href="{{ route('backend.users.edit', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
        <td class="desktop">{{ $user->username }}</td>
        <td class="desktop"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
        @if(\App\Models\Role::getValue('admin_roles'))
            @if(isset($user->group->role->name))
                <td class="desktop"><a data-toggle="tooltip" title="Edit group: {{ $user->group->role->name }}" href="{{ route('backend.roles.edit', ['id' => $user->group->role_id]) }}">{{ $user->group->role->name }}</a></td>
            @else
                <td class="desktop"><span class="badge badge-warning">missing</span></td>
            @endif
        @else
            @if(isset($user->group->role->name))
                <td class="desktop"><span data-toggle="tooltip" title="Group: {{ $user->group->role->name }}" href="{{ route('backend.roles.edit', ['id' => $user->group->role_id]) }}">{{ $user->group->role->name }}</span></td>
            @else
                <td class="desktop"><span class="badge badge-warning">missing</span></td>
            @endif
        @endif
        <td class="desktop">{{ timeElapsedString($user->created_at) }}</td>
        @if($user->last_activity)
            <td class="desktop">
                {{ timeElapsedString($user->last_activity) }}
            </td>
        @else
            <td class="desktop">Unknown</td>
        @endif
        <td class="text-center desktop">{{ $user->post_count }}</td>
        <td class="text-center desktop">{{ $user->song_count }}</td>
        <td class="text-center desktop">{{ $user->comment_count }}</td>
        <td class="desktop">
            <a class="row-button edit" href="{{ route('backend.users.edit', ['id' => $user->id]) }}"><i class="fas fa-fw fa-edit"></i></a>
            <a class="row-button delete" onclick="var r=confirm('By deleting this user, all song which linked to this user will be deleted too, Are you sure want to delete this user?');if (r==true){window.location='{{ route('backend.users.delete', ['id' => $user->id]) }}'}; return false;" href="{{ route('backend.users.delete', ['id' => $user->id]) }}"><i class="fas fa-fw fa-trash"></i></a>
        </td>
        <td>
            @if(isset($user->group) && isset($user->group->role) && ($user->group->role->id == 1 && auth()->user()->group->role->id != 1))

            @else
                <label class="engine-checkbox">
                    <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $user->id }}">
                    <span class="checkmark"></span>
                </label>
            @endif
        </td>
    </tr>
@endforeach