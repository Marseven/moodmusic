<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\CountryLanguage;
use Image;
use App\Models\Country;

class CountryLanguagesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $languages = CountryLanguage::withoutGlobalScopes();

        if ($this->request->has('region'))
        {
            $languages = $languages->leftJoin('country', 'countrylanguage.country_code', '=', 'country.code')
                ->select('countrylanguage.*', 'country.id as host_id', 'country.name as host_name')
                ->where('country.region_id', intval($this->request->input('region')));
        }

        if ($this->request->has('term'))
        {
            $languages = $languages->where('countrylanguage.name', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->has('fixed'))
        {
            $languages = $languages->where('countrylanguage.fixed', '=', 1);
        }

        if ($this->request->has('hidden'))
        {
            $languages = $languages->where('countrylanguage.visibility', '=', 0);
        }

        if ($this->request->has('name'))
        {
            $languages = $languages->orderBy('countrylanguage.name', $this->request->input('name'));
        }

        if ($this->request->has('results_per_page'))
        {
            $languages = $languages->paginate(intval($this->request->input('results_per_page')));
        } else {
            $languages = $languages->orderBy('fixed', 'desc')->paginate(20);
        }

        $governmentForms = [];
        foreach(Country::select('government_form')->groupBy('government_form')->get() as $country) {
            $governmentForms = array_merge($governmentForms, array(
                "{$country->government_form}" => $country->government_form
            ));
        }

        return view('backend.country-languages.index')
            ->with('languages', $languages)
            ->with('governmentForms', $governmentForms);
    }

    public function add()
    {
        return view('backend.country-languages.form');
    }

    public function savePost()
    {
        $this->request->validate([
            'country_code' => 'required|string|max:3',
            'name' => 'required|string',
            'is_official' => 'nullable|boolean',
        ]);

        if(request()->route()->getName() == 'backend.country.languages.add.post') {
            $language = new CountryLanguage();
        } else {
            $language = CountryLanguage::findOrFail($this->request->route('id'));
        }

        $this->request->input('is_official') ? $language->is_official = 1 : $language->is_official = 0;
        $language->country_code = $this->request->input('country_code');
        $language->name = $this->request->input('name');
        $this->request->input('fixed') ? $language->fixed = 1 : $language->fixed = 0;

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            if(request()->route()->getName() == 'backend.country.languages.edit.post') {
                $language->clearMediaCollection('artwork');
            }

            $language->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $language->save();

        return redirect()->route('backend.country.languages')->with('status', 'success')->with('message', 'Language successfully edited!');
    }

    public function edit()
    {
        $language = CountryLanguage::findOrFail($this->request->route('id'));
        return view('backend.country-languages.form')->with('language', $language);
    }

    public function delete()
    {
        $language = CountryLanguage::findOrFail($this->request->route('id'));
        $language->delete();

        return redirect()->route('backend.country.languages')->with('status', 'success')->with('message', 'Language successfully deleted!');
    }
    public function massAction()
    {
        if($this->request->input('action') == 'make_hidden') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = CountryLanguage::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Languages successfully saved!');
        } elseif($this->request->input('action') == 'make_visible') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = CountryLanguage::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Languages successfully saved!');
        } elseif($this->request->input('action') == 'fixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = CountryLanguage::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Languages successfully saved!');
        } elseif($this->request->input('action') == 'unfixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = CountryLanguage::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Languages successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = CountryLanguage::withoutGlobalScopes()->where('id', $id)->first();
                $item->delete();
            }

            return redirect()->back()->with('status', 'success')->with('message', 'Languages successfully deleted!');
        }
    }
}