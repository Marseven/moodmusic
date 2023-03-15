<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\City;
use App\Models\Country;
use Image;

class CitiesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $cities = City::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            $cities = $cities->where('name', 'like', '%' . $this->request->input('term') . '%');
        }

        if ($this->request->has('fixed'))
        {
            $cities = $cities->where('fixed', '=', 1);
        }

        if ($this->request->has('hidden'))
        {
            $cities = $cities->where('visibility', '=', 0);
        }

        if ($this->request->has('country'))
        {
            $cities = $cities->where('country_code', '=', $this->request->input('country'));
        }

        if ($this->request->has('name'))
        {
            $cities = $cities->orderBy('name', $this->request->input('name'));
        }

        if ($this->request->has('results_per_page'))
        {
            $cities = $cities->paginate(intval($this->request->input('results_per_page')));
        } else {
            $cities = $cities->orderBy('fixed', 'desc')->paginate(20);
        }

        $governmentForms = [];
        foreach(Country::select('government_form')->groupBy('government_form')->get() as $country) {
            $governmentForms = array_merge($governmentForms, array(
                "{$country->government_form}" => $country->government_form
            ));
        }

        return view('backend.cities.index')
            ->with('cities', $cities)
            ->with('governmentForms', $governmentForms);
    }

    public function add()
    {
        return view('backend.cities.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'country_code' => 'required|string|max:3',
        ]);

        $city = new City();

        $city->name = $this->request->input('name');
        $city->country_code = $this->request->input('country_code');
        $this->request->input('fixed') ? $city->fixed = 1 : $city->fixed = 0;

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $city->clearMediaCollection('artwork');
            $city->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $city->save();

        return redirect()->route('backend.cities')->with('status', 'success')->with('message', 'City successfully added!');

    }

    public function edit()
    {
        $city = City::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.cities.form')->with('city', $city);
    }

    public function editPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'country_code' => 'required|string|max:3',
        ]);

        $city = City::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        $city->name = $this->request->input('name');
        $city->country_code = $this->request->input('country_code');
        if($this->request->input('fixed')) {
            $city->fixed = 1;
        } else {
            $city->fixed = 0;
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $city->clearMediaCollection('artwork');
            $city->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $city->save();

        return redirect()->route('backend.cities')->with('status', 'success')->with('message', 'City successfully edited!');
    }

    public function delete()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $city = City::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        $city->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('backend.cities')->with('status', 'success')->with('message', 'City successfully deleted!');
    }

    public function massAction()
    {

        if($this->request->input('action') == 'make_hidden') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = City::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Cities successfully saved!');
        } elseif($this->request->input('action') == 'make_visible') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = City::withoutGlobalScopes()->where('id', $id)->first();
                $item->visibility = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Cities successfully saved!');
        } elseif($this->request->input('action') == 'fixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = City::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 1;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Cities successfully saved!');
        } elseif($this->request->input('action') == 'unfixed') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = City::withoutGlobalScopes()->where('id', $id)->first();
                $item->fixed = 0;
                $item->save();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Cities successfully saved!');
        } else if($this->request->input('action') == 'delete') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $item = City::withoutGlobalScopes()->where('id', $id)->first();
                $item->delete();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->with('status', 'success')->with('message', 'Cities successfully deleted!');
        }
    }
}