@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Create and Manage Music Moods</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('backend.moods.add') }}">Add new mood</a>
            <form method="post" action="{{ route('backend.moods.sort.post') }}">
                @csrf
                <table class="mt-4 table table-striped table-sortable">
                    <thead>
                    <tr>
                        <th class="th-handle"></th>
                        <th class="th-priority">Priority</th>
                        <th class="th-wide-image"></th>
                        <th>Name</th>
                        <th class="th-2action">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($moods as $index => $mood)
                        <tr>
                            <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
                            <td><input type="hidden" name="moodIds[]" value="{{ $mood->id }}"></td>
                            <td><img src="{{ $mood->artwork_url }}"></td>
                            <td><a href="{{ route('backend.moods.edit', ['id' => $mood->id]) }}">{{ $mood->name }}</a></td>
                            <td>
                                <a href="{{ route('backend.moods.edit', ['id' => $mood->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                <a href="{{ route('backend.moods.delete', ['id' => $mood->id]) }}" onclick="return confirm('Are you sure?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary mt-4">Save sort order</button>
            </form>
        </div>
    </div>
@endsection