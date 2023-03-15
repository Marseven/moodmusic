@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('backend.albums') }}">Albums</a></li>
        <li class="breadcrumb-item"><a href="{{ route('backend.albums.edit', ['id' => $album->id]) }}">{!! $album->title !!}</a> - @foreach($album->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</li>
        <li class="breadcrumb-item active">Tracks list</li>
    </ol>
    <div class="row">
        <div class="col-lg-12 media-info mb-3 album">
            <div class="media mb-3">
                <img class="mr-3" src="{{ $album->artwork_url }}">
                <div class="media-body">
                    <h5 class="m-0">{!! $album->title !!} - @foreach($album->artists as $artist)<a href="{{ route('backend.artists.edit', ['id' => $artist->id]) }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</h5>
                    <p>Songs: {{ $album->song_count }}</p>
                    <p class="m-0"><a href="{{ $album->permalink_url }}" class="btn btn-warning" target="_blank">Preview @if(! $album->approved) (only Moderator) @endif</a> <a href="{{ route('backend.albums.upload', ['id' => $album->id]) }}" class="btn btn-success">Upload</a></p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="box-suggest">
                <form method="get" id="suggest-form" data-object-type="album" data-object-id="{{ $album->id }}" action="{{ route('api.search.song') }}">
                    <div class="form-group input-group">
                        <input type="hidden" name="limit" value="5">
                        <input type="text" class="form-control suggest-tracks-form" name="q" placeholder="Add song to this album" autocomplete="off">
                    </div>
                </form>
                <div class="auto-suggest"></div>
            </div>
            <form id="mass-action-form" method="post" action="{{ route('backend.albums.tracklist.mass.action', ['id' => $album->id]) }}">
                @csrf
                <table class="table table-striped table-sortable">
                    <colgroup>
                        <col class="span1">
                        <col class="span7">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="th-handle"></th>
                        <th class="th-priority">Priority</th>
                        <th class="th-image"></th>
                        <th>Title <span title="Double click to edit" data-toggle="tooltip" data-placement="top">(?)</span></th>
                        <th class="desktop">Artist(s)</th>
                        <th class="desktop">Album</th>
                        <th class="desktop">Likes</th>
                        <th class="desktop">Plays</th>
                        <th class="desktop">Approved</th>
                        <th class="desktop">Extra</th>
                        <th class="desktop">Disk</th>
                        <th class="th-2action">Action</th>
                        <th class="th-checkbox">
                            <label class="engine-checkbox">
                                <input id="check-all" class="multi-check-box" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="song-row">
                        @include('backend.commons.song', ['songs' => $album->songs, 'sortOrder' => true])
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary mt-4">Save sort order</button>
                    </div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="remove_from_album">Remove from this album</option>
                                    <option value="delete">Remove from this album & delete song</option>
                                </select>
                            </div>
                            <button id="start-mass-action" type="button" class="btn btn-primary mb-2">Start</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection