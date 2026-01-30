<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-primary-subtle rounded-3">
                    <i class="ti ti-barcode fs-2 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">فحوصات IMEI</h4>
            <div class="text-primary">
                @php
                    $totalChecks = \App\Models\ImeiCheck::count();
                    $todayChecks = \App\Models\ImeiCheck::whereDate('created_at', today())->count();
                @endphp
                <span class="badge bg-primary-subtle text-primary me-2">{{ $totalChecks }} فحص</span>
                <span class="badge bg-success-subtle text-success me-2">{{ $todayChecks }} اليوم</span>
                سجل فحوصات IMEI للأجهزة المحمولة
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('imei-checks.export')
                <a href="{{ route('admin.imei-checks.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
