@extends('backend.install.index')
@section('content')
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Folder/File</th>
            <th width="100px" class="text-center">CHMOD</th>
            <th width="100px" class="text-center">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($chmod as $index => $item)
            <tr>
                <td>{{ $item->file }}</td>
                <td class="text-center {{ $item->isWritable ? 'text-success' : 'text-danger' }}">{{ $item->chmodValue }}</td>
                <td class="text-center {{ $item->isWritable ? 'text-success' : 'text-danger' }}">{{ $item->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(! $has_errors)
        <a href="{{ $_SERVER['PHP_SELF'] }}?step=license" class="btn btn-primary btn-block text-white">Continue</a>
    @else
        <div class="alert alert-danger text-center">Please fix all the problem before continue</div>
    @endif
@endsection