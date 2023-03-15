@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Posts ({{ $posts->total() }})</li>
        <li class="breadcrumb-item active">Edit news published on the website</li>

    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Advanced search</h6>
                    </button>
                    <a href="{{ route('backend.posts.add') }}" class="btn btn-primary btn-sm float-right">Add new article</a>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form class="search-form" action="{{ route('backend.posts') }}">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">News search:</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <input type="text" name="term" class="form-control" placeholder="Enter keyword..." value="{{ request()->input('term') }}">
                                            </div>
                                            <div class="col-6 input-group mb-2">
                                                {!! makeDropDown([0 => 'Every where', 1 => 'Title', 2 => 'Short content', 3 => 'Full content'], 'location',  request()->input('location') ? request()->input('location') : 0) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-10">
                                        <select multiple="" class="form-control select2-active" name="category[]">
                                            {!! categorySelection(request()->input('category')) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row multi-artists">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Search by author</label>
                                    <div class="col-sm-10">
                                        <select class="form-control multi-selector-without-sortable" data-ajax--url="/api/search/user" name="userIds[]" multiple="">
                                            @if(request()->input('userIds') && is_array(request()->input('userIds')))
                                                @foreach (\App\Models\User::whereIn('id', request()->input('userIds'))->get() as $index => $user)
                                                    <option value="{{ $user->id }}" selected="selected" data-artwork="{{ $user->artwork_url }}" data-title="{{ $user->name }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Publication date</label>
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
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Number of comments</label>
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
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">News status</label>
                                    <div class="col-sm-10">
                                        <div class="form-inline">
                                            {!! makeDropDown([3 => '--- All News ---', 1 => 'Approved Articles', 0 => 'Articles waiting for moderation'], 'status',  request()->input('status') ? request()->input('status') : 3) !!}
                                            <div class="input-group ml-3">
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
                                                <input class="form-check-input" type="checkbox" name="fixed" id="fixed" value="true" @if(request()->input('fixed')) checked @endif>
                                                <label class="form-check-label" for="fixed">
                                                    Fixed articles
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="has_vote" id="has_vote" value="true" @if(request()->input('has_vote')) checked @endif>
                                                <label class="form-check-label" for="has_vote">
                                                    Article has voting
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="comment_disabled" id="comment_disabled" value="true" @if(request()->input('comment_disabled')) checked @endif>
                                                <label class="form-check-label" for="comment_disabled">
                                                    Comment disabled
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="scheduled" id="scheduled" value="true" @if(request()->input('scheduled')) checked @endif>
                                                <label class="form-check-label" for="scheduled">
                                                    Scheduled articles
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <button type="submit" class="btn btn-primary">Find</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <a href="{{ route('backend.posts') }}" class="btn btn-secondary">Clear</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form id="mass-action-form" method="post" action="{{ route('backend.posts.mass.action') }}">
                @csrf
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(["title" => request()->get('title') == 'asc' ? 'desc' : 'asc' ]) }}">Title</a>
                            @if(request()->get('title') == 'asc')
                                <i class="fas fa-sort-alpha-down"></i>
                            @else
                                <i class="fas fa-sort-alpha-up"></i>
                            @endif
                        </th>
                        <th class="desktop">Category</th>
                        <th class="desktop" width="200px">
                            <a data-toggle="tooltip" title="Publish" href="{{ request()->fullUrlWithQuery(["created_at" => request()->get('created_at') == 'asc' ? 'desc' : 'asc' ]) }}">
                                Publish
                                @if(request()->get('created_at') == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            </a>
                        </th>
                        <th class="desktop" width="150px">Updated</th>
                        <th class="desktop" width="40px"><i class="fas fa-fw fa-comment"></i></th>
                        <th class="desktop" width="70px">
                            <a data-toggle="tooltip" title="View Count" href="{{ request()->fullUrlWithQuery(["view_count" => request()->get('view_count') == 'asc' ? 'desc' : 'asc' ]) }}">
                                <i class="fas fa-fw fa-eye"></i>
                                @if(request()->get('view_count') == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            </a>
                        </th>
                        <th class="desktop" width="40px">
                            <i class="fas fa-check-double" data-toggle="tooltip" title="Approved"></i>
                        </th>
                        <th class="th-2action">Action</th>
                        <th class="th-checkbox">
                            <label class="engine-checkbox">
                                <input id="check-all" class="multi-check-box" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    @foreach ($posts as $index => $post)
                        <tr>
                            <td>@if($post->fixed) <span class="badge badge-danger badge-pill">fixed</span> @endif<a href="{{ route('backend.posts.edit', ['id' => $post->id]) }}">{{ $post->title }}</a></td>
                            <td class="desktop" width="200px">@foreach($post->categories as $category)<a href="{{ route('backend.categories.edit', ['id' => $category->id]) }}" title="{{ $category->name }}">{{$category->name}}</a>@if(!$loop->last), @endif @endforeach</td>
                            @if(\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($post->created_at)))
                                <td class="desktop text-info" data-toggle="tooltip" title="This is a scheduling article"><i class="far fa-calendar-check"></i> {{ \Carbon\Carbon::parse($post->created_at)->format('M j Y, H:i') }}</td>
                            @else
                                <td class="desktop">{{ \Carbon\Carbon::parse($post->created_at)->format('M j Y, H:i') }}</td>
                            @endif
                            <td class="desktop">{{ timeElapsedString($post->updated_at) }}</td>
                            <td class="desktop">{{ $post->comment_count }}</td>
                            <td class="desktop">{{ $post->view_count }}</td>
                            <td class="desktop">
                                @if($post->approved)
                                    <i class="fas fa-check-circle text-success" data-toggle="tooltip" title="Published"></i>
                                @else
                                    <i class="fas fa-exclamation-circle text-danger" data-toggle="tooltip" title="Not published"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('backend.posts.edit', ['id' => $post->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                <a href="{{ route('backend.posts.delete', ['id' => $post->id]) }}" class="row-button delete" onclick="return confirm('Are you sure want to delete this post?')"><i class="fas fa-fw fa-trash"></i></a>
                            </td>
                            <td>
                                <label class="engine-checkbox">
                                    <input name="ids[]" class="multi-check-box" type="checkbox" value="{{ $post->id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="row">
                    <div class="col-6">{{ $posts->appends(request()->input())->links() }}</div>
                    <div class="col-6">
                        <div class="form-inline float-sm-right">
                            <div class="form-group mb-2">
                                <select name="action" class="form-control mr-2">
                                    <option value="">-- Action --</option>
                                    <option value="add_category">Add category</option>
                                    <option value="change_category">Change category</option>
                                    <option value="change_author">Select an author</option>
                                    <option value="approve">Publish article</option>
                                    <option value="not_approve">Send for Moderation</option>
                                    <option value="set_current">Set current date</option>
                                    <option value="fixed">Publish on the blog homepage</option>
                                    <option value="not_fixed">Disable publishing on the blog homepage</option>
                                    <option value="clear_views">Clear views</option>
                                    <option value="clear_tags">Clear tag cloud</option>
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