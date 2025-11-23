<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img
                src="{{ asset('assets/backend') }}/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
                />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'guestbook' ? 'active' : '' }}">
                    <a href="{{ route('guestbook') }}">
                        <i class="mdi mdi-book-account-outline"></i>
                        <p>Guest Book</p>
                    </a>
                </li>
                <li class="nav-item {{ in_array(request()->segment(2), ['master-user', 'master-visitor']) ? 'active' : '' }}">
                    <a
                    data-bs-toggle="collapse"
                    href="#master-data"
                    class="collapsed"
                    aria-expanded="false"
                    >
                        <i class="mdi mdi-database-cog-outline"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ in_array(request()->segment(2), ['master-user', 'master-visitor']) ? 'show' : '' }}" id="master-data">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->segment(2) == 'master-user' ? 'active' : '' }}">
                                <a href="{{ route('master-user.index') }}">
                                    <span class="sub-item">Master User</span>
                                </a>
                            </li>
                            <li class="{{ request()->segment(2) == 'master-visitor' ? 'active' : '' }}">
                                <a href="{{ route('master-visitor.index') }}">
                                    <span class="sub-item">Master Visitor</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->