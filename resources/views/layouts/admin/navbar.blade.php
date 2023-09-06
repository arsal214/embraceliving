<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link push-btn" href="#"><i class="fas fa-angle-double-left"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle">
                <img src="{{(auth()->user()->image) ? auth()->user()->image : '/images/avatar.png'}}" class="user-image img-rounded-sm" alt="User Image">
                <span class="d-none d-md-inline">{{auth()->user()->username}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="/images/avatar.png" class="img-circle" alt="User Image">
                    <p>
                        superadmin <small>Member since Jul. 2022</small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="#" class="btn btn-info btn-flat">Profile</a>
                    <a href="#" class="btn btn-warning btn-flat float-right">
                        Sign out
                    </a>
                    <form id="logout-form" class="d-none">
                        <input type="hidden" value="jZ9lPhuiyOYpUSXdTN5td7zR0I4KFMHojjrvU47N">
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
