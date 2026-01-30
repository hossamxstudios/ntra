<div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
    <div class="row align-items-center">
        <div class="col-auto">
            <div class="page-icon">
                <div class="avatar avatar-lg bg-warning-subtle rounded-3">
                    <i class="ti ti-bulb fs-2 text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col">
            <h4 class="mb-1">الاقتراحات</h4>
            <div class="text-warning">
                @php
                    $totalSuggestions = \App\Models\Suggestion::count();
                    $newSuggestions = \App\Models\Suggestion::where('status', 'new')->count();
                @endphp
                <span class="badge bg-warning-subtle text-warning me-2">{{ $totalSuggestions }} اقتراح</span>
                @if($newSuggestions > 0)
                <span class="badge bg-warning me-2">{{ $newSuggestions }} جديد</span>
                @endif
                اقتراحات المسافرين لتحسين الخدمة
            </div>
        </div>
        <div class="col-auto">
            <div class="gap-2 btn-list">
                @can('suggestions.export')
                <a href="{{ route('admin.suggestions.export') }}" class="btn btn-ghost-success">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    <span class="d-none d-sm-inline">تصدير</span>
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
