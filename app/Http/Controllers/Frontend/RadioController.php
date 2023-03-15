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
use Illuminate\Http\Request;
use View;
use App\Models\Radio;
use App\Models\Station;
use App\Models\Slide;
use App\Models\Channel;
use MetaTag;
use DB;

class RadioController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $channels = Channel::where('allow_radio', 1)->orderBy('priority', 'asc')->get();
        $slides = Slide::where('allow_radio', 1)->orderBy('priority', 'asc')->get();
        $radio = Radio::orderBy('priority', 'asc')->get();
        $radio->countries = Country::where('fixed', 1)->get();
        $radio->cities = City::where('fixed', 1)->get();
        $radio->languages = CountryLanguage::where('fixed', 1)->get();

        $radio->regions = DB::table('regions')->get();

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'slides' => json_decode(json_encode($slides)),
                'channels' => json_decode(json_encode($channels)),
                'radio' => $radio,
            ));
        }

        $view = View::make('radio.index')
            ->with('slides', json_decode(json_encode($slides)))
            ->with('channels', json_decode(json_encode($channels)))
            ->with('radio', $radio);

        if($this->request->ajax()) {

            $sections = $view->renderSections();
            return $sections['content'];
        }

        getMetatags();
        return $view;
    }

    public function browse() {

        $browse = new \stdClass();

        $stations = Station::withoutGlobalScopes();

        if($this->request->route()->getName() == 'frontend.radio.browse.languages' || $this->request->route()->getName() == 'api.radio.browse.languages') {
            $browse->languages = CountryLanguage::groupBy('name')->get();
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.by.language' || $this->request->route()->getName() == 'api.radio.browse.by.language') {
            $browse->language = CountryLanguage::findOrFail($this->request->route('id'));
            $browse->countries = Country::leftJoin('countrylanguage', 'countrylanguage.country_code', '=', 'country.code')
                ->select('country.*', 'countrylanguage.id as host_id', 'countrylanguage.name as host_name')
                ->groupBy('country.code')
                ->orderBy('country.fixed', 'desc')
                ->where('countrylanguage.name', $browse->language->name)
                ->get();
            $stations = $stations->where('language_id', $this->request->route('id'));
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.regions' || $this->request->route()->getName() == 'api.radio.browse.regions') {
            $browse->regions = DB::table('regions')->get();
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.by.region' || $this->request->route()->getName() == 'api.radio.browse.by.region') {
            $browse->region = DB::table('regions')->where('alt_name', $this->request->route('slug'))->first();
            $browse->countries = Country::where('region_id', $browse->region->id)->get();
            $browse->languages = CountryLanguage::leftJoin('country', 'countrylanguage.country_code', '=', 'country.code')
                ->select('countrylanguage.*', 'country.id as host_id', 'country.name as host_name')
                ->groupBy('countrylanguage.name')
                ->orderBy('countrylanguage.fixed', 'desc')
                ->where('country.region_id', $browse->region->id)
                ->get();
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.countries' || $this->request->route()->getName() == 'frontend.radio.browse.countries') {
            $browse->countries = Country::all();
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.by.country' || $this->request->route()->getName() == 'api.radio.browse.by.country') {
            $browse->country = Country::where('code', $this->request->route('code'))->firstOrFail();
            $browse->languages = CountryLanguage::leftJoin('country', 'countrylanguage.country_code', '=', 'country.code')
                ->select('countrylanguage.*', 'country.id as host_id', 'country.name as host_name')
                ->groupBy('countrylanguage.name')
                ->orderBy('countrylanguage.fixed', 'desc')
                ->where('country.id', $browse->country->id)
                ->get();
            $browse->cities = City::where('country_code', $browse->country->code)->get();
            $stations = $stations->where('country_code', $this->request->route('code'));
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.by.city' || $this->request->route()->getName() == 'api.radio.browse.by.city') {
            $browse->city = City::findOrFail($this->request->route('id'));
            $stations = $stations->where('city_id', $this->request->route('id'));
        } elseif($this->request->route()->getName() == 'frontend.radio.browse.category' || $this->request->route()->getName() == 'api.radio.browse.category'){
            $browse->category = Radio::where('alt_name',  $this->request->route('slug'))->firstOrFail();
            $browse->channels = json_decode(json_encode(Channel::where('radio', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)')->orderBy('priority', 'asc')->get()));
            $browse->slides = json_decode(json_encode(Slide::where('radio', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)')->orderBy('priority', 'asc')->get()));
            $stations = $stations->where('category', 'REGEXP', '(^|,)(' . $browse->category->id . ')(,|$)');

            MetaTag::set('title', $browse->category->meta_title ? $browse->category->meta_title : $browse->category->name);
            MetaTag::set('description', $browse->category->meta_description ? $browse->category->meta_description : $browse->category->description);
            MetaTag::set('keywords', $browse->category->meta_keywords);
            MetaTag::set('image', $browse->category->artwork_url);
        } else {
            abort(404);
        }

        if ($this->request->has('country'))
        {
            $stations = $stations->where('country_code', '=', $this->request->input('country'));
        }

        if ($this->request->has('city_id'))
        {
            $stations = $stations->where('city_id', '=', $this->request->input('city_id'));
        }

        if ($this->request->has('language_id'))
        {
            $stations = $stations->where('language_id', '=', $this->request->input('language_id'));
        }


        $browse->stations = $stations->paginate(20);

        if( $this->request->is('api*') )
        {
            return response()->json($browse);
        }

        $view = View::make('radio.browse')->with('browse', $browse);

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