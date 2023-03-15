<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 20:58
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class BulkController
{
    public function index(Request $request)
    {
        return view('backend.bulk.index');
    }
}