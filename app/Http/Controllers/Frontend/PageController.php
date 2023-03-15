<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-19
 * Time: 23:42
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Page;
use View;
use MetaTag;

class PageController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if( $this->request->is('api*') ) {
            $page = Page::findOrFail($this->request->route('id'));
        } else {
            $page = Page::where('alt_name', $this->request->route('slug'))->first();
        }

        if (isset($page->id))
        {
            $this->page = $page;
        } else {
            abort(404);
        }

        if( $this->request->is('api*') )
        {
            return response()->json($this->page);
        }

        $view = View::make('page.index')
            ->with('page', $page);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        MetaTag::set('title', $page->meta_title ? $page->meta_title : $page->title);
        MetaTag::set('description', $page->meta_description ? $page->meta_description : $page->content);
        MetaTag::set('keywords', $page->meta_keywords);

        echo ($view);

        die();

        return $view;
    }
}