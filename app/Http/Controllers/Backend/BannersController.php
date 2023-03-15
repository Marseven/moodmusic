<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:01
 */

namespace App\Http\Controllers\Backend;

use App\Models\BannerTrack;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Banner;
use Image;
use Cache;

class BannersController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $banners = Banner::paginate(20);

        return view('backend.banners.index')->with('banners', $banners);
    }

    public function delete()
    {
        Banner::where('id', '=', $this->request->route('id'))->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'banners successfully deleted!');
    }

    public function add()
    {
        $countries = Country::all();

        return view('backend.banners.form')->with('countries', $countries);
    }

    public function addPost()
    {
        $this->request->validate([
            'banner_tag' => 'required|string|alpha_dash|regex:/^[a-z0-9_]+$/|min:4|max:30',
            'description' => 'required|string',
            'started_at' => 'nullable|date_format:Y/m/d H:i',
            'ended_at' => 'nullable|date_format:Y/m/d H:i|after:' . Carbon::now(),
            'code' => 'nullable|string',
        ]);

        $banner = new Banner();

        $banner->banner_tag = $this->request->input('banner_tag');
        $banner->description = $this->request->input('description');

        if (env('MEDIA_AD_MODULE') == 'true') {
            $banner->age_from = $this->request->input('age_from');
            $banner->age_to = $this->request->input('age_to');
            $banner->gender = $this->request->input('gender');
            $banner->country = $this->request->input('country');
            $banner->skippable = $this->request->input('skippable') ? 1 : 0;
        }

        if($this->request->input('started_at'))
        {
            $banner->started_at = Carbon::parse($this->request->input('started_at'));
        }

        if($this->request->input('type') && intval($this->request->input('type')) > 0)
        {
            $this->request->validate([
                'file' => 'required',
            ]);

            $banner->type = intval($this->request->input('type'));
            $banner->addMedia($this->request->file('file')->getPathName())->usingFileName($this->request->file('file')->getClientOriginalName(), PATHINFO_FILENAME)->toMediaCollection('file');
        }

        if($this->request->input('ended_at'))
        {
            $banner->ended_at = Carbon::parse($this->request->input('ended_at'));
        }

        $banner->approved = $this->request->input('disabled') ? 0 : 1;
        $banner->code = $this->request->input('code');
        $banner->save();

        Cache::forget('banners');

        return redirect()->route('backend.banners')->with('status', 'success')->with('message', 'Banner successfully added!');
    }

    public function edit()
    {
        $countries = Country::all();
        $banner = Banner::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        return view('backend.banners.form')
            ->with('banner', $banner)->with('countries', $countries);
    }

    public function editPost()
    {
        $this->request->validate([
            'banner_tag' => 'required|string|alpha_dash|regex:/^[a-z0-9_]+$/|min:4|max:30',
            'description' => 'required|string',
            'started_at' => 'nullable|date_format:Y/m/d H:i',
            'ended_at' => 'nullable|date_format:Y/m/d H:i|after:' . Carbon::now(),
            'code' => 'nullable|string',
        ]);

        $banner = Banner::findOrFail($this->request->route('id'));

        $banner->banner_tag = $this->request->input('banner_tag');
        $banner->description= $this->request->input('description');

        if($this->request->input('type') && intval($this->request->input('type')) > 0)
        {
            $banner->type = intval($this->request->input('type'));
            if ($this->request->hasFile('file')) {
                $banner->clearMediaCollection('file');
                $banner->addMedia($this->request->file('file')->getPathName())->usingFileName($this->request->file('file')->getClientOriginalName(), PATHINFO_FILENAME)->toMediaCollection('file');
            }
        } else {
            $banner->type = 0;
        }

        if (env('MEDIA_AD_MODULE') == 'true') {
            $banner->age_from = $this->request->input('age_from');
            $banner->age_to = $this->request->input('age_to');
            $banner->gender = $this->request->input('gender');
            $banner->country = $this->request->input('country');
            $banner->skippable = $this->request->input('skippable') ? 1 : 0;
        }

        if($this->request->input('started_at'))
        {
            $banner->started_at = Carbon::parse($this->request->input('started_at'));
        }

        if($this->request->input('ended_at'))
        {
            $banner->ended_at = Carbon::parse($this->request->input('ended_at'));
        }

        $banner->approved = $this->request->input('disabled') ? 0 : 1;
        $banner->code = $this->request->input('code');
        $banner->save();

        Cache::forget('banners');

        return redirect()->route('backend.banners')->with('status', 'success')->with('message', 'Banner successfully edited!');
    }

    public function disable()
    {
        $banner = Banner::findOrFail($this->request->route('id'));
        $banner->approved = ! $banner->approved;
        $banner->save();

        Cache::forget('banners');
        return redirect()->route('backend.banners')->with('status', 'success')->with('message', 'Banner successfully edited!');
    }

    public function reports()
    {
        /** Get subscriptions charts */

        $fromDate = Carbon::now()->subMonth(1)->format('Y/m/d H:i:s');
        $toDate = Carbon::now()->format('Y/m/d H:i:s');

        $data = $this->getData($fromDate, $toDate);

        return view('backend.banners.reports')
            ->with('day', $data->day)
            ->with('data', $data);
    }

    public function reportByPeriod(){
        $fromDate = Carbon::parse(($this->request->input('from')))->format('Y/m/d H:i:s');
        $toDate = Carbon::parse(($this->request->input('to')))->format('Y/m/d H:i:s');
        if(strtotime($fromDate) > strtotime($toDate)) {
            return redirect()->route('backend.reports')->with('status', 'failed')->with('message', 'From date should not be bigger then To date.');

        }

        $data = $this->getData($fromDate, $toDate);

        return view('backend.banners.reports')
            ->with('day', $data->day)
            ->with('fromDate', Carbon::parse($fromDate)->format('Y/m/d H:i'))
            ->with('toDate', Carbon::parse($toDate)->format('Y/m/d H:i'))
            ->with('data', $data);
    }

    private function getData($fromDate, $toDate){
        $data = new \stdClass();

        $data->total_clicks = BannerTrack::where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->count();

        $subscriptions_data = DB::table('banner_tracks')
            ->select(DB::raw('count(*) AS earnings'), DB::raw('DATE(created_at) as date'))
            ->where('created_at', '<=', $toDate)
            ->where('created_at', '>=', $fromDate)
            ->groupBy('date')
            ->get();

        $data->countries = DB::table('banner_tracks')
            ->select(DB::raw('count(*) AS count'), 'country_code')
            ->where('created_at', '<=', $toDate)
            ->where('created_at', '>=', $fromDate)
            ->groupBy('country_code')
            ->get();

        $data->gender = DB::table('banner_tracks')
            ->select(DB::raw('count(*) AS count'), 'gender')
            ->where('created_at', '<=', $toDate)
            ->where('created_at', '>=', $fromDate)
            ->groupBy('gender')
            ->get();

        $ranges = [
            '0-18' => 18,
            '25-35' => 25,
            '36-45' => 36,
            '46-56' => 46,
            '56-65' => 56,
            '66+' => 66
        ];

        $data->age = BannerTrack::get()
            ->map(function ($people) use ($ranges) {
                $age = $people->age;
                foreach($ranges as $key => $breakpoint)
                {
                    if ($breakpoint >= $age)
                    {
                        $people->range = $key;
                        break;
                    }
                }

                return $people;
            })
            ->mapToGroups(function ($people, $key) {
                return [$people->range => $people];
            })
            ->map(function ($group) {
                return count($group);
            })
            ->sortKeys();


        $rows = insertMissingData($subscriptions_data, ['earnings'], $fromDate, $toDate);

        $data->day = new \stdClass();
        $data->day->earnings = array();
        $data->day->period = array();

        foreach ($rows as $item) {
            $item = (array) $item;
            $data->day->earnings[] = $item['earnings'];
            $data->day->period[] = Carbon::parse($item['date'])->format('F j');
        }

        return $data;
    }
}