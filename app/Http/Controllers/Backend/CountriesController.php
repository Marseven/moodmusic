<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\Country;
use Image;

class CountriesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $countries = Country::withoutGlobalScopes();

        if ($this->request->has('name'))
        {
            $countries = $countries->where('title', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->has('fixed'))
        {
            $countries = $countries->where('fixed', '=', 1);
        }

        if ($this->request->has('hidden'))
        {
            $countries = $countries->where('visibility', '=', 0);
        }

        if ($this->request->has('government_form'))
        {
            $countries = $countries->where('government_form', 'like', '%' . $this->request->input('government_form') . '%');
        }

        if ($this->request->has('region'))
        {
            $regionId = intval($this->request->input('region'));
            $countries = $countries->whereHas('region', function($query) use ($regionId) {
                $query->where('id', '=', $regionId);
            });
        }


        if ($this->request->has('name'))
        {
            $countries = $countries->orderBy('name', $this->request->input('name'));
        }

        if ($this->request->has('results_per_page'))
        {
            $countries = $countries->paginate(intval($this->request->input('results_per_page')));
        } else {
            $countries = $countries->orderBy('fixed', 'desc')->paginate(20);
        }

        $governmentForms = [];
        foreach(Country::withoutGlobalScopes()->select('government_form')->groupBy('government_form')->get() as $country) {
            $governmentForms = array_merge($governmentForms, array(
                "{$country->government_form}" => $country->government_form
            ));
        }

        return view('backend.countries.index')
            ->with('countries', $countries)
            ->with('governmentForms', $governmentForms);
    }

    public function add()
    {
        return view('backend.countries.form');
    }

    public function edit()
    {
        $country = Country::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.countries.form')->with('country', $country);
    }

    public function savePost()
    {
        $this->request->validate([
            'code' => 'required|string|max:3',
            'name' => 'required|string',
            'continent' => 'nullable|string',
            'government_form' => 'nullable|string',
        ]);

        if(request()->route()->getName() == 'backend.countries.add.post') {
            $country = new Country();
        } else {
            $country = Country::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        }

        $country->code = $this->request->input('code');
        $country->name = $this->request->input('name');
        $country->continent = $this->request->input('continent');
        $country->region_id = $this->request->input('region_id');
        $country->government_form = $this->request->input('government_form') ? $this->request->input('government_form') : '';
        $this->request->input('fixed') ? $country->fixed = 1 : $country->fixed = 0;

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            if(request()->route()->getName() == 'backend.countries.edit.post') {
                $country->clearMediaCollection('artwork');
            }

            $country->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(500 * 0.5625))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $country->save();

        return redirect()->route('backend.countries')->with('status', 'success')->with('message', 'Country successfully edited!');
    }

    public function delete()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $country = Country::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $country->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('backend.countries')->with('status', 'success')->with('message', 'Country successfully deleted!');
    }

    public function massAction()
    {

        if($this->request->input('action') == 'make_hidden') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = Country::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Countries successfully saved!');
        } elseif($this->request->input('action') == 'make_visible') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = Country::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Countries successfully saved!');
        } elseif($this->request->input('action') == 'fixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = Country::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Countries successfully saved!');
        } elseif($this->request->input('action') == 'unfixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = Country::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Countries successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = Country::withoutGlobalScopes()->where('id', $id)->first();
                $item->delete();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->with('status', 'success')->with('message', 'Countries successfully deleted!');
        }
    }
}