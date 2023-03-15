@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Media manager</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Media manager</h6>
                </div>
                <div class="card-body">
                    <div class="clearfix mb-b">
                        <div class="btn-group mb-4" role="group">
                            <button type="button" class="btn btn-primary btn media-reload" title="Refresh">
                                <i class="fa fa-sync"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn file-delete-multiple" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="btn-group mb-4" role="group">
                            <button class="btn btn-primary btn-upload-select"><i class="fa fa-upload"></i>&nbsp;&nbsp; Upload</button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#newFolderModal"><i class="fa fa-folder"></i>&nbsp;&nbsp; New folder</button>
                        </div>
                        <form action="{{ $url['upload'] }}" method="post" class="file-upload-form" enctype="multipart/form-data" pjax-container>
                            <input type="file" name="files[]" class="hidden file-upload" multiple>
                            <input type="hidden" name="dir" value="{{ $url['path'] }}" />
                            @csrf
                        </form>
                    </div>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('backend.media-index') }}" class="pjax"><i class="fa fa-hdd"></i> </a></li>
                        @foreach($nav as $item)
                            <li class="breadcrumb-item"><a href="{{ $item['url'] }}" class="pjax">{{ $item['name'] }}</a></li>
                        @endforeach
                    </ol>
                    <div class="table-responsive media-manager" data-delete-uri="{{ $url['delete'] }}" data-new-folder-uri="{{ $url['new-folder'] }}" data-move-uri="{{ $url['move'] }}" data-index-uri="{{ $url['index'] }}">
                        <table class="table table-hover datatables" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="desktop no-sort th-1action"><span class="file-select-all"><input type="checkbox" value=""/></span></th>
                                <th>Name</th>
                                <th class="desktop table-width100">Writable</th>
                                <th class="no-sort desktop">Action</th>
                                <th class="desktop table-width150">Last Modified</th>
                                <th class="desktop table-width150">Time</th>
                                <th width="80px">Size</th>
                                <th class="desktop table-width100">Size Byte</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (!empty($list))
                                @foreach($list as $item)
                                    <tr>
                                        <td  class="desktop">
                                            <span class="file-select">
                                                <input type="checkbox" value="{{ $item['name'] }}"/>
                                            </span>
                                        </td>
                                        <td>
                                            {!! $item['preview'] !!}
                                            <a @if(!$item['isDir']) target="_blank" @endif href="{{ $item['link'] }}" class="file-name @if($item['isDir']) pjax @endif " title="{{ $item['name'] }}">
                                                {{ $item['icon'] }} {{ basename($item['name']) }}
                                            </a>
                                        </td>
                                        <td class="desktop">{!! $item['writable'] ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' !!}</td>
                                        <td class="desktop table-width200">
                                            <a href="javascript:;" class="btn btn-link file-rename pl-0" data-toggle="modal" data-target="#moveModal" data-name="{{ $item['name'] }}"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:;" class="btn btn-link file-delete" data-path="{{ $item['name'] }}"><i class="fa fa-trash"></i></a>
                                            @unless($item['isDir'])
                                                <a target="_blank" class="btn btn-link"  href="{{ $item['download'] }}"><i class="fa fa-download"></i></a>
                                            @endunless
                                            <a href="javascript:;" class="btn btn-link" data-toggle="modal" data-target="#urlModal" data-url="{{ $item['url'] }}"><i class="fa fa-link"></i></a>
                                        </td>
                                        <td class="desktop">{{ timeElapsedString($item['time']) }}</td>
                                        <td class="desktop">{{ $item['time'] }}</td>
                                        <td>{{ fileSizeConverter($item['size']) }}</td>
                                        <td class="desktop">{{ $item['size'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveModalLabel">Rename & Move</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="file-move">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Path</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="new" />
                            </div>
                        </div>
                        <input type="hidden" name="path"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="urlModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="urlModalLabel">Url</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="new-folder">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" />
                        </div>
                        <input type="hidden" name="dir" value="{{ $url['path'] }}"/>
                        {{ csrf_field() }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection