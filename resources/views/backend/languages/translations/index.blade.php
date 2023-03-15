@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('backend.languages') }}">Languages</a></li>
        <li class="breadcrumb-item">Languages Translations</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline justify-content-end mb-2" method="get" action="">
                <div class="form-group mx-sm-3 mb-2">
                    <input type="text" class="form-control" name="filter" value="{{ request()->input('filter') }}" placeholder="{{ __('translation::translation.search') }}">
                </div>
                @include('backend.languages.translations.forms.select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                @include('backend.languages.translations.forms.select', ['name' => 'group', 'items' => $groups, 'submit' => true, 'selected' => Request::get('group'), 'optional' => true])
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#exampleModal">
                    {{ __('translation::translation.add') }}
                </button>
            </form>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Languages Translations</h6>
                </div>
                <div class="card-body">
                    @if(count($translations))
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="table-width150">{{ __('translation::translation.group_single') }}</th>
                                <th class="table-width150">{{ __('translation::translation.key') }}</th>
                                <th class="uppercase">{{ trans('langcode.en') }}</th>
                                @if('en' != $language)
                                    <th class="uppercase">{{ trans('langcode.' . $language) }}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($translations as $type => $items)
                                @foreach($items as $group => $translations)
                                    @foreach($translations as $key => $value)
                                        @if(!is_array($value['en']))
                                            <tr>
                                                <td>{{ $group }}</td>
                                                <td>{{ $key }}</td>
                                                <td class="lang-editable"
                                                    data-locale="{{ 'en' }}"
                                                    data-group="{{ $group }}"
                                                    data-key="{{ $key }}"
                                                    data-uri="{{ route('backend.languages.translations.update', ['language' => 'en']) }}"
                                                >{{ $value['en'] }}</td>
                                                @if('en' != $language)
                                                    <td class="lang-editable"
                                                        data-locale="{{ $language }}"
                                                        data-group="{{ $group }}"
                                                        data-key="{{ $key }}"
                                                        data-uri="{{ route('backend.languages.translations.update', ['language' => $language]) }}"
                                                    >
                                                        {{ $value[$language] }}
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('backend.languages.translations.create', ['language' => $language]) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create translation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">You have to create the default translation of <span class="text-danger">{{ trans('langcode.' . env('APP_LOCALE', 'en')) }}</span> language first. After that you can get it translated to the language you want. This form let you create a new language key for <span class="text-danger">{{ trans('langcode.' . env('APP_LOCALE', 'en')) }}</span> language.</div>
                        <div class="form-group">
                            <label>Key</label>
                            <input class="form-control" type="text" name="key" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="value" class="form-control" rows="5"></textarea>
                        </div>
                        <input type="hidden" name="group" value="{{ request()->get('group') }}">
                        <input type="hidden" name="language" value="{{ request()->get('language') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection