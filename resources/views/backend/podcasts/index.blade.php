@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Podcasts ({{ $podcasts->total() }})</a></li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex mb-3">
                <button class="btn btn-secondary btn-sm" type="button" data-toggle="modal" data-target="#addNewModal">Import Podcast RSS</button>
                <a href="{{ route('backend.podcasts.add') }}" class="btn btn-primary btn-sm ml-2">Add New Podcast</a>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Advanced search</h6>
                    </button>

                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form class="search-form" action="{{ route('backend.podcasts') }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Podcast search:</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <input type="text" name="term" class="form-control" placeholder="Enter keyword..." value="{{ request()->input('term') }}">
                                            </div>
                                            <div class="col-6 input-group mb-2">
                                                {!! makeDropDown([0 => 'Every where', 1 => 'Title', 2 => 'Description'], 'location',  request()->input('location') ? request()->input('location') : 0) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-10">
                                        <select multiple="" class="form-control select2-active" name="category[]">
                                            {!! podcastCategorySelection(request()->input('category')) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row multi-artists">
                                    <label class="col-sm-2 col-form-label">Added by</label>
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
                                    <label class="col-sm-2 col-form-label">Country</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            <div class="filter-country">
                                                {!! makeCountryDropDown('country', 'form-control select2-active filter-country-select', request()->input('country')) !!}
                                            </div>
                                            <div class="input-group ml-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Results/Page</div>
                                                </div>
                                                <input type="text" class="form-control" name="results_per_page" value="{{ request()->input('results_per_page') ? request()->input('results_per_page') : 50 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row @if(! request()->input('country')) d-none @endif filter-language">
                                    <label class="col-sm-2 col-form-label">Language</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            <div class="filter-city-select">
                                                @if(request()->input('country') || request()->input('language'))
                                                    {!! makeCountryLanguageDropDown(request()->input('country'), 'language', 'form-control select2-active', request()->input('language')) !!}
                                                @endif
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
                                            <div class="form-check disabled">
                                                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" value="true" @if(request()->input('hidden')) checked @endif>
                                                <label class="form-check-label" for="hidden">
                                                    Private
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Find</button>
                                <a href="{{ route('backend.podcasts') }}" class="btn btn-danger"><i class="fas fa-eraser"></i> Clear Filter</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form id="mass-action-form" method="post" action="{{ route('backend.songs.mass.action') }}">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="th-image"></th>
                        <th>Name</th>
                        <th>Hosts</th>
                        <th>Radio Category</th>
                        <th>Description</th>
                        <th width="100px">Episodes</th>
                        <th width="100px">Subscriber</th>
                        <th class="th-3action">Action</th>
                        <th class="th-checkbox">
                            <label class="engine-checkbox">
                                <input id="check-all" class="multi-check-box" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    @include('backend.commons.podcast', ['podcasts' => $podcasts])
                </table>
                <div class="row">
                    <div class="col-6">{{ $podcasts->appends(request()->input())->links() }}</div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="add_podcast_category">Add Category</option>
                                    <option value="change_podcast_category">Change Category</option>
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
    <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('backend.podcasts.import') }}" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewModalLabel">Import PodCast From RSS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group multi-artists">
                            <label>Artist (Hosting)</label>
                            <select class="form-control multi-selector" data-ajax--url="{{ route('api.search.artist') }}" name="artist_id" required>
                                <option disabled selected value></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title (<span class="text-danger">option, will be imported from rss if you leave it empty)</span></label>
                            <input class="form-control" type="text" name="title">
                        </div>
                        <div class="form-group">
                            <label>Description (<span class="text-danger">option, will be imported from rss if you leave it empty</span>)</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Category(s)</label>
                            <select multiple="" class="form-control select2-active" name="category[]">
                                {!! podcastCategorySelection() !!}
                            </select>
                        </div>
                        <div class="form-group filter-country">
                            <label>Country</label>
                            <div class="form-inline">
                                <div class="filter-country">
                                    {!! makeCountryDropDown('country_code', 'form-control select2-active filter-country-select', isset($podcast) && ! old('country_code') ? $podcast->country_code : old('country_code')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group filter-language @if(! isset($podcast) || ! $podcast->country_code) d-none @endif">
                            <label>Language</label>
                            <div class="form-inline filter-language-select">
                                @if(isset($podcast) && ($podcast->country_code || $podcast->language_id))
                                    {!! makeCountryLanguageDropDown($podcast->country_code, 'language_id', 'form-control select2-active', isset($podcast) && ! old('language_id') ? $podcast->language_id : old('language_id')) !!}
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Podcast RSS</label>
                            <input class="form-control" type="text" name="rss_feed_url" value="{{ isset($podcast) && ! old('rss_feed_url') ? $podcast->rss_feed_url : old('rss_feed_url') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection