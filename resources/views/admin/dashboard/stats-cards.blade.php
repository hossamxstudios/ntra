{{-- Main Stats Cards Section --}}
<div class="mb-4 row row-cols-xxl-4 row-cols-md-2 row-cols-1">
    {{-- Total Revenue Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">إجمالي الإيرادات</h5>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($totalRevenue, 2) }} <small class="fs-14">ج.م</small></h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-success"><i class="ti ti-arrow-up"></i> {{ number_format($todayRevenue, 2) }}</span> اليوم
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-success-subtle text-success rounded fs-22">
                            <i class="ti ti-currency-pound fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center card-footer bg-success-subtle border-0">
                <span class="text-success fw-semibold">
                    <i class="ti ti-calendar-week me-1"></i> هذا الأسبوع: {{ number_format($weekRevenue, 2) }} ج.م
                </span>
            </div>
        </div>
    </div>

    {{-- IMEI Checks Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">فحوصات IMEI</h5>
                        <h2 class="mb-0 fw-bold text-primary">{{ number_format($totalImeiChecks) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-primary"><i class="ti ti-arrow-up"></i> {{ number_format($todayImeiChecks) }}</span> اليوم
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-primary-subtle text-primary rounded fs-22">
                            <i class="ti ti-barcode fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center card-footer bg-primary-subtle border-0">
                <span class="text-primary fw-semibold">
                    <i class="ti ti-clock me-1"></i> قيد الانتظار: {{ number_format($pendingImeiChecks) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Mobile Devices Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">الأجهزة المسجلة</h5>
                        <h2 class="mb-0 fw-bold text-info">{{ number_format($totalDevices) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-success"><i class="ti ti-check"></i> {{ number_format($paidDevices) }}</span> مدفوعة |
                            <span class="text-warning">{{ number_format($unpaidDevices) }}</span> غير مدفوعة
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-info-subtle text-info rounded fs-22">
                            <i class="ti ti-device-mobile fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center card-footer bg-info-subtle border-0">
                <span class="text-info fw-semibold">
                    <i class="ti ti-lock me-1"></i> محظورة: {{ number_format($lockedDevices) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Passengers Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">المسافرين</h5>
                        <h2 class="mb-0 fw-bold text-purple">{{ number_format($totalPassengers) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-purple"><i class="ti ti-arrow-up"></i> {{ number_format($todayPassengers) }}</span> اليوم
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-purple-subtle text-purple rounded fs-22">
                            <i class="ti ti-users fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center card-footer bg-purple-subtle border-0">
                <span class="text-purple fw-semibold">
                    <i class="ti ti-calendar-week me-1"></i> هذا الأسبوع: {{ number_format($weekPassengers) }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats Row --}}
<div class="mb-4 row row-cols-xxl-4 row-cols-md-2 row-cols-1">
    {{-- Machines Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">أجهزة الخدمة</h5>
                        <h2 class="mb-0 fw-bold text-dark">{{ number_format($totalMachines) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-success">{{ $activeMachines }} نشط</span> |
                            <span class="text-warning">{{ $machinesInMaintenance }} صيانة</span>
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-dark-subtle text-dark rounded fs-22">
                            <i class="ti ti-device-desktop fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payments Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">المدفوعات</h5>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($totalPayments) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-success">{{ $todayPayments }} اليوم</span> |
                            <span class="text-warning">{{ $pendingPayments }} معلقة</span>
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-success-subtle text-success rounded fs-22">
                            <i class="ti ti-credit-card fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Complaints Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">الشكاوى</h5>
                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($totalComplaints) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-warning">{{ $openComplaints }} مفتوحة</span>
                            @if($urgentComplaints > 0)
                            | <span class="text-danger">{{ $urgentComplaints }} عاجلة</span>
                            @endif
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-danger-subtle text-danger rounded fs-22">
                            <i class="ti ti-message-report fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Suggestions Card --}}
    <div class="col">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3 text-uppercase text-muted">الاقتراحات</h5>
                        <h2 class="mb-0 fw-bold text-warning">{{ number_format($totalSuggestions) }}</h2>
                        <p class="mb-0 mt-2 text-muted fs-sm">
                            <span class="text-warning">{{ $newSuggestions }} جديدة</span>
                        </p>
                    </div>
                    <div class="avatar-md">
                        <div class="avatar-title bg-warning-subtle text-warning rounded fs-22">
                            <i class="ti ti-bulb fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
