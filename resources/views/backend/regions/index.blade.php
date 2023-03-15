@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.radios') }}">Radio</a></li>
        <li class="breadcrumb-item active">Regions ({{ $regions->total() }})</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('backend.regions.add') }}" class="btn btn-primary btn-sm mb-5">Add new region</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Visible</th>
                    <th class="th-2action">Action</th>
                </tr>
                </thead>
                @foreach ($regions as $index => $region)
                    <tr>
                        <td><a href="{{ route('backend.regions.edit', ['id' => $region->id]) }}">{{ $region->name }}</a></td>
                        <td>
                            @if($region->visibility)
                                <span class="badge badge-success badge-pill">Yes</span>
                            @else
                                <span class="badge badge-danger badge-pill">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('backend.regions.edit', ['id' => $region->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                            <a href="{{ route('backend.regions.delete', ['id' => $region->id]) }}" onclick="return confirm('Are you sure want to delete this region?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
                        </td>

                @endforeach
            </table>
        </div>
    </div>
@endsection