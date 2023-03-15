<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-06-18
 * Time: 13:09
 */

namespace App\Http\Controllers\Backend;

use App\Models\Artist;
use App\Models\Episode;
use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Response;
use DB;
use App\Models\Song;
use Config;
use Image;
use Storage;
use App\Models\Upload;
use View;
use Route;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Email;
use App\Models\Role;

class AdminAuthController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getLogin()
    {
        if (auth()->check()) {
            //if is already logged in
            return redirect(route('backend.dashboard'));
        } else {
            return view('backend.login');
        }

    }

    public function postLogin()
    {

        $this->request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $login = [
            'email' => $this->request->input('email'),
            'password' => $this->request->input('password'),
        ];
        if (auth()->attempt($login, $this->request->input('remember'))) {
            if(Role::getValue('admin_access')) {
                return redirect(route('backend.dashboard'));
            } else {
                return redirect()->back()->with('status', 'failed')->with('message', trans('auth.failed'));
            }
        } else {
            return redirect()->back()->with('status', 'failed')->with('message', trans('auth.failed'));
        }
    }

    /**
     * action admin/logout
     * @return RedirectResponse
     */
    public function getLogout()
    {
        auth()->logout();
        return redirect()->route('backend.login');
    }

    public function forgotPassword()
    {
        return view('backend.forgot-password');
    }

    public function forgotPasswordPost(){

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

        (new Email)->resetPassword($user, route('backend.reset-password', ['token' => $token]));

        return redirect()->back()->with('status', 'success')->with('message', trans('passwords.sent'));
    }

    public function resetPassword()
    {
        $row = DB::table("password_resets")->select('email')->where('token', $this->request->route('token'))->first();

        if(isset($row->email))
        {
            $user = User::where('email',  $row->email)->firstOrFail();
            /**
             * Log user in then show the change password form
             */
            auth()->login($user);
            return view('backend.reset-password');
        } else {
            return redirect()->route('backend.forgot-password')->with('status', 'failed')->with('message', trans('Your reset code is invalid or has expired.'));
        }
    }

    public function resetPasswordPost()
    {
        if(! auth()->check())
        {
            abort('403');
        }

        $this->request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        /**
         * Change user password
         */
        $user = auth()->user();
        $user->password = bcrypt($this->request->input('password'));
        $user->save();

        /**
         * Delete password reset token
         */
        DB::table("password_resets")->where('email', $user->email)->delete();

        return redirect()->route('backend.dashboard')->with('status', 'success')->with('message', __('passwords.reset'));
    }

    public function addSong(){
        $this->request->validate([
            'object_type' => 'required|string',
            'object_id' => 'required|int',
            'song_id' => 'required|int',
        ]);
        $object_type = $this->request->input('object_type');
        $object_id = $this->request->input('object_id');
        $song_id = $this->request->input('song_id');


        if($object_type == 'playlist')
        {
            try {
                DB::table('playlist_songs')->insert(
                    [ 'song_id' => $song_id, 'playlist_id' => $object_id ]
                );
            } catch (\Exception $e) {

            }
        } else if($object_type == 'album') {
            try {
                DB::table('album_songs')->insert(
                    [ 'song_id' => $song_id, 'album_id' => $object_id ]
                );
            } catch (\Exception $e) {

            }
        }

        return response()->json(Song::findOrFail($song_id));
    }

    public function removeSong(){
        $this->request->validate([
            'object_type' => 'required|string',
            'object_id' => 'required|int',
            'song_id' => 'required|int',
        ]);
        $object_type = $this->request->input('object_type');
        $object_id = $this->request->input('object_id');
        $song_id = $this->request->input('song_id');


        if($object_type == 'playlist')
        {
            DB::table('playlist_songs')
                ->where('playlist_id', $object_id)
                ->where('song_id', $song_id)
                ->delete();
        } else if($object_type == 'album') {
            DB::table('album_songs')
                ->where('album_id', $object_id)
                ->where('song_id', $song_id)
                ->delete();
        }

        return Response::json(array(
            'success' => true
        ), 200);
    }

    public function upload(){
        /**
         * Call upload function, set isAdminPanel = true to force script automatic general info from ID3
         */

        if($this->request->routeIs('backend.artist.upload.bulk')) {
            $song = (new Upload)->handle($this->request, $this->request->route('artistId'), null, true);
        }  elseif($this->request->routeIs('backend.album.upload.bulk')) {
            $song = (new Upload)->handle($this->request, null, $this->request->route('album_id'), true);
        } else {
            $song = (new Upload)->handle($this->request, null, null, true);
        }

        return response()->json($song);
    }

    public function uploadEpisode(){
        $episode = (new Upload)->handleEpisode($this->request, $this->request->route('podcast_id'));
        return response()->json($episode);
    }

    public function editSong(){
        $this->request->validate([
            'id' => 'required|numeric',
            'title' => 'required|max:100',
            'artistIds' => 'required|array',
            'copyright' => 'nullable|string|max:100',
            'created_at' => 'nullable|date_format:m/d/Y|after:' . Carbon::now(),
        ]);

        $song = Song::withoutGlobalScopes()->findOrFail($this->request->input('id'));

        $artistIds = $this->request->input('artistIds');
        if(is_array($artistIds))
        {
            $song->artistIds = implode(",", $this->request->input('artistIds'));
        }

        if ($this->request->hasFile('artwork')) {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);
            $song->clearMediaCollection('artwork');
            $song->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $song->title = $this->request->input('title');
        $genre = $this->request->input('genre');
        $mood = $this->request->input('mood');

        if($this->request->input('created_at'))
        {
            $song->created_at = Carbon::parse($this->request->input('created_at'));
        }

        if(is_array($genre))
        {
            $song->genre = implode(",", $this->request->input('genre'));
        }

        if(is_array($mood))
        {
            $song->mood = implode(",", $this->request->input('mood'));
        }

        $song->copyright = $this->request->input('copyright');
        $song->save();

        return response()->json($song);

    }

    public function editEpisode(){
        $this->request->validate([
            'id' => 'required|numeric',
            'title' => 'required|max:100',
            'description' => 'nullable|string',
            'season' => 'nullable|numeric',
            'number' => 'nullable|numeric',
            'type' => 'nullable|numeric:in:1,2,3',
            'created_at' => 'nullable|date_format:m/d/Y',
        ]);

        $episode = Episode::withoutGlobalScopes()->findOrFail($this->request->input('id'));

        $episode->title = $this->request->input('title');
        $episode->description = $this->request->input('description');
        $episode->season = $this->request->input('season');
        $episode->number = $this->request->input('number');
        $episode->type = $this->request->input('type');

        if($this->request->input('created_at'))
        {
            $episode->created_at = Carbon::parse($this->request->input('created_at'));
        }

        $episode->approved = 1;

        if($this->request->input('visibility')) {
            $episode->visibility = 1;
        } else {
            $episode->visibility = 0;
        }

        if($this->request->input('downloadable')) {
            $episode->allow_download = 1;
        } else {
            $episode->allow_download = 0;
        }

        $episode->save();

        if($this->request->is('api*') || $this->request->wantsJson())
        {
            return response()->json($episode);
        } else {
            return redirect()->back()->with('status', 'success')->with('message', 'Episode successfully edited!');
        }
    }
}