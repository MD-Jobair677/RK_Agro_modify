@php
    $admin = auth('admin')->user();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2 logo-big">
                <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder logo-small">
                <img src="{{ getImage(getFilePath('logoFavicon') . '/favicon.png') }}" alt="logo">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="las la-chevron-left align-middle"></i>
        </a>
    </div>

    <div id="searchBoxSm"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ sideMenuActive('admin.dashboard', 1) }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons las la-tachometer-alt text-primary"></i>
                <div class="text-truncate">@lang('Dashboard')</div>
            </a>
        </li>
        <!-- manange users -->
        {{-- <li class="menu-item {{ sideMenuActive('admin.user*', 2) }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons las la-users text-purple"></i>
                <div class="text-truncate text-nowrap d-inline-block">@lang('Manage Users')</div>
                @if ($bannedUsersCount > 0 || $emailUnconfirmedUsersCount > 0 || $mobileUnconfirmedUsersCount > 0 || $kycUnconfirmedUsersCount > 0 || $kycPendingUsersCount > 0)
                <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">
                    <i class="las la-exclamation"></i>
                </div>
                @endif
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ sideMenuActive('admin.user.index', 1) }}">
                    <a href="{{ route('admin.user.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('All Users')</div>
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.active', 1) }}">
                    <a href="{{ route('admin.user.active') }}" class="menu-link">
                        <div class="text-truncate">@lang('Active')</div>
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.banned', 1) }}">
                    <a href="{{ route('admin.user.banned') }}" class="menu-link">
                        <div class="text-truncate text-nowrap d-inline-block">@lang('Banned')</div>
                        @if ($bannedUsersCount)
                        <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">{{ $bannedUsersCount }}</div>
                        @endif
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.kyc.pending', 1) }}">
                    <a href="{{ route('admin.user.kyc.pending') }}" class="menu-link">
                        <div class="text-truncate text-nowrap d-inline-block">@lang('KYC Pending')</div>
                        @if ($kycPendingUsersCount)
                        <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">{{ $kycPendingUsersCount }}</div>
                        @endif
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.kyc.unconfirmed', 1) }}">
                    <a href="{{ route('admin.user.kyc.unconfirmed') }}" class="menu-link">
                        <div class="text-truncate text-nowrap d-inline-block">@lang('KYC Unconfirmed')</div>
                        @if ($kycUnconfirmedUsersCount)
                        <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">{{ $kycUnconfirmedUsersCount }}</div>
                        @endif
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.email.unconfirmed', 1) }}">
                    <a href="{{ route('admin.user.email.unconfirmed') }}" class="menu-link">
                        <div class="text-truncate text-nowrap d-inline-block">@lang('Email Unconfirmed')</div>
                        @if ($emailUnconfirmedUsersCount)
                        <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">{{ $emailUnconfirmedUsersCount }}</div>
                        @endif
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.user.mobile.unconfirmed', 1) }}">
                    <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="menu-link">
                        <div class="text-truncate text-nowrap d-inline-block">@lang('Mobile Unconfirmed')</div>
                        @if ($mobileUnconfirmedUsersCount)
                        <div class="badge bg-label-danger fs-tiny rounded-pill ms-auto">{{ $mobileUnconfirmedUsersCount }}</div>
                        @endif
                    </a>
                </li>
            </ul>
        </li> --}}


        @if (Gate::forUser($admin)->check('has-permission', 'admin list'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Admins')</span>
            </li>

            <li class="menu-item {{ sideMenuActive('admin.admin*', 1) }}">
                <a href="{{ route('admin.admin.index') }}" class="menu-link">
                    <div class="text-truncate">@lang('Admin List')</div>
                </a>
            </li>
        @endif


        @if (Gate::forUser($admin)->check('has-permission', 'category list'))
            {{-- Category manage --}}
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Category')</span>
            </li>

            <li class="menu-item {{ sideMenuActive('admin.category*', 1) }}">
                <a href="{{ route('admin.category.index') }}" class="menu-link">
                    <div class="text-truncate">@lang('Category')</div>
                </a>
            </li>
        @endif


        @if (Gate::forUser($admin)->check('has-permission', 'cattle category'))
            <li class="menu-item {{ sideMenuActive('admin.cattle.category*', 1) }}">
                <a href="{{ route('admin.cattle.category.index') }}" class="menu-link">
                    <div class="text-truncate">@lang('Cattle Category')</div>
                </a>
            </li>
        @endif

        {{-- Accounts manage --}}
        @if (Gate::forUser($admin)->check('has-permission', 'expenses list'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Expense Manage')</span>
            </li>

            <li class="menu-item {{ sideMenuActive('admin.account*', 3) }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons las la-credit-card text-info"></i>
                    <div class="text-truncate">@lang('Expenses')</div>
                </a>
                <ul class="menu-sub">
                    {{-- <li class="menu-item {{ sideMenuActive('admin.account.head.index*', 1) }}">
                    <a href="{{ route('admin.account.head.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Account Head')</div>
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.account.sub_head.index*', 1) }}">
                    <a href="{{ route('admin.account.sub_head.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Account Sub Head')</div>
                    </a>
                </li> --}}
                    <li class="menu-item {{ sideMenuActive('admin.account.gen_expns.index*', 1) }}">
                        <a href="{{ route('admin.account.gen_expns.index', 'general') }}" class="menu-link">
                            <div class="text-truncate">@lang('General')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.account.gen_expns.index*', 1) }}">
                        <a href="{{ route('admin.account.gen_expns.index', 'food') }}" class="menu-link">
                            <div class="text-truncate">@lang('Food')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.account.gen_expns.index*', 1) }}">
                        <a href="{{ route('admin.account.gen_expns.index', 'medicine') }}" class="menu-link">
                            <div class="text-truncate">@lang('Medicine')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.account.gen_expns.index*', 1) }}">
                        <a href="{{ route('admin.account.gen_expns.index', 'cattle') }}" class="menu-link">
                            <div class="text-truncate">@lang('Cattle Purchase')</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Cattle manage --}}

        @if (Gate::forUser($admin)->check('has-permission', 'cattle list'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Cattle Manage')</span>
            </li>
        @endif

        @if (Gate::forUser($admin)->check('has-permission', 'cattle list'))
            <li class="menu-item {{ sideMenuActive('admin.cattle*', 3) }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons las la-credit-card text-info"></i>
                    <div class="text-truncate">@lang('Cattles')</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ sideMenuActive('admin.cattle.index*', 1) }}">
                        <a href="{{ route('admin.cattle.index') }}" class="menu-link">
                            <div class="text-truncate">@lang('Cattle List')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.booking.index*', 1) }}">
                        <a href="{{ route('admin.booking.index') }}" class="menu-link">
                            <div class="text-truncate">@lang('Cattle bookings')</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        {{-- Common System Manage --}}
        <li class="menu-header small">
            <span class="menu-header-text">@lang('Common System Manage')</span>
        </li>

        <li class="menu-item {{ sideMenuActive('admin.common*', 3) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons las la-credit-card text-info"></i>
                <div class="text-truncate">@lang('Common Setup')</div>
            </a>
            <ul class="menu-sub">
                {{-- <li class="menu-item {{ sideMenuActive('admin.common.warehouse.index*', 1) }}">
                    <a href="{{ route('admin.common.warehouse.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Warehouse')</div>
                    </a>
                </li> --}}
                <li class="menu-item {{ sideMenuActive('admin.common.item.index*', 1) }}">
                    <a href="{{ route('admin.common.item.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Item List')</div>
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.supplier.index*', 1) }}">
                    <a href="{{ route('admin.supplier.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Supplier List')</div>
                    </a>
                </li>
                <li class="menu-item {{ sideMenuActive('admin.customer.index*', 1) }}">
                    <a href="{{ route('admin.customer.index') }}" class="menu-link">
                        <div class="text-truncate">@lang('Customer List')</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Inventory Manage --}}
        <li class="menu-header small">
            <span class="menu-header-text">@lang('Inventory Manage')</span>
        </li>

            <li class="menu-item {{ sideMenuActive('admin.inventory*', 3) }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons las la-credit-card text-info"></i>
                    <div class="text-truncate">@lang('Inventory')</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ sideMenuActive('admin.inventory.stock.index.*', 1) }}">
                        <a href="{{ route('admin.inventory.stock.index', 'food store') }}" class="menu-link">
                            <div class="text-truncate">@lang('Food Store')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.inventory.stock.index.*', 1) }}">
                        <a href="{{ route('admin.inventory.stock.index', 'medicine store') }}" class="menu-link">
                            <div class="text-truncate">@lang('Medicine Store')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.inventory.stock.index.*', 1) }}">
                        <a href="{{ route('admin.inventory.stock.index', 'general store') }}" class="menu-link">
                            <div class="text-truncate">@lang('General Store')</div>
                        </a>
                    </li>
                    <li class="menu-item {{ sideMenuActive('admin.inventory.issue*', 1) }}">
                        <a href="{{ route('admin.inventory.issue.index') }}" class="menu-link">
                            <div class="text-truncate">@lang('Inventory Issues History')</div>
                        </a>
                    </li>
                </ul>
            </li>

        @if (Gate::forUser($admin)->check('has-permission', 'delivery list'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Delivery')</span>
            </li>
            <li class="menu-item sidebar-menu-item">
                <a href="{{ route('admin.booking.delivery.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons las la-eraser text-indigo"></i>
                    <div class="text-truncate">@lang('Delivery List')</div>
                </a>
            </li>
        @endif

        {{-- Role & Permission manage --}}
        @if (Gate::forUser($admin)->check('has-permission', 'role permission list'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('Role & Permission')</span>
            </li>

            <li class="menu-item {{ sideMenuActive('admin.role*', 3) }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons las la-credit-card text-info"></i>
                    <div class="text-truncate">@lang('Role')</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ sideMenuActive('admin.role.index*', 1) }}">
                        <a href="{{ route('admin.role.index') }}" class="menu-link">
                            <div class="text-truncate">@lang('Role List')</div>
                        </a>
                    </li>

                    <li class="menu-item {{ sideMenuActive('admin.role.list*', 1) }}">
                        <a href="{{ route('admin.role.list') }}" class="menu-link">
                            <div class="text-truncate">@lang('User Role')</div>
                        </a>
                    </li>

                    <li class="menu-item {{ sideMenuActive('admin.role.permission.list*', 1) }}">
                        <a href="{{ route('admin.role.permission.list') }}" class="menu-link">
                            <div class="text-truncate">@lang('Role & Permission')</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (Gate::forUser($admin)->check('has-permission', 'settings'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('GENERAL PREFERENCES')</span>
            </li>

            <li class="menu-item {{ sideMenuActive('admin.basic*', 1) }}">
                <a href="{{ route('admin.basic.setting') }}" class="menu-link">
                    <i class="menu-icon tf-icons las la-cog text-purple"></i>
                    <div class="text-truncate">@lang('Settings')</div>
                </a>
            </li>
        @endif

        @if (Gate::forUser($admin)->check('has-permission', 'cache clear'))
            <li class="menu-header small">
                <span class="menu-header-text">@lang('OTHERS')</span>
            </li>
            <li class="menu-item sidebar-menu-item">
                <a href="{{ route('admin.cache.clear') }}" class="menu-link">
                    <i class="menu-icon tf-icons las la-eraser text-indigo"></i>
                    <div class="text-truncate">@lang('Cache Clear')</div>
                </a>
            </li>
        @endif

    </ul>
</aside>
