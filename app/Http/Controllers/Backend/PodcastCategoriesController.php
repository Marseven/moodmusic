<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\PodcastCategory;
use Image;

class PodcastCategoriesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {
        $categories = PodcastCategory::orderBy('priority', 'asc')->get();
        return view('backend.podcast-categories.index')->with('categories', $categories);
    }

    public function sort()
    {
        $categoryIds = $this->request->input('categoryIds');

        foreach ($categoryIds AS $index => $categoryId) {
            DB::table('podcast_categories')
                ->where('id', $categoryId)
                ->update(['priority' => $index + 1]);
        }

        return redirect()->route('backend.podcast-categories')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        PodcastCategory::where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.podcast-categories')->with('status', 'success')->with('message', 'Podcast Category successfully deleted!');
    }

    public function add()
    {
        return view('backend.podcast-categories.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|unique:podcast_categories',
            'alt_name' => 'nullable|string|unique:podcast_categories',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
            'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);

        $category = new PodcastCategory();

        $category->fill($this->request->except('_token'));
        $category->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        $category->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
            ->usingFileName(time(). '.jpg')
            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

        if(! $category->alt_name) {
            $category->alt_name = str_slug($category->name);
        }

        $category->save();

        return redirect()->route('backend.podcast-categories')->with('status', 'success')->with('message', 'Podcast Category successfully added!');
    }

    public function edit()
    {
        $category = PodcastCategory::findOrFail($this->request->route('id'));

        return view('backend.podcast-categories.form')
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

        $category = PodcastCategory::findOrFail($this->request->route('id'));
        $name = $this->request->input('name');
        $alt_name = $this->request->input('alt_name');

        if($category->name != $name) {
            $this->request->validate([
                'name' => 'required|string|unique:category',

            ]);
        }

        if($alt_name && $category->alt_name != $alt_name) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:category',
            ]);
        }

        $category->fill($this->request->except('_token'));

        $category->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $category->clearMediaCollection('artwork');
            $category->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if(! $alt_name)
        {
            $category->alt_name = str_slug($name);
        } else {
            $category->alt_name = str_slug($alt_name);
        }

        $category->save();

        return redirect()->route('backend.podcast-categories')->with('status', 'success')->with('message', 'Radio successfully edited!');
    }
}