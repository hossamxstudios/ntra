<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.machines.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو المنطقة أو الرقم التسلسلي..." value="{{ request('search') }}">
                    @if(request('search'))
                    <a href="{{ route('admin.machines.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x"></i>
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="area" class="form-select form-select-sm">
                    <option value="">جميع المناطق</option>
                    @foreach(\App\Models\Machine::distinct()->whereNotNull('area')->pluck('area') as $area)
                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="ti ti-search me-1"></i>بحث
                </button>
            </div>
        </form>
    </div>
</div>
