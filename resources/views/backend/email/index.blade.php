@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Email templates</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="accordion">
                @foreach ($emails as $index => $email)
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <a href="{{ route('backend.email.edit', ['id' => $email->id]) }}"class="btn btn-link">
                                    {{ $email->description }}
                                </a>
                            </h2>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection