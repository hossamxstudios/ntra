<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.complaints.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد أو الهاتف..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">جميع الحالات</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">جميع الأولويات</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>عاجل</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالي</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسط</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفض</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="reason" class="form-select form-select-sm">
                    <option value="">جميع الأسباب</option>
                    @foreach(\App\Models\Complaint::distinct()->whereNotNull('reason')->pluck('reason') as $reason)
                    <option value="{{ $reason }}" {{ request('reason') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-danger btn-sm flex-grow-1">
                        <i class="ti ti-search me-1"></i>بحث
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'reason']))
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-x"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
