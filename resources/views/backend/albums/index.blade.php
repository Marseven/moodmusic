@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Albums ({{ $albums->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Advanced search</h6>
                    </button>
                    <a href="{{ route('backend.albums.add') }}" class="btn btn-primary btn-sm float-right">Add new album</a>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form class="search-form" action="{{ route('backend.albums') }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Keyword</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="term" class="form-control" placeholder="Album title..." value="{{ request()->input('term') }}">
                                    </div>
                                </div>
                                <div class="form-group row multi-artists">
                                    <label class="col-sm-2 col-form-label">Artist</label>
                                    <div class="col-sm-10">
                                        <select class="form-control multi-selector-without-sortable" data-ajax--url="{{ route('api.search.artist') }}" name="artistIds[]" multiple="">
                                            @if(request()->input('artistIds') && is_array(request()->input('artistIds')))
                                                @foreach (\App\Models\Artist::whereIn('id', request()->input('artistIds'))->get() as $index => $artist)
                                                    <option value="{{ $artist->id }}" selected="selected" data-artwork="{{ $artist->artwork_url }}" data-title="{!! $artist->name !!}">{!! $artist->name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row multi-artists">
                                    <label class="col-sm-2 col-form-label">Uploader</label>
                                    <div class="col-sm-10">
                                        <select class="form-control multi-selector-without-sortable" data-ajax--url="{{ route('api.search.user') }}" name="userIds[]" multiple="">
                                            @if(request()->input('userIds') && is_array(request()->input('userIds')))
                                                @foreach (\App\Models\User::whereIn('id', request()->input('userIds'))->get() as $index => $user)
                                                    <option value="{{ $user->id }}" selected="selected" data-artwork="{{ $user->artwork_url }}" data-title="{{ $user->name }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Genre</label>
                                    <div class="col-sm-10">
                                        <select multiple="" class="form-control select2-active" name="genre[]">
                                            {!! genreSelection(request()->input('genre')) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Mood</label>
                                    <div class="col-sm-10">
                                        <select multiple="" class="form-control select2-active" name="mood[]">
                                            {!! moodSelection(request()->input('mood')) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Date of the creation</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            <div class="input-group mr-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">From</div>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-with-form" name="created_from" value="{{ request()->input('created_from') }}" autocomplete="off">
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Until</div>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-with-form" name="created_until" value="{{ request()->input('created_until') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Number of comments</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            <div class="input-group mb-2 mr-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">From</div>
                                                </div>
                                                <input type="text" class="form-control" name="comment_count_from" value="{{ request()->input('comment_count_from') }}">
                                            </div>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Until</div>
                                                </div>
                                                <input type="text" class="form-control" name="comment_count_until" value="{{ request()->input('comment_count_until') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Show</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Results/Page</div>
                                                </div>
                                                <input type="text" class="form-control" name="results_per_page" value="{{ request()->input('results_per_page') ? request()->input('results_per_page') : 50 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="row">
                                        <legend class="col-form-label col-sm-2 pt-0">Options</legend>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="comment_disabled" id="comment_disabled" value="true" @if(request()->input('comment_disabled')) checked @endif>
                                                <label class="form-check-label" for="comment_disabled">
                                                    Comment disabled
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="not_approved" id="not_approved" value="true" @if(request()->input('not_approved')) checked @endif>
                                                <label class="form-check-label" for="not_approved">
                                                    Waiting for approved
                                                </label>
                                            </div>
                                            <div class="form-check disabled">
                                                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" value="true" @if(request()->input('hidden')) checked @endif>
                                                <label class="form-check-label" for="hidden">
                                                    Private
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <button type="submit" class="btn btn-primary">Find</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <a href="{{ route('backend.songs') }}" class="btn btn-secondary">Clear</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form id="mass-action-form" method="post" action="{{ route('backend.albums.mass.action') }}">
                @csrf
                <table class="table table-striped datatables table-hover">
                    <colgroup>
                        <col class="span1">
                        <col class="span7">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="th-image"></th>
                        <th>Name</th>
                        <th class="desktop">Artist(s)</th>
                        <th class="text-danger">Owner</th>
                        <th class="desktop">Genre(s)</th>
                        <th class="desktop">Mood(s)</th>
                        <th class="desktop th-2action">Approved</th>
                        <th class="desktop text-center th-2action"><i class="fas fa-music"></i></th>
                        <th class="desktop text-center th-2action"><i class="fas fa-comment fa-fw"></i></th>
                        <th class="th-3action">Action</th>
                        <th class="th-checkbox">
                            <label class="engine-checkbox">
                                <input id="check-all" class="multi-check-box" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @include('backend.commons.album', ['albums' => $albums])
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-6">{{ $albums->appends(request()->input())->links() }}</div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="add_genre">Add Genre</option>
                                    <option value="change_genre">Change Genre</option>
                                    <option value="add_mood">Add Mood</option>
                                    <option value="change_mood">Change Mood</option>
                                    <option value="change_artist">Change Artist</option>
                                    <option value="approve">Approve (Publish)</option>
                                    <option value="not_approve">Send for Moderation</option>
                                    <option value="comments">Enable Comments</option>
                                    <option value="not_comments">Disable Comments</option>
                                    <option value="delete">Delete</option>
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