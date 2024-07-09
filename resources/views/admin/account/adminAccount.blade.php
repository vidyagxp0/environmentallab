@php
    $mainmenu = 'Admin Management';
    $submenu = 'Admin Login Account';

@endphp

@extends('admin.layout')

@section('container')

@php
    $checkAdmin = false;
    $checkSuperAdmin = DB::table('admins')->where('id', Auth::guard('admin')->user()->id)->first();
    if ($checkSuperAdmin && $checkSuperAdmin->role == 'superadmin') {
        $checkAdmin = true;
    }
@endphp

<div class="fluid-container mb-3">
    @if($checkAdmin)
        <a href="{{ route('admin_management.create') }}" class="btn btn-primary">
            New
        </a>
    @endif
</div>



    <div class="row">

        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Admin Login Accounts</h3>
                </div>


                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($admin as $user)
                            
                            <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if($checkAdmin || Auth::guard('admin')->user()->id == $user->id)
                                                <a class="mdi mdi-table-edit"
                                                href="{{ route('admin_management.edit', $user->id) }}"><button
                                                    class="btn btn-dark">Edit</button></a>
                                        @endif
                                        @if($checkAdmin)
                                            @if($user->id != 1)
                                                <form action="{{ route('admin_management.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="confirmation btn btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                $(document).ready(function() {
                                    $('.view-role').click(function() {
                                        var roleName = $(this).data('role');
                                        var roleList = roleName.split(','); // Split the role names into an array
                            
                                        // Create an unordered list
                                        var roleDisplay = $('<div><ul></ul></div>').css({
                                            'position': 'fixed',
                                            'top': '50%',
                                            'left': '78%',
                                            'transform': 'translate(-50%, -50%)',
                                            'background-color': '#fff',
                                            'padding': '20px',
                                            'border': '1px solid #000',
                                            'border-radius': '10px',
                                            'box-shadow': '0px 0px 10px rgba(0, 0, 0, 0.3)',
                                            'z-index': '9999'
                                        });
                            
                                        // Append list items for each role
                                        $.each(roleList, function(index, role) {
                                            roleDisplay.find('ul').append('<li>' + role + '</li>');
                                        });
                            
                                        // Append the list to the body
                                        $('body').append(roleDisplay);
                            
                                        // Remove the role display after a certain time
                                        setTimeout(function() {
                                            roleDisplay.remove();
                                        }, 2000); // Adjust the time (in milliseconds) as needed
                                    });
                                });
                            </script>
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                            </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->

                <!-- /.card -->
            </div>
            <!-- /.col -->


        </div>




    </div>
@endsection


@section('jquery')
@endsection
