<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 7/23/19
 * Time: 3:55 PM
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Encore\MediaManager;
use DB;
use Auth;
use Carbon\Carbon;
use View;
use Cache;
use App\Models\Post;
use App\Models\Poll;
use App\Models\File;
use Validator;
use Storage;
use Image;

class PostsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $posts = Post::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            if($this->request->has('location')) {
                switch ($this->request->input('location')) {
                    case 0:
                        $posts = $posts->search($this->request->input('term'));
                        break;
                    case 1:
                        $posts = $posts->where('title', 'like', '%' . $this->request->input('term') . '%');
                        break;
                    case 2:
                        $posts = $posts->where('short_content', 'like', '%' . $this->request->input('term') . '%');
                        break;
                    case 3:
                        $posts = $posts->where('full_content', 'like', '%' . $this->request->input('term') . '%');
                        break;
                }
            } else {
                $posts = $posts->where('title', 'like', '%' . $this->request->input('term') . '%');
            }

        }

        if ($this->request->input('userIds') && is_array($this->request->input('userIds')))
        {
            $posts = $posts->where(function ($query) {
                foreach($this->request->input('userIds') as $index => $userId) {
                    if($index == 0) {
                        $query->where('user_id', '=', $userId);
                    } else {
                        $query->orWhere('user_id', '=', $userId);
                    }
                }
            });
        }

        if ($this->request->input('category') && is_array($this->request->input('category')))
        {
            $posts = $posts->where('category', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('category')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $posts = $posts->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $posts = $posts->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $posts = $posts->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $posts = $posts->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('fixed'))
        {
            $posts = $posts->where('fixed', '=', 1);
        }

        if ($this->request->has('comment_disabled'))
        {
            $posts = $posts->where('allow_comments', '=', 0);
        }

        if ($this->request->has('scheduled'))
        {
            $posts = $posts->where('created_at', '>', Carbon::now());
        }

        if ($this->request->has('status') && intval($this->request->input('status')) != 3)
        {
            $posts = $posts->where('approved', '=', intval($this->request->input('status')));
        }

        if ($this->request->has('hidden'))
        {
            $posts = $posts->where('visibility', '=', 0);
        }

        if ($this->request->has('approved'))
        {
            $posts->orderBy('approved', $this->request->input('approved'));
        }

        if ($this->request->has('title'))
        {
            $posts = $posts->orderBy('title', $this->request->input('title'));
        }

        if ($this->request->has('created_at'))
        {
            $posts = $posts->orderBy('created_at', $this->request->input('created_at'));
        }

        if ($this->request->has('view_count'))
        {
            $posts = $posts->orderBy('view_count', $this->request->input('view_count'));
        }

        $total_posts = $posts->count();

        if ($this->request->has('results_per_page'))
        {
            $posts = $posts->paginate(intval($this->request->input('results_per_page')));
        } else {
            $posts = $posts->paginate(20);
        }

        return view('backend.posts.index')
            ->with('posts', $posts)
            ->with('total_posts', $total_posts);
    }

    public function add()
    {
        $view = View::make('backend.posts.form');

        return $view;
    }

    public function delete()
    {
        DB::table('posts')
            ->where('id', $this->request->route('id'))
            ->delete();

        return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Article successfully deleted!');
    }

    public function addPost()
    {
        $post = new Post();

        $post->user_id = auth()->user()->id;

        if($this->request->input('alt_name'))
        {
            $this->request->validate([
                'alt_name' => 'required|unique:post',
            ]);
        }

        $post->title = $this->request->input('title');
        $alt_name = $this->request->input('alt_name');

        if( ! $alt_name ) {
            $alt_name = str_slug($post->title);
        } else {
            $alt_name = str_slug($alt_name);
        }

        $post->alt_name = $alt_name;

        $category = $this->request->input('category');

        if(is_array($category))
        {
            $category = implode(",", $this->request->input('category'));

        }

        $post->category = $category;

        $tags = $this->request->input('tags');

        if(is_array($tags))
        {
            $post->tags = implode(",", $this->request->input('tags'));

        }

        if(is_array($this->request->input('meta_keywords')))
        {
            $post->meta_keywords = implode(",", $this->request->input('meta_keywords'));

        }

        $created_at = $this->request->input('published_at');

        if(! strtotime($created_at)){
            $created_at = Carbon::now()->format('Y/m/d H:i');
        }

        $post->created_at = $created_at;

        $post->visibility = $this->request->input('visibility');
        $post->allow_main = $this->request->input('allow_main');
        $post->fixed = $this->request->input('fixed');
        $post->allow_comments = $this->request->input('allow_comments');
        $post->disable_index = $this->request->input('disable_index');
        $post->short_content = $this->request->input('short_content');
        $post->full_content = $this->request->input('full_content');
        $post->meta_title = $this->request->input('meta_title');
        $post->meta_description = $this->request->input('meta_description');
        $post->approved = $this->request->input('approved');

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $post->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $post->access = $group_regel;
        }

        $post->save();

        if($this->request->input('poll_title')) {

            $poll = Poll::where('object_type', 'post')->where('object_id', $post->id)->first();

            if ( ! $poll) {
                $poll = new Poll();
            }

            $poll->object_type = 'post';
            $poll->object_id = $post->id;
            $poll->title = $this->request->input('poll_title');
            $poll->body = $this->request->input('poll_answers');
            $poll->multiple = $this->request->input('poll_multiple');
            $poll->visibility = $this->request->input('poll_visibility');
            $poll->ended_at = $this->request->input('poll_ended_at');
            $poll->save();

        }

        /**
         * Save to post tags cloud
         */

        if( $tags ) {
            $tags = explode( ",", $tags );
            foreach ( $tags as $tag ) {
                DB::table('post_tags')->insert([
                    'post_id' => $this->request->route('id'),
                    'tag' => $tag
                ]);

            }
        }

        Cache::clear('post_tags');

        return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Article successfully created!');
    }

    public function edit()
    {

        $post = Post::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $options = groupPermission($post->access);

        $view = View::make('backend.posts.form')
            ->with('post', $post)
            ->with('options', $options);

        $poll = Poll::where('object_type', 'post')->where('object_id', $post->id)->first();

        if ($poll) {
            $view = $view->with('poll', $poll);
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['media'];
        }

        return $view;
    }

    public function editPost()
    {
        $this->request->validate([
            'title' => 'required|string|max:250',
            'publish' => 'date_format:Y/m/d H:i',
            'visibility' => 'boolean',
            'allow_main' => 'boolean',
            'fixed' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'string|max:140|nullable',
            'meta_description' => 'string|max:300|nullable',
            'meta_keywords' => 'nullable|array',
            'tags' => 'nullable|array',
            'poll_title' => 'nullable|string|max:250',
            'poll_answer' => 'nullable|string|max:1000',
            'poll_multiple' => 'nullable|boolean',
            'group_extra' => 'nullable|array',

        ]);

        $post = Post::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        if($this->request->input('alt_name') && $post->alt_name != $this->request->input('alt_name'))
        {
            $this->request->validate([
                'alt_name' => 'required|unique:posts',
            ]);
        }

        $post->title = $this->request->input('title');
        $alt_name = $this->request->input('alt_name');

        if( ! $alt_name ) {
            $alt_name = str_slug($post->title);
        } else {
            $alt_name = str_slug($alt_name);
        }

        $post->alt_name = $alt_name;

        $category = $this->request->input('category');

        if(is_array($category))
        {
            $category = implode(",", $this->request->input('category'));

        }

        $post->category = $category;

        $tags = $this->request->input('tags');

        if(is_array($tags))
        {
            $post->tags = implode(",", $this->request->input('tags'));

        }

        if(is_array($this->request->input('meta_keywords')))
        {
            $post->meta_keywords = implode(",", $this->request->input('meta_keywords'));

        }

        $created_at = $this->request->input('published_at');

        if(! strtotime($created_at)){
            $created_at = Carbon::now()->format('Y/m/d H:i');
        }

        $post->created_at = $created_at;

        $post->visibility = $this->request->input('visibility');
        $post->allow_main = $this->request->input('allow_main');
        $post->fixed = $this->request->input('fixed');
        $post->allow_comments = $this->request->input('allow_comments');
        $post->disable_index = $this->request->input('disable_index');
        $post->short_content = $this->request->input('short_content');
        $post->full_content = $this->request->input('full_content');
        $post->meta_title = $this->request->input('meta_title');
        $post->meta_description = $this->request->input('meta_description');
        $post->approved = $this->request->input('approved');


        if ($this->request->input('remove_artwork') && $this->request->input('remove_artwork') == 1)
        {
            $post->clearMediaCollection('artwork');
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $post->clearMediaCollection('artwork');
            $post->addMedia($this->request->file('artwork'))
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('group_extra')) {
            $group_regel = array ();

            foreach ( $this->request->input('group_extra') as $key => $value ) {
                if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
            }

            if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
            else $group_regel = null;

            $post->access = $group_regel;
        }

        $post->save();

        if($this->request->input('poll_title')) {

            $poll = Poll::where('object_type', 'post')->where('object_id', $post->id)->first();

            if ( ! $poll) {
                $poll = new Poll();
            }

            $poll->object_type = 'post';
            $poll->object_id = $post->id;
            $poll->title = $this->request->input('poll_title');
            $poll->body = $this->request->input('poll_answers');
            $poll->multiple = $this->request->input('poll_multiple');
            $poll->visibility = $this->request->input('poll_visibility');
            $poll->ended_at = $this->request->input('poll_ended_at');
            $poll->save();

        } else {
            Poll::where('object_type', 'post')->where('object_id', $post->id)->delete();
        }

        /**
         * Save to post tags cloud
         */

        if( is_array($tags) && count($tags) ) {
            DB::table('post_tags')->where('post_id', $this->request->route('id'))->delete();

            foreach ( $tags as $tag ) {
                DB::table('post_tags')->insert([
                    'post_id' => $this->request->route('id'),
                    'tag' => $tag
                ]);

            }
        }

        Cache::clear('post_tags');

        return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Article successfully edited!');
    }

    /** Post media controller (use for featured image) */
    public function media()
    {
        $posts = Post::withoutGlobalScopes()->orderBy('id', 'desc')->limit(20)->get();

        if($this->request->route('id')) {
            $media = File::where('post_id', $this->request->route('id'))->first();
            if(!isset($media->id)) {
                $media = File::where('post_id', '=', '0')->orderBy('id', 'desc')->first();
                if(!isset($media->id)) {
                    $media = new File();
                    $media->save();
                }
            }
        } else {
            $media = File::where('post_id', '=', '0')->orderBy('id', 'desc')->first();
            if(!isset($media->id)) {
                $media = new File();
                $media->save();
            }
        }

        $attachments = $media->getMedia('attachments');
        $images = $media->getMedia('images');

        $view = View::make('backend.posts.media')
            ->with('posts', $posts)
            ->with('attachments', $attachments)
            ->with('images', $images)
            ->with('media', $media);

        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function getMedia()
    {
        if($this->request->input('id') == 0) {
            $media = File::where('post_id', '=', 0)->orderBy('id', 'desc')->first();
            $attachments = $media->getMedia('attachments');
            $images = $media->getMedia('images');
        } else {
            $media = File::where('post_id', '=', $this->request->input('id'))->first();
            if(isset($media->id)) {
                $attachments = $media->getMedia('attachments');
                $images = $media->getMedia('images');
            } else {
                $attachments = new \stdClass();
                $images = new \stdClass();
            }
        }

        $view = View::make('backend.posts.media')
            ->with('attachments', $attachments)
            ->with('images', $images);

        $sections = $view->renderSections();

        return $sections['media'];
    }

    public function uploadMedia(Request $request)
    {

        $this->request->validate([
            'id' => 'required|integer',
        ]);

        $files = $request->file('files');
        if($this->request->input('id') == 0) {
            $media = File::where('post_id', '=', 0)->orderBy('id', 'desc')->first();
        } else {
            $media = File::where('post_id', '=', $this->request->input('id'))->firstOrFail();
        }


        $input_data = $request->all();
        $validator = Validator::make(
            $input_data, [
                'files.*' => 'required|max:20000'
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        }

        foreach ($files as $file) {
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
            );

            $validator = Validator::make(array('image' => $file), $rules);

            if ($validator->fails())
            {
                $media->addMedia($file->getPathName())->usingFileName($file->getClientOriginalName(), PATHINFO_FILENAME)->toMediaCollection('attachments');
            } else {
                $media->addMedia($file->getPathName())->usingFileName($file->getClientOriginalName(), PATHINFO_FILENAME)->toMediaCollection('images');
            };
        }

        try {
            if ($files) {
                return response()->json([
                    'status'  => true,
                    'message' => ('Upload succeeded.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
        return back();
    }

    public function attachFile()
    {
        $files = $this->request->file('files');
        $media = File::find(4);
        $input_data = $this->request->all();

        $validator = Validator::make(
            $input_data, [
                'files.*' => 'required|max:200'
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 422);
        }

        foreach ($files as $file) {
            $media->addMedia($file->getPathName())->usingFileName($file->getClientOriginalName(), PATHINFO_FILENAME)->toMediaCollection('attachments');
        }

        try {
            if ($files) {
                return response()->json([
                    'status'  => true,
                    'message' => ('Upload succeeded.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function downloadMedia()
    {
        $file = $this->request->get('file');
        $manager = new MediaManager('public', $file);
        return $manager->download();
    }



    public function deleteMedia()
    {
        $this->request->validate([
            'id' => 'required|integer'
        ]);

        $media_id = $this->request->input('id');

        File::whereHas('media', function ($query) use($media_id){
            $query->whereId($media_id);
        })->first()->deleteMedia($media_id);

        return response()->json([
            'status'  => true
        ]);
    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);

        if($this->request->input('action') == 'add_category') {
            $message = 'Add category';
            $subMessage = 'Add Category for Chosen Posts (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_category')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_add_category') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::find($id);
                if(isset($post->id)){
                    $currentCategory = explode(',', $post->category);
                    $newCategory = array_unique(array_merge($currentCategory, $this->request->input('category')));
                    $post->category = implode(',', $newCategory);
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } elseif($this->request->input('action') == 'change_category') {
            $message = 'Change category';
            $subMessage = 'Change Category for Chosen Posts (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_category')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_category') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->category = implode(',', $this->request->input('category'));
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } elseif($this->request->input('action') == 'change_author') {
            $message = 'Change Author';
            $subMessage = 'Change Author for Chosen Posts (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_user')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_change_author') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->user_id = $this->request->input('user_id');
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->approved = 1;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'not_approve') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->approved = 0;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'set_current') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->created_at = Carbon::now();
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'fixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->fixed = 1;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'not_fixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->fixed = 0;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'clear_views') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->view_count = 0;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'clear_tags') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->tags = null;
                    $post->save();
                    DB::table('post_tags')->where('post_id', $post->id)->delete();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->allow_comments = 1;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'not_comments') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->find($id);
                if(isset($post->id)){
                    $post->allow_comments = 0;
                    $post->save();
                }
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $post = Post::withoutGlobalScopes()->where('id', $id)->first();
                $post->delete();
            }
            return redirect()->route('backend.posts')->with('status', 'success')->with('message', 'Posts successfully deleted!');
        }
    }
}