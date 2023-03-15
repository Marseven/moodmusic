<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Genre;
use DB;
use Image;
use Cache;

class GenresController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $genres = Genre::orderBy('discover', 'desc')->orderBy('priority', 'asc');

        if ($this->request->has('term'))
        {
            $genres = $genres->where('name', 'like', '%' . $this->request->input('term') . '%');
        }

        $genres = $genres->paginate(50);

        return view('backend.genres.index')->with('genres', $genres);
    }

    public function sort()
    {
        $genreIds = $this->request->input('genreIds');

        foreach ($genreIds AS $index => $genreId) {
            DB::table('genres')
                ->where('id', $genreId)
                ->update(['priority' => $index + 1]);
        }

        Cache::clear('discover');

        return redirect()->route('backend.genres')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        Genre::where('id', $this->request->route('id'))->delete();
        Cache::clear('discover');
        return redirect()->route('backend.genres')->with('status', 'success')->with('message', 'Genre successfully deleted!');
    }

    public function add()
    {
        return view('backend.genres.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|unique:genres',
            'alt_name' => 'nullable|string|unique:genres',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|array|max:300',
            'artwork' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 10240)
        ]);

        $genre = new Genre();
        $genre->fill($this->request->except('_token'));
        $genre->alt_name = str_slug($this->request->input('alt_name'));

        if(! $genre->alt_name) {
            $genre->alt_name = str_slug($genre->name);
        }

        if($this->request->input('discover')) {
            $genre->discover = 1;
        } else {
            $genre->discover = 0;
        }

        $genre->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);
        $genre->save();

        if ($this->request->hasFile('artwork'))
        {
            $genre->clearMediaCollection('artwork');
            $genre->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        Cache::clear('discover');

        return redirect()->route('backend.genres')->with('status', 'success')->with('message', 'Genre successfully added!');
    }

    public function edit()
    {
        $genre = Genre::findOrFail($this->request->route('id'));

        return view('backend.genres.form')
            ->with('genre', $genre);
    }

    public function editPost()
    {
        $this->request->validate([
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        $genre = Genre::findOrFail($this->request->route('id'));

        if($this->request->input('alt_name') && $genre->alt_name != $this->request->input('alt_name')) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:genres',

            ]);
        }

        if($genre->name != $this->request->input('name')) {
            $this->request->validate([
                'name' => 'required|string|unique:genres',

            ]);
        }

        $genre->fill($this->request->except('_token'));
        $genre->alt_name = str_slug($this->request->input('alt_name'));

        if(! $genre->alt_name) {
            $genre->alt_name = str_slug($genre->name);
        }

        if($this->request->input('discover')) {
            $genre->discover = 1;
        } else {
            $genre->discover = 0;
        }

        $genre->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 10240)
            ]);

            $genre->clearMediaCollection('artwork');
            $genre->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $genre->save();
        Cache::clear('discover');

        return redirect()->route('backend.genres')->with('status', 'success')->with('message', 'Genre successfully edited!');
    }
}