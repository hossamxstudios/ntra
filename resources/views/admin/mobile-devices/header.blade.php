<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-info-subtle rounded-3">
                    <i class="ti ti-device-mobile fs-2 text-info"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">الأجهزة المحمولة</h4>
            <div class="text-info">
                @php
                    $totalDevices = \App\Models\MobileDevice::count();
                    $paidDevices = \App\Models\MobileDevice::where('is_paid', true)->count();
                @endphp
                <span class="badge bg-info-subtle text-info me-2">{{ $totalDevices }} جهاز</span>
                <span class="badge bg-success-subtle text-success me-2">{{ $paidDevices }} مدفوع</span>
                إدارة الأجهزة المحمولة المسجلة
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('mobile-devices.export')
                <a href="{{ route('admin.mobile-devices.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
                @can('mobile-devices.create')
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addMobileDeviceModal">
                    <i class="ti ti-plus me-1"></i>إضافة جهاز
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>
