<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                EDURIDE
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('dashboard')}}" aria-expanded="false">
                        <iconify-icon icon="solar:atom-line-duotone"></iconify-icon>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- ADMIN MENU --}}
                @if(Auth::user()->role === 'admin')
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('users.index')}}" :active="request()->routeIs('users.*')">
                        Users
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('vehicles.index')}}" :active="request()->routeIs('vehicles.*')">
                        Vehicles
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('drivers.index')}}" :active="request()->routeIs('drivers.*')">
                        Drivers
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('routes.index')}}" :active="request()->routeIs('routes.*')">
                        Routes
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('trips.index')}}" :active="request()->routeIs('trips.*')">
                        Trips
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('students.index')}}" :active="request()->routeIs('students.*')">
                        Students
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('subscriptions.index')}}" :active="request()->routeIs('subscriptions.*')">
                        Subscriptions
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking')}}" :active="request()->routeIs('live.tracking')">
                        Live Tracking
                    </a>
                </li>
                @endif

                {{-- MANAGER MENU --}}
                @if(Auth::user()->role === 'manager')
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('vehicles.index')}}" :active="request()->routeIs('vehicles.*')">
                        Vehicles
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('routes.index')}}" :active="request()->routeIs('routes.*')">
                        Routes
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('trips.index')}}" :active="request()->routeIs('trips.*')">
                        Trips
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking')}}" :active="request()->routeIs('live.tracking')">
                        Live Tracking
                    </a>
                </li>
                @endif

                {{-- DRIVER MENU --}}
                @if(Auth::user()->role === 'driver')
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="route('trips.index')" :active="request()->routeIs('trips.*')">
                        My Trips
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="{{ route('live.tracking')}}" :active="request()->routeIs('live.tracking')">
                        Live Map
                    </a>
                </li>
                @endif

                {{-- PARENT MENU --}}
                @if(Auth::user()->role === 'parent')
                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="route('subscriptions.index')" :active="request()->routeIs('subscriptions.*')">
                        My Children
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link primary-hover-bg" href="route('live.tracking')" :active="request()->routeIs('live.tracking')">
                        Track Bus
                    </a>
                </li>
                @endif
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->