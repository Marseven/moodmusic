@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"> <a href="{{ route('backend.dashboard') }}">Control Panel</a></li>
        <li class="breadcrumb-item active">Bulk Upload Mp3</li>
    </ol>
    <div class="row">
        <div class="col-12">
            <div class="uploaded-files card-columns card-2-columns"></div>
            <form id="fileupload" method="POST" enctype="multipart/form-data" action="{{ route('backend.upload.bulk')  }}">
                <div class="upload-container">
                    <div id="upload-file-button" class="btn btn-primary btn-secondary">
                        <svg class="icon" height="26" width="18" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"></path>
                        </svg>
                        <span data-translate-text="PLAY_PLAYLIST">Upload Your Music</span>
                        <input id="upload-file-input" type="file" accept="audio/*" name="file" multiple>
                    </div>
                    <p>Browse for files to upload or drag and drop them here</p>
                </div>
            </form>
        </div>
    </div>
    @include('backend.commons.upload-item')
@endsection