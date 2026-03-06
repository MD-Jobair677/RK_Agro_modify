<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="fas fa-bars"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div id="searchBoxLg">
                <div class="nav-item d-flex align-items-center navbar-search">
                    <i class="las la-search fs-4 lh-0"></i>
                    <input type="text" class="form-control border-0 shadow-none w-100 navbar-search-field" id="searchInput" placeholder="Search..." autocomplete="off">
                    <ul class="search-list d-none"></ul>
                </div>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Visit Website  -->
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link text-primary" href="{{ route('home') }}" target="_blank" title="@lang('Visit Website')">
                    <i class="fas fa-globe"></i>
                </a>
            </li>
            <!-- Visit Website  -->

         
            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger badge-notifications">{{ $adminNotificationCount }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">@lang('Notification')</h5>
                            <a href="{{ route('admin.system.notification.read.all') }}" class="dropdown-notifications-all text-body" title="@lang('Mark all as read')">
                                <i class="las la-envelope-open-text fs-4"></i>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            @forelse ($adminNotifications as $notification)
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <a href="{{ route('admin.system.notification.read', $notification->id) }}" class="flex-grow-1">
                                            <h6 class="mb-1">{{ __($notification->title) }}</h6>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <div class="dropdown-notifications-read"><span class="badge badge-dot"></span></div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <b>@lang('No notifications left to read')</b>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top p-3">
                        <a href="{{ route('admin.system.notification.all') }}" class="btn btn-primary w-100">@lang('View all notifications')</a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- Admin -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ getImage(getFilePath('adminProfile') . '/' . auth('admin')->user()->image, getFileSize('adminProfile')) }}" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex" href="{{ route('admin.profile') }}">
                            <i class="las la-user-tie fs-4 me-2"></i>
                            <span class="align-middle">@lang('Profile')</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex" href="{{ route('admin.password') }}">
                            <i class="las la-key fs-4 me-2"></i>
                            <span class="align-middle">@lang('Password')</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex" href="{{ route('admin.basic.setting') }}">
                            <i class="las la-cog fs-4 me-2"></i>
                            <span class="align-middle">@lang('Settings')</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex" href="{{ route('admin.logout') }}">
                            <i class="las la-power-off fs-4 me-2"></i>
                            <span class="align-middle">@lang('Log Out')</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Admin -->
        </ul>
    </div>
</nav>
