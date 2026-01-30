<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-success-subtle rounded-3">
                    <i class="ti ti-credit-card fs-2 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">المدفوعات</h4>
            <div class="text-success">
                @php
                    $totalPayments = \App\Models\Payment::count();
                    $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
                @endphp
                <span class="badge bg-success-subtle text-success me-2">{{ $totalPayments }} عملية</span>
                <span class="badge bg-success me-2">{{ number_format($totalRevenue, 2) }} ج.م</span>
                سجل المدفوعات والإيرادات
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('payments.export')
                <a href="{{ route('admin.payments.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
