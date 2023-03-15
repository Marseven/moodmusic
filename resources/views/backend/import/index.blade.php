@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Import Music From Spotify</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form role="form" method="get" action="">
                <div class="form-group row mb-2 p-0">
                    <label class="col-sm-3 col-3 col-form-label text-right">Keyword</label>
                    <div class="col-xl-3 col-sm-6 col-9">
                        <input class="form-control" type="text" name="term" required>
                    </div>
                </div>
                <div class="form-group row mb-2 p-0">
                    <label class="col-sm-3 col-3 col-form-label text-right">Type</label>
                    <div class="col-xl-3 col-sm-6 col-9">
                        <select class="form-control" name="type">
                            <option value="song">Song</option>
                            <option value="album">Album</option>
                            <option value="artist">Artist</option>
                            <!-- <option value="song">Playlist</option> -->
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2 mt-4 p-0">
                    <label class="col-sm-3 col-3 col-form-label text-right"></label>
                    <div class="col-xl-3 col-sm-6 col-9">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
            <div class="alert alert-info">Note: Import an artist mean all their songs and albums will be imported along with artist.</div>
            @if(isset($data))
                <form id="mass-action-form" method="post" action="{{ route('backend.import.mass.action') }}">
                    @csrf
                    @if($type == 'song')
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="th-image"></th>
                                <th class="desktop">Title</th>
                                <th class="desktop">Artist(s)</th>
                                <th class="desktop">Source</th>
                                <th class="th-checkbox">
                                    <label class="engine-checkbox">
                                        <input id="check-all" class="multi-check-box" type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['tracks']['items'] as $item)
                                <tr>
                                    <td class="td-image">
                                        <img src="{{ $item['album']['images'][1]['url'] }}">
                                    </td>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="desktop">
                                        @foreach ($item['album']['artists'] as $artist_item)
                                            {{ $artist_item['name'] }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="desktop"><span class="badge badge-pill badge-success">Spotify</span></td>
                                    <td>
                                        <label class="engine-checkbox">
                                            <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $item['id'] }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @elseif($type == 'artist')
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="th-image"></th>
                                    <th class="desktop">Name</th>
                                    <th class="desktop">Source</th>
                                    <th class="th-checkbox">
                                        <label class="engine-checkbox">
                                            <input id="check-all" class="multi-check-box" type="checkbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['artists']['items'] as $item)
                                    <tr>
                                        <td class="td-image">
                                            @if(isset($item['images'][1]))
                                                <img src="{{ $item['images'][1]['url'] }}">

                                            @elseif(isset($item['images'][0]))
                                                <img src="{{ $item['images'][0]['url'] }}">
                                            @else
                                                <img src="{{ asset( 'common/default/artist.png') }}">
                                            @endif
                                        </td>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="desktop"><span class="badge badge-pill badge-success">Spotify</span></td>
                                        <td>
                                            <label class="engine-checkbox">
                                                <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $item['id'] }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @elseif($type == 'album')
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="th-image"></th>
                                <th class="desktop">Title</th>
                                <th class="desktop">Artist(s)</th>
                                <th class="desktop">Source</th>
                                <th class="th-checkbox">
                                    <label class="engine-checkbox">
                                        <input id="check-all" class="multi-check-box" type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['albums']['items'] as $item)
                                <tr>
                                    <td class="td-image">
                                        <img src="{{ $item['images'][1]['url'] }}">
                                    </td>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="desktop">
                                        @foreach ($item['artists'] as $artist_item)
                                            {{ $artist_item['name'] }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="desktop"><span class="badge badge-pill badge-success">Spotify</span></td>
                                    <td>
                                        <label class="engine-checkbox">
                                            <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $item['id'] }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    <div class="d-flex float-right">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="form-group mb-2">
                            <select name="action" class="form-control mr-2">
                                <option value="import">Start Import</option>
                            </select>
                        </div>
                        <button id="start-mass-action" type="button" class="btn btn-primary mb-2 ml-2">Start</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection