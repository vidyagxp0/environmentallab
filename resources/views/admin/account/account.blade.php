@php
    $mainmenu = 'User Management';
    $submenu = 'Login Account';
@endphp

@extends('admin.layout')

@section('container')
    <div class="fluid-container mb-3">
        <a href="{{ route('user_management.create') }}" class="btn btn-primary">
            New Account
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Login Account</h3>
                </div>

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $RoleList = DB::table('user_roles')->where(['user_id' => $user->id])->pluck('role_id')->toArray();
                                    $role = '';
                                    $roleName = '';
                                    if ($RoleList) {
                                        $role = implode(',', $RoleList);
                                        $roleNameList = DB::table('q_m_s_roles')
                                            ->whereIn('id', $RoleList)
                                            ->pluck('name')
                                            ->toArray();
                                        $roleName = implode(',', $roleNameList);
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->dname }}</td>
                                    <td>
                                        <button class="btn btn-dark view-role" data-role="{{ $roleName }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ route('user_management.edit', $user->id) }}" class="btn btn-dark">Edit</a>
                                        <form action="{{ route('user_management.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="confirmation btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom CSS */
        .table thead th {
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            background-color: #23272b;
            border-color: #1d2124;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bd2130;
            border-color: #b21f2d;
        }

        .role-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        .role-popup ul {
            list-style-type: none;
            padding-left: 0;
        }

        .role-popup li {
            margin-bottom: 10px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-role').click(function() {
                var roleName = $(this).data('role');
                var roleList = roleName.split(','); // Split the role names into an array

                // Create an unordered list for roles
                var roleDisplay = $('<div class="role-popup"><ul></ul></div>');

                // Append list items for each role
                $.each(roleList, function(index, role) {
                    roleDisplay.find('ul').append('<li>' + role + '</li>');
                });

                // Append the role display to the body
                $('body').append(roleDisplay);

                // Remove the role display after 2 seconds
                setTimeout(function() {
                    roleDisplay.remove();
                }, 2000); // Adjust the time (in milliseconds) as needed
            });
        });
    </script>
@endsection
