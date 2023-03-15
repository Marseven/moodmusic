<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-09
 * Time: 17:49
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Activity;
use App\Models\ArtistRequest;
use App\Models\Connect;
use App\Models\Session;
use Illuminate\Http\Request;
use Response;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Playlist;
use App\Models\Notification;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Album;
use Hash;
use Socialite;
use App\Notifications\ResetPassword;
use Illuminate\Support\Str;
use View;
use App\Models\Banned;
use App\Models\Email;
use Image;
use App\Models\RoleUser;
use App\Models\Report;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\HashTag;
use App\Models\Role;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function userInfoValidate()
    {
        $this->request->validate([
            'email' => 'required|string|email|unique:users',
            'name' => 'required|string|min:3|max:30',
            'password' => 'required|confirmed|min:6',
        ]);

        if(config('settings.dob_signup')) {
            $this->request->validate([
                'country' => 'required|string|max:3',
            ]);

            $dob_day = $this->request->input('dob-day');
            $dob_month = $this->request->input('dob-month');
            $dob_year = $this->request->input('dob-year');

            $dob = $dob_year . '-' . $dob_month . '-' . $dob_day;
            $dob = Carbon::parse($dob)->format('Y-m-d');
            $this->request->merge(array('dob' => $dob));


            $this->request->validate(
                [
                    'dob' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(intval(config('settings.age_restriction'))),
                ],
                [
                    'dob.before' => __('web.SIGN_UP_AGE_RESTRICTION', ['age' => intval(config('settings.age_restriction'))]),
                ]
            );
        }

        if(config('settings.gender_signup')) {
            $this->request->validate([
                'gender' => 'required|string|in:F,M,O',
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function usernameValidate()
    {
        $this->request->validate([
            'username' => 'required|string|alpha_dash|regex:/^[a-z0-9_]+$/|min:4|max:30|unique:users',
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup()
    {
        $this->request->validate([
            'name' => 'required|string|min:3|max:30',
            'username' => 'required|string|alpha_dash|min:4|max:50|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $verifyCoder = Str::random(32);

        $user = new User();

        $user->email = $this->request->email;
        $user->name = $this->request->name ? strip_tags($this->request->name) : strip_tags($this->request->username);
        $user->username = $this->request->username;
        $user->password = bcrypt($this->request->password);
        $user->email_verified_code = $verifyCoder;

        if(config('settings.dob_signup') && ! $this->request->is('api*')) {
            $this->request->validate([
                'country' => 'required|string|max:3',
            ]);

            $dob_day = $this->request->input('dob-day');
            $dob_month = $this->request->input('dob-month');
            $dob_year = $this->request->input('dob-year');

            $dob = $dob_year . '-' . $dob_month . '-' . $dob_day;
            $dob = Carbon::parse($dob)->format('Y-m-d');
            $this->request->merge(array('dob' => $dob));

            $this->request->validate(
                [
                    'dob' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(intval(config('settings.age_restriction'))),
                ],
                [
                    'dob.before' => __('web.SIGN_UP_AGE_RESTRICTION', ['age' => intval(config('settings.age_restriction'))]),
                ]
            );

            $user->country = $this->request->country;
            $user->birth = Carbon::parse($dob);
        }

        if(config('settings.gender_signup') && ! $this->request->is('api*')) {
            $this->request->validate([
                'gender' => 'required|string|in:F,M,O',
            ]);
            $user->gender = $this->request->gender;
        }

        $user->save();

        /** Send activation email if registration method is advanced */
        if(config('settings.registration_method') == 1) {

            if( $this->request->input('isArtist')  == 'on') {
                DB::table('artist_requests')->insert(['user_id' => $user->id]);
            }

            (new Email)->verifyAccount($user, route('frontend.account.verify', ['code' => $verifyCoder]));

            return response()->json([
                'activation' => true,
                'email' => 'sent'
            ]);
        }

        /** If registration method is simplified then login the user right away  */
        $login = [
            'username' => $this->request->username,
            'password' => $this->request->password,
        ];

        if(auth()->attempt($login, true))
        {
            /** send welcome email */
            (new Email)->newUser(auth()->user());


            if( $this->request->is('api*') )
            {
                $user = $this->request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($this->request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]);
            } else {
                return $this->request->user();
            }
        }
    }

    /**
     * Check if banned and get user IP
     */

    private function userBannedCheck(){
        if(auth()->user()->banned) {
            $banned = Banned::find(auth()->user()->id);
            if(isset($banned->end_at)) {
                if(Carbon::now()->timestamp >= Carbon::parse($banned->end_at)->timestamp){
                    User::where('id', auth()->user()->id)
                        ->update(['banned' => 0]);
                    Banned::destroy($banned->user_id);
                } else {
                    return response()->json([
                        'message' => 'Unauthorized',
                        'errors' => array('message' => array(__('auth.banned', ['banned_reason' => $banned->reason, 'banned_time' =>  Carbon::parse($banned->end_at)->format('H:i F j Y')])))
                    ], 403);
                }
            }
        }

        $user = auth()->user();
        $user->logged_ip = request()->ip();
        $user->save();
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login()
    {
        if( $this->request->is('api*') ) {
            $this->request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if(filter_var( $this->request->username, FILTER_VALIDATE_EMAIL)) {
                $credentials = [
                    'email' => $this->request->username,
                    'password' => $this->request->password,
                ];
            }
            else {
                $credentials = [
                    'username' => $this->request->username,
                    'password' => $this->request->password,
                ];
            }
        } else {
            if(config('settings.authorization_method', 0) == 0) {
                $this->request->validate([
                    'username' => 'required|string|alpha_dash',
                    'password' => 'required|string',
                ]);

                $credentials = [
                    'username' => $this->request->username,
                    'password' => $this->request->password,
                ];
            } else {
                $this->request->validate([
                    'email' => 'required|email',
                    'password' => 'required|string',
                ]);

                $credentials = [
                    'email' => $this->request->email,
                    'password' => $this->request->password,
                ];
            }
        }

        if(!auth()->attempt($credentials, true))
        {
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => array('message' => array(__('auth.failed')))
            ], 401);
        }

        if(config('settings.registration_method') == 1) {
            if(!auth()->user()->email_verified){
                auth()->logout();
                return response()->json([
                    'message' => 'Unauthorized',
                    'errors' => array('message' => array(__('auth.email_verification_required')))
                ], 401);
            }
        }

        $this->userBannedCheck();

        if(env('SESSION_DRIVER') == 'database') {
            $conCurrentCount = Session::where('user_id')->count();
            if(intval(Role::getValue('option_concurent')) != 0 && $conCurrentCount >= intval(Role::getValue('option_concurent'))) {
                $lastSession = Session::where('user_id')->first();
                $lastSession->delete();
            }
        }

        if( $this->request->is('api*') )
        {
            $user = $this->request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(30);
            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }

        return $this->request->user();
    }

    private function createdToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(30);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function socialiteLogin($provider)
    {
        if(request()->route()->getName() == 'frontend.auth.login.socialite.redirect' && ! request()->isMethod('post')) {
            if(($provider) == 'google') {
                return Socialite::driver('google')->setScopes(['openid', 'email'])->redirect();
            } else {
                return Socialite::driver($provider)->redirect();
            }
        } else {
            if(($provider) == 'twitter') {
                $service = Socialite::driver('twitter')->userFromTokenAndSecret($this->request->input('oauth_token'), config('services.twitter.client_secret'));
            } else {
                $service = Socialite::driver($provider)->user();
            }

            if(auth()->check()) {

                $connect = Connect::where('provider_id', $service->id)->where('service', $provider)->first();
                if(isset($connect->id) && $connect->user_id != auth()->user()->id) {
                    return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'error' => true]);
                }

                Connect::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'provider_id' => $service->id,
                        'service' => $provider
                    ],
                    [
                        'provider_name' => $service->name,
                        'provider_artwork' => $service->avatar ? $service->avatar : null,
                        'provider_email' => $service->email ? $service->email : null,

                    ]
                );

                /**
                 * Create token for mobile app sign
                 */

                if(file_exists(storage_path('oauth-private.key'))) {
                    $user = auth()->user();
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    $token->expires_at = Carbon::now()->addWeeks(30);
                    $token->save();
                    return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => $tokenResult->accessToken]);
                } else {
                    return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => '']);
                }
            } else {
                $connect = Connect::where('provider_id', $service->id)->where('service', $provider)->first();
                if(isset($connect->user_id)) {
                    $authUser = User::find($connect->user_id);

                    if(isset($authUser->id)) {
                        $authUser->logged_ip = request()->ip();
                        $authUser->save();

                        if ($this->request->is('api*')) {
                            return $this->createdToken($authUser);
                        } else {
                            Auth::loginUsingId($authUser->id, true);

                            /**
                             * Create token for mobile app sign
                             */

                            if(file_exists(storage_path('oauth-private.key'))) {
                                $user = auth()->user();
                                $tokenResult = $user->createToken('Personal Access Token');
                                $token = $tokenResult->token;
                                $token->expires_at = Carbon::now()->addWeeks(30);
                                $token->save();
                                return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => $tokenResult->accessToken]);
                            } else {
                                return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => '']);
                            }
                        }
                    } else {
                        $connect->delete();
                    }
                }

                if(isset($service->email))
                {
                    $authUser = User::where('email', $service->email)->first();

                    if($authUser)
                    {
                        $authUser->logged_ip = request()->ip();
                        $authUser->save();

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
                                'service' => $provider
                            ]
                        );

                        $this->userBannedCheck();

                        /**
                         * Create token for mobile app sign
                         */

                        if(file_exists(storage_path('oauth-private.key'))) {
                            $user = auth()->user();
                            $user->logged_ip = request()->ip();
                            $user->save();

                            $tokenResult = $user->createToken('Personal Access Token');
                            $token = $tokenResult->token;
                            $token->expires_at = Carbon::now()->addWeeks(30);
                            $token->save();
                            return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => $tokenResult->accessToken]);
                        } else {
                            return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => '']);
                        }
                    }
                }

                $user = User::create([
                    'name' => $service->name ? $service->name : Str::random(8),
                    'username' => strtolower(Str::random(16)),
                    'password' => bcrypt(Str::random(16)),
                    'logged_ip' => request()->ip(),
                    'email' => isset($service->email) ? $service->email : NULL
                ]);

                if($service->avatar) {
                    if ($provider == 'google') {
                        $service->avatar = str_replace('?sz=50', '', $service->avatar);
                    } elseif ($provider == 'twitter') {
                        $service->avatar = str_replace('_normal', '', $service->avatar);
                    } elseif ($provider == 'facebook') {
                        $service->avatar = str_replace('type=normal', 'type=large', $service->avatar);
                    }
                }

                if($service->avatar) {
                    try {
                        $user->addMediaFromUrl($service->avatar)
                            ->usingFileName(time(). '.jpg')
                            ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
                    } catch (\Exception $e) {

                    }
                }

                Connect::create(
                    [
                        'user_id' => $user->id,
                        'provider_id' => $service->id,
                        'provider_name' => $service->name ? $service->name : Str::random(8),
                        'provider_email' => $service->email ? $service->email : null,
                        'provider_artwork' => $service->avatar ? $service->avatar : null,
                        'service' => $provider
                    ]
                );

                if( $this->request->is('api*') )
                {
                    return $this->createdToken($user);
                }

                auth()->loginUsingId($user->id, true);

                /**
                 * Create token for mobile app sign
                 */

                if(file_exists(storage_path('oauth-private.key'))) {
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    $token->expires_at = Carbon::now()->addWeeks(30);
                    $token->save();
                    return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => $tokenResult->accessToken]);
                } else {
                    return view('commons.service')->with(['service' => $service, 'provider' => $provider, 'token' => '']);
                }
            }
        }
    }

    public function socialiteRemove($provider)
    {
        $connect = Connect::where('user_id', auth()->user()->id)->where('service', $provider)->first();
        $connect->delete();

        return Response::json(array(
            'success' => true,
            'message' => 'Successfully removed service.'
        ), 200);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
        auth()->logout();
        return Response::json(array(
            'success' => true,
            'message' => 'Successfully logged out.'
        ), 200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user()
    {
        $user = $this->request->user();

        /**
         * Check and send user back to default group if there is time limit
         */

        $role = RoleUser::where('user_id', $user->id)->first();

        if(isset($role->end_at) && Carbon::now()->gt(Carbon::parse($role->end_at))) {
            $role->role_id = config('settings.default_usergroup', 5);
            $role->end_at = null;
            $role->save();
        }

        $user = $user->makeVisible(['banned', 'location', 'email', 'email_verified_at', 'last_seen_notif',
            'logged_ip', 'gender', 'birth', 'city', 'country', 'activity_privacy', 'created_at', 'updated_at',
            'restore_queue', 'persist_shuffle', 'play_pause_fade', 'disablePlayerShortcuts', 'crossfade_amount', 'notif_follower',
            'notif_playlist', 'notif_shares', 'notif_features', 'email_verified',
            'playlist_count', 'favorite_count', 'following_count', 'follower_count', 'can_stream_high_quality', 'can_upload', 'track_skip_limit'])
            ->makeHidden(['roles']);

        $user->can_upload = auth()->user()->can_upload;
        $user->can_stream_high_quality = auth()->user()->can_stream_high_quality;
        $user->track_skip_limit = auth()->user()->track_skip_limit;
        $user->allow_genres = Genre::where('discover', 1)->get()->makeHidden(['artwork_url', 'created_at', 'description', 'media', 'meta_description', 'meta_keywords', 'meta_title', 'parent_id', 'priority', 'priority', 'updated_at', 'alt_name']);
        $user->allow_moods = Mood::all()->makeHidden(['artwork_url', 'created_at', 'description', 'media', 'meta_description', 'meta_keywords', 'meta_title', 'parent_id', 'priority', 'priority', 'updated_at', 'alt_name']);
        $user->should_subscribe = ! isset($user->group->role_id) || $user->group->role_id == config('settings.default_usergroup', 5);
        $user->can_download = !! Role::getValue('option_download');
        $user->can_download_high_quality = !! Role::getValue('option_download_hd');
        if(config('settings.dob_signup')) {
            $user->should_update_dob = $user->birth ? false : true;
        } else {
            $user->should_update_dob = false;
        }

        if ($this->request->is('api*')) {
            return response()->json($user);
        }

        //get user playlist
        $user->playlists = Playlist::withoutGlobalScopes()->where('user_id', auth()->user()->id)->get();
        $menu = array();

        if (count($user->playlists)) {
            foreach ($user->playlists AS $playlist) {
                $menu['playlist_id_' . $playlist->id]['name'] = $playlist->title;
            }
        }

        $user->playlists_menu = $menu;

        $user->setRelation('collaborations', $user->collaborations()->with('user')->get());

        $menu = array();

        //get user collaborate playlist
        if (count($user->collaborations)) {
            foreach ($user->collaborations AS $playlist) {
                $menu['playlist_id_' . $playlist->id]['name'] = $playlist->title;
            }
        }

        $user->collaborate_playlists_menu = $menu;

        //get user subscribed playlist
        $user->setRelation('subscribed', $user->subscribed()->with('user')->get());

        $user->admin_panel = !! Role::getValue('admin_access');

        if(Role::getValue('admin_access')) {
            $user->admin_panel_url = route('backend.dashboard');
        }

        return response()->json($user);
    }

    public function settingsProfile()
    {
        $this->request->validate([
            'name' => 'required|string|max:50',
            'country' => 'nullable|string|max:3',
            'bio' => 'nullable|string|max:180',
            'gender' => 'nullable|string|max:1',
            'birth' => 'nullable|date_format:m/d/Y',
            'website_url' => 'nullable|string|max:100',
            'facebook_url' => 'nullable|string|max:100',
            'twitter_url' => 'nullable|string|max:100',
            'youtube_url' => 'nullable|string|max:100',
            'instagram_url' => 'nullable|string|max:100',
            'soundcloud_url' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=' . config('settings.image_avatar_size', 300) . ',min_height=' . config('settings.image_avatar_size', 300) . '|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $user->clearMediaCollection('artwork');
            $user->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $user->name = strip_tags($this->request->input('name'));
        $user->country = strip_tags($this->request->input('country'));
        $user->bio = strip_tags($this->request->input('bio'));
        $user->gender = $this->request->input('gender');
        $user->birth = Carbon::parse($this->request->input('birth'));
        $user->website_url = $this->request->input('website_url');
        $user->facebook_url = $this->request->input('facebook_url');
        $user->twitter_url = $this->request->input('twitter_url');
        $user->youtube_url = $this->request->input('youtube_url');
        $user->instagram_url = $this->request->input('instagram_url');
        $user->soundcloud_url = $this->request->input('soundcloud_url');

        $user->save();

        auth()->setUser($user);

        $output = $this->request->user();

        $output = $output->makeVisible(['banned', 'location', 'email', 'email_verified_at', 'last_seen_notif',
            'logged_ip', 'gender', 'birthyear', 'city', 'country', 'activity_privacy', 'created_at', 'updated_at',
            'restore_queue', 'persist_shuffle', 'play_pause_fade', 'disablePlayerShortcuts', 'crossfade_amount', 'notif_follower',
            'notif_playlist', 'notif_shares', 'notif_features', 'email_verified',
            'playlist_count', 'favorite_count', 'following_count', 'follower_count', 'can_stream_high_quality', 'can_upload', 'track_skip_limit'])
            ->makeHidden(['roles']);

        return response()->json($output);
    }

    public function settingsAccount()
    {
        $this->request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if($this->request->input('email') != $user->email)
        {
            $this->request->validate([
                'email' => 'required|string|email|unique:users',
            ]);
        }

        if($this->request->input('username') != $user->username)
        {
            $this->request->validate([
                'username' => 'required|string|alpha_dash|unique:users',
            ]);
        }


        if(Hash::check($this->request->input('password'),  $user->password)) {
            if($this->request->input('email') != $user->email)
            {
                $user = auth()->user();
                $user->email = $this->request->input('email');
                $user->save();
            }

            if($this->request->input('username') != $user->email)
            {
                $user = auth()->user();
                $user->username = $this->request->input('username');
                $user->save();
            }

            auth()->setUser($user);
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => array('message' => array('Wrong password.'))
            ], 401);
        }
    }

    public function settingsPassword()
    {
        $this->request->validate([
            'old-password' => 'required|string',
            'password' => 'required|string|confirmed'

        ]);
        if(Hash::check($this->request->input('old-password'),  auth()->user()->password)) {
            if($this->request->input('email') != auth()->user()->email)
            {
                $user = auth()->user();
                $user->password = Hash::make($this->request->input('password'));
                $user->save();
                auth()->setUser($user);

                $user = $this->request->user();

                return response()->json($this->request->user());
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => array('message' => array('Your current password is incorrect.'))
            ], 401);
        }
    }

    public function settingsPreferences()
    {
        $this->request->validate([
            'activity_privacy' => 'required|in:0,1,2',
        ]);

        $user = auth()->user();
        $user->activity_privacy = $this->request->input('activity_privacy');

        $user->restore_queue = $this->request->has('restore_queue') ? 1 : 0;
        $user->hd_streaming = $this->request->has('hd_streaming') ? 1 : 0;
        $user->persist_shuffle = $this->request->has('persist_shuffle') ? 1 : 0;
        $user->play_pause_fade = $this->request->has('play_pause_fade') ? 1 : 0;
        $user->disablePlayerShortcuts = $this->request->has('disablePlayerShortcuts') ? 1 : 0;
        $user->notif_follower = $this->request->has('notif_follower') ? 1 : 0;
        $user->notif_playlist = $this->request->has('notif_playlist') ? 1 : 0;
        $user->notif_shares = $this->request->has('notif_shares') ? 1 : 0;
        $user->notif_features = $this->request->has('notif_features') ? 1 : 0;
        $user->allow_comments = $this->request->has('allow_comments') ? 1 : 0;

        $user->save();

        auth()->setUser($user);

        return response()->json($this->request->user());
    }

    public function notifications (){
        $user = auth()->user();
        $user->last_seen_notif =  Carbon::now();
        $user->save();

        if( $this->request->is('api*') )
        {
            $notifications = [];
            foreach ($user->notifications()->toArray() as $index => $item) {
                $notifications[] = $item;
            }
            return response()->json($notifications);
        }

        $view = View::make('commons.notification')
            ->with('notifications', $user->notifications());
        return $view;
    }

    public function notificationCount (){
        $user = auth()->user();

        return response()->json(array(
            'success' => true,
            'notification_count' => $user->notification_count,
            'last_seen_notif' => $user->last_seen_notif
        ));
    }


    public function playlists()
    {
        $playlists = Playlist::withoutGlobalScopes()->where('user_id', auth()->user()->id)->get();
        if( $this->request->is('api*') )
        {
            return response()->json($playlists);
        }
    }

    public function subscribed()
    {
        $playlists = (new Playlist)->getUserSubscribedPlaylists(auth()->user()->id, 20);

        if( $this->request->is('api*') )
        {
            return response()->json($playlists);
        }
    }

    public function favorite()
    {
        $this->request->validate([
            'id' => 'required',
            'object_type' => 'required',
            'action' => 'required|boolean',
        ]);

        $object_id = intval($this->request->input('id'));
        $object_type = $this->request->input('object_type');
        $action = $this->request->input('action');

        if ($action) {
            makeFavorite("love", $object_id, $object_type);
        } else {
            makeFavorite("unlove", $object_id, $object_type);
        }

        return Response::json(array(
            'success' => true,
        ), 200);
    }

    public function songFavorite()
    {
        $this->request->validate([
            'ids' => 'required|string',
        ]);
        foreach(explode(',', $this->request->input('ids')) as $id) {
            makeFavorite("love", $id, 'song');
        }
        return Response::json(array(
            'success' => true,
        ), 200);
    }

    public function library()
    {
        $this->request->validate([
            'id' => 'required|integer',
            'object_type' => 'required',
            'action' => 'required|boolean',
        ]);

        $object_id = intval($this->request->input('id'));
        $object_type = $this->request->input('object_type');
        $action = $this->request->input('action');

        if ($action) {
            makeLibrary("love", $object_id, $object_type);
        } else {
            makeLibrary("unlove", $object_id, $object_type);
        }

        return Response::json(array(
            'success' => true,
        ), 200);
    }

    public function songLibrary()
    {
        $this->request->validate([
            'ids' => 'required|string',
        ]);
        foreach(explode(',', $this->request->input('ids')) as $id) {
            makeLibrary("love", $id, 'song');
        }
        return Response::json(array(
            'success' => true,
        ), 200);
    }

    public function collaborativePlaylist()
    {
        $this->request->validate([
            'id' => 'required|integer',
            'action' => 'required|in:invite,cancel,accept'
        ]);

        $playlist_id = $this->request->input('id');
        $action = $this->request->input('action');

        if($action == 'invite')
        {
            try {

                $this->request->validate([
                    'friend_id' => 'required|integer',
                ]);
                $friend_id = $this->request->input('friend_id');
                $playlist = Playlist::findOrFail($playlist_id);

                DB::table('collaborators')->insert(
                    ['user_id' => auth()->user()->id,
                        'playlist_id' => $playlist_id,
                        'friend_id' => $friend_id,
                        'approved' => 0,
                        'created_at' => Carbon::now()
                    ]
                );

                pushNotification(
                    $friend_id,
                    $playlist->id,
                    (new $playlist)->getMorphClass(),
                    'inviteCollaboration',
                    $playlist->id
                );

                return response()->json(array('success' => true));
            } catch (\Exception $e) {
                return response()->json(['success' => true, 'message' => 'Duplicated row']);
            }
        } elseif ($action == 'accept') {
            $collab = DB::table('collaborators')
                ->where('friend_id', auth()->user()->id)
                ->where('playlist_id', $playlist_id)
                ->first();


            DB::table('collaborators')
                ->where('friend_id', auth()->user()->id)
                ->where('playlist_id', $playlist_id)
                ->update(['approved' => 1]);

            Notification::where('notificationable_type', 'App\Models\Playlist')
                ->where('notificationable_id', $playlist_id)
                ->where('action', 'inviteCollaboration')
                ->where('user_id', auth()->user()->id)
                ->delete();

            pushNotification(
                $collab->user_id,
                $playlist_id,
                'App\Models\Playlist',
                'acceptedCollaboration',
                $playlist_id
            );

            return response()->json(array('success' => true));
        } elseif ($action == 'cancel') {

            $collab = DB::table('collaborators')
                ->where('friend_id', auth()->user()->id)
                ->where('playlist_id', $playlist_id)
                ->delete();

            Notification::where('notificationable_type', 'App\Models\Playlist')
                ->where('notificationable_id', $playlist_id)
                ->where('action', 'inviteCollaboration')
                ->where('user_id', auth()->user()->id)
                ->delete();

            return response()->json(array('success' => true));
        }
    }

    public function editPlaylist()
    {
        $this->request->validate([
            'id' => 'required',
            'title' => 'required',
            'description' => 'nullable|string'
        ]);

        $playlist = Playlist::withoutGlobalScopes()->findOrFail($this->request->input('id'));

        if($playlist->user_id != auth()->user()->id) {
            abort(403, 'You do not have permission to edit this playlist');
        }

        $playlist->title = strip_tags($this->request->input('title'));
        $playlist->description = strip_tags($this->request->input('description'));
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        if(is_array($genre))
        {
            $playlist->genre = implode(",", $this->request->input('genre'));
        } else {
            $playlist->genre = null;
        }

        if(is_array($mood))
        {
            $playlist->mood = implode(",", $this->request->input('mood'));
        } else {
            $playlist->genre = null;
        }

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $playlist->clearMediaCollection('artwork');

            $playlist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        if($this->request->input('visibility')) {
            $playlist->visibility = 1;
        } else {
            $playlist->visibility = 0;
        }

        $playlist->save();

        return Response::json($playlist);
    }

    public function createPlaylist () {
        if(intval(\App\Models\Role::getValue('artist_max_playlists')) != 0 && auth()->user()->playlist_count > intval(\App\Models\Role::getValue('artist_max_playlists'))) {
            return response()->json([
                'message' => __('web.UPLOAD_LIMITED_PLAYLIST'),
                'errors' => array('message' => array(__('web.UPLOAD_LIMITED_PLAYLIST')))
            ], 403);
        }

        $this->request->validate([
            'playlistName' => 'required|string',
            'genre' => 'nullable|array',
            'mood' => 'nullable|array',
            'artwork' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
        ]);

        $playlist = new Playlist();
        $playlist->title = strip_tags($this->request->input('playlistName'));

        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        if(is_array($genre))
        {
            $playlist->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $playlist->mood = implode(",", $this->request->input('mood'));
        }

        if ($this->request->hasFile('artwork'))
        {
            $playlist->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $playlist->user_id = auth()->user()->id;

        if($this->request->input('visibility')) {
            $playlist->visibility = 1;
        } else {
            $playlist->visibility = 0;
        }

        $playlist->save();

        auth()->user()->increment('playlist_count');

        return response()->json($playlist);
    }
    public function deletePlaylist (){
        $this->request->validate([
            'playlist_id' => 'required|int',
        ]);

        $playlist = Playlist::withoutGlobalScopes()->where('id', $this->request->input('playlist_id'))
            ->where('user_id', auth()->user()->id)->firstOrFail();

        $playlist->delete();

        auth()->user()->decrement('playlist_count');

        return response()->json(['success' => true]);

    }
    public function addToPlaylist() {
        $this->request->validate([
            'mediaId' => 'required|int',
            'mediaType' => 'required',
            'playlist_id' => 'required|int'
        ]);

        $mediaId = $this->request->input('mediaId');
        $mediaType = $this->request->input('mediaType');
        $playlist_id = $this->request->input('playlist_id');
        $permission = false;

        $playlist = Playlist::withoutGlobalScopes()->where('id', $playlist_id)->where('user_id', auth()->user()->id)->first();

        if(intval(\App\Models\Role::getValue('artist_max_playlist_songs')) != 0 && $playlist->songs()->count() > intval(\App\Models\Role::getValue('artist_max_playlist_songs'))) {
            return response()->json([
                'message' => __('web.UPLOAD_LIMITED_PLAYLIST'),
                'errors' => array('message' => array(__('web.UPLOAD_LIMITED_PLAYLIST')))
            ], 403);
        }

        if(isset($playlist->id)) {
            $permission = true;
        }

        if( ! $permission ) {
            $row = DB::table('collaborators')
                ->select('id')
                ->where('playlist_id', $playlist_id)
                ->where('friend_id', auth()->user()->id)
                ->first();

            if(isset($row->id)) {
                $permission = true;
            }
        }

        if(! $permission) {
            abort(403);
        } else {

            if ($mediaType == "song")
            {
                try {
                    DB::table('playlist_songs')->insert(
                        [ 'song_id' => $mediaId, 'playlist_id' => $playlist_id ]
                    );
                    makeActivity(auth()->user()->id, $playlist_id, (new Playlist)->getMorphClass(), 'addToPlaylist', $mediaId);
                } catch (\Exception $e) {
                    return response()->json(['success' => true, 'message' => 'Duplicated row']);
                }

            } elseif ($mediaType == "album") {
                $album = Album::findOrFail($mediaId);
                $album->setRelation('songs', $album->songs()->get());

                foreach($album->songs as $song) {
                    try {
                        DB::table('playlist_songs')->insert(
                            [ 'song_id' => $song->id, 'playlist_id' => $playlist_id ]
                        );
                        makeActivity(auth()->user()->id, $playlist_id, (new Playlist)->getMorphClass(), 'addToPlaylist', $song->id);
                    } catch (\Exception $e) {

                    }
                }
                return response()->json($album->songs);
            } elseif ($mediaType == "playlist") {
                $playlist = Playlist::findOrFail($mediaId);
                $playlist->setRelation('songs', $playlist->songs()->get());

                foreach($playlist->songs as $song) {
                    try {
                        DB::table('playlist_songs')->insert(
                            [ 'song_id' => $song->id, 'playlist_id' => $playlist_id ]
                        );
                        makeActivity(auth()->user()->id, $playlist_id, (new Playlist)->getMorphClass(), 'addToPlaylist', $song->id);
                    } catch (\Exception $e) {

                    }
                }
                return response()->json($playlist->songs);
            } elseif ($mediaType == "queue") {
                $this->request->validate([
                    'mediaItems' => 'required|array'
                ]);
                $mediaItems = $this->request->input('mediaItems');

                if(is_array($mediaItems))
                {
                    foreach ($mediaItems as $item) {
                        $item = intval($item);
                        if ($item)
                        {
                            try {
                                DB::table('playlist_songs')->insert(
                                    [ 'song_id' => $item, 'playlist_id' => $playlist_id ]
                                );
                                makeActivity(auth()->user()->id, $playlist_id, (new Playlist)->getMorphClass(), 'addToPlaylist', $item);
                            } catch (\Exception $e) {

                            }
                        }
                    }
                }
            }
        }
        return response()->json(['success' => true]);
    }

    public function removeFromPlaylist() {
        $this->request->validate([
            'song_id' => 'required|int',
            'playlist_id' => 'required|int'
        ]);

        DB::table('playlist_songs')
            ->where('playlist_id', $this->request->input('playlist_id'))
            ->where('song_id', $this->request->input('song_id'))
            ->delete();

        return response()->json(['success' => true]);
    }

    public function managePlaylist (){
        $this->request->validate([
            'playlist_id' => 'required|int',
            'removeIds' => 'nullable|string',
            'nextOrder' => 'required|string',
        ]);

        $playlist_id = $this->request->input('playlist_id');
        $removeIds = strip_tags(json_decode($this->request->input('removeIds')));
        $nextOrder = strip_tags(json_decode($this->request->input('nextOrder')));

        if(is_array($removeIds))
        {
            foreach ($removeIds as $trackId){
                DB::table('playlist_songs')
                    ->where('playlist_id', $playlist_id)
                    ->where('song_id', $trackId)
                    ->delete();
            }
        }

        if(is_array($nextOrder))
        {
            foreach ($nextOrder as $index => $trackId) {
                DB::table('playlist_songs')
                    ->where('playlist_id', $playlist_id)
                    ->where('song_id', $trackId)
                    ->update(['priority' => $index]);
            }
        }

        if( $this->request->is('api*') )
        {
            $playlist = Playlist::findOrFail($playlist_id);
            $playlist->setRelation('songs', $playlist->songs()->get());
            return response()->json($playlist->songs);
        }

        return response()->json(array("success" => true));
    }

    public function setPlaylistCollaboration(){
        $this->request->validate([
            'playlist_id' => 'required|int',
            'action' => 'required|boolean'
        ]);

        $playlist = Playlist::findOrFail($this->request->input('playlist_id'));
        $playlist->collaboration = $this->request->input('action');
        $playlist->save();

        return response()->json(array("success" => true));
    }

    public function removeActivity (){
        $this->request->validate([
            'id' => 'required|int',
        ]);

        $activity = Activity::where('user_id', auth()->user()->id)
            ->where('id', $this->request->input('id'))->firstOrFail();

        $activity->delete();
        return response()->json(array("success" => true));
    }

    public function suggest()
    {
        $songs = Song::where('plays', '>', 20)->get();
        return response()->json($songs);
    }

    public function artistClaim()
    {
        $this->request->validate([
            'stage' => 'string|required',
        ]);

        if($this->request->input('stage') == 'account')
        {
            $this->request->validate([
                'email' => 'string|required|email',
                'fullName' => 'string|required|max:50',
            ]);

            if($this->request->input('email') != auth()->user()->email)
            {
                $this->request->validate([
                    'email' => 'required|string|email|unique:users',
                ]);
            }

            return response()->json(auth()->user()->connect);

        } else if($this->request->input('stage') == 'info') {
            $this->request->validate([
                'artist_id' => 'nullable|integer',
                'artist_name' => 'required|string|min:3|max:30',
                'phone' => 'required|string|min:5|max:15',
                'ext' => 'nullable|numeric|digits_between:1,3',
                'affiliation' => 'required|string',
                'message' => 'nullable|string',
            ]);

            /** Insert artist request to user database */

            $row = DB::table('artist_requests')->select('id')->where('user_id', auth()->user()->id)->first();

            if(! isset($row->id)) {
                $artistRequest = new ArtistRequest();
                $artistRequest->user_id = auth()->user()->id;
                $artistRequest->artist_id = $this->request->input('artist_id');
                $artistRequest->artist_name = $this->request->input('artist_name');
                $artistRequest->phone = $this->request->input('phone');
                $artistRequest->ext = $this->request->input('ext');
                $artistRequest->affiliation = $this->request->input('affiliation');
                $artistRequest->message = $this->request->input('message');

                if ($this->request->hasFile('passport'))
                {
                    $this->request->validate([
                        'passport' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
                    ]);

                    $artistRequest->addMediaFromBase64(base64_encode(Image::make($this->request->file('passport'))->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                        ->usingFileName(time(). '.jpg')
                        ->toMediaCollection('passport', config('settings.storage_artwork_location', 'public'));
                }

                $artistRequest->save();
            } else {
                return response()->json([
                    'message' => 'You can not claim this artist profile',
                    'errors' => array('message' => array('You have already claimed another artist profile, please wait for our email.'))
                ], 403);
            }

            return response()->json([
                'success' => true
            ]);
        }
    }

    public function checkRole(){
        $this->request->validate([
            'permission' => 'required|string',
        ]);

        if(Role::getValue('option_hd_stream')) {
            return response()->json([
                $this->request->input('permission') => true
            ]);
        } else {
            abort(403, 'You don not have the permission!');
        }
    }

    public function cancelSubscription (){
        if(!auth()->user()->subscription) {
            abort(403);
        }

        $subscription = auth()->user()->subscription;
        $subscription->delete();

        RoleUser::updateOrCreate([
            'user_id' => auth()->user()->id,
        ], [
            'role_id' => config('settings.default_usergroup', 5),
            'end_at' => null
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function getMention() {
        $this->request->validate([
            'term' => 'required|string',
        ]);

        $users = User::where('name', 'like', '%' . $this->request->input('term') . '%')->limit(5)->get();
        return response()->json($users);
    }

    public function getHashTag() {
        $this->request->validate([
            'term' => 'required|string',
        ]);

        $tags = HashTag::where('tag', 'like', '%' . $this->request->input('term') . '%')->groupBy('tag')->limit(5)->get();
        return response()->json($tags);
    }

    public function postFeed() {

        $this->request->merge(array('body' => trim(strip_tags( preg_replace("/\s|&nbsp;/",' ',$this->request->input('content'))))));


        $this->request->validate([
            'object' => 'required|array',
            'body' => 'required|string|min:' . config('settings.share_min_chars', 1) . '|max:' . config('settings.share_max_chars', 160),
        ]);


        $content = strip_tags($this->request->input('content'), '<tag>');

        switch ($this->request->input('object')['type']) {
            case 'song':
                $notificationType = (new Song)->getMorphClass();
                break;
            case 'album':
                $notificationType = (new Album)->getMorphClass();
                break;
            case 'artist':
                $notificationType = (new Artist)->getMorphClass();
                break;
            case 'playlist':
                $notificationType = (new Playlist)->getMorphClass();
                break;
            default:
                $notificationType = null;
        }

        $activity = new Activity();
        $activity->user_id = auth()->user()->id;
        $activity->activityable_id = $this->request->input('object')['id'];
        $activity->activityable_type = $notificationType;
        $activity->events = $content;
        $activity->action = 'postFeed';
        $activity->save();

        pushNotificationMentioned(
            $content,
            $this->request->input('object')['id'],
            $notificationType,
            'sharedMusic',
            $activity->id
        );

        //handle hashtag
        preg_match_all('/#(\w+)/', $content, $allMatches);

        if(count($allMatches[1])) {
            foreach($allMatches[1] as $tag) {
                if($tag) {
                    HashTag::insert(
                        [
                            'hashable_id' => $activity->id,
                            'hashable_type' => (new Activity)->getMorphClass(),
                            'tag' => $tag,
                            'created_at' => Carbon::now()
                        ]
                    );
                }
            }
        }

        if( $this->request->is('api*') )
        {
            return response()->json($activity);
        }

        return view('commons.activity')->with('activities', [$activity])->with('type', 'full');
    }

    public function report() {
        $this->request->validate([
            'reportable_type' => 'required|string|in:App\Models\Comment,App\Models\Song,App\Models\Podcast,App\Models\Episode',
            'reportable_id' => 'required|string',
            'message' => 'required|string|max:255',
        ]);

        Report::updateOrCreate([
            'user_id' => auth()->user()->id,
            'reportable_type' => $this->request->input('reportable_type'),
            'reportable_id' => $this->request->input('reportable_id'),
        ], [
            'message' => $this->request->input('message'),
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function removeSession() {
        $this->request->validate([
            'session_id' => 'required|string',
        ]);

        $session_id = $this->request->input('session_id');

        DB::table('sessions')->where('id', $session_id)->delete();

        return response()->json(['success' => true]);
    }

    public function dobUpdate() {
        if($this->request->is('api*')) {
            $this->request->validate(
                [
                    'gender' => 'required|string|in:F,M,O',
                    'country' => 'required|string|max:3',
                    'date_of_birth' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(intval(config('settings.age_restriction'))),
                ],
                [
                    'date_of_birth.before' => __('web.SIGN_UP_AGE_RESTRICTION', ['age' => intval(config('settings.age_restriction'))]),
                ]
            );

            $dob = $this->request->input('date_of_birth');
        } else {
            $this->request->validate([
                'gender' => 'required|string|in:F,M,O',
                'country' => 'required|string|max:3',
            ]);

            $dob_day = $this->request->input('dob-day');
            $dob_month = $this->request->input('dob-month');
            $dob_year = $this->request->input('dob-year');

            $dob = $dob_year . '-' . $dob_month . '-' . $dob_day;
            $dob = Carbon::parse($dob)->format('Y-m-d');
            $this->request->merge(array('dob' => $dob));

            $this->request->validate(
                [
                    'dob' => 'required|date_format:Y-m-d|before:' . Carbon::now()->subYears(intval(config('settings.age_restriction'))),
                ],
                [
                    'dob.before' => __('web.SIGN_UP_AGE_RESTRICTION', ['age' => intval(config('settings.age_restriction'))]),
                ]
            );
        }



        $user = auth()->user();

        $user->gender = $this->request->gender;
        $user->country = $this->request->country;
        $user->birth = Carbon::parse($dob);
        $user->save();

        return response()->json(['success' => true]);
    }
}
