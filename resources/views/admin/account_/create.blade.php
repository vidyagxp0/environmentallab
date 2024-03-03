@php
    $mainmenu = 'User Management';
    $submenu = 'Login Account';

@endphp

@extends('admin.layout')


@section('container')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Create Account </h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('user_management.store') }}" method="POST">
            @csrf

            <div class="card-body">

                <div class="form-group">

                    <label for="name">Name*</label>
                    <input type="name" name="name" class="form-control" id="name" placeholder="Enter User Name"
                        required>
                </div>



                <div class="form-group">

                    <label for="exampleInputName1">email*</label>
                    <input type="email" name="email" class="form-control" id="exampleInputName1"
                        placeholder="enter email" required>
                </div>

                <div class="form-group">

                    <label for="exampleInputName1">password*</label>
                    <input type="name" name="password" class="form-control" id="exampleInputName1"
                        placeholder="Enter password">
                </div>
                <div class="form-group">

                    <label for="exampleInputName1">Department Name*</label>
                    <select class="form-control" id="departmentid" name="departmentid" required />
                    <option class="selected disabled hidden;">Select Deaprtment Name</option>
                    @foreach ($department as $temp)
                        <option value="{{ $temp->id }}">{{ $temp->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">

                    <label for="exampleInputName1">Role*</label>
                    <select id="choices-multiple-remove-button" placeholder="Select the person.." id="role_id" name="role_id[]" multiple>


                        @foreach ($group as $div)
                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                        @endforeach

                    </select>
                </div>


            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
@endsection
