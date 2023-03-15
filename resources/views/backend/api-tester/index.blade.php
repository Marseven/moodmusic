@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">API Tester</li>
    </ol>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Routes</h6>
                </div>
                <div class="card-body">
                    <form class="mb-4" action="#" method="post">
                        <div class="input-group">
                            <input type="text" name="message" placeholder="Type Url ..." class="form-control filter-routes"><span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                        </div>
                    </form>
                    <ul class="list-group routes">
                        @foreach($routes as $route)
                            @php ($color = \App\Http\Controllers\Backend\Encore\ApiTester::$methodColors[$route['method']])
                            <li class="route-item list-group-item clearfix"
                                data-uri="{{ $route['uri'] }}"
                                data-method="{{ $route['method'] }}"
                                data-method-color="{{$color}}"
                                data-parameters='{!! $route['parameters'] !!}' >
                                <a href="javascript:;" class="float-left">{{ $route['uri'] }}</a>
                                <button class="btn btn-sm float-right text-white {{ $color }}">{{ $route['method'] }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request</h6>
                </div>
                <div class="card-body">
                    <form class="form-horizontal api-tester-form">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 control-label">Request</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                <span class="input-group-btn">
                                    <a class="btn btn-info btn-flat method text-white">method</a>
                                </span>
                                    <input type="text" name="uri" class="form-control uri">
                                    <input type="hidden" name="method" class="form-control method">
                                    {{ csrf_field() }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputUser" class="col-sm-2 control-label">Login as</label>

                            <div class="col-sm-10">
                                <select class="form-control select-ajax" data-ajax--url="{{ route('api.search.user') }}" name="user"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 control-label">Parameters</label>
                            <div class="col-sm-10">
                                <div class="params">
                                    <div class="form-inline param-add">
                                        <div class="form-group mr-2">
                                            <input type="text" class="form-control" placeholder="key"/>
                                        </div>
                                        <div class="form-group mr-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="value"/>
                                                <span class="input-group-btn">
                                                    <a type="button" class="btn btn-primary btn-flat change-val-type"><i class="fa fa-upload"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="response" class="card shadow mt-4 d-none">
                <div class="card-header p-0">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="pill">Response Content</a></li>
                        <li class="nav-item"><a href="#tab2" class="nav-link"  data-toggle="pill">Response Headers</a></li>
                        <li class="nav-item"><a href="#tab3" class="nav-link"  data-toggle="pill">Request Headers</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div id="tab1" class="tab-pane fade show active">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="jsonEditorContent" class="mt-4"></div>
                                </div>
                            </div>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="jsonEditorResponseHeaders" class="mt-4"></div>
                                </div>
                            </div>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="jsonEditorRequestHeaders" class="mt-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="error" class="alert alert-danger mt-4 d-none" role="alert">
                Error, no data or unknown response!
            </div>
        </div>
    </div>
    <template class="param-tpl">
        <div class="form-inline param mb-3">
            <div class="form-group mr-2">
                <input type="text" name="key[__index__]" class="form-control param-key" placeholder="Key"/>
            </div>
            <div class="form-group mr-2">
                <div class="input-group">
                    <input type="text" name="val[__index__]" class="form-control param-val"  placeholder="value"/>
                    <span class="input-group-btn">
                  <a type="button" class="btn btn-default btn-flat change-val-type"><i class="fa fa-upload"></i></a>
                </span>
                </div>
            </div>

            <div class="form-group text-danger mr-2">
                <i class="fa fa-times-circle param-remove"></i>
            </div>
            <br/>
            <div class="form-group param-desc mr-2 d-none">
                <i class="fa fa-info-circle"></i>&nbsp;
                <span class="text"></span>
                <b class="text-red d-none param-required">*</b>
            </div>
        </div>
    </template>
@endsection