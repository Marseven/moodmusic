@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Pages</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4 py-3 border-left-info">
                <div class="card-body">
                    Create and edit pages that are rarely changed and have a permanent address
                </div>
            </div>
            <a href="{{ route('backend.pages.add') }}" class="btn btn-primary">Add new page</a>
            <table class="mt-4 table table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th class="desktop">User-friendly URL</th>
                    <th class="desktop">Created at</th>
                    <th class="desktop">Updated at</th>
                    <th class="th-3action">Action</th>
                </tr>
                </thead>
                @foreach ($pages as $index => $page)
                    <tr>
                        <td><a href="{{ route('backend.pages.edit', ['id' => $page->id]) }}">{{ $page->title }}</a></td>
                        <td class="desktop">{{ $page->alt_name }}</td>
                        <td class="desktop">{{ timeElapsedString($page->created_at) }}</td>
                        <td class="desktop">{{ timeElapsedString($page->updated_at) }}</td>
                        <td>
                            <a target="_blank" href="{{ route('frontend.page', ['slug' => $page->alt_name]) }}" class="row-button edit"><i class="fas fa-fw fa-link"></i></a>
                            <a href="{{ route('backend.pages.edit', ['id' => $page->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                            <a href="{{ route('backend.pages.delete', ['id' => $page->id]) }}" class="row-button delete" onclick="return confirm('Are you sure to delete this page page?')"><i class="fas fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection