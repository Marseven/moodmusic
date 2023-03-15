@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Create and update maps for search engines</li>
    </ol>
    <div class="row">
        <div class="col-lg-7 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create and update maps for search engines</h6>
                </div>
                <div class="card-body">
                    @if(isset($filemtime))
                        <div class="card mt-4 py-3 border-left-info">
                            <div class="card-body card-small">
                                <p class="mb-0">Index file for Google Sitemap was created at <strong class="text-success">{{ $filemtime }}</strong> (server time) and is available at: <a href="{{ route('frontend.homepage') }}/sitemap.xml" target="_blank">{{ route('frontend.homepage') }}/sitemap.xml</a></p>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <form method="post" action="{{ route('backend.sitemap.make') }}">
                                @csrf
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Number of post articles:
                                        <p class="small mb-0">You can set amount of news that will be exported to a Google Sitemap file. If you leave this field blank, all news will be exported.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="post_num" value="1000" required="">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Number of music item:
                                        <p class="small mb-0">You can set amount of music item (artist, album, song ...) that will be exported to a Google Sitemap file. If you leave this field blank, all music item will be exported.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="song_num" value="1000" required="">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Priority of static pages:
                                        <p class="small mb-0">Correlation between the exported URLs and the rest URLs on your website. The valid range is from 0.0 to 1.0. This value does not affect the comparison of your site’s pages to pages on other sites.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="static_priority" value="0.5" required="">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Music's priority:
                                        <p class="small mb-0">Included genres and moods and user's playlist, this is the correlation between the exported URLs and the rest URLs on your website.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="song_priority" value="0.5" required="">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom mb-0 pt-3 pb-3">
                                    <label class="col-sm-8 mb-0">Blog's priority:
                                        <p class="small mb-0">Included blog categories. The valid range is from 0.0 to 1.0. This value does not affect the comparison of your site’s pages to pages on other sites.</p>
                                    </label>
                                    <div class="col-sm-4">
                                        <input class="form-control" name="blog_priority" value="0.5" required="">
                                    </div>
                                </div>
                                <button type="btn btn-primary" class="btn btn-primary mt-4">Create/update the site map file </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">A brief description of the Google Sitemaps</h6>
                </div>
                <div class="card-body">
                    <div class="card mt-4 py-3 border-left-info">
                        <div class="card-body card-small">
                            <p>1. You need to register in <a href="https://www.google.com/accounts/ServiceLogin?service=sitemaps&passive=true">Google Sitemaps</a> using your Google account.</p>
                            <p>2. Click "Add first sitemap".</p>
                            <p>3. Enter your Sitemap index file in the "URL" field and press "Sitemap URL" button.</p>
                            <p class="mb-0">4. More detailed help can be found at <a href="http://www.google.com/support/webmasters/bin/topic.py?topic=8476" target="_blank">Google website.</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection