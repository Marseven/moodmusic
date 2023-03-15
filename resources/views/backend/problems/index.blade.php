@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Problems Reported ({{ $reports->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form id="mass-action-form" method="post" action="{{ route('backend.albums.mass.action') }}">
                @csrf
                <table class="table table-striped datatables table-hover">
                    <thead>
                    <tr>
                        <th class="th-image"></th>
                        <th class="desktop">Type</th>
                        <th class="desktop">Title</th>
                        <th class="text-danger">Message</th>
                        <th class="desktop">Reported by</th>
                        <th class="desktop">Preview</th>
                        <th class="th-1action">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            @if($report->object)
                                <tr>
                                    <td class="td-image">
                                        @if($report->reportable_type === 'App\\Models\\Episode')
                                            <img src="{{ $report->object->podcast->artwork_url }}">
                                        @else
                                            <img src="{{ $report->object->artwork_url }}">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-warning">{{ str_replace('App\\Models\\', '', $report->reportable_type) }}</span>
                                    </td>
                                    <td>
                                        @if($report->reportable_type === 'App\\Models\\Episode')
                                            <a href="{{ route('backend.podcasts.' . strtolower(str_replace('App\\Models\\', '', $report->reportable_type)) . 's.edit', ['id' => $report->object->podcast->id, 'eid' => $report->object->id]) }}">{{ $report->object->title }}</a>
                                        @else
                                            <a href="{{ route('backend.' . strtolower(str_replace('App\\Models\\', '', $report->reportable_type)) . 's.edit', ['id' => $report->object->id]) }}">{{ $report->object->title }}</a>
                                        @endif
                                    </td>
                                    <td class="desktop text-danger">{{ $report->message }}</td>
                                    <td class="desktop"><a href="{{ route('backend.users.edit', ['id' => $report->user->id]) }}">{{ $report->user->name }}</a></td>
                                    <td class="desktop"><a href="{{ $report->object->permalink_url }}" class="btn btn-sm btn-info" target="_blank">Preview</a></td>
                                    <td>
                                        <a class="row-button delete" onclick="return confirm('Are you sure want to delete this report?');" href="{{ route('backend.problems.delete', ['id' => $report->id]) }}"><i class="fas fa-fw fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-6">{{ $reports->appends(request()->input())->links() }}</div>
                </div>
            </form>
        </div>
    </div>
@endsection