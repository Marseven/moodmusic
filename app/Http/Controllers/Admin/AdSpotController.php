<?php

namespace App\Http\Controllers\Admin;

use App\AdSpot;
use Common\Core\BaseController;
use Common\Database\Datasource\Datasource;
use Illuminate\Http\Request;

class AdSpotController extends BaseController
{
    public function __construct(protected Request $request)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dataSource = new Datasource(
            AdSpot::query(),
            $this->request->all(),
        );

        $pagination = $dataSource->paginate();

        return $this->success(['pagination' => $pagination]);
    }

    public function store()
    {
        $data = $this->request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:audio,banner',
            'audio_url' => 'required_if:type,audio|nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'click_url' => 'nullable|string|max:500',
            'duration' => 'required_if:type,audio|nullable|integer|min:1',
            'active' => 'boolean',
            'priority' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $adSpot = AdSpot::create($data);

        return $this->success(['ad_spot' => $adSpot]);
    }

    public function update(AdSpot $adSpot)
    {
        $data = $this->request->validate([
            'name' => 'string|max:100',
            'type' => 'in:audio,banner',
            'audio_url' => 'required_if:type,audio|nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'click_url' => 'nullable|string|max:500',
            'duration' => 'required_if:type,audio|nullable|integer|min:1',
            'active' => 'boolean',
            'priority' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $adSpot->update($data);

        return $this->success(['ad_spot' => $adSpot]);
    }

    public function destroy(string $ids)
    {
        $ids = explode(',', $ids);
        AdSpot::whereIn('id', $ids)->delete();

        return $this->success();
    }
}
