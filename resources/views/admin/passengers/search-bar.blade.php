<div class="mb-3 border-0 card">
    <div class="py-2 card-body">
        <form action="{{ route('admin.passengers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو رقم الجواز أو الرقم القومي..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="nationality" class="form-select form-select-sm">
                    <option value="">جميع الجنسيات</option>
                    @foreach(\App\Models\Passenger::distinct()->whereNotNull('nationality')->pluck('nationality') as $nationality)
                    <option value="{{ $nationality }}" {{ request('nationality') == $nationality ? 'selected' : '' }}>{{ $nationality }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-purple btn-sm flex-grow-1">
                        <i class="ti ti-search me-1"></i>بحث
                    </button>
                    @if(request()->hasAny(['search', 'nationality']))
                    <a href="{{ route('admin.passengers.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-x"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
