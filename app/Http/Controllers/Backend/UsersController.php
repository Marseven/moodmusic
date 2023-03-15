<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;

use App\Models\Activity;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Banned;
use App\Models\Comment;
use App\Models\Role;
use App\Models\Playlist;
use App\Models\Post;
use App\Models\RoleUser;
use Carbon\Carbon;
use Auth;
use Image;

class UsersController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        $users = User::withoutGlobalScopes();

        if ($this->request->has('username'))
        {
            if($this->request->has('exact_username')) {
                $users = $users->where('username', '=', $this->request->input('username'));
            } else {
                $users = $users->where('username', 'like', '%' . $this->request->input('username') . '%');
            }
        }

        if ($this->request->has('email'))
        {
            $users = $users->where('email', 'like', '%' . $this->request->input('email') . '%');
        }

        if ($this->request->input('created_from'))
        {
            $users = $users->where('created_at', '>=', Carbon::parse($this->request->input('created_from')));
        }

        if ($this->request->has('created_until'))
        {
            $users = $users->where('created_at', '<=', Carbon::parse($this->request->input('created_until')));
        }

        if ($this->request->input('logged_from'))
        {
            $users = $users->where('last_activity', '>=', Carbon::parse($this->request->input('logged_from')));
        }

        if ($this->request->has('logged_until'))
        {
            $users = $users->where('last_activity', '<=', Carbon::parse($this->request->input('logged_until')));
        }

        if ($this->request->input('comment_count_from'))
        {
            $users = $users->where('comment_count', '>=', intval($this->request->input('comment_count_from')));
        }

        if ($this->request->has('comment_count_until'))
        {
            $users = $users->where('comment_count', '<=', intval($this->request->input('comment_count_until')));
        }

        if ($this->request->has('banned'))
        {
            $users = $users->has('ban');
        }

        if ($this->request->has('comment_disabled'))
        {
            $users = $users->where('allow_comments', '=', 0);
        }

        if ($this->request->has('group'))
        {
            $role_id = $this->request->input('group');

            $users = $users->whereHas('group', function($query) use($role_id) {
                $query->where('role_id', '=', $role_id);
            });
        }

        if ($this->request->has('results_per_page'))
        {
            $users = $users->paginate(intval($this->request->input('results_per_page')));
        } else {
            $users = $users->paginate(20);
        }

        $total_users = User::count();

        return view('backend.users.index')
            ->with('users', $users)
            ->with('total_users', $total_users);
    }

    public function delete()
    {
        $user = User::findOrFail($this->request->route('id'));

        if(isset($user->group) && ($user->group->role->id == 1 && auth()->user()->group->role->id != 1)) {
            abort(403);
        }

        $user->delete();

        return redirect()->back()->with('status', 'success')->with('message', 'User successfully deleted!');
    }

    public function add()
    {
        return view('backend.users.add');
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string',
            'username' => 'required|string|alpha_dash|unique:users',
            'email' => 'nullable|email|unique:users',
        ]);

        $user = new User();

        $user->name = $this->request->input('name');
        $user->username = $this->request->input('username');
        $user->email = $this->request->input('email');
        $user->password = bcrypt($this->request->input('password'));

        if ($this->request->hasFile('artwork'))
        {
            $this->request->validate([
                'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
            ]);

            $user->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)),  intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                ->usingFileName(time(). '.jpg')
                ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
        }

        $user->save();

        //update user group
        if (Role::getValue('admin_roles')) {
            RoleUser::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'role_id' => $this->request->input('role'),
            ]);
        }

        return redirect()->route('backend.users')->with('status', 'success')->with('message', 'User successfully updated!');
    }

    public function edit()
    {
        $user = User::findOrFail($this->request->route('id'));

        if(isset($user->group) && ($user->group->role->id == 1 && auth()->user()->group->role->id != 1)) {
            abort(403);
        }

        return view('backend.users.edit')
            ->with('user', $user);
    }

    public function editPost()
    {
        $user = User::find($this->request->route('id'));

        if(isset($user->group) && ($user->group->role->id == 1 && auth()->user()->group->role->id != 1)) {
            abort(403);
        }

        $this->request->validate([
            'name' => 'required|string',
        ]);

        if ($this->request->input('username') != $user->username) {
            $this->request->validate([
                'username' => 'required|string|alpha_dash|unique:users',
            ]);
        }

        if ($this->request->input('email') != $user->email) {
            $this->request->validate([
                'email' => 'string|email|unique:users',
            ]);
        }

        $this->request->validate([
            'banned' => 'required|boolean',
        ]);


        if ($this->request->input('removeArtwork')) {
            $user->clearMediaCollection('artwork');
        } else {
            if ($this->request->hasFile('artwork')) {
                $this->request->validate([
                    'artwork' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . config('settings.max_image_file_size', 8096)
                ]);

                $user->clearMediaCollection('artwork');
                $user->addMediaFromBase64(base64_encode(Image::make($this->request->file('artwork'))->orientate()->fit(intval(config('settings.image_artwork_max', 500)), intval(config('settings.image_artwork_max', 500)))->encode('jpg', config('settings.image_jpeg_quality', 90))->encoded))
                    ->usingFileName(time() . '.jpg')
                    ->toMediaCollection('artwork', config('settings.storage_artwork_location', 'public'));
            }
        }

        //update user group
        if (Role::getValue('admin_roles')) {
            RoleUser::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'role_id' => $this->request->input('role'),
            ]);
        }

        if ($this->request->input('deleteComments')) {
            Comment::where('user_id', $user->id)->delete();
        }

        $user->name = $this->request->input('name');
        $user->username = $this->request->input('username');
        $user->email = $this->request->input('email');
        $user->banned = $this->request->input('banned');

        if($this->request->input('banned')){
            if(auth()->user()->id == $user->id) {
                return redirect()->route('backend.users.edit', ['id' => $this->request->route('id')])->with('status', 'failed')->with('message', 'You can not banned yourself.');
            }
            Banned::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'reason' => $this->request->input('ban_reason'),
                'end_at' => $this->request->input('ban_end_at') ? Carbon::parse($this->request->input('ban_end_at')) : Carbon::now()->addYears(),
            ]);
        } else {
            Banned::where('user_id', $user->id)->delete();
        }

        if($this->request->input('password'))
        {
            $user->password = bcrypt($this->request->input('password'));
        }

        if(isset($user->distributor)) {
            if($this->request->input('distributor'))
            {
                $user->distributor = 1;
            } else {
                $user->distributor = 0;
            }
        }


        $user->save();

        return redirect()->route('backend.users')->with('status', 'success')->with('message', 'User successfully updated!');

    }

    public function massAction()
    {
        $this->request->validate([
            'action' => 'required|string',
            'ids' => 'required|array',
        ]);
        if($this->request->input('action') == 'change_usergroup') {
            if(Role::getValue('admin_roles')) {
                $message = 'Change usergroup';
                $subMessage = 'Change User Group for Chosen Users (<strong>' . count($this->request->input('ids')) . '</strong>)';
                return view('backend.commons.mass_usergroup')
                    ->with('message', $message)
                    ->with('subMessage', $subMessage)
                    ->with('action', $this->request->input('action'))
                    ->with('ids', $this->request->input('ids'));
            } else {
                abort(403);
            }
        } else if($this->request->input('action') == 'save_change_usergroup') {
            if(Role::getValue('admin_roles')) {
                $ids = $this->request->input('ids');
                foreach($ids as $id) {
                    $user = User::withoutGlobalScopes()->find($id);
                    if(isset($user->id)){
                        RoleUser::updateOrCreate([
                            'user_id' => $user->id,
                        ], [
                            'role_id' => $this->request->input('role'),
                        ]);
                    }
                }
                return redirect()->route('backend.users')->with('status', 'success')->with('message', 'Users successfully saved!');
            } else {
                abort(403);
            }
        } elseif($this->request->input('action') == 'ban_user') {
            $message = 'Ban user';
            $subMessage = 'Ban Chosen Users (<strong>' . count($this->request->input('ids')) . '</strong>)';
            return view('backend.commons.mass_ban_user')
                ->with('message', $message)
                ->with('subMessage', $subMessage)
                ->with('action', $this->request->input('action'))
                ->with('ids', $this->request->input('ids'));
        } else if($this->request->input('action') == 'save_ban_user') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $user = User::withoutGlobalScopes()->find($id);
                if(isset($user->id)){
                    if(auth()->user()->id != $user->id) {
                        Banned::updateOrCreate([
                            'user_id' => $user->id,
                        ], [
                            'reason' => $this->request->input('ban_reason'),
                            'end_at' => Carbon::now()->addYears(),
                        ]);
                    }
                }
            }
            return redirect()->route('backend.users')->with('status', 'success')->with('message', 'Users successfully banned!');
        } else if($this->request->input('action') == 'delete_comment') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                Comment::withoutGlobalScopes()->where('user_id', $id)->delete();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'User\'s comments successfully deleted!');
        } else if($this->request->input('action') == 'delete') {
            $ids = $this->request->input('ids');
            foreach($ids as $id) {
                $user = User::withoutGlobalScopes()->where('id', $id)->first();
                $user->delete();
            }
            return redirect()->back()->with('status', 'success')->with('message', 'Users successfully deleted!');
        }
    }

}