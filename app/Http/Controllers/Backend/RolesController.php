<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 21:04
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Role;
use DB;
use Cache;

class RolesController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $roles = Role::all();

        return view('backend.roles.index')
            ->with('roles', $roles);
    }

    public function addPost()
    {
        $this->request->validate([
            'name' => 'required|string|max:40|unique:roles',
        ]);

        $name = $this->request->input('name');

        $roleId = DB::table('roles')
            ->insertGetId([
                'name' => $name,
            ]);

        Cache::clear('usergroup');

        return redirect()->route('backend.roles.edit', ['id' => $roleId]);
    }

    public function delete()
    {
        if($this->request->route('id') == 1 && $this->request->route('id') == 5 && $this->request->route('id') == 6){
            return redirect()->route('backend.roles')->with('status', 'failed')->with('message', 'Role can not be deleted!');
        }

        DB::table('roles')
            ->where('id', $this->request->route('id'))
            ->delete();

        Cache::clear('usergroup');

        return redirect()->route('backend.roles')->with('status', 'success')->with('message', 'Role successfully deleted!');
    }

    public function edit()
    {
        $role = Role::find($this->request->route('id'));

        if(! isset($role->id)) abort(404);

        return view('backend.roles.edit')
            ->with('role', $role);
    }

    public function editPost()
    {
        $this->request->validate([
            'group_name' => 'required|string|max:40',
        ]);

        $saveRole = $this->request->input('save_role');
        $saveRole['group_name'] = $this->request->input('group_name');

        Role::where('id', $this->request->route('id'))
            ->update([
                'permissions' => json_encode($saveRole),
                'name' => $this->request->input('group_name'),
            ]);

        Cache::clear('usergroup');

        return redirect()->route('backend.roles')->with('status', 'success')->with('message', 'Role successfully updated!');
    }
}