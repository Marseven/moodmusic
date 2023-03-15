@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Radio</a></li>
        <li class="breadcrumb-item active">Stations ({{ $stations->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Advanced search</h6>
                    </button>
                    <a href="{{ route('backend.stations.add') }}" class="btn btn-primary btn-sm float-right">Add new station</a>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form class="search-form" action="{{ route('backend.stations') }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Station search:</label>
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
                                    <label class="col-sm-2 col-form-label">Radio Category</label>
                                    <div class="col-sm-10">
                                        <select multiple="" class="form-control select2-active" name="category[]">
                                            {!! radioCategorySelection(request()->input('category')) !!}
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
                                            <div class="ml-3 @if(! request()->input('country')) d-none @endif filter-city filter-city-select">
                                                @if(request()->input('country'))
                                                    {!! makeCityDropDown(request()->input('country'), 'city', 'form-control select2-active', request()->input('city')) !!}
                                                @endif
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
                                <a href="{{ route('backend.stations') }}" class="btn btn-danger"><i class="fas fa-eraser"></i> Clear Filter</a>
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
                        <th>Radio Category</th>
                        <th>Description</th>
                        <th width="100px" data-toggle="tooltip" title="Times player unable to play the station. You should check stream link if the number of failing keep increasing.">Failed</th>
                        <th width="100px">Plays</th>
                        <th class="th-2action">Action</th>
                    </tr>
                    </thead>
                    @include('backend.commons.station', ['stations' => $stations])
                </table>
                <div class="row">
                    <div class="col-6">{{ $stations->appends(request()->input())->links() }}</div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="add_radio_category">Add Radio Category</option>
                                    <option value="change_radio_category">Change Radio Category</option>
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