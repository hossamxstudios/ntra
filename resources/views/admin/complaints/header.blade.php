<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-danger-subtle rounded-3">
                    <i class="ti ti-message-report fs-2 text-danger"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">الشكاوى</h4>
            <div class="text-danger">
                @php
                    $totalComplaints = \App\Models\Complaint::count();
                    $openComplaints = \App\Models\Complaint::whereIn('status', ['new', 'in_progress'])->count();
                    $urgentComplaints = \App\Models\Complaint::where('priority', 'urgent')->whereIn('status', ['new', 'in_progress'])->count();
                @endphp
                <span class="badge bg-danger-subtle text-danger me-2">{{ $totalComplaints }} شكوى</span>
                @if($openComplaints > 0)
                <span class="badge bg-warning me-2">{{ $openComplaints }} مفتوحة</span>
                @endif
                @if($urgentComplaints > 0)
                <span class="badge bg-danger me-2">{{ $urgentComplaints }} عاجلة</span>
                @endif
                إدارة شكاوى المسافرين
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('complaints.export')
                <a href="{{ route('admin.complaints.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
