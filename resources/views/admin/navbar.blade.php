<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ URL::to('admin/dashboard') }}" class="nav-link">Home</a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">


        

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <div class="image">
                    <img width="30" src="{{ asset('user/images/logo1.png') }}"
                        class="img-circle elevation-2" alt="User Image">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right " style="left: inherit; right: 0px;">
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right card card-widget widget-user">
                    <div class="widget-user-header bg-danger">
                        <h3 class="widget-user-username">
                             @if (Auth::guard('admin')->check())
                                {{ Auth::guard('admin')->user()->name }}
                            @endif
                        </h3>
                       
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="{{ asset('user/images/logo1.png') }}" alt="User Avatar">
                    </div>
                    <div class="card-footer m-2">
                        
                        <center> <a href="{{ url('admin/logout') }}"><i class="fas fa-sign-out-alt nav-icon"></i>LogOut
                           
                            </a> </center>
                    </div>
                </div>
            </div>
        </li>

        @if (session()->has('adminLogin'))
            @if (session()->get('adminLogin') == true)
                <li class="nav-item">
                    <a href="{{ URL::to('/admin/logout') }}"><button class="btn btn-danger">Logout</button></a>

                </li>
            @endif
        @endif


    </ul>
</nav>
<!-- /.navbar -->


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-danger elevation-4">
    <!-- Brand Logo -->
    <div class="logo-container">
                        <div class="logo" >
                            <img src="{{ asset('user/images/logo1.png') }}" alt="..." class="w-50 h-50">
                        </div>
</div>
    

    <!-- Sidebar -->
    <div class="sidebar">
        @if (session()->has('adminLogin'))
            @if (session()->get('adminLogin') == true)
            @endif
        @endif


        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-flat nav-child-indent nav-sidebar flex-column " data-widget="treeview"
                role="menu" data-accordion="false">
              

                <li class="nav-item {{ $mainmenu == 'Admin Management' ? 'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link  
                    @php
                        if($mainmenu=="Admin Management"){
                            echo "active";
                        } 
                    @endphp">
                    <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Admin Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin_management.index') }}"
                                class="nav-link 
                                @php
                                    if($submenu=="Admin Login Account"){
                                        echo "active";
                                    }
                                @endphp">
                                <i class="fas fa-users-cog nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $mainmenu == 'User Management' ? 'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link  @php
                    if($mainmenu=="User Management"){
                                                echo "active";
                                            } @endphp">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">


                        <li class="nav-item">
                            <a href="{{ route('user_management.index') }}"
                                class="nav-link @php
                                    if($submenu=="Login Account"){
                                                            echo "active";
                                                        } @endphp">
                                  <i class="fas fa-sign-in-alt nav-icon"></i>
                                <p>Login</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ $mainmenu == 'Control Management' ? 'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link  @php
                        if($mainmenu=="Control Management"){
                                                echo "active";
                                            } @endphp">
                        <i class="nav-icon fa fa-briefcase"></i>
                        <p>
                            Control Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">




                        <li class="nav-item">
                            <a href="{{ route('printcontrol.index') }}"
                                class="nav-link @php
if($submenu=="Print Control"){
                            echo "active";
                        } @endphp">
                                <i class="fa fa-print nav-icon"></i>
                                <p>Print Control</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('downloadcontrol.index') }}"
                                class="nav-link @php
if($submenu=="Download Control"){
                            echo "active";
                        } @endphp">
                                <i class="fa fa-download nav-icon"></i>
                                <p>Download Control</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item {{ $mainmenu == 'Activity' ? 'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link  @php
                    if($mainmenu=="Activity"){
                                                echo "active";
                                            } @endphp">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Activity
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">


                        <li class="nav-item ">
                            <a href="{{ route('auditTrail') }}"
                                class="nav-link @php
                                    if($submenu=="Audit Trail"){
                                                            echo "active";
                                                        } @endphp">
                                                        <p>Audit Trails
                                                        <i class="fas fa-sign-in-alt nav-icon"></i>
                                                        </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="{{ route('admin_logs') }}"
                                class="nav-link @php
                                    if($submenu=="Login Logs"){
                                                            echo "active";
                                                        } @endphp">
                                                        <p>Login Logs
                                                        <i class="fas fa-sign-in-alt nav-icon"></i>
                                                        </p>
                            </a>
                        </li>

                    </ul>
                </li>

 


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
   
       <style>
        .brand-link {
            height: 60px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .logo {
            background-color: white;
            border-radius: 10px;
            padding: 10px;
        }

        .sidebar .nav-link {
            padding: 10px 15px;
            font-size: 14px;
            color: #c2c7d0;
        }

        .sidebar .nav-link.active {
            background-color: #343a40;
            color: #ffffff;
            font-weight: bold;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .sidebar .nav-item {
            margin-bottom: 5px;
        }

        .sidebar .nav-pills .nav-treeview > .nav-item > .nav-link {
            padding-left: 30px;
        }

        .sidebar-dark-danger {
            background-color: #343a40;
        }

        .nav-pills .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        .nav-treeview > .nav-item > .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        /* Add some smooth transitions for hover effects */
        .sidebar .nav-link, .sidebar .nav-link:hover {
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
 
    <!-- /.sidebar -->
</aside>
