<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\Radio;
use App\Models\Station;
use Image;

class RadiosController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {
        $radios = Radio::orderBy('priority', 'asc')->get();
        return view('backend.radios.index')->with('radios', $radios);
    }

    public function sort()
    {
        $radioIds = $this->request->input('radioIds');

        foreach ($radioIds AS $index => $radioId) {
            DB::table('radio')
                ->where('id', $radioId)
                ->update(['priority' => $index + 1]);
        }

        return redirect()->route('backend.radios')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        Radio::where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.radios')->with('status', 'success')->with('message', 'Radio category successfully deleted!');
    }

    public function add()
    {
        return view('backend.radios.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|unique:radio',
            'alt_name' => 'nullable|string|unique:radio',
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
            'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);


        $radio = new Radio();
        $radio->fill($this->request->except('_token'));
        $radio->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        $radio->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
            ->usingFileName(time(). '.jpg')
            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

        if(! $radio->alt_name) {
            $radio->alt_name = str_slug($radio->name);
        }

        $radio->save();

        return redirect()->route('backend.radios')->with('status', 'success')->with('message', 'Radio category successfully added!');

    }

    public function edit()
    {
        $radio = Radio::findOrFail($this->request->route('id'));

        return view('backend.radios.form')
            ->with('radio', $radio);
    }

    public function editPost()
    {
        $this->request->validate([
            'description' => 'nullable|string|max:300',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'array',
        ]);

        $radio = Radio::findOrFail($this->request->route('id'));
        $name = $this->request->input('name');
        $alt_name = $this->request->input('alt_name');

        if($radio->name != $name) {
            $this->request->validate([
                'name' => 'required|string|unique:radio',

            ]);
        }

        if($alt_name && $radio->alt_name != $alt_name) {
            $this->request->validate([
                'alt_name' => 'required|string|unique:radio',
            ]);
        }

        $radio->fill($this->request->except('_token'));

        $radio->meta_keywords = implode(",", $this->request->input('meta_keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $radio->clearMediaCollection('artwork');
            $radio->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if(! $alt_name)
        {
            $radio->alt_name = str_slug($name);
        } else {
            $radio->alt_name = str_slug($alt_name);
        }

        $radio->save();

        return redirect()->route('backend.radios')->with('status', 'success')->with('message', 'Radio successfully edited!');
    }
}