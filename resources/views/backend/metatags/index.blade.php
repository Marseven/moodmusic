@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Manage meta tags on the website</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h6 class="m-0 font-weight-bold text-primary">Add New Page</h6>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="collapseMetaTags">
                        <div id="collapseOne" class="collapse p-4" aria-labelledby="headingOne" data-parent="#collapseMetaTags">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-4">URL
                                        <p class="small">Specify URL of the website for which you want to assign meta tags.</p>
                                    </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="url" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Information</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="info">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Page title</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="title" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Page description
                                        <p class="small">Text that can be displayed on the search engine.</p>
                                    </label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" rows="2" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Keywords</label>
                                    <div class="col-sm-8">
                                        {!! makeTagSelector('keywords[]', isset($metatag) && ! old('keywords') ? $metatag->meta_keywords : old('keywords')) !!}
                                    </div>
                                </div>
                                <div class="form-group row border-bottom">
                                    <label class="col-sm-4">Auto general keywords
                                        <p class="small">Automatically mass generate keywords (base on title and description) to maximise your search engine presence. .</p>
                                    </label>
                                    <div class="col-sm-8 col-9">
                                        <label class="switch">
                                            {!! makeCheckBox('auto_keyword', 0) !!}
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Page Artwork
                                        <p class="small mt-2">This will use for social share, if the page already contain an artwork this will overwrite the current one. For example song page will use this instead of song artwork.</p>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group col-xs-12">
                                            <input type="file" name="artwork" class="file-selector" accept="image/*">
                                            <span class="input-group-addon"><i class="fas fa-fw fa-image"></i></span>
                                            <input type="text" class="form-control input-lg" disabled placeholder="Upload Image">
                                            <span class="input-group-btn">
                                                <button class="browse btn btn-primary input-lg" type="button"><i class="fas fa-fw fa-file"></i> Browse</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-primary">Create</button>
                                <button type="reset" class="btn btn-info">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Manage Titles, Descriptions, and Keywords</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('backend.metatags.sort.post') }}">
                        @csrf
                        <table class="table table-striped table-sortable">
                            <thead>
                            <tr>
                                <th class="th-handle"></th>
                                <th class="th-priority">Priority</th>
                                <th class="th-priority">Image</th>
                                <th>Alternative name</th>
                                <th>Url</th>
                                <th>Description</th>
                                <th class="th-2action">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($metatags as $index => $metatag)
                                <tr data-id="{{ $metatag->id }}">
                                    <td><i class="handle fas fa-fw fa-arrows-alt"></i></td>
                                    <td><input type="hidden" name="metaIds[]" value="{{ $metatag->id }}"></td>
                                    <td><img src="{{ $metatag->artwork_url }}"/></td>

                                    <td>{{ $metatag->url }}</td>
                                    <td><span title="{{ url($metatag->url) }}">{{ url($metatag->url) }}</span></td>
                                    <td>{{ $metatag->info }}</td>
                                    <td>
                                        <a href="{{ route('backend.metatags.edit', ['id' => $metatag->id]) }}" class="row-button edit"><i class="fas fa-fw fa-edit"></i></a>
                                        <a href="{{ route('backend.metatags.delete', ['id' => $metatag->id]) }}" onclick="return confirm('Are you sure?')" class="row-button delete"><i class="fas fa-fw fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                    <div class="card mt-4 py-3 border-left-info">
                        <div class="card-body card-small">
                            <p class="mb-0">In this section you can assign meta tags for site pages. For example, you can assign specific values for meta tags for the trending/community page, rather than use general values from the engine settings. You can specify the address of the page for which you want to change meta tags, and also you can specify a URL group using the '*' character which stands for search by any set of characters. E.g., if you use /page/*/, then specified meta tags will be used for pages /page/1/, /page/2/, /page/any text/, etc.</p>
                        </div>
                    </div>
                    <div class="card mt-4 py-3 border-left-info">
                        <div class="card-body card-small">
                            <p>A meta tag is an HTML tag containing information for search engines about a specific website. Meta tags contain keywords or phrases alerting search engines of a website's content to be included in search results for users requesting related information.</p>
                            <p><strong>Title Tag</strong> — The title meta tag is the most important element in the site optimization process. Many search engines pay particular attention to keywords that occur in the title tag. As well, search engines generally display the title tag's contents in their site listings.</p>
                            <p><strong>Description Tag</strong> — The description meta tag defines site information a search engine displays when it lists the site. The description meta tag should concisely explain the nature and contents of the page.</p>
                            <p class="mb-0"><strong>Keywords Tag</strong> — The keywords meta tag lists the search keywords for a site. The keywords entered here should reflect any words or phrases Internet users might use to search for the site. Although many search engines have ceased to incorporate this tag into their ranking procedures, it's still a good idea to add this tag before submitting a page.</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection