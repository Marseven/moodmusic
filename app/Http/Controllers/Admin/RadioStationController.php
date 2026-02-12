<?php

namespace App\Http\Controllers\Admin;

use App\RadioStation;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;

class RadioStationController extends BaseController
{
    public function __construct(protected Request $request)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dataSource = new Datasource(
            RadioStation::query(),
            $this->request->all(),
        );

        $pagination = $dataSource->paginate();

        return $this->success(['pagination' => $pagination]);
    }

    public function store()
    {
        $data = $this->request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|string|max:500',
            'stream_url' => 'required|string|max:500',
            'frequency' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $station = RadioStation::create($data);

        return $this->success(['station' => $station]);
    }

    public function update(RadioStation $radioStation)
    {
        $data = $this->request->validate([
            'name' => 'string|max:100',
            'image' => 'nullable|string|max:500',
            'stream_url' => 'string|max:500',
            'frequency' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $radioStation->update($data);

        return $this->success(['station' => $radioStation]);
    }

    public function destroy(string $ids)
    {
        $ids = explode(',', $ids);
        RadioStation::whereIn('id', $ids)->delete();

        return $this->success();
    }
}
