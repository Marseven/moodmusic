<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Mood;
use DB;
use Image;
use Cache;

class MoodsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $moods = Mood::all();

        return view('backend.moods.index')->with('moods', $moods);
    }

    public function sort()
    {
        $moodIds = $this->request->input('moodIds');

        foreach ($moodIds AS $index => $moodId) {
            Mood::where('id', $moodId)
                ->update(['priority' => $index + 1]);
        }

        Cache::clear('discover');
        return redirect()->route('backend.moods')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        Mood::where('id', $this->request->route('id'))->delete();

        return redirect()->route('backend.moods')->with('status', 'success')->with('message', 'Mood successfully deleted!');
    }

    public function add()
    {
        return view('backend.moods.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|unique:moods',
            'alt_name' => 'nullable|string|unique:moods',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        $mood = new Mood();
        $mood->fill($this->request->except('_token'));
        $mood->alt_name = str_slug($this->request->input('alt_name'));

        if(! $mood->alt_name) {
            $mood->alt_name = str_slug($mood->name);
        }

        $mood->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $mood->clearMediaCollection('artwork');
            $mood->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $mood->save();
        Cache::clear('discover');
        return redirect()->route('backend.moods')->with('status', 'success')->with('message', 'Mood successfully added!');
    }

    public function edit()
    {
        $mood = Mood::findOrFail($this->request->route('id'));

        return view('backend.moods.form')
            ->with('mood', $mood);
    }

    public function editPost()
    {
        $this->request->validate([
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        $mood = Mood::findOrFail($this->request->route('id'));

        if($this->request->input('alt_name') && $mood->alt_name != $this->request->input('alt_name')) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:moods',

            ]);
        }

        if($mood->name != $this->request->input('name')) {
            $this->request->validate([
                'name' => 'required|string|unique:moods',
            ]);
        }

        $mood->fill($this->request->except('_token'));

        $mood->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);
        $mood->alt_name = str_slug($this->request->input('alt_name'));

        if(! $mood->alt_name) {
            $mood->alt_name = str_slug($mood->name);
        }

        if ($this->request->hasFile('artwork'))
        {
            $mood->clearMediaCollection('artwork');
            $mood->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $mood->save();
        Cache::clear('discover');
        return redirect()->route('backend.moods')->with('status', 'success')->with('message', 'Mood successfully edited!');
    }
}