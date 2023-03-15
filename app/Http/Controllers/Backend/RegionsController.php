<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-26
 * Time: 10:54
 */

namespace App\Http\Controllers\Backend;

use App\Models\Region;
use Illuminate\Http\Request;
use DB;
use Image;

class RegionsController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {

        $regions = Region::withoutGlobalScopes()->paginate(20);
        return view('backend.regions.index')
            ->with('regions', $regions);
    }

    public function add()
    {
        return view('backend.regions.form');
    }

    public function edit()
    {
        $region = Region::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        return view('backend.regions.form')->with('region', $region);
    }

    public function savePost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'visibility' => 'nullable|boolean',
        ]);

        if(request()->route()->getName() == 'backend.regions.add.post') {
            $region = new Region();
        } else {
            $region = Region::withoutGlobalScopes()->findOrFail($this->request->route('id'));
        }

        if($this->request->input('alt_name'))
        {
            $this->request->validate([
                'alt_name' => 'required|string|unique:categories',
            ]);
        }

        $region->alt_name = str_slug($this->request->input('alt_name'));

        if(! $region->alt_name) {
            $region->alt_name = str_slug($region->name);
        }

        $this->request->input('visibility') ? $region->visibility = 1 : $region->visibility = 0;
        $region->name = $this->request->input('name');
        $region->save();

        return redirect()->route('backend.regions')->with('status', 'success')->with('message', 'Region successfully added!');
    }

    public function delete()
    {
        $region = Region::withoutGlobalScopes()->findOrFail($this->request->route('id'));

        $region->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'Region successfully deleted!');
    }
}