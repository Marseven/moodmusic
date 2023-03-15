<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-30
 * Time: 19:31
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Lang;
use App;
use Filesystem;
use File;

class LanguageController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function switchLanguage()
    {
        $this->request->validate([
            'locale' => 'required|string'
        ]);

        \Session::put('website_language', $this->request->input('locale'));
        App::setLocale($this->request->input('locale'));
        $lang = Lang::get('web');
        return response()->json($lang);
    }

    public function currentLanguage()
    {
        if(app()->getLocale() !== 'en') {
            $lang = Lang::get('web');
            $english = File::getRequire(resource_path() . '/lang/en/web.php');
            foreach ($english as $key => $value) {
                if(! isset($lang[$key])) {
                    $lang[$key] = $english[$key];
                }
            }
            $output = $lang;
        } else {
            $output = Lang::get('web');
        }

        return response()->json($output);
    }
}