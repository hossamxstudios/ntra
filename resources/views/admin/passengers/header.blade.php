<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-purple-subtle rounded-3">
                    <i class="ti ti-users fs-2 text-purple"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">المسافرين</h4>
            <div class="text-purple">
                <span class="badge bg-purple-subtle text-purple me-2">{{ \App\Models\Passenger::count() }} مسافر</span>
                إدارة بيانات المسافرين القادمين
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('passengers.export')
                <a href="{{ route('admin.passengers.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
                @can('passengers.create')
                <button type="button" class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#addPassengerModal">
                    <i class="ti ti-plus me-1"></i>إضافة مسافر
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>

<style>
.btn-purple { background-color: #7c3aed; border-color: #7c3aed; color: #fff; }
.btn-purple:hover { background-color: #6d28d9; border-color: #6d28d9; color: #fff; }
.text-purple { color: #7c3aed !important; }
.bg-purple-subtle { background-color: rgba(124, 58, 237, 0.1) !important; }
</style>
