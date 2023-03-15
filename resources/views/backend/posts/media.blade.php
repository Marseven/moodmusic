@section('media')
    @foreach($images as $image)
        <div id="media-{{ $image->id }}" class="col-4 col-sm-3 col-md-2 col-xl-1">
            <div class="engine-file engine-fm-item border">
                <div class="delete" data-id="{{ $image->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                </div>
                <div class="image image-item" data-id="{{ $image->id }}" data-url="{{ $image->getUrl('thumbnail') }}" style="background-image: url('{{ $image->getUrl('thumbnail') }}')">
                </div>
                <span class="engine-fm-item-title image-item" data-id="{{ $image->id }}" data-url="{{ $image->getUrl('thumbnail') }}">{{ $image->file_name }}</span>
                <small class="text-secondary image-item" data-id="{{ $image->id }}" data-url="{{ $image->getUrl('thumbnail') }}">{{ $image->human_readable_size }}</small>
                <div class="form-check">
                    <input class="position-static form-check-input" type="checkbox">
                </div>
            </div>
        </div>
    @endforeach
    @foreach($attachments as $attachment)
        <div id="media-{{ $image->id }}" class="col-4 col-sm-3 col-md-2 col-xl-1" data-id="{{ $attachment->id }}" data-url="{{ $attachment->getUrl() }}">
            <div class="engine-file engine-fm-item border">
                <div class="delete" data-id="{{ $attachment->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                </div>
                <span class="engine-fm-item-thumbnail engine-icon engine-icon-folder-rounded attachment-item" data-id="{{ $attachment->id }}" data-name="{{ $attachment->file_name }}">
                    <i class="fas fa-file-alt"></i>
                </span>
                <span class="engine-fm-item-title attachment-item" data-id="{{ $attachment->id }}" data-name="{{ $attachment->file_name }}">{{ $attachment->file_name }}</span>
                <small class="text-secondary attachment-item" data-id="{{ $attachment->id }}" data-name="{{ $attachment->file_name }}">{{ $attachment->human_readable_size }}</small>
                <div class="form-check">
                    <input class="position-static form-check-input" type="checkbox">
                </div>
            </div>
        </div>
    @endforeach
@stop
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Music Engine - Control Panel</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/vendor/fontawesome-free/css/brands.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/vendor/fronteed/skins/all.css') }}" rel="stylesheet">

    @if(config('settings.admin_dark_mode'))
        <link href="{{ asset('backend/css/style_dark.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    @endif

    <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/file.css') }}" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <div class="engine-file-manager card">
        <div class="card-header">
            <div class="engine-toolbar-adaptive engine-fm-toolbar no-adaptive-group">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group ml-auto" role="group">
                        <button class="btn btn-primary btn-upload-select" disabled="disabled"><i class="fa fa-upload"></i>&nbsp;&nbsp; Upload</button>
                    </div>
                    <form action="" method="post" class="file-upload-form" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="files[]" class="hidden file-upload" multiple>
                        <input type="hidden" name="dir" value="" />
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="engine-folder-container fm-folders-demo col col-auto">
                    <nav class="engine-tree">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item engine-collapse">
                                <a class="nav-link" aria-expanded="true">
                                    <span aria-hidden="true" class="expand-btn engine-icon engine-icon-angle-down">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                    <span aria-hidden="true" class="image engine-fm-item-thumbnail engine-icon engine-icon-folder">
                                        <i class="fa fa-folder-open"></i>
                                    </span>
                                    <span class="text">Post</span>
                                </a>
                                <ul class="nav nav-pills flex-column">
                                    @if(isset($media) && $media->post_id == 0)
                                        <li class="nav-item engine-folder" data-id="0">
                                            <a class="nav-link" aria-expanded="false">
                                                <span aria-hidden="true" class="image engine-fm-item-thumbnail engine-icon engine-icon-folder">
                                                    <i class="fa fa-folder"></i>
                                                </span>
                                                <span class="text">New Folder</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if(isset($posts))
                                        @foreach($posts as $post)
                                            <li class="nav-item engine-folder" data-id="{{ $post->id }}">
                                                <a class="nav-link" aria-expanded="false">
                                                <span aria-hidden="true" class="image engine-fm-item-thumbnail engine-icon engine-icon-folder">
                                                    <i class="fa fa-folder"></i>
                                                </span>
                                                    <span class="text">{{ $post->title }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col card">
                    <nav class="engine-fm-breadcrumb card-header" aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item engine-fm-breadcrumb-btn border-secondary">
                                <span class="engine-fm-item-thumbnail engine-icon engine-icon-arrow-level-up" aria-hidden="true">
                                    <i class="fa fa-hdd"></i>
                                </span>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="" class="">Files</a>
                            </li>
                            <li class="breadcrumb-item active">Images</li></ul>
                    </nav>
                    <div class="engine-file-container card-body">
                        <div id="media-items" class="row">

                        </div>
                    </div>
                    <div class="engine-fm-upload-panel card-footer">
                        <div class="engine-uc" role="presentation">
                            <div role="presentation">
                                <div class="progress">
                                    <div class="progress-bar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" role="progressbar">
                                        0%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/vendor/jquery-pjax/jquery.pjax.min.js') }}"></script>
<script>
    var file_id = null;
    $(function () {
        function loadMedia(){
            $.ajax({
                method: 'post',
                url: '{{ route('backend.posts.get.media') }}',
                data: {
                    id: file_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#media-items').html(data);
                }
            });
        }
        $(document).on("click", ".image-item", function() {
            var url = $(this).data('url');
            @if(request()->has('insert') && request()->input('insert') == "false")
                window.parent.dispatchEvent(new CustomEvent('message', {detail: url}));
            @else
                window.parent.tinymce.activeEditor.insertContent('<img src="' + url + '"/>');
                window.parent.tinymce.activeEditor.windowManager.close(window);
            @endif
            return false;
        });
        $(document).on("click", ".attachment-item", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            window.parent.tinymce.activeEditor.insertContent('<p>[attachment='+ id + ':' + name + ']</p>');
            window.parent.tinymce.activeEditor.windowManager.close(window);
            return false;
        });
        $(document).on("click", ".delete", function() {
            var id = $(this).data('id');
            var result = confirm("Are you sure want to delete this media?");
            if (result) {
                $.ajax({
                    method: 'post',
                    url: '{{ route('backend.posts.delete.media') }}',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $('#media-' + id).remove();
                    }
                });
            }
        });

        $(document).on("click", ".nav-item.engine-folder", function() {
            var id = $(this).data('id');
            $('.btn-upload-select').removeAttr('disabled');
            file_id = id;
            loadMedia();
            $('.nav-item.engine-folder').removeClass('selected');
            $(this).addClass('selected');
            return false;
        });
        $(document).on("click", ".file-delete", function() {
            var path = $(this).data('path');
            var r = confirm("Are you sure to delete?");
            if (r == true) {
                $.ajax({
                    method: 'delete',
                    url: '',
                    data: {
                        'files[]':[path],
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status === true) {
                            $.pjax.reload('#pjax-container');
                        } else {
                            $('#alert-message-text').text(data.message);
                            $('#alertModal').modal('show')
                        }
                    }
                });
            }
        });
        $(document).on("change", ".file-upload-form", function() {
            $('.file-upload-form').submit();
        });
        $(document).on("submit", ".file-upload-form", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('id', file_id);
            $.ajax({
                type:'POST',
                url: '{{ route('backend.posts.upload.media') }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('.toast').addClass('show');
                },
                success:function(){
                    loadMedia();
                },
                error: function(data){
                    console.log("error");
                    console.log(data);
                    $('.toast').removeClass('show');
                },
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            var uploadpercent = e.loaded / e.total;
                            uploadpercent = Math.round(uploadpercent * 100);
                            $('.progress-bar').text(uploadpercent + '%');
                            $('.progress-bar').width(uploadpercent + '%');
                        }
                    }, false);
                    return xhr;
                }
            });
        });
        $(document).on("change", "#ImageBrowse", function() {
            $("#imageUploadForm").submit();
        });

        function closeModal() {
            $("#moveModal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
        $(document).on("click", ".btn-upload-select", function() {
            $('.file-upload').trigger('click');
        });
        $(document).on("click", ".media-reload", function() {
            $.pjax.reload('#pjax-container');
        });
        $(document).on("click", ".goto-url button", function() {
            var path = $('.goto-url input').val();
            $.pjax({container:'#pjax-container', url: '?path=' + path, push: false });
        });
        $('.goto-url button').click(function () {
        });
        $(document).on("click", ".file-delete-multiple", function() {
            var files = $(".file-select input:checked").map(function(){
                return $(this).val();
            }).toArray();
            if (!files.length) {
                return;
            }
            var r = confirm("Are you sure to delete?");
            if (r == true) {
                $.ajax({
                    method: 'delete',
                    url: '',
                    data: {
                        'files[]': files,
                        _token:'{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.status === true) {
                            $.pjax.reload('#pjax-container');
                        } else {
                            $('#alert-message-text').text(data.message);
                            $('#alertModal').modal('show')
                        }
                    }
                });
            }
        });

        $.pjax.defaults.timeout = 5000;
        $.pjax.defaults.scrollTo = false;
        $(document).pjax('a.media-pjax', '#pjax-container', {push: false});
        $(document).pjax('a.pjax', '#pjax-container', {push: false});
        $(document).on("click", ".file-icon.has-img", function() {
            var artworkUrl = $(this).data('url');
            $('.post-set-featured-image img').attr('src', artworkUrl).removeClass('d-none');
            $('.post-set-featured-image span.set').addClass('d-none');
            $('.post-remove-featured-image').removeClass('d-none');
            $('.post-set-featured-image').addClass('border-0');
            $('#post-feature-image').val(artworkUrl);
        });
        $(document).on("click", ".post-remove-featured-image", function() {
            $('.post-set-featured-image img').addClass('d-none');
            $('.post-set-featured-image span.set').removeClass('d-none');
            $('.post-remove-featured-image').addClass('d-none');
            $('.post-set-featured-image').removeClass('border-0');
            $('#post-feature-image').val('');
        });
    });
    setTimeout(function () {
        $('.nav-item.engine-folder:first').trigger('click');
    }, 1000)
</script>
</body>
</html>
@endsection