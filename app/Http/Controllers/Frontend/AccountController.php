<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 21:20
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Email;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use View;
use App\Models\User;
use Auth;
use Hash;

class AccountController
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function sendResetPassword()
    {
        $this->request->validate([
            'email' => 'string|email|exists:users',
        ]);

        $user = User::where('email',  $this->request->input('email'))->firstOrFail();

        $row = DB::table("password_resets")->select('email')->where('email', $user->email)->first();
        $token = Str::random(60);

        if(isset($row->email))
        {
            DB::table("password_resets")->where('email', $user->email)->update([
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

        } else {
            DB::table("password_resets")->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        (new Email)->resetPassword($user, route('frontend.account.reset.password', ['token' => $token]));

        return response()->json([
            'message' => __('passwords.sent')
        ]);
    }

    public function resetPassword(){
        $row = DB::table('password_resets')->where('token', '=', $this->request->route('token'))->first();

        if(isset($row->email)) {
            auth()->logout();
            $user = User::where('email', '=', $row->email)->firstOrFail();

            auth()->loginUsingId($user->id);

            $view = View::make('account.reset-password')->with('token', $this->request->route('token'));

            if($this->request->ajax()) {
                $sections = $view->renderSections();
                return $sections['content'];
            }

            getMetatags();

            return $view;

        } else {
            abort(403, 'Reset password token is invalid.');
        }
    }

    public function setNewPassword(){
        $this->request->validate([
            'password' => 'required|string|min:6|max:32|confirmed',
            'token' => 'required|string'

        ]);

        $row = DB::table('password_resets')->where('token', '=', $this->request->input('token'))->first();

        if(isset($row->email) && $row->email == auth()->user()->email) {
            $user = auth()->user();
            $user->password = Hash::make($this->request->input('password'));
            $user->save();
            Auth::setUser($user);

            DB::table('password_resets')->where('token', '=', $this->request->input('token'))->delete();

            return response()->json($this->request->user());

        } else {
            abort(403, 'Reset password token is invalid.');
        }
    }

    public function verifyEmail(){
        $user = User::where('email_verified_code', $this->request->route('code'))->first();

        if(isset($user->id) && $user->email) {
            if($user->email_verified) {
                $message = __('auth.email_verification_verified');
            } else {
                $user->email_verified = 1;
                $user->email_verified_at = Carbon::now();
                $user->save();
                $message = __('auth.email_verification_success');
                (new Email)->newUser($user);

                Auth::loginUsingId($user->id);
                header("Refresh: 5; URL=" . route('frontend.homepage'));

            }
        } else {
            $message = __('auth.email_verification_invalid');
        }

        return View::make('account.verify-email')->with('message', $message);
    }
}