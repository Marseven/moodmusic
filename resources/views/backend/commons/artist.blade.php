@foreach ($artists as $index => $artist)
<tr>
    <td class="td-image"><div class="play" data-id="{{ $artist->id }}" data-type="artist">
            <img src="{{ $artist->artwork_url }}"/>
        </div>
    </td>
    <td><a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}">{!! $artist->name !!}</a></td>
    <td>
        @if(isset($artist->user))
            <a href="{{ route('backend.users.edit', ['id' => $artist->user->id]) }}">{{ $artist->user->name }} <span class="badge badge-success badge-pill">{{ __('symbol.' . config('settings.currency', 'USD')) . intval($artist->user->balance) }}</span></a>
        @else
            <span class="text-muted font-weight-light font-italic">System</span>
        @endif
    </td>
    <td class="desktop">@foreach($artist->genres as $genre)<a href="{{ route('backend.genres.edit', ['id' => $genre->id]) }}" title="{{ $genre->name }}">{{$genre->name}}</a>@if(!$loop->last), @endif @endforeach</td>
    <td class="desktop">@foreach($artist->moods as $mood)<a href="{{ route('backend.moods.edit', ['id' => $mood->id]) }}" title="{{ $mood->name }}">{{$mood->name}}</a>@if(!$loop->last), @endif @endforeach</td>
    <td class="desktop">
        @if($artist->verified)
            <span class="badge badge-success">Verified</span>
        @else
            <span class="badge badge-warning">Unverified</span>
        @endif
    </td>
    <td class="desktop text-center">{{ $artist->song_count }}</td>
    <td class="desktop text-center">{{ $artist->album_count }}</td>
    <td class="desktop text-center">{{ $artist->comment_count }}</td>
    <td>
        <a class="row-button upload" href="{{ route('backend.artists.upload', ['id' => $artist->id]) }}"><i class="fas fa-fw fa-upload"></i></a>
        <a class="row-button edit" href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}"><i class="fas fa-fw fa-edit"></i></a>
        <a class="row-button delete" onclick="return confirm('By deleting this artist, all song which linked to this artist will be deleted, Are you sure want to delete this artist?');" href="{{ route('backend.artists.delete', ['id' => $artist->id]) }}"><i class="fas fa-fw fa-trash"></i></a>
    </td>
    <td>
        <label class="engine-checkbox">
            <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $artist->id }}">
            <span class="checkmark"></span>
        </label>
    </td>
</tr>
@endforeach