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
use App\Models\Radio;
use App\Models\Station;
use Image;

class StationsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $stations = Station::withoutGlobalScopes();

        if ($this->request->has('term'))
        {
            if($this->request->has('location')) {
                switch ($this->request->input('location')) {
                    case 0:
                        $stations = $stations->search($this->request->input('term'));
                        break;
                    case 1:
                        $stations = $stations->where('title', 'like', '%' . $this->request->input('term') . '%');
                        break;
                    case 2:
                        $stations = $stations->where('description', 'like', '%' . $this->request->input('term') . '%');
                        break;
                }
            } else {
                $stations = $stations->where('title', 'like', '%' . $this->request->input('term') . '%');
            }

        }

        if ($this->request->input('userIds') && is_array($this->request->input('userIds')))
        {
            $stations = $stations->where(function ($query) {
                foreach($this->request->input('userIds') as $index => $userId) {
                    if($index == 0) {
                        $query->where('user_id', '=', $userId);
                    } else {
                        $query->orWhere('user_id', '=', $userId);
                    }
                }
            });
        }

        if ($this->request->input('category') && is_array($this->request->input('category')))
        {
            $stations = $stations->where('category', 'REGEXP', '(^|,)(' . implode(',', $this->request->input('category')) . ')(,|$)');
        }

        if ($this->request->input('created_from'))
        {
            $stations = $stations->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $stations = $stations->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $stations = $stations->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $stations = $stations->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('fixed'))
        {
            $stations = $stations->where('fixed', '=', 1);
        }

        if ($this->request->has('comment_disabled'))
        {
            $stations = $stations->where('allow_comments', '=', 0);
        }

        if ($this->request->has('hidden'))
        {
            $stations = $stations->where('visibility', '=', 0);
        }

        if ($this->request->has('country'))
        {
            $stations = $stations->where('country_code', '=', $this->request->input('country'));
        }

        if ($this->request->has('city'))
        {
            $stations = $stations->where('city_id', '=', $this->request->input('city'));
        }

        if ($this->request->has('language'))
        {
            $stations = $stations->where('language_id', '=', $this->request->input('language'));
        }

        if ($this->request->has('title'))
        {
            $stations = $stations->orderBy('title', $this->request->input('title'));
        }

        if ($this->request->has('created_at'))
        {
            $stations = $stations->orderBy('created_at', $this->request->input('created_at'));
        }

        if ($this->request->has('results_per_page'))
        {
            $stations = $stations->paginate(intval($this->request->input('results_per_page')));
        } else {
            $stations = $stations->orderBy('failed_count', 'desc')->paginate(20);
        }

        return view('backend.stations.index')
            ->with('stations', $stations);
    }

    public function add()
    {
        return view('backend.stations.form');
    }

    public function edit()
    {
        $station = Station::findOrFail($this->request->route('id'));
        return view('backend.stations.form')->with('station', $station);
    }

    public function savePost()
    {
        $this->request->validate([
            'title' => 'required|string',
            'stream_url' => 'required|string',
        ]);

        if(request()->route()->getName() == 'backend.stations.add.post') {
            $station = new Station();
        } else {
            $station = Station::findOrFail($this->request->route('id'));
        }

        $station->title = $this->request->input('title');
        $station->description = $this->request->input('description');
        $station->stream_url = $this->request->input('stream_url');
        $station->country_code = $this->request->input('country_code');
        $station->city_id = $this->request->input('city_id');
        $station->language_id = $this->request->input('language_id');
        $category = $this->request->input('category');

        if(is_array($category))
        {
            $station->category = implode(",", $this->request->input('category'));
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            if(request()->route()->getName() == 'backend.stations.edit.post') {
                $station->clearMediaCollection('artwork');
            }

            $station->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $station->save();

        return redirect()->route('backend.stations')->with('status', 'success')->with('message', 'Station successfully edited!');
    }

    public function delete()
    {
        $station = Station::findOrFail($this->request->route('id'));
        $station->delete();
        return redirect()->route('backend.stations')->with('status', 'success')->with('message', 'Station successfully deleted!');
    }

    public function cityByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCityDropDown($this->request->input('countryCode'), 'city_id', 'form-control select2-active', $selected = null);
    }

    public function languageByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCountryLanguageDropDown($this->request->input('countryCode'), 'language_id', 'form-control select2-active', $selected = null);
    }
}