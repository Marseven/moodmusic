@foreach ($episodes as $index => $episode)
    <tr>
        <td>{{ $episode->title }}</td>
        <td class="desktop">{{ $episode->download_cound }}</td>
        <td class="desktop">{{ $episode->play_count }}</td>
        <td class="desktop">
            @if($episode->approved)
                <span class="badge badge-pill badge-success">Yes</span>
            @else
                <span class="badge badge-pill badge-danger">No</span>
            @endif
        </td>
        <td class="desktop">
            @if($episode->mp3)
                <span class="badge badge-pill badge-dark">MP3</span>
            @endif
            @if($episode->hd)
                <span class="badge badge-pill badge-info">HD</span>
            @endif
            @if($episode->hls)
                <span class="badge badge-pill badge-warning">HLS</span>
            @endif
            @if($episode->stream_url)
                <span class="badge badge-pill badge-secondary">URL</span>
            @endif
        </td>
        <td class="desktop">
            <a class="row-button edit" href="{{ route('backend.podcasts.episodes.edit', ['id' => $podcast->id, 'eid' => $episode->id]) }}" data-toggle="tooltip" data-placement="left" title="Edit this episode"><i class="fas fa-fw fa-edit"></i></a>
            <a class="row-button delete"  href="{{ route('backend.podcasts.episodes.delete', ['id' => $podcast->id, 'eid' => $episode->id]) }}" onclick="return confirm('Are you sure want to delete this episode?')" data-toggle="tooltip" data-placement="left" title="Delete this episode"><i class="fas fa-fw fa-trash"></i></a>
        </td>
        <td>
            <label class="engine-checkbox">
                <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $episode->id }}">
                <span class="checkmark"></span>
            </label>
        </td>
    </tr>
@endforeach