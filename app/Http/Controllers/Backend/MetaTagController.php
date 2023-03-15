<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-21
 * Time: 13:17
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use Cache;
use App\Models\Meta;
use Image;
use MetaTag;

class MetaTagController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $metatags = Meta::orderBy('priority', 'asc')->get();
        return view('backend.metatags.index')->with('metatags', $metatags);
    }

    public function sort()
    {
        $metaIds = $this->request->input('metaIds');

        foreach ($metaIds AS $index => $metaId) {
            Meta::where('id', $metaId)->update(['priority' => $index + 1]);
        }

        Cache::forget('metatags');

        return redirect()->route('backend.metatags')->with('status', 'success')->with('message', 'Priority successfully changed!');
    }

    public function delete()
    {
        Meta::where('id', $this->request->route('id'))->delete();

        Cache::forget('metatags');

        return redirect()->route('backend.metatags')->with('status', 'success')->with('message', 'Meta Tag  successfully deleted!');
    }

    public function addPost()
    {
        $this->request->validate([
            'url' => 'required|string|min:1|unique:metatags',
            'title' => 'required|string',
            'page_keywords' => 'nullable|array'
        ]);

        $metatag = new Meta();

        $metatag->url = clearUrlForMetatags($this->request->input('url'));
        $metatag->info = $this->request->input('info');
        $metatag->page_title = $this->request->input('title');
        $metatag->page_description = $this->request->input('description');
        $metatag->page_keywords = implode(",", $this->request->input('keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $metatag->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $metatag->save();

        Cache::forget('metatags');

        return redirect()->route('backend.metatags')->with('status', 'success')->with('message', 'Meta Tag successfully added!');
    }

    public function edit()
    {
        $metatag = Meta::findOrFail($this->request->route('id'));

        MetaTag::set('title', $metatag->page_title);
        MetaTag::set('description', $metatag->page_description);
        MetaTag::set('keywords', $metatag->page_keywords);

        return view('backend.metatags.edit')
            ->with('metatag', $metatag);
    }

    public function editPost()
    {
        $metatag = Meta::findOrFail($this->request->route('id'));

        $url = $this->request->input('url');

        if($metatag->url != $url) {
            $this->request->validate([
                'url' => 'required|string|unique:metatags',
            ]);
        }

        $this->request->validate([
            'title' => 'required|string',
            'page_keywords' => 'nullable|array'
        ]);

        $metatag->url = clearUrlForMetatags($this->request->input('url'));

        $this->request->validate([
            'url' => 'required|string|min:1',
        ]);

        $metatag->info = $this->request->input('info');
        $metatag->page_title = $this->request->input('title');
        $metatag->page_description = $this->request->input('description');
        $metatag->page_keywords = implode(",", $this->request->input('keywords') ?? []);

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $metatag->clearMediaCollection('artwork');
            $metatag->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $metatag->save();

        Cache::forget('metatags');

        return redirect()->route('backend.metatags')->with('status', 'success')->with('message', 'Meta Tag successfully edited!');
    }
}