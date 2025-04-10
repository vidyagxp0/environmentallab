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
                    <label for="name">Name <span style="color: red">*</span></label>
                    <input type="name" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter User Name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="form-group">

                    <label for="email">Email <span style="color: red">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- <div class="form-group">

                    <label for="password">Password <span style="color: red">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> -->
                    <div class="form-group">
                       <label for="password">Password <span style="color: red">*</span></label>
                       <div class="input-group">
                         <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter password" required>
                        <div class="input-group-append">
                          <span class="input-group-text" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                           </span>
                         </div>
                      </div>
                       @error('password')
                       <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

                <div class="form-group">
                    <label for="departmentid">Department Name <span style="color: red">*</span></label>
                    <select class="form-control @error('departmentid') is-invalid @enderror" id="departmentid" name="departmentid" required>
                        @foreach ($department as $temp)
                            <option value="{{ $temp->id }}">{{ $temp->name }}</option>
                        @endforeach
                    </select>
                    @error('departmentid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="roleGroup">
                    <label for="roles">Roles (Ctrl (windows) or Command (Mac) button to select multiple options)<span style="color: red">*</span></label>
                    <select class="form-control2 @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple required onchange="updateSelectedOptions()">
                        @foreach ($group as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach

                    </select>
                </div>
                <div id="selectedOptions"></div>

                <script>
                    function updateSelectedOptions() {
                        var selectElement = document.getElementById("roles");
                        var selectedOptions = [];
                        for (var i = 0; i < selectElement.options.length; i++) {
                            if (selectElement.options[i].selected) {
                                selectedOptions.push(selectElement.options[i].text);
                            }
                        }
                        document.getElementById("selectedOptions").innerHTML = "Selected roles: <br>" + selectedOptions.join("<br>");
                    }
                    function togglePasswordVisibility() {
                      const passwordField = document.getElementById('password');
                      const toggleIcon = document.getElementById('togglePasswordIcon');
    
                             if (passwordField.type === 'password') {
                                 passwordField.type = 'text';
                                 toggleIcon.classList.remove('fa-eye');
                                  toggleIcon.classList.add('fa-eye-slash');
                           } else {
                                  passwordField.type = 'password';
                                  toggleIcon.classList.remove('fa-eye-slash');
                                  toggleIcon.classList.add('fa-eye');
                                 }
                            }

                </script>


            </div>


            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <style>
            .form-control2 {
                width: 100%;
                height: 200px;
                font-size: 14px;
                line-height: 0.5;
                border-radius: 5px;
                padding: 15px 25px;
                border: 1px solid rgba(0, 0, 0, 0.1);
             }
             body:not(.layout-fixed) .main-sidebar {
                height: inherit;
                min-height: 100%;
                position: fixed;
                top: 0;
            }  select.form-control {
        height: auto;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #f9f9f9;
        font-size: 16px;
        outline: none;
        transition: all 0.3s ease;
    }

    select.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
    }

   
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
        padding: 15px;
    }

    
    #selectedOptions {
        background-color: #e9ecef;
        padding: 10px;
        border-radius: 0.25rem;
        font-size: 14px;
    }
        </style>
    </div>
    <!-- /.card -->
@endsection
