<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-dark-subtle rounded-3">
                    <i class="ti ti-device-desktop fs-2 text-dark"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">أجهزة الخدمة</h4>
            <div class="text-dark">
                <span class="badge bg-dark-subtle text-dark me-2">{{ \App\Models\Machine::count() }} جهاز</span>
                إدارة أجهزة تسجيل الهواتف المحمولة في المطارات
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('machines.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMachineModal">
                    <i class="ti ti-plus me-1"></i>إضافة جهاز
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>
