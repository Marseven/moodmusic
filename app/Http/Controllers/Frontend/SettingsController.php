<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-27
 * Time: 17:31
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Session;
use Illuminate\Http\Request;
use View;
use App\Models\Service;
use DB;

class SettingsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function profile(Request $request)
    {
        if($request->ajax()) {
            $view = View::make('settings.profile');
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return view('settings.profile');
    }

    public function subscription(Request $request)
    {
        $plans = Service::where('active', 1)->get();

        if($this->request->is('api*')) {
            return response()->json($plans);
        }

        $view = View::make('settings.subscription')->with('plans', $plans);

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }

    public function account(Request $request)
    {
        if($request->ajax()) {
            $view = View::make('settings.account');
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return view('settings.account');
    }

    public function password(Request $request)
    {
        if($request->ajax()) {
            $view = View::make('settings.password');
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return view('settings.password');
    }

    public function preferences(Request $request)
    {
        if($request->ajax()) {
            $view = View::make('settings.preferences');
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return view('settings.preferences');
    }

    public function services(Request $request)
    {
        if($request->ajax()) {
            $view = View::make('settings.services');
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return view('settings.services');
    }

    public function devices(Request $request)
    {
        $devices = DB::table('sessions')->where('user_id', auth()->user()->id)->get();

        $view = View::make('settings.devices')->with('devices', $devices);

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }
}