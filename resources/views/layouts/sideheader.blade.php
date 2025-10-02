<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <title>SBMS - Admin Dashboard</title>
    <meta content="Admin Dashboard" name="description">
    <meta content="Pooja Mane" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/plugins/morris/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body class="fixed-left"><!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div><!-- Begin page -->

    <div id="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect"><i class="ion-close"></i></button>
            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center"><a href="index.html" class="logo"><i class="mdi mdi-assistant"></i> SBMS</a>
                    <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> --></div>
            </div>
            <div class="sidebar-inner slimscrollleft">
                <div id="sidebar-menu">
                    <ul>
                        @if (session('user_type') === 'admin')
                        <li class="menu-title">Main</li>
                        <li><a href="{{route('dashboard')}}" class="waves-effect">
                                <i class="mdi mdi-airplay"></i>
                                <span>Dashboard
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('employees') }}"
                                class="waves-effect {{ request()->routeIs('employees', 'employee.profileview', 'employee.edit') ? 'active' : '' }}">
                                <i class="mdi mdi-account-multiple"></i>
                                <span>Employees</span>
                            </a>
                        </li>

                        <li><a href="{{route('attendance')}}" class="waves-effect {{ request()->routeIs('attendance', 'employee.view', 'employee.attendance') ? 'active' : '' }}">
                                <i class="mdi mdi-book-multiple"></i>
                                <span>Attendance
                                </span>
                            </a>
                        </li>
                        @else
                        <li class="menu-title">Main</li>
                        <li><a href="{{route('empdashboard')}}" class="waves-effect">
                                <i class="mdi mdi-airplay"></i>
                                <span>Dashboard
                                </span>
                            </a>
                        </li>
                        <li><a href="{{route('profile')}}" class="waves-effect">
                                <i class="mdi mdi-account-settings"></i>
                                <span>Profile
                                </span>
                            </a>
                        </li>
                        @endif
                        <li><a href="{{route('slip')}}" class="waves-effect {{ request()->routeIs('slip', 'employee.slip') ? 'active' : '' }}">
                                <i class="mdi mdi-cash-multiple"></i>
                                <span>Salary Slip
                                </span>
                            </a>
                        </li>
                        <li><a href="{{route('leads')}}" class="waves-effect {{ request()->routeIs('leads', 'leads.create') ? 'active' : '' }}">
                                <i class="mdi mdi-comment-text"></i>
                                <span>Leads
                                </span>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div><!-- end sidebarinner -->
        </div><!-- Left Sidebar End -->

        <!-- Start right Content here -->
        <div class="content-page"><!-- Start content -->
            <div class="content"><!-- Top Bar Start -->
                <div class="topbar">
                    <nav class="navbar-custom">
                        <ul class="list-inline float-right mb-0">

                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                                    <div class="dropdown-item noti-title">
                                        @if (session('user_type') === 'admin')
                                        <h5>Welcome Admin</h5>
                                        @else
                                        <h5>Welcome {{ session('user_name')}}</h5>
                                        @endif
                                    </div><a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                                    <div class="dropdown-divider"></div>
                                    @if (session('user_type') === 'admin')
                                    <a class="dropdown-item" href="{{ route('admin.login') }}">
                                        <i class="mdi mdi-logout m-r-5 text-muted"></i> Logout
                                    </a>
                                    @else
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="mdi mdi-logout m-r-5 text-muted"></i> Logout
                                    </a>
                                    @endif

                                </div>
                            </li>
                        </ul>
                        <ul class="list-inline menu-left mb-0">
                            <li class="float-left"><button class="button-menu-mobile open-left waves-light waves-effect"><i class="mdi mdi-menu"></i></button></li>
                            <li class="hide-phone app-search">
                                <form role="search" class=""><input type="text" placeholder="Search..." class="form-control"> <a href="#"><i class="fa fa-search"></i></a></form>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </nav>
                </div>
                <!-- Top Bar End -->

                @yield('content')

                <footer class="footer">© 2025 SBMS by Pooja Mane</footer>
            </div><!-- End Right content here -->
        </div><!-- END wrapper --><!-- jQuery  -->
        <!-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> -->
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/detect.js') }}"></script>
        <script src="{{ asset('assets/js/fastclick.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/skycons/skycons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/raphael/raphael-min.js') }}"></script>
        <script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('assets/pages/dashboard.js') }}"></script><!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script>
            /* BEGIN SVG WEATHER ICON */
            if (typeof Skycons !== 'undefined') {
                var icons = new Skycons({
                        "color": "#fff"
                    }, {
                        "resizeClear": true
                    }),
                    list = [
                        "clear-day", "clear-night", "partly-cloudy-day",
                        "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                        "fog"
                    ],
                    i;

                for (i = list.length; i--;)
                    icons.set(list[i], list[i]);
                icons.play();
            };

            // scroll

            $(document).ready(function() {

                $("#boxscroll").niceScroll({
                    cursorborder: "",
                    cursorcolor: "#cecece",
                    boxzoom: true
                });
                $("#boxscroll2").niceScroll({
                    cursorborder: "",
                    cursorcolor: "#cecece",
                    boxzoom: true
                });

            });
        </script>
</body>

</html>