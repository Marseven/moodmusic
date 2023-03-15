<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 21:20
 */

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Socialite;
use App\Models\Connect;

class ConnectController
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) {
        $service = Socialite::driver($provider)->user();
        $social = Connect::where('provider_id', $service->id)->where('service', $provider)->first();

        if(isset($social->user_id)) {
            $authUser = User::findOrFail($social->user_id);
            if ($this->request->is('api*')) {
                return $this->createdToken($authUser);
            } else {
                auth()->loginUsingId($authUser->id);
                $this->response($service, $provider);
                exit;
            }
        }

        if(isset($service->email))
        {
            $authUser = User::where('email', $service->email)->first();

            if($authUser)
            {
                if( $this->request->is('api*') )
                {
                    return $this->createdToken($authUser);
                }

                auth()->loginUsingId($authUser->id);

                Connect::create(
                    [
                        'user_id' => auth()->user()->id,
                        'provider_id' => $service->id,
                        'provider_name' => $service->name,
                        'provider_email' => $service->email ? $service->email : null,
                        'provider_artwork' => $service->avatar ? $service->avatar : null,
                        'service' => $this->request->route('service')
                    ]
                );

                $this->response($service, $provider);
                exit;
            }
        }

        $user = User::create([
            'name' => $service->name,
            'username' => strtolower(Str::random(16)),
            'password' => bcrypt(Str::random(16)),
            'email' => isset($service->email) ? $service->email : NULL
        ]);

        $user->addMediaFromUrl($service->avatar)
            ->usingFileName(time(). '.jpg')
            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));

        Connect::create(
            [
                'user_id' => $user->id,
                'provider_id' => $service->id,
                'provider_name' => $service->name,
                'provider_email' => $service->email ? $service->email : null,
                'provider_artwork' => $service->avatar ? $service->avatar : null,
                'service' => $this->request->route('service')
            ]
        );

        if( $this->request->is('api*') )
        {
            return $this->createdToken($user);
        }

        auth()->loginUsingId($user->id);





        //return response()->json(auth()->user());
        }

    public function response($data, $provider){

        echo '<script type="text/javascript">
            var opener = window.opener;
            if(opener) {
                opener.User.SignIn.thirdParty.callback(' . json_encode($data) . ', "' . $provider . '")
                window.close();
            }
            </script>';

        exit();
    }
}