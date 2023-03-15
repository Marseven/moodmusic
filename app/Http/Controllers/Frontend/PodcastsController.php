<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-10
 * Time: 22:03
 */

namespace App\Http\Controllers\Frontend;

use App\Models\City;
use App\Models\Country;
use App\Models\CountryLanguage;
use App\Models\Podcast;
use App\Models\PodcastCategory;
use Illuminate\Http\Request;
use View;
use App\Models\Radio;
use App\Models\Station;
use App\Models\Slide;
use App\Models\Channel;
use MetaTag;
use DB;

class PodcastsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $channels = Channel::where('allow_podcasts', 1)->orderBy('priority', 'asc')->get();
        $slides = Slide::where('allow_podcasts', 1)->orderBy('priority', 'asc')->get();

        $podcasts = new \stdClass();
        $podcasts->categories = PodcastCategory::orderBy('priority', 'asc')->get();
        $podcasts->countries = Country::where('fixed', 1)->get();
        $podcasts->languages = CountryLanguage::where('fixed', 1)->get();
        $podcasts->regions = DB::table('regions')->get();

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'slides' => json_decode(json_encode($slides)),
                'channels' => json_decode(json_encode($channels)),
                'podcasts' => $podcasts,
            ));
        }

        $view = View::make('podcasts.index')
            ->with('slides', json_decode(json_encode($slides)))
            ->with('channels', json_decode(json_encode($channels)))
            ->with('podcasts', $podcasts);

        if($this->request->ajax()) {

            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();
        return $view;
    }

    public function browse() {
        $browse = new \stdClass();
        $podcasts = Podcast::withoutGlobalScopes();

        $routeRoot = 'frontend';

        if($this->request->is('api*') || $this->request->wantsJson())
        {
            $routeRoot = 'api';
        }

        if($this->request->route()->getName() == $routeRoot . '.podcasts.browse.languages') {
            $browse->languages = CountryLanguage::groupBy('name')->get();
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.by.language') {
            $browse->language = CountryLanguage::findOrFail($this->request->route('id'));
            $browse->countries = Country::leftJoin('countrylanguage', 'countrylanguage.country_code', '=', 'country.code')
                ->select('country.*', 'countrylanguage.id as host_id', 'countrylanguage.name as host_name')
                ->groupBy('country.code')
                ->orderBy('country.fixed', 'desc')
                ->where('countrylanguage.name', $browse->language->name)
                ->get();
            $podcasts = $podcasts->where('language_id', $this->request->route('id'));
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.regions') {
            $browse->regions = DB::table('regions')->get();
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.by.region') {
            $browse->region = DB::table('regions')->where('alt_name', $this->request->route('slug'))->first();
            $browse->countries = Country::where('region_id', $browse->region->id)->get();
            $browse->languages = CountryLanguage::leftJoin('country', 'countrylanguage.country_code', '=', 'country.code')
                ->select('countrylanguage.*', 'country.id as host_id', 'country.name as host_name')
                ->groupBy('countrylanguage.name')
                ->orderBy('countrylanguage.fixed', 'desc')
                ->where('country.region_id', $browse->region->id)
                ->get();
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.countries') {
            $browse->countries = Country::all();
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.by.country') {
            $browse->country = Country::where('code', $this->request->route('code'))->firstOrFail();
            $browse->languages = CountryLanguage::leftJoin('country', 'countrylanguage.country_code', '=', 'country.code')
                ->select('countrylanguage.*', 'country.id as host_id', 'country.name as host_name')
                ->groupBy('countrylanguage.name')
                ->orderBy('countrylanguage.fixed', 'desc')
                ->where('country.id', $browse->country->id)
                ->get();
            $browse->cities = City::where('country_code', $browse->country->code)->get();
            $podcasts = $podcasts->where('country_code', $this->request->route('code'));
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.by.city') {
            $browse->city = City::findOrFail($this->request->route('id'));
            $podcasts = $podcasts->where('city_id', $this->request->route('id'));
        } elseif($this->request->route()->getName() == $routeRoot . '.podcasts.browse.category'){
            $browse->category = PodcastCategory::where('alt_name',  $this->request->route('slug'))->first();
            $browse->channels = json_decode(json_encode(Channel::where('podcast', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)')->orderBy('priority', 'asc')->get()));
            $browse->slides = json_decode(json_encode(Slide::where('podcast', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)')->orderBy('priority', 'asc')->get()));
            $podcasts = $podcasts->where('category', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)');

            MetaTag::set('title', $browse->category->meta_title ? $browse->category->meta_title : $browse->category->name);
            MetaTag::set('description', $browse->category->meta_description ? $browse->category->meta_description : $browse->category->description);
            MetaTag::set('keywords', $browse->category->meta_keywords);
            MetaTag::set('image', $browse->category->artwork_url);
        } else {
            abort(404);
        }

        if ($this->request->has('country'))
        {
            $podcasts = $podcasts->where('country_code', '=', $this->request->input('country'));
        }

        if ($this->request->has('language_id'))
        {
            $podcasts = $podcasts->where('language_id', '=', $this->request->input('language_id'));
        }

        $browse->podcasts = $podcasts->with('artist')->paginate(20);

        if( $this->request->is('api*') )
        {
            return response()->json($browse);
        }

        $view = View::make('podcasts.browse')->with('browse', $browse);


        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if(($this->request->input('page') && intval($this->request->input('page')) > 1) || (($this->request->input('country') || $this->request->input('city_id')  || $this->request->input('language_id')) && $this->request->input('browsing')))
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        return $view;

    }
    public function categories()
    {
        $radio = Radio::all();

        if( $this->request->is('api*') )
        {
            return response()->json($radio);
        }
    }

    public function stations()
    {

        $stations = (new Station)->get('stations.category = ?', $this->request->route('id'));

        if( $this->request->is('api*') )
        {
            return response()->json($stations);
        }

        getMetatags();
    }

    public function cityByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCityDropDown($this->request->input('countryCode'), 'city_id', 'toolbar-filter-city-select2', $selected = null);
    }

    public function languageByCountryCode()
    {
        $this->request->validate([
            'countryCode' => 'required|string|max:3'
        ]);

        return makeCountryLanguageDropDown($this->request->input('countryCode'), 'language_id', 'toolbar-filter-language-select2', $selected = null);
    }
}