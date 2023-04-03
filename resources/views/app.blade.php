@extends('common::framework')

@section('angular-styles')
    {{--angular styles begin--}}
		<link rel="stylesheet" href="client/styles.bd1b100edc4a44659421.css">
	{{--angular styles end--}}
@endsection

@section('angular-scripts')
    {{--angular scripts begin--}}
		<script>setTimeout(function() {
        var spinner = document.querySelector('.global-spinner');
        if (spinner) spinner.style.display = 'flex';
    }, 100);</script>
		<script src="client/runtime-es2015.7514a0b0211d92f4399d.js" type="module"></script>
		<script src="client/runtime-es5.7514a0b0211d92f4399d.js" nomodule defer></script>
		<script src="client/polyfills-es5.85a146d3b567a2ddbb57.js" nomodule defer></script>
		<script src="client/polyfills-es2015.394385f3043280af1d7f.js" type="module"></script>
		<script src="client/main-es2015.a0aef30887b5ce6f2fc5.js" type="module"></script>
		<script src="client/main-es5.a0aef30887b5ce6f2fc5.js" nomodule defer></script>
	{{--angular scripts end--}}
@endsection
