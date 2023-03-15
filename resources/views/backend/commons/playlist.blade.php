@foreach ($playlists as $index => $playlist)
    <tr>
        <td class="td-image"><div class="play" data-id="{$row['playlist_id']}" data-type="playlist"><img class="media-object" src="{{ $playlist->artwork_url }}"></div></td>
        <td><a href="{{ route('backend.playlists.tracklist', ['id' => $playlist->id]) }}">{!! $playlist->title !!}</a></td>
        <td class="desktop">@foreach($playlist->genres as $genre)<a href="{{ route('backend.genres.edit', ['id' => $genre->id]) }}" title="{{ $genre->name }}">{{$genre->name}}</a>@if(!$loop->last), @endif @endforeach</td>
        <td class="desktop">@foreach($playlist->moods as $mood)<a href="{{ route('backend.moods.edit', ['id' => $mood->id]) }}" title="{{ $mood->name }}">{{$mood->name}}</a>@if(!$loop->last), @endif @endforeach</td>
        @if(isset($playlist->user))
            <td class="desktop"><a href="{{ route('backend.users.edit', ['id' => $playlist->user->id]) }}">{{ $playlist->user->name }}</a></td>
        @else
            <td class="desktop">Unknown</td>
        @endif
        <td class="desktop">
            @if($playlist->visibility)
                <span class="badge badge-pill badge-success">Pubic</span>
            @else
                <span class="badge badge-pill badge-warning">Private</span>
            @endif
        </td>
        <td class="desktop text-center">{{ $playlist->comment_count }}</td>
        <td class="desktop text-center">{{ $playlist->loves }}</td>
        <td>
            <a href="{{ route('backend.playlists.edit', ['id' => $playlist->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
            <a href="{{ route('backend.playlists.tracklist', ['id' => $playlist->id]) }}" class="row-button list"><i class="fas fa-fw fa-list"></i></a>
            <a href="{{ route('backend.playlists.delete', ['id' => $playlist->id]) }}" onclick="return confirm('Are you sure want to delete this playlist?');" class="row-button delete delete-mixes"><i class="fas fa-fw fa-trash"></i></a>
        </td>
        <td>
            <label class="engine-checkbox">
                <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $playlist->id }}">
                <span class="checkmark"></span>
            </label>
        </td>
    </tr>
@endforeach