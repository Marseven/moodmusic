@foreach ($podcasts as $index => $podcast)
    <tr>
        <td class="td-image">
            <img class="media-object" class="img-square-24" src="{{ $podcast->artwork_url }}">
        </td>
        <td><a href="{{ route('backend.podcasts.edit', ['id' => $podcast->id]) }}">{{ $podcast->title }}</a></td>
        @if(isset($podcast->artist))
        <td><a href="{{ route('backend.artists.edit', ['id' => $podcast->artist->id]) }}">{{ $podcast->artist->name }}</a></td>
        @else
            <td>Unknown</td>
        @endif
        <td class="desktop" width="200px">@foreach($podcast->categories as $category)<a href="{{ route('backend.radios.edit', ['id' => $category->id]) }}" title="{{ $category->name }}">{{$category->name}}</a>@if(!$loop->last), @endif @endforeach</td>
        <td>{{ substr($podcast->description, 0, 100) }}...</td>
        <td>{{ $podcast->episodes()->count() }}</td>
        <td>{{ $podcast->loves }}</td>
        <td>
            <a href="{{ route('backend.podcasts.edit', ['id' => $podcast->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
            <a href="{{ route('backend.podcasts.episodes', ['id' => $podcast->id]) }}"><i class="fas fa-fw fa-list"></i></a>
            <a href="{{ route('backend.podcasts.delete', ['id' => $podcast->id]) }}" onclick="return confirm('Are you sure want to delete this podcast?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
        </td>
        <td>
            <label class="engine-checkbox">
                <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $podcast->id }}">
                <span class="checkmark"></span>
            </label>
        </td>
@endforeach