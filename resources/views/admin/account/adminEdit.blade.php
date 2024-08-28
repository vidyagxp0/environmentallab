@php
    $mainmenu = 'Admin Management';
    $submenu = 'Admin Login Account';

@endphp

@extends('admin.layout')


@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Admin Account </h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('admin_management.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">

                <div class="form-group">

                    <label for="exampleInputName1">Name <span style="color: red">*</span></label>
                    <input type="name" name="name" class="form-control" value="{{ $data->name }}"
                        id="exampleInputName1" placeholder="Enter User Name" required>
                </div>



                <div class="form-group">

                    <label for="exampleInputName1">Email <span style="color: red">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ $data->email }}"
                        id="exampleInputName1" placeholder="enter email" required>
                </div>

                <div class="form-group">

                    <label for="exampleInputName1">Password <span style="color: red">*</span></label>
                    <input type="name" name="password" class="form-control" id="exampleInputName1"
                        placeholder="Enter password">
                </div>

                <div class="form-group" id="roleGroup">
                    <label for="exampleInputName1">Roles <span style="color: red">*</span></label>
                    <select class="form-control2" id="roles" name="roles"  required>
                        @if($data->role == 'superadmin')<option value="superadmin" {{ $data->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>@else
                        <option value="admin" {{ $data->role == 'admin' ? 'selected' : '' }}>Admin</option>@endif

                    </select>
                </div>
            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <style>
            .form-control2 {
                width: 100%;
                /* height: 200px; */
                font-size: 14px;
                line-height: 0.5;
                border-radius: 5px;
                padding: 5px 15px;
                border: 1px solid rgba(0, 0, 0, 0.1);
             }
             /* body:not(.layout-fixed) .main-sidebar {
                height: inherit;
                min-height: 100%;
                position: fixed;
                top: 0; 
            }*/
        </style>
    </div>
    <!-- /.card -->
@endsection
