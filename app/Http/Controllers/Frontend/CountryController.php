<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:44
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function countries()
    {
        $countries = Country::where('visibility', 1)->get();

        foreach ($countries as $country) {
            $country->makeHidden(['media', 'continent', 'region_id', 'local_name', 'government_form', 'code2', 'fixed', 'visibility', 'created_at', 'updated_at', 'artwork_url']);
        }

        return response()->json($countries);
    }
}
