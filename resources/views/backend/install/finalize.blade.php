@extends('backend.install.index')
@section('content')
<h3 class="text-center">Finally let set your site url</h3>
<form method="POST" action="">
    <div class="form-group">
        <label>Your website full url (with http or https)</label>
        <input class="form-control" name="siteUrl" type="text" value="{{ $request->getSchemeAndHttpHost() }}">
    </div>
    <div class="alert alert-info">If you are going to use the site with full https, please do a redirect 301 for all url from non-https to https. Otherwise the system will not working correctly. You can do that by edit file public/.htaccess, remove comment (#) form line 9 and 10.</div>
    <p>Before</p>
    <pre>
#RewriteCond %{HTTPS} off
#RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
    </pre>
    <p>After</p>
    <pre>
RewriteCond %{HTTPS} off
RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
    </pre>

    <button class="btn btn-primary btn-block" type="submit">Im's Finished</button>
</form>
@endsection