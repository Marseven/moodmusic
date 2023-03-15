<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 7/23/19
 * Time: 12:17 PM
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;
use Cache;
use Image;

class CategoriesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        if(! Cache::has('categories')) {
            $catInfo = Category::all();
            Cache::forever('categories', $catInfo);
        }

        return view('backend.categories.index')->with('nestable_categories', $this->displayCategories());
    }

    private function displayCategories($parentId = 0, $subLevel = false) {

        if(! Cache::has('categories')) abort('403', 'Can not get categories cache');

        $catInfo = Cache::get('categories')->toArray();
        /** re-arrange array */
        $array = array();

        foreach ($catInfo as $row) {
            $array[$row['id']] = $row;
        }

        $catInfo = $array;
        $cat_item = "";
        $root_category = array();

        if( count( $catInfo ) ) {

            foreach ( $catInfo as $cats ) {
                if( $cats['parent_id'] == $parentId ) $root_category[] = $cats['id'];
            }

            if( count( $root_category ) ) {

                foreach ( $root_category as $id ) {
                    $cat_item .= "<li class=\"dd-item dd3-item\" data-id=\"{$catInfo[$id]['id']}\"><div class=\"dd-handle dd3-handle\"></div><div class=\"dd3-content\"><a href=\"" . route('backend.categories.edit', ['id' => $catInfo[$id]['id']]) . "\">{$catInfo[$id]['name']}</a></div><div class=\"dd3-action\"><a class=\"row-button upload\" href=\"" . route('backend.categories.edit', ['id' => $catInfo[$id]['id']]) . "\"><i class=\"fa fa-fw fa-edit\"></i></a><a class=\"row-button edit\" href=\"" . route('backend.categories.delete', ['id' => $catInfo[$id]['id']]) . "\"><i class=\"fa fa-fw fa-trash\"></i></a></div>";
                    $cat_item .= $this->displayCategories($id, true);
                    $cat_item .= "</li>";
                }

                if( $subLevel ) return "<ol class=\"dd-list\">" . $cat_item . "</ol>"; else return $cat_item;

            }
        }

    }

    public function delete()
    {
        Category::where('id', $this->request->route('id'))->delete();
        Cache::clear('categories');
        return redirect()->route('backend.categories')->with('status', 'success')->with('message', 'Category successfully deleted!');
    }

    public function cartSort(){
        $this->request->validate([
            'list' => 'required|json',

        ]);

        $list = $this->request->input('list');
        $list = json_decode(stripslashes($list), true);
        if ( !is_array($list) ) die ("error");
        $list = parseJsonArray($list);
        foreach ($list as $value) {
            $id = intval($value['id']);
            $parentId = intval($value['parent_id']);
            if ( $id ) {
                DB::table('categories')
                    ->where('id', $id)
                    ->update(['parent_id' => $parentId]);

            }
        }

        Cache::clear('categories');

        return redirect()->route('backend.categories')->with('status', 'success')->with('message', 'Category successfully re-arranged!');
    }

    public function add()
    {
        return view('backend.categories.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|unique:categories',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        if($this->request->input('alt_name'))
        {
            $this->request->validate([
                'alt_name' => 'required|string|unique:categories',
            ]);
        }


        $category = new Category();
        $category->fill($this->request->except('_token'));
        $category->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);
        $category->alt_name = str_slug($this->request->input('alt_name'));

        if(! $category->alt_name) {
            $category->alt_name = str_slug($category->name);
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $category->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $category->save();

        if ($category) {
            Cache::clear('categories');
            return redirect()->route('backend.categories')->with('status', 'success')->with('message', 'Category successfully added!');
        }

    }

    public function edit()
    {
        $category = Category::findOrFail($this->request->route('id'));

        if(! isset($category->id)) abort(404);

        Cache::clear('categories');

        return view('backend.categories.form')
            ->with('category', $category);
    }

    public function editPost()
    {
        $this->request->validate([
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        $category = Category::findOrFail($this->request->route('id'));

        $name = $this->request->input('name');
        $alt_name = $this->request->input('alt_name');

        if($category->name != $name) {
            $this->request->validate([
                'name' => 'required|string|unique:categories',

            ]);
        }

        if($alt_name && $category->alt_name != $alt_name) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:categories',

            ]);
        }

        $category->fill($this->request->except('_token'));
        $category->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);
        $category->alt_name = str_slug($this->request->input('alt_name'));

        if(! $category->alt_name) {
            $category->alt_name = str_slug($category->name);
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $category->clearMediaCollection('artwork');
            $category->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $category->save();
        Cache::clear('categories');

        return redirect()->route('backend.categories')->with('status', 'success')->with('message', 'Category successfully edited!');
    }
}