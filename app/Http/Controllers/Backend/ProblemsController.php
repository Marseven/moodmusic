<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:01
 */

namespace App\Http\Controllers\Backend;

use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\Report;
use Image;

class ProblemsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $reports = Report::withoutGlobalScopes()->where('reportable_type', 'App\\Models\\Song')->orWhere('reportable_type', 'App\\Models\\Podcast')->orWhere('reportable_type', 'App\\Models\\Episode')->paginate(20);

        return view('backend.problems.index')
            ->with('reports', $reports);
    }

    public function delete()
    {
        Report::withoutGlobalScopes()->where('id', $this->request->route('id'))->delete();
        return redirect()->route('backend.problems')->with('status', 'success')->with('message', 'Report successfully deleted!');
    }
}