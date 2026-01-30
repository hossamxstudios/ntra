<!-- Topbar Start -->
<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="gap-2 d-flex align-items-center justify-content-center">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <a href="{{ route('welcome') }}" class="logo-dark">
                    <span class="gap-1 d-flex align-items-center">
                        <span class="logo-text text-body fw-bold fs-xl"> الجهاز القومي لتنظيم الاتصالات</span>
                    </span>
                </a>
                <a href="{{ route('welcome') }}" class="logo-light">
                    <span class="gap-1 d-flex align-items-center">
                        <span class="avatar avatar-xs rounded-circle text-bg-dark">
                            <span class="avatar-title">
                                <i data-lucide="sparkles" class="fs-md"></i>
                            </span>
                        </span>
                        <span class="text-white logo-text fw-bold fs-xl"> الجهاز القومي لتنظيم الاتصالات</span>
                    </span>
                </a>
            </div>
            <div class="mx-1 d-lg-none d-flex">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('logo.webp') }}" height="28" alt="Logo">
                </a>
            </div>
            <!-- Sidebar Hover Menu Toggle Button -->
            {{-- <button class="button-collapse-toggle d-xl-none">
                <i data-lucide="menu" class="align-middle fs-22"></i>
            </button> --}}
        </div>
    </div>
</header>
<!-- Topbar End -->
