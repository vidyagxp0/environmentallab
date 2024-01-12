<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\GroupCategory;
use App\Models\RoleGroup;
use App\Models\Department;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $users = user::leftJoin("departments", "departments.id", "=", "users.departmentid")
        ->get(['users.*', 'departments.name as dname']);
        return view('admin.account.account', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $group = RoleGroup::all();
        $department = Department::all();
        return view('admin.account.create', compact('group','department'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // $user->role = $request->role_id;
        $user->departmentid = $request->departmentid;
        if ($user->save()) {
            foreach($request->role_id as $temp){
                $role = new UserRole();
                $role->user_id = $user->id;
                $role->role_id = $temp;
                $role->save();
            }
            toastr()->success('Added successfully');
            return redirect()->route('user_management.index');
        } else {
            toastr()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $group = RoleGroup::all();
        $department = Department::all();

        $data = User::find($id);
        return view('admin.account.edit', compact('group', 'data','department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        // $user->role = $request->role_id;
        $user->departmentid = $request->departmentid;
        if ($user->update()) {
            if($request->role_id){
                $data = UserRole::where('user_id',$id)->get();
                foreach($data as $datas){
                    $datas->delete();
                }
                foreach($request->role_id as $temp){
                    $role = new UserRole();
                    $role->user_id = $user->id;
                    $role->role_id = $temp;
                    $role->save();
                }
            }

            toastr()->success('Update successfully');
            return redirect()->route('user_management.index');
        } else {
            toastr()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);

        if ($user->delete()) {
            toastr()->success('Deleted successfully');
            return redirect()->back();
        } else {
            toastr()->error('Something went wrong');
            return redirect()->back();
        }
    }
}
