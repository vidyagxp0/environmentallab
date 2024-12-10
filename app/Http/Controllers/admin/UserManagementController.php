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
use Illuminate\Support\Facades\Auth;
use App\Models\AdminLoginAuditTrail;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


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
        $group = Roles::all();
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'departmentid' => 'required',
            'roles' => 'required|array',
        ]);

        // $user = new User();
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        // $user->departmentid = $request->departmentid;
        // $usertableRole = '';
        // if ($user->save()) {
        //     foreach ($request->roles as $roleId) {
        //     $userRole = new UserRole();
        //     $checkRole = Roles::find($roleId);

        //     // Split the string using the '-' delimiter
        //     $roleArray = explode('-', $checkRole->name);

        //     // Assign values to three variables
        //     $q_m_s_divisions_name = trim($roleArray[0]);
        //     $q_m_s_processes_name = trim($roleArray[1]);
        //     $q_m_s_roles_name = trim($roleArray[2]);

        //     // Assuming you have models for q_m_s_divisions and q_m_s_process
        //     $division = QMSDivision::where('name', $q_m_s_divisions_name)->first();
        //     $process = QMSProcess::where('process_name', $q_m_s_processes_name)->first();
        //     $qmsroles = QMSRoles::where('name', $q_m_s_roles_name)->first();
        //     $q_m_s_divisions_id = $division->id;
        //     $q_m_s_processes_id = $process->id;
        //     $q_m_s_roles_id = $qmsroles->id;

        //     $usertableRole = //concatinate the $q_m_s_roles_id by comma seprated
        //     $userRole->user_id = $user->id;
        //     $userRole->role_id = $roleId;
        //     $userRole->q_m_s_divisions_id = $q_m_s_divisions_id;
        //     $userRole->q_m_s_processes_id = $q_m_s_processes_id;
        //     $userRole->q_m_s_roles_id = $q_m_s_roles_id;
        //     $userRole->save();
        // }

        $admins = Admin::all()->count();
        $users = User::all()->count();

        $total_users =  $admins + $users;
        if ($total_users <= 40) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->departmentid = $request->departmentid;
            $usertableRole = ''; // Initialize the variable to store concatenated role IDs
            if ($user->save()) {
                foreach ($request->roles as $roleId) {
                    $userRole = new UserRole();
                    $checkRole = Roles::find($roleId);

                    // Ensure $checkRole is not null before proceeding
                    if (!$checkRole) {
                        \Log::error('Role not found for ID: ' . $roleId);
                        continue; // Skip to the next role
                    }

                    // Split the string using the '-' delimiter
                    $roleArray = explode('-', $checkRole->name);

                    // Assign values to three variables
                    $q_m_s_divisions_name = trim($roleArray[0]);
                    $q_m_s_processes_name = trim($roleArray[1]);
                    $q_m_s_roles_name = trim($roleArray[2]);

                    // Check for null division
                    $division = QMSDivision::where('name', $q_m_s_divisions_name)->first();
                    if (!$division) {
                        \Log::error('Division not found for name: ' . $q_m_s_divisions_name);
                        continue; // Skip to the next role
                    }

                    // Check for null process
                    $process = QMSProcess::where(['division_id' => $division->id, 'process_name' => $q_m_s_processes_name])->first();
                    if (!$process) {
                        \Log::error('Process not found for division_id: ' . $division->division_id . ' and process_name: ' . $q_m_s_processes_name);
                        continue; // Skip to the next role
                    }

                    // Check for null role
                    $qmsroles = QMSRoles::where('name', $q_m_s_roles_name)->first();
                    if (!$qmsroles) {
                        \Log::error('QMS Role not found for name: ' . $q_m_s_roles_name);
                        continue; // Skip to the next role
                    }

                    // Assign IDs after checking that the entities exist
                    $q_m_s_divisions_id = $division->id;
                    $q_m_s_processes_id = $process->id;
                    $q_m_s_roles_id = $qmsroles->id;

                    // Concatenate the q_m_s_roles_id with previous ones
                    $usertableRole .= $q_m_s_roles_id . ',';

                    $userRole->user_id = $user->id;
                    $userRole->role_id = $roleId;
                    $userRole->q_m_s_divisions_id = $q_m_s_divisions_id;
                    $userRole->q_m_s_processes_id = $q_m_s_processes_id;
                    $userRole->q_m_s_roles_id = $q_m_s_roles_id;
                    $userRole->save();
                }
                // Remove the trailing comma from the concatenated string
                $usertableRole = rtrim($usertableRole, ',');

                // Explode the concatenated string into an array
                $rolesArray = explode(',', $usertableRole);

                // Remove duplicate entries
                $uniqueRolesArray = array_unique($rolesArray);

                // Implode the unique array back into a string
                $uniqueUsertableRole = implode(',', $uniqueRolesArray);

                // Update the user table with the unique concatenated role IDs
                $user->role = $uniqueUsertableRole;

                $user->save();

                if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {

                    if (!empty($request->name)) {
                        $validation2 = new AdminLoginAuditTrail();
                        $validation2->adminAudit_id = $user->id;
                        $validation2->previous = "Null";
                        $validation2->current = $request->name;
                        $validation2->activity_type = 'Name';
                        $validation2->user_id = Auth::guard('admin')->user()->id;
                        $validation2->user_name = Auth::guard('admin')->user()->name;
                        $validation2->user_role = Auth::guard('admin')->user()->role;
                        $validation2->change_to = "";
                        $validation2->change_from = "";
                        $validation2->action_name = 'Create';
                        $validation2->save();
                    }
                
                    // Check if email is provided
                    if (!empty($request->email)) {
                        $validation2 = new AdminLoginAuditTrail();
                        $validation2->adminAudit_id = $user->id;
                        $validation2->previous = "Null";
                        $validation2->current = $request->email;
                        $validation2->activity_type = 'Email';
                        $validation2->user_id = Auth::guard('admin')->user()->id;
                        $validation2->user_name = Auth::guard('admin')->user()->name;
                        $validation2->user_role = Auth::guard('admin')->user()->role;
                        $validation2->change_to = "";
                        $validation2->change_from = "";
                        $validation2->action_name = 'Create';
                        $validation2->save();
                    }
                
                    // Check if department is provided
                    if (!empty($request->departmentid)) {
                        $departmentName = Department::where('id', $request->departmentid)->value('name');
                
                        if (!$departmentName) {
                            \Log::error('Department not found for ID: ' . $request->departmentid);
                            $departmentName = 'Unknown';
                        }
                
                        $validation2 = new AdminLoginAuditTrail();
                        $validation2->adminAudit_id = $user->id;
                        $validation2->previous = "Null";
                        $validation2->current = $departmentName;
                        $validation2->activity_type = 'Department';
                        $validation2->user_id = Auth::guard('admin')->user()->id;
                        $validation2->user_name = Auth::guard('admin')->user()->name;
                        $validation2->user_role = Auth::guard('admin')->user()->role;
                        $validation2->change_to = "";
                        $validation2->change_from = "";
                        $validation2->action_name = 'Create';
                        $validation2->save();
                    }
                
                    // Check if role is provided
                    if (!empty($usertableRole)) {
                        $roleNames = DB::table('role_groups')
                            ->whereIn('id', explode(',', $usertableRole))
                            ->pluck('name')
                            ->toArray();
                
                        $roleNamesString = implode(', ', $roleNames);
                
                        $validation2 = new AdminLoginAuditTrail();
                        $validation2->adminAudit_id = $user->id;
                        $validation2->previous = "Null";
                        $validation2->current = $roleNamesString;
                        $validation2->activity_type = 'Roles';
                        $validation2->user_id = Auth::guard('admin')->user()->id;
                        $validation2->user_name = Auth::guard('admin')->user()->name;
                        $validation2->user_role = Auth::guard('admin')->user()->role;
                        $validation2->change_to = "";
                        $validation2->change_from = "";
                        $validation2->action_name = 'Create';
                        $validation2->save();
                    }
                }
                


                toastr()->success('User added successfully');
                return redirect()->route('user_management.index');
            } else {
                toastr()->error('Something went wrong');
                return redirect()->back();
            }
        } else {
            toastr()->error('Cannot add more than 40 users!');
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
        $group = Roles::all();
        $department = Department::all();

        $data = User::find($id);
        $userRoles = UserRole::where('user_id', $data->id)->pluck('role_id')->toArray();

        // dd($data->id, $userRoles);
        return view('admin.account.edit', compact('group', 'data','department', 'userRoles'));
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
        $user = User::with('userRoles')->find($id);
        $lastUser = User::with('userRoles')->find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->departmentid = $request->departmentid;

        if ($user->save()) {
            // Delete existing user roles
            $user->userRoles()->delete();

            // Attach new roles
            foreach ($request->roles as $roleId) {
                $userRole = new UserRole();
                $checkRole = Roles::find($roleId);

                // Split the string using the '-' delimiter
                $roleArray = explode('-', $checkRole->name);

                // Assign values to three variables
                $q_m_s_divisions_name = trim($roleArray[0]);
                $q_m_s_processes_name = trim($roleArray[1]);
                $q_m_s_roles_name = trim($roleArray[2]);
                // Assuming you have models for q_m_s_divisions and q_m_s_process
                $division = QMSDivision::where('name', $q_m_s_divisions_name)->first();
                $process = QMSProcess::where([
                    'process_name' => $q_m_s_processes_name,
                    'division_id' => $division->id
                ])->first();
                $qmsroles = QMSRoles::where('name', $q_m_s_roles_name)->first();
                $q_m_s_divisions_id = $division->id;
                $q_m_s_processes_id = $process->id;
                $q_m_s_roles_id = $qmsroles->id;
                $userRole->user_id = $user->id;
                $userRole->role_id = $roleId;
                $userRole->q_m_s_divisions_id = $q_m_s_divisions_id;
                $userRole->q_m_s_processes_id = $q_m_s_processes_id;
                $userRole->q_m_s_roles_id = $q_m_s_roles_id;
                $userRole->save();
            }


            if ($lastUser->name != $request->name) {
                $validation2 = new AdminLoginAuditTrail();
                $validation2->adminAudit_id = $user->id;
                $validation2->previous = $lastUser->name;
                $validation2->current = $request->name;
                $validation2->activity_type = 'Name';
                $validation2->user_id = Auth::user()->id;
                $validation2->user_name = Auth::user()->name;
                $validation2->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
    
                $validation2->change_to =   "Not Applicable";
                $validation2->change_from = $lastUser->status;
                if (is_null($lastUser->name) || $lastUser->name === '') {
                    $validation2->action_name = 'New';
                } else {
                    $validation2->action_name = 'Update';
                }
                $validation2->save();
            }

            if ($lastUser->email != $request->email) {
                $validation2 = new AdminLoginAuditTrail();
                $validation2->adminAudit_id = $user->id;
                $validation2->previous = $lastUser->email;
                $validation2->current = $request->email;
                $validation2->activity_type = 'Email';
                $validation2->user_id = Auth::user()->id;
                $validation2->user_email = Auth::user()->email;
                $validation2->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
    
                $validation2->change_to =   "Not Applicable";
                $validation2->change_from = $lastUser->status;
                if (is_null($lastUser->email) || $lastUser->email === '') {
                    $validation2->action_name = 'New';
                } else {
                    $validation2->action_name = 'Update';
                }
                $validation2->save();
            }

            if ($lastUser->departmentid != $request->departmentid) {
                $currentDepartmentName = Department::where('id', $request->departmentid)->value('name');
                
                $previousDepartmentName = Department::where('id', $lastUser->departmentid)->value('name');
                
                if (!$currentDepartmentName) {
                    \Log::error('Department not found for ID: ' . $request->departmentid);
                    $currentDepartmentName = 'Unknown';
                }
                if (!$previousDepartmentName) {
                    \Log::error('Department not found for ID: ' . $lastUser->departmentid);
                    $previousDepartmentName = 'Unknown';
                }
            
                $validation2 = new AdminLoginAuditTrail();
                $validation2->adminAudit_id = $user->id;
                $validation2->previous = $previousDepartmentName;
                $validation2->current = $currentDepartmentName;
                $validation2->activity_type = 'Department';
                $validation2->user_id = Auth::user()->id;
                $validation2->user_name = Auth::user()->name;
                $validation2->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $validation2->change_to = $currentDepartmentName;
                $validation2->change_from = $previousDepartmentName;
                if (is_null($previousDepartmentName->email) || $previousDepartmentName->email === '') {
                    $validation2->action_name = 'New';
                } else {
                    $validation2->action_name = 'Update';
                }
                $validation2->save();
            }

            if ($lastUser->role != $request->role) {
                $oldRoleNames = DB::table('role_groups')
                    ->whereIn('id', explode(',', $lastUser->role))
                    ->pluck('name')
                    ->toArray();
                $oldRoleNamesString = implode(', ', $oldRoleNames);
            
                $newRoleNames = DB::table('role_groups')
                    ->whereIn('id', explode(',', $request->role))
                    ->pluck('name')
                    ->toArray();
                $newRoleNamesString = implode(', ', $newRoleNames);
            
                if (empty($oldRoleNamesString)) {
                    $oldRoleNamesString = 'Null';
                }
                if (empty($newRoleNamesString)) {
                    $newRoleNamesString = 'Unknown';
                }
            
                $validation2 = new AdminLoginAuditTrail();
                $validation2->adminAudit_id = $user->id;
                $validation2->previous = $oldRoleNamesString;
                $validation2->current = $newRoleNamesString;
                $validation2->activity_type = 'Roles'; 
                $validation2->user_id = Auth::user()->id;
                $validation2->user_name = Auth::user()->name;
                $validation2->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $validation2->change_to = "Not Applicable";
                $validation2->change_from = $lastUser->status;
            
                if (is_null($lastUser->role) || $lastUser->role === '') {
                    $validation2->action_name = 'New';
                } else {
                    $validation2->action_name = 'Update';
                }
            
                $validation2->save();
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


    public function auditTrail()
    {
        //  $users = DB::table('users')
        // ->select('id', 'name', 'email')
        // ->get();
        $users = User::all();
        // $adminUsers = $users->filter(function ($user) {
        //     $RoleList = DB::table('user_roles')->where(['user_id' => $user->id])->pluck('role_id')->toArray();
        //     return in_array(2, $RoleList);
        // });
        $admin_audit = AdminLoginAuditTrail::paginate(5);

        return view('admin.account.admin_auditTrail', compact('users','admin_audit'));
    }

    public function showLogs()
    {
        $logs = \DB::table('admin_login_logs')->orderBy('created_at', 'desc')->get();
        return view('admin.account.login_logs', compact('logs'));
    }
}
