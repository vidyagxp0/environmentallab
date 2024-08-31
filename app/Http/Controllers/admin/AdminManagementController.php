<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\GroupCategory;
use App\Models\QMSDivision;
use App\Models\QMSProcess;
use App\Models\QMSRoles;
use App\Models\RoleGroup;
use App\Models\Department;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{

    public function index()
    {
        $admin = Admin::get();
        return view('admin.account.adminAccount', compact('admin'));
    }

    public function create()
    {
        return view('admin.account.adminCreate');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
        ]);

        $admins = Admin::all()->count();
        $users = User::all()->count();
        
        $total_users =  $admins + $users;

        if ($total_users <= 40)
        {
            $user = new Admin();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->roles;
            $user->password = Hash::make($request->password);
            if($user){
                $user->save();
                toastr()->success('User added successfully');
                return redirect()->route('admin_management.index');
            } else {
                toastr()->error('Something went wrong');
                return redirect()->back();
            }
        } else {
            toastr()->error('Cannot add more than 40 users!');
            return redirect()->back();
        }

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Admin::find($id);
        return view('admin.account.adminEdit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::find($id);    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->roles;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

    if ($user) {
        $user->save();
            toastr()->success('Update successfully');
            return redirect()->route('admin_management.index');
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
        $user = Admin::find($id);

        if ($user->delete()) {
            toastr()->success('Deleted successfully');
            return redirect()->back();
        } else {
            toastr()->error('Something went wrong');
            return redirect()->back();
        }
    }
}
