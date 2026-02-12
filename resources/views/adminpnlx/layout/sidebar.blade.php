<nav class="vertnav navbar navbar-light">
    <!-- nav bar -->
    <div class="w-100 mb-4 d-flex">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('Admin.dashboard') }}">
            <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
                <g>
                    <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
                    <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
                    <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
                </g>
            </svg>
        </a>
    </div>

    <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item w-100">
            <a class="nav-link" href="{{ route('Admin.dashboard') }}">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">Dashboard</span>
            </a>
        </li>

        @if(hasPermission('Staff', 'listing_permission'))
            <li class="nav-item dropdown">
                <a href="#staff_management" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-book fe-16"></i>
                    <span class="ml-3 item-text">Staff Management</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="staff_management">
                @if(hasPermission('Staff', 'listing_permission'))
                    <a class="nav-link pl-3" href="{{ route('Staff.index') }}"><span class="ml-1">Staff</span></a>
                @endif
                @if(hasPermission('Role', 'listing_permission'))
                    <a class="nav-link pl-3" href="{{ route('Role.index') }}"><span class="ml-1">Role</span></a>
                @endif
                @if(hasPermission('Acl', 'listing_permission'))
                    <a class="nav-link pl-3" href="{{ route('Acl.index') }}"><span class="ml-1">Acl</span></a>
                @endif
                </ul>
            </li>
        @endif

        @if(hasPermission('User', 'listing_permission'))
            <li class="nav-item dropdown">
                <a href="#user_management" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-book fe-16"></i>
                    <span class="ml-3 item-text">User Management</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="user_management">
                    <a class="nav-link pl-3" href="{{ route('User.index') }}"><span class="ml-1">User</span></a>
                </ul>
            </li>
        @endif
    </ul>

</nav>
