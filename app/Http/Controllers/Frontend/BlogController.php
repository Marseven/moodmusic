<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-28
 * Time: 01:41
 */

namespace App\Http\Controllers\Frontend;

use App\Models\File;
use App\Models\Poll;
use Illuminate\Http\Request;
use DB;
use App\Models\Post;
use App\Models\User;
use View;
use Cache;
use MetaTag;
use App\Models\Comment;
use App\Models\Category;
use Auth;
use App\Models\Role;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function paginate($items, $perPage = 1, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function breadcrumb($cat_id){
        $breadcrumbCategories = new \stdClass();

        if(Cache::has('categories')) {
            $id = $cat_id;
            $cat_info = Cache::get('categories')->toArray();

            $array = array();
            foreach ($cat_info as $row) {
                $array[$row['id']] = $row;
            }

            $cat_info = $array;
            $parent_id = $cat_info[$id]['parent_id'];
            $list = "{$cat_info[$id]['name']}\n";
            $breadcrumb_array = array();
            $breadcrumb_array[] = $cat_info[$id];

            if ($parent_id && isset($cat_info[$parent_id])) {
                $breadcrumb_array[] = $cat_info[$parent_id];
            }

            while ($parent_id) {
                $list .= "{$cat_info[$parent_id]['name']} \n" . " &raquo; " . $list;
                $parent_id = $cat_info[$parent_id]['parent_id'];

                if (isset($cat_info[$parent_id])) {
                    $breadcrumb_array[] = $cat_info[$parent_id];
                }
            }
            $breadcrumbCategories = (object) array_reverse($breadcrumb_array);
        } else {
            $catInfo = (new Category)->get();
            Cache::forever('categories', $catInfo);
        }

        return $breadcrumbCategories;
    }

    public function index()
    {
        /** All the the post with fixed config should be at top of every where */
        $posts = Post::orderBy('fixed', 'desc');

        /** if is home page, get only post which allowed to show on homepage (allow_main = 1) */
        if($this->request->route()->getName() == 'frontend.blog.browse.by.day') {
            $posts = $posts->where('allow_main', 1);
        }

        /** Browse by year, month, day, category and tag  */
        if($this->request->route()->getName() == 'frontend.blog.browse.by.day') {
            $posts = $posts->whereYear('created_at', $this->request->route('year'))
                ->whereMonth('created_at', $this->request->route('month'))
                ->whereDay('created_at', $this->request->route('day'));

        } else if($this->request->route()->getName() == 'frontend.blog.browse.by.month') {
            $posts = $posts->whereYear('created_at', $this->request->route('year'))
                ->whereMonth('created_at', $this->request->route('month'));

        } else if($this->request->route()->getName() == 'frontend.blog.browse.by.year') {
            $posts = $posts->whereYear('created_at', $this->request->route('year'));

        } else if($this->request->route()->getName() == 'frontend.blog.category') {
            $category = Category::where('alt_name', $this->request->route('category'))->firstOrFail();
            if(is_array(Role::getValue('blog_prohibited_view_categories')) && in_array($category->id, Role::getValue('blog_prohibited_view_categories'))) {
                abortNoPermission();
            }

            $posts = $posts->where('category', 'regexp', $category->id);
            $breadcrumbCategories = $this->breadcrumb($category->id);
        } else if($this->request->route()->getName() == 'frontend.blog.tags') {
            $posts = $posts->where('tags', 'regexp', $this->request->route('tag'));
        }

        /** set sort setting  */
        if($this->request->route()->getName() == 'frontend.blog.category') {

        } else {
            /** sort order the post */
            if(config('settings.post_sort_order') == 0) {
                $posts = $posts->orderBy('id', 'desc');
            } else if(config('settings.post_sort_order') == 1) {
                $posts = $posts->orderBy('id', 'asc');
            }
        }

        /** navigation setting */
        if(config('settings.post_navigation') == 0) {
            $posts = $posts->get(intval(config('settings.num_post_per_page')));
        } else if(config('settings.post_navigation') == 1) {
            $posts = $posts->paginate(intval(config('settings.num_post_per_page')));
        } else if(config('settings.post_navigation') == 2) {
            $posts = $posts->simplePaginate(intval(config('settings.num_post_per_page')));
        }

        $view = View::make('post.index')
            ->with('posts', $posts)
            ->with('categories', $this->buildCategory())
            ->with('archives', $this->archives())
            ->with('tags', $this->tags());

        if(isset($breadcrumbCategories)) {
            $view = $view->with('breadcrumbCategories', $breadcrumbCategories);
        }

        if($this->request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();

        return $view;
    }

    public function show(Request $request)
    {
        $post = Post::where('visibility', 1)->where('approved', 1)->where('id', $this->request->route('id'))->firstOrFail();

        $options = groupPermission($post->access);

        if(isset($options[Role::groupId()])) {
            $permission = $options[Role::groupId()];
            switch ($permission) {
                case 1:
                    $post->allow_comments = 0;
                    break;
                case 2:
                    $post->allow_comments = 1;
                    break;
                case 3:
                    abortNoPermission();
                    break;
            }
        }

        $post->user = User::findOrFail($post->user_id);
        $post->categories = Category::whereIn('id', explode(',', $post->category))->get();

        if(isset($post->categories[0])) {
            $breadcrumbCategories = $this->breadcrumb($post->categories[0]->id);
        }

        if (stripos ( $post->full_content, "[hide" ) !== false ) {
            $post->full_content = preg_replace_callback ( "#\[hide(.*?)\](.+?)\[/hide\]#is",
                function ($matches) {
                    $matches[1] = str_replace(array("=", " "), "", $matches[1]);
                    if( Role::getValue('blog_allow_hide') ){
                        return $matches[2];
                    }  else {
                        return "<div class=\"alert alert-danger\">" . __('blog.prohibited_of_viewing') . "</div>";
                    }
                }, $post->full_content );
        }

        $post->full_content = preg_replace( "'\[attachment=(.*?)\:(.*?)\]'si", "<span class=\"attachment\"><a href=\"" . route('frontend.post.download.attachment', ['id' => $post->id, 'attachment-id' => 111]) . "\" target=\"_blank\">$2</a> [306.84 Kb] (downloads: 2)</span>", $post->full_content );

        $news_seiten = explode( "<!-- pagebreak -->", $post->full_content );

        $paginator = $this->paginate($news_seiten);
        $paginator->setPath(url()->current());

        if($paginator->currentPage() > count($news_seiten)) {
            abort(404);
        }

        $post->full_content = $news_seiten[$paginator->currentPage() - 1];


        $view = View::make('post.show')
            ->with('post', $post)
            ->with('pages', $paginator->links());

        if(isset($breadcrumbCategories)) {
            $view = $view->with('breadcrumbCategories', $breadcrumbCategories);
        }



        /** Setup poll */

        $poll = Poll::where('object_type', 'post')->where('object_id', $post->id)->where('visibility', 1)->first();

        if ($poll) {
            $poll->result = Poll::buildResult($poll);
            $view = $view->with('poll', $poll);
        }

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        /** Set meta tags */
        MetaTag::set('title', $post->meta_title ? $post->meta_title : $post->title);
        MetaTag::set('description', $post->meta_description ? $post->meta_description : $post->short_content);
        MetaTag::set('keywords', $post->meta_keywords ? $post->meta_keywords : keywordGenerator($post->full_content));

        isset($post->artwork) && MetaTag::set('image', url($post->artwork));

        /** increment view num */
        $post->increment('view_count', 1);

        return $view;
    }

    private function buildCategory(){
        if(Cache::has('categories') && count(Cache::get('categories'))) {
            $categories = Cache::get('categories');
        } else {
            $categories = (new Category)->get();
            Cache::forever('categories', $categories);
        }

        $categories = $categories->map(function($row) {
            $row->news_num = DB::table('posts')->whereRaw("category REGEXP '(^|,)(" . $row->id . ")(,|$)'")->count();
            return $row;
        });

        return $categories;
    }

    private function archives(){
        return DB::table('posts')->select(DB::raw('count(*) as count'), DB::raw('DATE_FORMAT(created_at, \'%Y-%m-%d\') as created_at'))->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))->get();
    }

    private function tags(){
        if(Cache::has('post_tags'))
        {
            return Cache::get('post_tags');
        } else {
            $tags =  DB::table('post_tags')
                ->select('tag', DB::raw('count(*) as count'))
                ->groupBy('tag')
                ->orderBy('count', 'desc')
                ->limit(15)
                ->get();
            Cache::forever('post_tags', $tags);

            return $tags;
        }
    }
}