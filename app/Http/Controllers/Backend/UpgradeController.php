<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:01
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use View;

class UpgradeController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function index(Request $request)
    {
        return view('backend.upgrade.index');
    }


    public function checkingLicense(Request $request)
    {
        $this->request->validate([
            'license' => 'required|string',
        ]);

        $code = $request->input('license');

		return view('backend.upgrade.process');
    }
}