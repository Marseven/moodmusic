<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 03:57
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\Encore\ApiTester;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use View;

class ApiTesterController extends Controller
{
    public function index(Request $request)
    {
        $tester = new ApiTester();

        $view = View::make('backend.api-tester.index')
            ->with('routes', $tester->getRoutes());

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return $view;
    }
    public function handle(Request $request)
    {
        $method = $request->get('method');
        $uri = $request->get('uri');
        $user = $request->get('user');
        $all = $request->all();
        $keys = array_get($all, 'key', []);
        $vals = array_get($all, 'val', []);
        ksort($keys);
        ksort($vals);
        $parameters = [];
        foreach ($keys as $index => $key) {
            $parameters[$key] = array_get($vals, $index);
        }
        $parameters = array_filter($parameters, function ($key) {
            return $key !== '';
        }, ARRAY_FILTER_USE_KEY);
        $tester = new ApiTester();
        $response = $tester->call($method, $uri, $parameters, $user);
        unset($_POST['method'], $_POST['_token']);
        return [
            'status'    => true,
            'message'   => 'success',
            'data'      => $tester->parseResponse($response),
            'request_headers' => getallheaders(),
            'post_data' => $_POST
        ];
    }
}