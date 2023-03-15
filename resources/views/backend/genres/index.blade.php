@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Create and Manage Music Genres</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('backend.genres.add') }}">Add new genre</a>
            <button type="submit" class="btn btn-primary mt-4 mb-4">Save sort order</button>
            <form method="get" action="">
                <div class="input-group mb-3">
                    <input type="text" name="term" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <a href="{{ route('backend.genres') }}" class="btn btn-warning" id="button-addon1">Clear Filter</a>
                        <button class="btn btn-info" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
            </form>
            <form method="post" action="{{ route('backend.genres.sort.post') }}">
                @csrf
                <table class="mt-4 table table-striped table-sortable">
                    <thead>
                    <tr>
                        <th class="th-handle"></th>
                        <th class="th-priority">Priority</th>
                        <th class="th-wide-image"></th>
                        <th>Name</th>
                        <th>Show on Discover</th>
                        <th class="th-2action">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($genres as $index => $genre)
                        <tr>
                            <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
                            <td><input type="hidden" name="genreIds[]" value="{{ $genre->id }}"></td>
                            <td><img src="{{ $genre->artwork_url }}"></td>
                            <td><a href="{{ route('backend.genres.edit', ['id' => $genre->id]) }}">{{ $genre->name }}</a></td>
                            <td class="desktop">
                                @if($genre->discover)
                                    <span class="badge badge-pill badge-success">Yes</span>
                                @else
                                    <span class="badge badge-pill badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('backend.genres.edit', ['id' => $genre->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                <a href="{{ route('backend.genres.delete', ['id' => $genre->id]) }}}" onclick="return confirm('Are you sure?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary mt-4 mb-4">Save sort order</button>
            </form>
            {{ $genres->appends(request()->input())->links() }}
        </div>
    </div>
@endsection