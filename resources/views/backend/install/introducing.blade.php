@extends('backend.install.index')
@section('content')
    <p>Welcome to the installation wizard for Music Engine. This wizard helps you install the script in just a couple of minutes. However, despite this, we strongly recommend that you review the documentation, as well as on its installation, which comes with this script.</p>
    <p>Your hosting must install module modrewrite and have been permitted to use it to enable SEO (Search Engine Optimization) support in script.</p>
    <p>Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p>
    <p>1. Database name</p>
    <p>2. Database username</p>
    <p>3. Database password</p>
    <p>4. Database host</p>
    <p>Super, <a href="http://ninacoder.info/">ninacoder.info</a></p>
    <a href="{{ $_SERVER['PHP_SELF'] }}?step=requirements" class="btn btn-primary btn-block">Continue</a>
@endsection