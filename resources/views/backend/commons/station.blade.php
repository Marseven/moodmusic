@foreach ($stations as $index => $station)
    <tr>
        <td class="td-image">
            <img class="media-object" class="img-square-24" src="{{ $station->artwork_url }}">
        </td>
        <td><a href="{{ route('backend.stations.edit', ['id' => $station->id]) }}">{{ $station->title }}</a></td>
        <td class="desktop" width="200px">@foreach($station->categories as $category)<a href="{{ route('backend.radios.edit', ['id' => $category->id]) }}" title="{{ $category->name }}">{{$category->name}}</a>@if(!$loop->last), @endif @endforeach</td>
        <td>{{ substr($station->description, 0, 100) }}...</td>
        <td><span class="badge badge-danger badge-pill">{{ $station->failed_count }}</span></td>
        <td>{{ $station->play_count }}</td>
        <td>
            <a href="{{ route('backend.stations.edit', ['id' => $station->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
            <a href="{{ route('backend.stations.delete', ['id' => $station->id]) }}" onclick="return confirm('Are you sure want to delete this station?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
        </td>
@endforeach