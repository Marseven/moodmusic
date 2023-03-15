@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('backend.podcasts') }}">Podcasts</a></li>
        <li class="breadcrumb-item"><a href="{{ route('backend.podcasts.edit', ['id' => $podcast->id]) }}">{{ $podcast->title }}</a>@if(isset($podcast->artist)) - <a href="{{ route('backend.artists.edit', ['id' => $podcast->artist->id]) }}" title="{{$podcast->artist->name}}">{{$podcast->artist->name}}</a>@endif</li>
        <li class="breadcrumb-item active">Episodes</li>
    </ol>
    <div class="row">
        <div class="col-lg-12 media-info mb-3 podcast">
            <div class="media mb-3">
                <img class="mr-3" src="{{ $podcast->artwork_url }}">
                <div class="media-body">
                    <h5 class="m-0">{{ $podcast->title }}@if(isset($podcast->artist)) - <a href="{{ route('backend.artists.edit', ['id' => $podcast->artist->id]) }}" title="{{$podcast->artist->name}}">{{$podcast->artist->name}}</a>@endif</h5>
                    <p>Episodes: {{ $podcast->episodes->total() }}</p>
                    <p class="m-0"><a href="{{ $podcast->permalink_url }}" class="btn btn-warning" target="_blank">Preview @if(! $podcast->approved) (only Moderator) @endif</a> <a href="{{ route('backend.podcasts.upload.episode', ['id' => $podcast->id]) }}" class="btn btn-success">Upload</a></p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <form id="mass-action-form" method="post" action="{{ route('backend.podcasts.episodes.mass.action', ['id' => $podcast->id]) }}">
                @csrf
                <table class="table table-striped table-sortable">
                    <colgroup>
                        <col class="span1">
                        <col class="span7">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>Title <span title="Double click to edit" data-toggle="tooltip" data-placement="top">(?)</span></th>
                        <th class="desktop" width="100px">Download</th>
                        <th class="desktop" width="100px">Plays</th>
                        <th class="desktop" width="100px">Approved</th>
                        <th class="desktop" width="200px">Extra</th>
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
                        @include('backend.commons.episode', ['episodes' => $podcast->episodes])
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
                                    <option value="remove_from_podcast">Remove from this podcast</option>
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