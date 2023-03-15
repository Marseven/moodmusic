@extends('index')
@section('content')
    <div id="page-content" class="community logged-out">
        <div class="container">
            <div class="page-header community main no-separator desktop">
                <h1 id="community-header">{{ $profile->name }}</h1>
            </div>
            <div id="column1" class="community-feed full">
                <div id="community" class="content" data-action="trigger" data-target="comments">
                    @include('commons.activity', ['activities' => [$activity], 'type' => 'full'])
                </div>
            </div>
        </div>
    </div>
@endsection