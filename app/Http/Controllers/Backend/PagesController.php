<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Page;
use Auth;

class PagesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function index()
    {
        $pages = Page::paginate(20);

        return view('backend.pages.index')
            ->with('pages', $pages);
    }

    public function delete()
    {
        Page::where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.pages')->with('status', 'success')->with('message', 'Static page successfully deleted!');
    }

    public function add()
    {
        return view('backend.pages.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'title' => 'required|string|unique:pages',
            'alt_name' => 'nullable|string|unique:pages',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
        ]);

        $page = new Page();
        $page->fill($this->request->except('_token'));
        $page->user_id = auth()->user()->id;
        $page->alt_name = str_slug($this->request->input('alt_name'));

        if(! $page->alt_name) {
            $page->alt_name = str_slug($page->title);
        }

        $page->save();

        return redirect()->route('backend.pages')->with('status', 'success')->with('message', 'Static page successfully created!');
    }

    public function edit()
    {
        $page = Page::findOrFail($this->request->route('id'));
        return view('backend.pages.form')->with('page', $page);
    }

    public function editPost()
    {

        $this->request->validate([
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:300',
        ]);

        $page = Page::findOrFail($this->request->route('id'));

        if($this->request->input('alt_name') && $page->alt_name != $this->request->input('alt_name')) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:pages',

            ]);
        }

        if($page->title != $this->request->input('title')) {
            $this->request->validate([
                'title' => 'required|string|unique:pages',

            ]);
        }

        $page->fill($this->request->except('_token'));
        $page->alt_name = str_slug($this->request->input('alt_name'));
        $page->user_id = auth()->user()->id;

        if(! $page->alt_name) {
            $page->alt_name = str_slug($page->name);
        }

        $page->save();

        return redirect()->route('backend.pages')->with('status', 'success')->with('message', 'Static page successfully created!');
    }
}