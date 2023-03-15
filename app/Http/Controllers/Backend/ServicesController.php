<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use DB;
use App\Models\Service;
use Carbon\Carbon;

class ServicesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        isset($_GET['q']) ? $term = $_GET['q'] : $term = '';

        if($term) {
            $services = Service::where('title', '=', $term)->paginate(20);
        } else {
            $services = Service::paginate(20);
        }

        $total = DB::table('services')->count();

        return view('backend.services.index')
            ->with('total', $total)
            ->with('term', $term)
            ->with('services', $services);
    }

    public function delete()
    {
        $service = Service::findOrFail($this->request->route('id'));
        $service->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Plan successfully deleted!');
    }

    public function add()
    {
        return view('backend.services.form');
    }

    public function addPost()
    {
        $this->request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
            'trial' => 'required|boolean',
            'active' => 'required|boolean',
            'trial_period' => 'nullable|integer',
            'trial_period_format' => 'nullable|string|in:D,W,M,Y',
            'plan_period' => 'required|integer',
            'plan_period_format' => 'required|string|in:D,W,M,Y',
            'role_id' => 'required|numeric',
        ]);

        $service = new Service();

        $service->title = $this->request->input('title');
        $service->description = $this->request->input('description');
        $service->price = $this->request->input('price');
        $service->active = $this->request->input('active');
        $service->role_id = $this->request->input('role_id');
        if($this->request->input('trial')) {
            $service->trial = $this->request->input('trial');
            $service->trial_period = $this->request->input('trial_period');
            $service->trial_period_format = $this->request->input('trial_period_format');

        }
        $service->plan_period = $this->request->input('plan_period');
        $service->plan_period_format = $this->request->input('plan_period_format');
        $service->save();

        return redirect()->route('backend.services')->with('status', 'success')->with('message', 'Plan successfully added!');
    }

    public function edit()
    {
        $service = Service::findOrFail($this->request->route('id'));

        return view('backend.services.form')
            ->with('service', $service);
    }

    public function editPost()
    {

        $this->request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
            'trial' => 'required|boolean',
            'active' => 'required|boolean',
            'trial_period' => 'nullable|integer',
            'trial_period_format' => 'nullable|string|in:D,W,M,Y',
            'plan_period' => 'required|integer',
            'plan_period_format' => 'required|string|in:D,W,M,Y',
            'role_id' => 'required|numeric',
        ]);

        $service = Service::findOrFail($this->request->route('id'));
        $service->title = $this->request->input('title');
        $service->description = $this->request->input('description');
        $service->price = $this->request->input('price');
        $service->active = $this->request->input('active');
        $service->role_id = $this->request->input('role_id');
        if($this->request->input('trial')) {
            $service->trial = 1;
            $service->trial_period = $this->request->input('trial_period');
            $service->trial_period_format = $this->request->input('trial_period_format');
        } else {
            $service->trial = 0;
        }
        $service->plan_period = $this->request->input('plan_period');
        $service->plan_period_format = $this->request->input('plan_period_format');

        $service->save();

        return redirect()->route('backend.services')->with('status', 'success')->with('message', 'Plan successfully updated!');

    }

}