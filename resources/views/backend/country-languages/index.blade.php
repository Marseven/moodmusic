@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Radio</a></li>
        <li class="breadcrumb-item active">Country Languages ({{ $languages->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Advanced search</h6>
                    </button>
                    <a href="{{ route('backend.country.languages.add') }}" class="btn btn-primary btn-sm float-right">Add new language</a>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form class="search-form" action="{{ route('backend.country.languages') }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Search:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="term" class="form-control" placeholder="Enter keyword..." value="{{ request()->input('term') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Region</label>
                                    <div class="col-sm-10">
                                        {!! makeRegionDropDown("region", 'select2-active', request()->input('region')) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Government Form</label>
                                    <div class="col-sm-10">
                                        {!! makeDropDown( $governmentForms, "government_form", null, false) !!}
                                    </div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="row">
                                        <legend class="col-form-label col-sm-2 pt-0">Options</legend>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fixed" id="fixed" value="true" @if(request()->input('fixed')) checked @endif>
                                                <label class="form-check-label" for="fixed">
                                                    Fixed
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" value="true" @if(request()->input('hidden')) checked @endif>
                                                <label class="form-check-label" for="hidden">
                                                    Hidden
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Find</button>
                                <a href="{{ route('backend.country.languages') }}" class="btn btn-danger"><i class="fas fa-eraser"></i> Clear Filter</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form id="mass-action-form" method="post" action="">
                @csrf
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Language</th>
                        <th>Country</th>
                        <th>Region</th>
                        <th>Fixed</th>
                        <th>Visible</th>
                        <th class="th-2action">Action</th>
                        <th class="th-checkbox">
                            <label class="engine-checkbox">
                                <input id="check-all" class="multi-check-box" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    @foreach ($languages as $index => $language)
                        <tr>
                            <td><a href="{{ route('backend.country.languages.edit', ['id' => $language->id]) }}">{{ $language->name }}</a></td>
                            <td>
                                @if(isset($language->country))
                                    <a href="{{ route('backend.countries.edit', ['id' => $language->country->id]) }}">{{ $language->country->name }}</a>
                                @endif
                            </td>
                            <td>
                                @if(isset($language->country))
                                {{ isset($language->country->region) ? $language->country->region->name : '' }}
                                @endif
                            </td>
                            <td>
                                @if($language->fixed)
                                    <span class="badge badge-success badge-pill">Yes</span>
                                @else
                                    <span class="badge badge-danger badge-pill">No</span>
                                @endif
                            </td>
                            <td>
                                @if($language->visibility)
                                    <span class="badge badge-success badge-pill">Yes</span>
                                @else
                                    <span class="badge badge-danger badge-pill">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('backend.country.languages.edit', ['id' => $language->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                <a href="{{ route('backend.country.languages.delete', ['id' => $language->id]) }}" onclick="return confirm('Are you sure want to delete this country?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
                            </td>
                            <td>
                                <label class="engine-checkbox">
                                    <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $language->id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                    @endforeach
                </table>
                <div class="row">
                    <div class="col-6">{{ $languages->appends(request()->input())->links() }}</div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="make_hidden">Make Hidden</option>
                                    <option value="make_visible">Make Visible</option>
                                    <option value="fixed">Pin Language</option>
                                    <option value="unfixed">Unpin Language</option>
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