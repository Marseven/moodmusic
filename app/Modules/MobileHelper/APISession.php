<?php


namespace App\Modules\MobileHelper;

use Illuminate\Http\Request;

class APISession
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apiSession() {
        if($this->request->is('api*'))
        {
            $token = $this->request->input('api-token');

            $http = new \GuzzleHttp\Client;

            $response = $http->post(route('api.auth.user'), [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '. $token,
                ],
            ]);

            $user = json_decode((string) $response->getBody());

            if(isset($user->id)) {
                auth()->loginUsingId($user->id);
            } else {
                abort('403', 'Unauthenticated');
                exit;
            }
        }
    }
}