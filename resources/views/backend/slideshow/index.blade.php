@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">SlideShow @if(Route::current()->getName() == 'radio') (Radio) @endif</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('backend.slideshow.add') }}" class="btn btn-primary">Add new slide</a>
            <div class="card mt-4">
                <div class="card-header p-0">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link pl-3 pr-3  @if(Route::currentRouteName() == 'backend.slideshow.overview') active @endif" href="{{ route('backend.slideshow.overview') }}"><i class="fas fa-fw fa-border-all"></i> Overview ({{ DB::table('slides')->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.slideshow.home') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.home') active @endif"><i class="fas fa-fw fa-home"></i> Home ({{ DB::table('slides')->where('allow_home', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.slideshow.discover')  }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.discover') active @endif"><i class="fas fa-fw fa-compass"></i> Discover ({{ DB::table('slides')->where('allow_discover', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.slideshow.radio') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.radio') active @endif"><i class="fas fa-fw fa-broadcast-tower"></i> Radio ({{ DB::table('slides')->where('allow_radio', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.slideshow.community') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.community') active @endif"><i class="fas fa-fw fa-users"></i> Community ({{ DB::table('slides')->where('allow_community', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.slideshow.trending') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.trending') active @endif"><i class="fas fa-fw fa-chart-line"></i> Trending ({{ DB::table('slides')->where('allow_trending', 1)->count() }})</a></li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.genre') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-tags"></i> Genre ({{ DB::table('slides')->where('genre', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($genres as $genre)
                                        <a class="dropdown-item" href="{{ route('backend.slideshow.genre', ['id' => $genre->id]) }}">{{ $genre->name }} ({{ DB::table('slides')->whereRaw("genre REGEXP '(^|,)(" . $genre->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.mood') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Mood ({{ DB::table('slides')->where('mood', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($moods as $mood)
                                        <a class="dropdown-item" href="{{ route('backend.slideshow.mood', ['id' => $mood->id]) }}">{{ $mood->name }} ({{ DB::table('slides')->whereRaw("mood REGEXP '(^|,)(" . $mood->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.station-category') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Station Category ({{ DB::table('slides')->where('radio', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($radio as $category)
                                        <a class="dropdown-item" href="{{ route('backend.slideshow.station-category', ['id' => $category->id]) }}">{{ $category->name }} ({{ DB::table('slides')->whereRaw("radio REGEXP '(^|,)(" . $category->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.slideshow.podcast-category') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Podcast Category ({{ DB::table('slides')->where('podcast', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($podcast as $category)
                                        <a class="dropdown-item" href="{{ route('backend.slideshow.podcast-category', ['id' => $category->id]) }}">{{ $category->name }} ({{ DB::table('slides')->whereRaw("podcast REGEXP '(^|,)(" . $category->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('backend.slideshow.sort.post') }}">
                        @csrf
                        <table class="mt-4 table table-striped table-sortable">
                            <thead>
                            <tr>
                                <th class="th-handle"></th>
                                <th class="th-priority desktop">Priority</th>
                                <th width="100px"></th>
                                <th width="90px">Type</th>
                                <th class="desktop">Description</th>
                                <th class="desktop">Created by</th>
                                <th class="desktop">Created at</th>
                                <th class="desktop">Updated at</th>
                                <th class="th-2action">Action</th>
                            </tr>
                            </thead>
                            @foreach ($slides as $index => $slide)
                                <tr>
                                    <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
                                    <td class="desktop"><input type="hidden" name="slideshowIds[]" value="{{ $slide->id }}"></td>
                                    <td class="td-image">
                                        <a href="{{ route('backend.slideshow.edit', ['id' => $slide->id]) }}" class="row-button edit">
                                            <img class="media-object img-70-40" src="{{ $slide->artwork_url }}">
                                        </a>
                                    </td>
                                    <td><span class="label label-text">{{ ucwords($slide->object_type) }}</span></td>
                                    <td class="desktop">{{ $slide->description }}</td>
                                    <td class="desktop"><a href="{{ route('backend.users.edit', ['id' => $slide->user_id]) }}">{{\App\Models\User::findOrFail($slide->user_id)->name }}</a></td>
                                    <td class="desktop">{{ timeElapsedString($slide->created_at) }}</td>
                                    <td class="desktop">{{ timeElapsedString($slide->updated_at) }}</td>
                                    <td>
                                        <a href="{{ route('backend.slideshow.edit', ['id' => $slide->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                        <a href="{{ route('backend.slideshow.delete', ['id' => $slide->id]) }}" class="row-button delete" onclick="return confirm('Are you sure?')"><i class="fas fa-fw fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <button type="submit" class="btn btn-primary mt-4">Save sort order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection