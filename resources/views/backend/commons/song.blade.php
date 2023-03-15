@foreach ($songs as $index => $song)
    <tr>
        @if(isset($sortOrder))
            <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
            <td><input type="hidden" name="songIds[]" value="{{ $song->id }}"></td>
        @endif
        <td class="td-image">
            <a href="{{ route('backend.songs.edit', ['id' => $song->id]) }}" data-toggle="tooltip" data-placement="left" title="Edit this song">
                <img src="{{ $song->artwork_url }}"/>
            </a>
        </td>
        <td id="track_{{ $song->id }}" class="editable" title="Click to edit">{!! $song->title !!}</td>
        <td class="desktop">@foreach($song->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</td>
        @if($song->album)
            <td class="desktop">
                <a href="{{ route('backend.albums.edit', ['id' => $song->album->id]) }}">{{ $song->album->title }}</a>
            </td>
        @else
            <td></td>
        @endif
        <td class="desktop">{{ $song->loves }}</td>
        <td class="desktop">{{ $song->plays }}</td>
        <td class="desktop">
            @if($song->approved)
                <span class="badge badge-pill badge-success">Yes</span>
            @else
                <span class="badge badge-pill badge-danger">No</span>
            @endif
        </td>
        <td class="desktop">
            @if($song->preview)
                <span class="badge badge-pill badge-danger">PR</span>
            @endif
            @if($song->mp3)
                <span class="badge badge-pill badge-dark">MP3</span>
            @endif
            @if($song->hd)
                <span class="badge badge-pill badge-info">HD</span>
            @endif
            @if($song->hls)
                <span class="badge badge-pill badge-warning">HLS</span>
            @endif
        </td>
            <td class="desktop">
                @if($song->getFirstMedia('audio'))
                    <span class="badge badge-pill badge-dark">{{ $song->getFirstMedia('audio')->disk }}</span>
                @elseif($song->getFirstMedia('hls'))
                    <span class="badge badge-pill badge-dark">{{ $song->getFirstMedia('hls')->disk }}</span>
                @elseif($song->getFirstMedia('hd_audio'))
                    <span class="badge badge-pill badge-dark">{{ $song->getFirstMedia('hd_audio')->disk }}</span>
                @elseif($song->getFirstMedia('hd_hls'))
                    <span class="badge badge-pill badge-dark">{{ $song->getFirstMedia('hd_hls')->disk }}</span>
                @endif
            </td>
        <td class="desktop">
            <a class="row-button edit" href="{{ route('backend.songs.edit', ['id' => $song->id]) }}" data-toggle="tooltip" data-placement="left" title="Edit this song"><i class="fas fa-fw fa-edit"></i></a>
            <a class="row-button delete"  href="{{ route('backend.songs.delete', ['id' => $song->id]) }}" onclick="return confirm('Are you sure want to delete this song?')" data-toggle="tooltip" data-placement="left" title="Delete this song"><i class="fas fa-fw fa-trash"></i></a>
        </td>
        <td>
            <label class="engine-checkbox">
                <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $song->id }}">
                <span class="checkmark"></span>
            </label>
        </td>
    </tr>
@endforeach
