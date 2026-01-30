<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.mobile-devices.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث بـ IMEI أو الموديل أو الرقم التسلسلي..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="is_paid" class="form-select form-select-sm">
                    <option value="">حالة الدفع</option>
                    <option value="1" {{ request('is_paid') === '1' ? 'selected' : '' }}>مدفوع</option>
                    <option value="0" {{ request('is_paid') === '0' ? 'selected' : '' }}>غير مدفوع</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_activated" class="form-select form-select-sm">
                    <option value="">حالة التفعيل</option>
                    <option value="1" {{ request('is_activated') === '1' ? 'selected' : '' }}>مفعل</option>
                    <option value="0" {{ request('is_activated') === '0' ? 'selected' : '' }}>غير مفعل</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_locked" class="form-select form-select-sm">
                    <option value="">حالة الحظر</option>
                    <option value="1" {{ request('is_locked') === '1' ? 'selected' : '' }}>محظور</option>
                    <option value="0" {{ request('is_locked') === '0' ? 'selected' : '' }}>غير محظور</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-info btn-sm flex-grow-1">
                        <i class="ti ti-search me-1"></i>بحث
                    </button>
                    @if(request()->hasAny(['search', 'is_paid', 'is_activated', 'is_locked']))
                    <a href="{{ route('admin.mobile-devices.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-x"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
