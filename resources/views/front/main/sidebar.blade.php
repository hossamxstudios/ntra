<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <div class="scrollbar" data-simplebar>
        <div class="p-2 border border-dashed sidenav-user rounded-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('logo.webp') }}" width="45" class="flex-shrink-0 rounded-circle me-2" alt="user-image">
                <div class="overflow-hidden flex-grow-1">
                    <h6 class="my-0 fw-semibold text-truncate">
                        {{ auth()->user()->first_name . ' ' . auth()->user()->last_name ?? 'مستخدم' }}
                    </h6>
                    <small class="text-muted">{{ auth()->user()->roles->first()->name ?? 'مدير' }}</small>
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <!-- Dashboard -->
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text">لوحة التحكم</span>
                </a>
            </li>

            <!-- ==================== NTRA System Module ==================== -->
            <li class="mt-2 side-nav-title">نظام تسجيل الأجهزة</li>

            <!-- Machines -->
            @can('machines.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.machines.index') }}" class="side-nav-link {{ request()->routeIs('admin.machines.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-device-desktop"></i></span>
                        <span class="menu-text">أجهزة الخدمة</span>
                    </a>
                </li>
            @endcan

            <!-- IMEI Checks -->
            @can('imei-checks.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.imei-checks.index') }}" class="side-nav-link {{ request()->routeIs('admin.imei-checks.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-barcode"></i></span>
                        <span class="menu-text">فحوصات IMEI</span>
                    </a>
                </li>
            @endcan

            <!-- Mobile Devices -->
            @can('mobile-devices.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.mobile-devices.index') }}" class="side-nav-link {{ request()->routeIs('admin.mobile-devices.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-device-mobile"></i></span>
                        <span class="menu-text">الأجهزة المحمولة</span>
                    </a>
                </li>
            @endcan

            <!-- Passengers -->
            @can('passengers.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.passengers.index') }}" class="side-nav-link {{ request()->routeIs('admin.passengers.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-users"></i></span>
                        <span class="menu-text">المسافرين</span>
                    </a>
                </li>
            @endcan

            <!-- ==================== Payments Module ==================== -->
            <li class="mt-2 side-nav-title">المدفوعات والمالية</li>

            <!-- Payments -->
            @can('payments.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.payments.index') }}" class="side-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-credit-card"></i></span>
                        <span class="menu-text">المدفوعات</span>
                    </a>
                </li>
            @endcan

            <!-- ==================== Feedback Module ==================== -->
            <li class="mt-2 side-nav-title">الشكاوى والاقتراحات</li>

            <!-- Complaints -->
            @can('complaints.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.complaints.index') }}" class="side-nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-message-report"></i></span>
                        <span class="menu-text">الشكاوى</span>
                        @php
                            $openComplaintsCount = \App\Models\Complaint::whereIn('status', ['new', 'in_progress'])->count();
                        @endphp
                        @if($openComplaintsCount > 0)
                            <span class="badge bg-danger ms-auto">{{ $openComplaintsCount }}</span>
                        @endif
                    </a>
                </li>
            @endcan

            <!-- Suggestions -->
            @can('suggestions.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.suggestions.index') }}" class="side-nav-link {{ request()->routeIs('admin.suggestions.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-bulb"></i></span>
                        <span class="menu-text">الاقتراحات</span>
                        @php
                            $newSuggestionsCount = \App\Models\Suggestion::where('status', 'new')->count();
                        @endphp
                        @if($newSuggestionsCount > 0)
                            <span class="badge bg-warning ms-auto">{{ $newSuggestionsCount }}</span>
                        @endif
                    </a>
                </li>
            @endcan

            <!-- ==================== System Settings ==================== -->
            <li class="mt-3 side-nav-title">إدارة النظام</li>

            <!-- Users Management -->
            @can('users.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.users.index') }}" class="side-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-user-cog"></i></span>
                        <span class="menu-text">إدارة المستخدمين</span>
                    </a>
                </li>
            @endcan

            <!-- Roles & Permissions -->
            @can('roles.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="side-nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-shield-lock"></i></span>
                        <span class="menu-text">الأدوار والصلاحيات</span>
                    </a>
                </li>
            @endcan

            <!-- ==================== Monitoring ==================== -->
            <li class="mt-3 side-nav-title">المراقبة والتتبع</li>

            <!-- Activity Logs -->
            @can('activity-logs.view')
                <li class="side-nav-item">
                    <a href="{{ route('admin.activity-logs.index') }}" class="side-nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-activity"></i></span>
                        <span class="menu-text">سجل النشاطات</span>
                    </a>
                </li>
            @endcan

            <!-- Backup -->
            @can('backup.access')
                <li class="side-nav-item">
                    <a href="{{ route('admin.backup.index') }}" class="side-nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-database-export"></i></span>
                        <span class="menu-text">النسخ الاحتياطي</span>
                    </a>
                </li>
            @endcan

        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->

<style>
    /* Sidebar submenu animation */
    .sub-menu-animated {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.3s ease-out, opacity 0.25s ease-out, padding 0.3s ease-out;
        padding-top: 0;
        padding-bottom: 0;
    }

    .sub-menu-animated.open {
        max-height: 500px;
        opacity: 1;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        transition: max-height 0.4s ease-in, opacity 0.3s ease-in, padding 0.3s ease-in;
    }

    /* Chevron rotation animation */
    .has-sub-menu .menu-icon-close,
    .has-sub-menu .menu-icon-open {
        transition: transform 0.3s ease;
    }

    .has-sub-menu.open .menu-icon-close {
        transform: rotate(180deg);
    }
</style>

<script>
    // Toggle system basics submenu with animation
    function toggleSystemBasicsMenu(e) {
        e.preventDefault();
        e.stopPropagation();

        const submenu = document.getElementById('sub-menu-system-basics');
        const link = e.currentTarget;
        const parentLi = link.closest('.has-sub-menu');

        if (!submenu.classList.contains('open')) {
            submenu.classList.add('open');
            link.setAttribute('aria-expanded', 'true');
            parentLi?.classList.add('open');
        } else {
            submenu.classList.remove('open');
            link.setAttribute('aria-expanded', 'false');
            parentLi?.classList.remove('open');
        }
    }

    // Auto-expand submenu if child is active
    document.addEventListener('DOMContentLoaded', function() {
        const submenu = document.getElementById('sub-menu-system-basics');
        if (submenu) {
            // Add animation class
            submenu.classList.add('sub-menu-animated');

            const activeChild = submenu.querySelector('.side-nav-link.active');
            if (activeChild) {
                submenu.classList.add('open');
                const link = document.querySelector('[onclick="toggleSystemBasicsMenu(event)"]');
                if (link) {
                    link.setAttribute('aria-expanded', 'true');
                    link.closest('.has-sub-menu')?.classList.add('open');
                }
            }
        }
    });
</script>
