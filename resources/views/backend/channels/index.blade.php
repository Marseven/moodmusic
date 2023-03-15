@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Channels</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('backend.channels.add') }}" class="btn btn-primary">Add new channel</a>
            <div class="card mt-4">
                <div class="card-header p-0">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link pl-3 pr-3  @if(Route::currentRouteName() == 'backend.channels.overview') active @endif" href="{{ route('backend.channels.overview') }}"><i class="fas fa-fw fa-border-all"></i> Overview ({{ DB::table('channels')->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.channels.home') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.home') active @endif"><i class="fas fa-fw fa-home"></i> Home ({{ DB::table('channels')->where('allow_home', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.channels.discover') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.discover') active @endif"><i class="fas fa-fw fa-compass"></i> Discover ({{ DB::table('channels')->where('allow_discover', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.channels.radio') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.radio') active @endif"><i class="fas fa-fw fa-broadcast-tower"></i> Radio ({{ DB::table('channels')->where('allow_radio', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.channels.community') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.community') active @endif"><i class="fas fa-fw fa-users"></i> Community ({{ DB::table('channels')->where('allow_community', 1)->count() }})</a></li>
                        <li class="nav-item"><a href="{{ route('backend.channels.trending') }}" class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.trending') active @endif"><i class="fas fa-fw fa-chart-line"></i> Trending ({{ DB::table('channels')->where('allow_trending', 1)->count() }})</a></li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.genre') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-tags"></i> Genre ({{ DB::table('channels')->where('genre', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($genres as $genre)
                                        <a class="dropdown-item" href="{{ route('backend.channels.genre', ['id' => $genre->id]) }}">{{ $genre->name }} ({{ DB::table('channels')->whereRaw("genre REGEXP '(^|,)(" . $genre->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.mood') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Mood ({{ DB::table('channels')->where('mood', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($moods as $mood)
                                        <a class="dropdown-item" href="{{ route('backend.channels.mood', ['id' => $mood->id]) }}">{{ $mood->name }} ({{ DB::table('channels')->whereRaw("mood REGEXP '(^|,)(" . $mood->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.station-category') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Station Category ({{ DB::table('channels')->where('radio', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($radio as $category)
                                        <a class="dropdown-item" href="{{ route('backend.channels.station-category', ['id' => $category->id]) }}">{{ $category->name }} ({{ DB::table('channels')->whereRaw("radio REGEXP '(^|,)(" . $category->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="nav-link pl-3 pr-3 @if(Route::currentRouteName() == 'backend.channels.podcast-category') active @endif clearfix">
                                <a href="javascript:;" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-fw fa-smile"></i> Podcast Category ({{ DB::table('channels')->where('podcast', '!=' , '')->count() }})
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach($podcast as $category)
                                        <a class="dropdown-item" href="{{ route('backend.channels.podcast-category', ['id' => $category->id]) }}">{{ $category->name }} ({{ DB::table('channels')->whereRaw("podcast REGEXP '(^|,)(" . $category->id . ")(,|$)'")->count() }})</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('backend.channels.sort.post') }}">
                        @csrf
                        <table class="mt-4 table table-striped table-sortable">
                            <thead>
                            <tr>
                                <th class="th-handle"></th>
                                <th class="th-priority">Priority</th>
                                <th>Name</th>
                                <th>Created by</th>
                                <th class="th-3action">Type</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th class="th-2action">Action</th>
                            </tr>
                            </thead>
                            @foreach ($channels as $index => $channel)
                                <tr>
                                    <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
                                    <td><input type="hidden" name="object_ids[]" value="{{ $channel->id }}"></td>
                                    <td><a href="{{ route('backend.channels.edit', ['id' => $channel->id]) }}" class="row-button edit">{{ $channel->title }}</a></td>
                                    <td><a href="{{ route('backend.users.edit', ['id' => $channel->user_id]) }}">{{\App\Models\User::findOrFail($channel->user_id)->name }}</a></td>
                                    <td>{{ $channel->object_type }}</td>
                                    <td>{{ timeElapsedString($channel->created_at) }}</td>
                                    <td>{{ timeElapsedString($channel->updated_at) }}</td>

                                    <td>
                                        <a href="{{ route('backend.channels.edit', ['id' => $channel->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                        <a href="{{ route('backend.channels.delete', ['id' => $channel->id]) }}" class="row-button delete" onclick="return confirm('Are you sure?')"><i class="fas fa-fw fa-trash"></i></a>
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