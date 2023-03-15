@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.playlists') }}">Playlists</a></li>
        <li class="breadcrumb-item active">{!! $playlist->title !!}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="box-suggest">
                <form mothod="get" id="suggest-form" data-object-type="playlist" data-object-id="{{ $playlist->id }}" action="{{ route('api.search.song') }}">
                    <div class="form-group input-group">
                        <input type="hidden" name="limit" value="5">
                        <input type="text" class="form-control suggest-tracks-form" name="q" placeholder="Add song to this playlist" autocomplete="off">
                    </div>
                </form>
                <div class="auto-suggest"></div>
            </div>
            <form id="mass-action-form" method="post" action="{{ route('backend.playlists.tracklist.mass.action', ['id' => $playlist->id]) }}">
                @csrf
                <table class="table table-striped table-sortable" id="diagnosis_list">
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
                        <th class="desktop">Media</th>
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
                    @include('backend.commons.song', ['songs' => $playlist->songs, 'sortOrder' => true])
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
                                    <option value="remove_from_playlist">Remove from this playlist</option>
                                    <option value="delete">Remove from this playlist & delete song</option>
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