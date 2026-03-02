<style>
    /* Online status dot */
.status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #28c76f;
    border: 2px solid #fff;
    border-radius: 50%;
}

/* Desktop hover dropdown ONLY */
@media (hover: hover) and (pointer: fine) {
    .user-dropdown:hover > .dropdown-menu {
        display: block;
        opacity: 1;
        visibility: visible;
        margin-top: 6px;
    }
}

/* Default hidden */
.dropdown-menu {
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 1055;
}
</style>

<!--  Header Start -->
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <span class="text-muted d-none d-sm-inline">
    Welcome, {{ Auth::user()->name }}
</span>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown user-dropdown">

                    <a class="nav-link d-flex align-items-center gap-2 p-0"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <div class="position-relative">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                {{ Auth::user()->name[0] }} <!-- First letter as avatar -->
                            </div>
                            <!-- Online status dot -->
                            <span class="status-dot"></span>
                        </div>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                        aria-labelledby="userDropdown">
                        <li class="dropdown-item">
                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-capitalize">
                                {{ Auth::user()->role }}
                            </small>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('profile.edit') ?? '#' }}">
                                <i class="ti ti-user"></i>
                                My Profile
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                    <i class="ti ti-logout"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>

                </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->

