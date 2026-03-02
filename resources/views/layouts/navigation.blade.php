<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <span style="font-size: 25px; font-weight: 800; color: #fff;"><iconify-icon icon="solar:bus-line-duotone"></iconify-icon> EDURIDE</span>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-linear"
                        class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Home</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('dashboard') }}">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- ADMIN MENU --}}
                @if(Auth::user()->role === 'admin')

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('users.index') }}">
                        <iconify-icon icon="solar:users-group-rounded-line-duotone"></iconify-icon>
                        <span class="hide-menu">Users</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('vehicles.index') }}">
                        <iconify-icon icon="solar:bus-line-duotone"></iconify-icon>
                        <span class="hide-menu">Buses</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('drivers.index') }}">
                        <iconify-icon icon="solar:users-group-rounded-line-duotone"></iconify-icon>
                        <span class="hide-menu">Drivers</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('routes.index') }}">
                        <iconify-icon icon="solar:map-point-line-duotone"></iconify-icon>
                        <span class="hide-menu">Routes</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('trips.index') }}">
                        <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                        <span class="hide-menu">Trips</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('students.index') }}">
                        <iconify-icon icon="solar:user-id-line-duotone"></iconify-icon>
                        <span class="hide-menu">Students</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('subscriptions.index') }}">
                        <iconify-icon icon="solar:wallet-line-duotone"></iconify-icon>
                        <span class="hide-menu">Subscriptions</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking') }}">
                        <iconify-icon icon="solar:map-arrow-right-line-duotone"></iconify-icon>
                        <span class="hide-menu">Live Tracking</span>
                    </a>
                </li>
                @endif

                {{-- MANAGER MENU --}}
                @if(Auth::user()->role === 'manager')

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('vehicles.index') }}">
                        <iconify-icon icon="solar:bus-line-duotone"></iconify-icon>
                        <span class="hide-menu">Buses</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('routes.index') }}">
                        <iconify-icon icon="solar:map-point-line-duotone"></iconify-icon>
                        <span class="hide-menu">Routes</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('trips.index') }}">
                        <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                        <span class="hide-menu">Trips</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking') }}">
                        <iconify-icon icon="solar:map-arrow-right-line-duotone"></iconify-icon>
                        <span class="hide-menu">Live Tracking</span>
                    </a>
                </li>
                @endif

                {{-- DRIVER MENU --}}
                @if(Auth::user()->role === 'driver')

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('trips.index') }}">
                        <iconify-icon icon="solar:route-line-duotone"></iconify-icon>
                        <span class="hide-menu">My Trips</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking') }}">
                        <iconify-icon icon="solar:map-line-duotone"></iconify-icon>
                        <span class="hide-menu">Live Map</span>
                    </a>
                </li>
                @endif

                {{-- PARENT MENU --}}
                @if(Auth::user()->role === 'parent')

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('subscriptions.index') }}">
                        <iconify-icon icon="solar:family-line-duotone"></iconify-icon>
                        <span class="hide-menu">My Children</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking') }}">
                        <iconify-icon icon="solar:bus-stop-line-duotone"></iconify-icon>
                        <span class="hide-menu">Track Bus</span>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
<!-- Sidebar End -->