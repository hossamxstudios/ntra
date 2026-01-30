<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.suggestions.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">جميع الحالات</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                    <option value="addressed" {{ request('status') == 'addressed' ? 'selected' : '' }}>تمت المعالجة</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="reason" class="form-select form-select-sm">
                    <option value="">جميع الأسباب</option>
                    @foreach(\App\Models\Suggestion::distinct()->whereNotNull('reason')->pluck('reason') as $reason)
                    <option value="{{ $reason }}" {{ request('reason') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-warning btn-sm flex-grow-1">
                        <i class="ti ti-search me-1"></i>بحث
                    </button>
                    @if(request()->hasAny(['search', 'status', 'reason']))
                    <a href="{{ route('admin.suggestions.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-x"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
