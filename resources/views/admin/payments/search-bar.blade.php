<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث برقم العملية..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="machine_id" class="form-select form-select-sm">
                    <option value="">جميع الأجهزة</option>
                    @foreach($machines as $machine)
                    <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>{{ $machine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success btn-sm w-100">
                    <i class="ti ti-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>
