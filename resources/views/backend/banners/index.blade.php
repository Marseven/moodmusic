@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Banners ({{ $banners->total() }}) - Manage promotional materials</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('backend.banners.add') }}" class="btn btn-primary">Add banner</a>
            @if(env('MEDIA_AD_MODULE') == 'true')
                <a href="{{ route('backend.banners.reports') }}" class="btn btn-warning">Report</a>
            @endif
            <form id="mass-action-form" method="post" action="{{ route('backend.albums.mass.action') }}">
                @csrf
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Variable tag</th>
                        <th></th>
                        <th>Start</th>
                        <th>End</th>
                        <th  class="th-3action">Status</th>
                        <th class="th-3action">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td width="100px">{{ $banner->description }}</td>
                            <td width="100px">
                                @if($banner->type == 0)
                                    <span class="badge badge-warning">Banner</span>
                                @elseif($banner->type == 1)
                                    <span class="badge badge-warning">Audio</span>
                                @elseif($banner->type == 2)
                                    <span class="badge badge-warning">Video</span>
                                @endif
                            </td>
                            <td width="300px">
                                @if($banner->type == 0)
                                &lcub;!! Advert::get('{!! $banner->banner_tag !!}') !!}
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td>
                                @if($banner->type == 0)
                                    <pre class="m-0"><code>{{ $banner->code }}</code></pre>
                                @elseif($banner->type == 1)
                                    <audio width="320" height="180" controls>
                                        <source src="{{ $banner->getFirstMediaUrl('file') }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </audio>
                                @elseif($banner->type == 2)
                                    <video width="320" height="180" controls>
                                        <source src="{{ $banner->getFirstMediaUrl('file') }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </td>
                            <td width="200px">{{ $banner->started_at }}</td>
                            <td width="200px">{{ $banner->ended_at }}</td>
                            <td>
                                @if($banner->approved)
                                    <span class="badge badge-success">active</span>
                                @else
                                    <span class="badge badge-danger">disabled</span>
                                @endif
                            </td>
                            <td>
                                <a class="row-button upload" href="{{ route('backend.banners.disable', ['id' => $banner->id]) }}"><i class="fas fa-fw fa-ban"></i></a>
                                <a class="row-button edit" href="{{ route('backend.banners.edit', ['id' => $banner->id]) }}"><i class="fas fa-fw fa-edit"></i></a>
                                <a class="row-button delete" onclick="return confirm('Are you sure want to delete this banner?');" href="{{ route('backend.banners.delete', ['id' => $banner->id]) }}"><i class="fas fa-fw fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection