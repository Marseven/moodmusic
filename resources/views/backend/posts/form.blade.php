@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.posts') }}">Posts</a></li>
        <li class="breadcrumb-item active">{{ isset($post) ? $post->title : ' Add news article' }}</li>
    </ol>
    <form method="POST" action="" class="article-form form-horizontal" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-8 col-12">
                <div class="card">
                    <div class="card-header p-0 position-relative">
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link active" href="#news" data-toggle="pill"><i class="fas fa-fw fa-newspaper"></i> News</a></li>
                            <li class="nav-item"><a href="#advanced" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-cog"></i> Advanced</a></li>
                            <li class="nav-item"><a href="#voting" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-poll"></i> Voting</a></li>
                            <li class="nav-item"><a href="#access" class="nav-link" data-toggle="pill"><i class="fas fa-fw fa-lock"></i> Access</a></li>
                        </ul>
                        <a class="btn btn-link post-fullscreen"><i class="fas fa-expand-arrows-alt"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="tab-content mt-2" id="myTabContent">
                            <div id="news" class="tab-pane fade show active">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="title" value="{{ isset($post) && ! old('title') ? $post->title : old('title') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-4 col-form-label">Publish</label>
                                    <div class="col-sm-4 col-8">
                                        <input class="form-control @if(isset($post)) datetimepicker-no-mask @else datetimepicker @endif" name="published_at" value="{{ isset($post) ? \Carbon\Carbon::parse(($post->created_at))->format('Y/m/d H:i') : old('published_at') ?? \Carbon\Carbon::now()->format('Y/m/d H:i') }}" autocomplete="off">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Categories</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2-active" multiple="" name="category[]">
                                            {!! categorySelection(explode(',', isset($post) && ! old('category') ? $post->category : old('category'))) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Short Content</label>
                                    <textarea data-filemanager-plugin-path="{{ asset('backend/js/filePlugin.js') }}" data-responsive-filemanager-plugin-path="{{ asset('backend/js/tinyPlugin.js') }}" data-external-filemanager-path="{{ isset($post) ? route('backend.post-media-index.associated', ['id' => $post->id]) : route('backend.post-media-index')}}" name="short_content" class="form-control post editor" rows="5">{{ isset($post) && ! old('short_content') ? $post->short_content : old('short_content') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Full Content</label>
                                    <textarea data-filemanager-plugin-path="{{ asset('backend/js/filePlugin.js') }}" data-responsive-filemanager-plugin-path="{{ asset('backend/js/tinyPlugin.js') }}" data-external-filemanager-path="{{ isset($post) ? route('backend.post-media-index.associated', ['id' => $post->id]) : route('backend.post-media-index')}}" name="full_content" class="form-control post editor" rows="20">{{ isset($post) && ! old('full_content') ? $post->full_content : old('full_content') }}</textarea>
                                </div>
                            </div>
                            <div id="advanced" class="tab-pane fade">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">User-friendly URL</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="alt_name" value="{{ isset($post) && ! old('alt_name') ? $post->alt_name : old('alt_name') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tags</label>
                                    <div class="col-sm-9">
                                        {!! makeTagSelector('tags[]', isset($post) && ! old('tags') ? $post->tags : old('tags')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Validity term</label>
                                    <div class="col-sm-9">
                                        <div class="form-inline">
                                            <div class="input-group mr-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Date</div>
                                                </div>
                                                <input type="text" class="form-control datetimepicker-with-form" name="log_expires" autocomplete="off">
                                            </div>
                                            <select name="log_action" class="form-control select2-active">
                                                <option value="">--- Choose the action ---</option>
                                                <option value="1">Delete</option>
                                                <option value="2">Send for moderation</option>
                                                <option value="3">Disable publishing on the blog homepage</option>
                                                <option value="4">Unpin</option>
                                                <option value="5">Move to other category</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-info">Manual addition of meta tags for the article. Meta tags for this article can be added manually or generated automatically. Leave the fields blank if you want meta tags to be generated automatically.</p>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Title meta tag</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="meta_title" value="{{ isset($post) && ! old('meta_title') ? $post->meta_title : old('meta_title') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description meta tag</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="meta_description" value="{{ isset($post) && ! old('meta_description') ? $post->meta_description : old('title') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Keywords meta tag</label>
                                    <div class="col-sm-9">
                                        {!! makeTagSelector('meta_keywords[]', isset($post) && ! old('meta_keywords') ? $post->meta_keywords : old('meta_keywords')) !!}
                                    </div>
                                </div>
                            </div>
                            <div id="voting" class="tab-pane fade">
                                <div class="alert alert-info">Adding to the voting to a article is optional. Just leave it blank if you do not want to add a voting to this article.</div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Voting question</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="poll_title" value="{{ isset($poll) && ! old('poll_title') ? $poll->title : old('poll_title') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Voting question<p class="text-muted small">Each new line is a new answer option.</p></label>
                                    <div class="col-sm-9">
                                        <textarea name="poll_answers" class="form-control" rows="5">{{ isset($poll) && ! old('poll_answers') ? $poll->body : old('poll_answers') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-4 col-form-label">Allow multiple selection</label>
                                    <div class="col-sm-9 col-8">
                                        <label class="switch">
                                            {!! makeCheckBox('poll_multiple', isset($poll) && ! old('poll_multiple') ? $poll->multiple : (old('poll_multiple') ? old('poll_multiple') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-4 col-form-label">End at</label>
                                    <div class="col-sm-4 col-8">
                                        <input class="form-control datetimepicker-no-mask" name="poll_ended_at" value="{{ isset($poll) ? \Carbon\Carbon::parse(($poll->ended_at))->format('Y/m/d H:i') : old('poll_ended_at') ?? \Carbon\Carbon::now()->format('Y/m/d H:i') }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-4 col-form-label">Visibility</label>
                                    <div class="col-sm-9 col-8">
                                        <label class="switch">
                                            {!! makeCheckBox('poll_visibility', isset($poll) && ! old('poll_visibility') ? $poll->visibility : (old('poll_visibility') ? old('poll_visibility') : 1)) !!}
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="access" class="tab-pane fade">
                                @if(cache()->has('usergroup'))
                                    @foreach(cache()->get('usergroup') as $group)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">{{ $group->name }}</label>
                                            <div class="col-sm-9">
                                                {!! makeDropDown([
                                                        0 => 'Group settings',
                                                        1 => 'Read Only',
                                                        2 => 'Read And Comment',
                                                        3 => 'Reading denied'
                                                    ], 'group_extra[' . $group->id . ']', isset($options) && isset($options[$group->id]) ? $options[$group->id] : 0)
                                                !!}

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="alert alert-info">Note: You can configure additional news access parameters for different groups in this section, but these options are valid only for the full articles.</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary article-submit">Save</button>
                        <button type="reset" class="btn btn-info article-reset">Reset</button>

                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Options</h6>
                    </div>
                    <div class="card-body">
                        <div class="featured-image">
                            <div id="featured-image" class="post-set-featured-image">
                                <span class="set @if(isset($post) && $post->getFirstMediaUrl('artwork')) d-none @endif">Set featured image</span>
                                @if(isset($post) && $post->getFirstMediaUrl('artwork'))
                                    <img id="artwork_url" src="{{$post->getFirstMediaUrl('artwork') }}">
                                @else
                                    <img id="artwork_url" class="d-none" src="">
                                @endif
                                <div class="post-remove-featured-image @if((isset($post) && ! $post->getFirstMediaUrl('artwork')) || ! isset($post)) d-none @endif">Remove featured image</div>
                            </div>
                            <input id="artwork_picker" type="file" name="artwork" accept="image/*"/>
                            <input id="remove_artwork" type="hidden" name="remove_artwork"/>
                        </div>
                        <div class="form-group row mt-5">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('visibility', isset($post) && ! old('visibility') ? $post->visibility : (old('visibility') ? old('visibility') : 1)) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Visibility</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('approved', isset($post) && ! old('approved') ? $post->approved : (old('approved') ? old('approved') : 1)) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Approve this article</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('allow_main', isset($post) && ! old('allow_main') ? $post->allow_main : (old('allow_main') ? old('allow_main') : 1)) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Publish on the main page section</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('fixed', isset($post) && ! old('fixed') ? $post->fixed : old('fixed')) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Stick to the top of the news</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('allow_comments', isset($post) && ! old('allow_comments') ? $post->allow_comments : (old('allow_comments') ? old('allow_comments') : 1)) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Allow comments</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="switch">
                                    {!! makeCheckBox('disable_index', isset($post) && ! old('disable_index') ? $post->disable_index : old('disable_index')) !!}
                                    <span class="slider round"></span>
                                </label>
                                <label class="pl-6 col-form-label">Disable page indexation for the search engines</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection