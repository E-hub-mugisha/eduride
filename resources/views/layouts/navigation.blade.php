<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        EDURIDE
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- COMMON --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    {{-- ADMIN MENU --}}
                    @if(Auth::user()->role === 'admin')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        Users
                    </x-nav-link>

                    <x-nav-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                        Vehicles
                    </x-nav-link>

                    <x-nav-link :href="route('drivers.index')" :active="request()->routeIs('drivers.*')">
                        Drivers
                    </x-nav-link>

                    <x-nav-link :href="route('routes.index')" :active="request()->routeIs('routes.*')">
                        Routes
                    </x-nav-link>

                    <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.*')">
                        Trips
                    </x-nav-link>

                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        Students
                    </x-nav-link>

                    <x-nav-link :href="route('subscriptions.index')" :active="request()->routeIs('subscriptions.*')">
                        Subscriptions
                    </x-nav-link>

                    <x-nav-link :href="route('live.tracking')" :active="request()->routeIs('live.tracking')">
                        Live Tracking
                    </x-nav-link>
                    @endif

                    {{-- MANAGER MENU --}}
                    @if(Auth::user()->role === 'manager')
                    <x-nav-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                        Vehicles
                    </x-nav-link>

                    <x-nav-link :href="route('routes.index')" :active="request()->routeIs('routes.*')">
                        Routes
                    </x-nav-link>

                    <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.*')">
                        Trips
                    </x-nav-link>

                    <x-nav-link :href="route('live.tracking')" :active="request()->routeIs('live.tracking')">
                        Live Tracking
                    </x-nav-link>
                    @endif

                    {{-- DRIVER MENU --}}
                    @if(Auth::user()->role === 'driver')
                    <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.*')">
                        My Trips
                    </x-nav-link>

                    <x-nav-link :href="route('live.tracking')" :active="request()->routeIs('live.tracking')">
                        Live Map
                    </x-nav-link>
                    @endif

                    {{-- PARENT MENU --}}
                    @if(Auth::user()->role === 'parent')
                    <x-nav-link :href="route('subscriptions.index')" :active="request()->routeIs('subscriptions.*')">
                        My Children
                    </x-nav-link>

                    <x-nav-link :href="route('live.tracking')" :active="request()->routeIs('live.tracking')">
                        Track Bus
                    </x-nav-link>
                    @endif

                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('users.index')">Users</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vehicles.index')">Vehicles</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('drivers.index')">Drivers</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('routes.index')">Routes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('trips.index')">Trips</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('students.index')">Students</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subscriptions.index')">Subscriptions</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('live.tracking')">Live Tracking</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'manager')
            <x-responsive-nav-link :href="route('vehicles.index')">Vehicles</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('routes.index')">Routes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('trips.index')">Trips</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('live.tracking')">Live Tracking</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'driver')
            <x-responsive-nav-link :href="route('trips.index')">My Trips</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('live.tracking')">Live Map</x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'parent')
            <x-responsive-nav-link :href="route('subscriptions.index')">My Children</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('live.tracking')">Track Bus</x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>