<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 18:11
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Encore\Scheduling;
use View;

class SchedulingController
{

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */

    public function index(Request $request)
    {
        $scheduling = new Scheduling();

        $view = View::make('backend.scheduling.index')
            ->with('events', $scheduling->getTasks());

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return $view;
    }
    /**
     * @param Request $request
     *
     * @return array
     */
    public function runEvent(Request $request)
    {
        $scheduling = new Scheduling();
        try {
            $output = $scheduling->runTask($request->get('id'));
            return [
                'status'    => true,
                'message'   => 'success',
                'data'      => $output,
            ];
        } catch (\Exception $e) {
            return [
                'status'    => false,
                'message'   => 'failed',
                'data'      => $e->getMessage(),
            ];
        }
    }
}