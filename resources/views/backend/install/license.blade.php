@extends('backend.install.index')
@section('content')
    <h3 class="text-center">Welcome to the Music Engine automatic installation system. | <a target="_blank" href="https://https://nullur.com">nullur.com</a></h3>
    <p>To continue installing the Music Engine you must enter your license key. In order to automatically install the engine, you need an Internet connection between your server and https://codecanyon.com site. License key is the purchase's code you get from your codecanyon.com account.</p>
    <form method="post" action="{{ $_SERVER['PHP_SELF'] }}?step=license">
        <div class="form-row align-items-center">
            <div class="col-sm-3 my-1">
                <input name="license" type="text" class="form-control" id="inlineFormInputName" placeholder="Enter a key to activate the license">
            </div>
            <div class="col-auto my-1">
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </div>
    </form>
@endsection