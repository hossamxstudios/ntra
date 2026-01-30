<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>{{ $mobileDevice->model }} - الأجهزة المحمولة</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                <div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('admin.mobile-devices.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $mobileDevice->brand }} {{ $mobileDevice->model }}</h4>
                            <div class="text-muted">{{ $mobileDevice->device_type }}</div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            @if($mobileDevice->is_paid)
                            <span class="badge bg-success fs-6">مدفوع</span>
                            @else
                            <span class="badge bg-danger fs-6">غير مدفوع</span>
                            @endif
                            @if($mobileDevice->is_activated)
                            <span class="badge bg-success fs-6">مفعل</span>
                            @endif
                            @if($mobileDevice->is_locked)
                            <span class="badge bg-danger fs-6">محظور</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">معلومات الجهاز</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">IMEI 1</th>
                                        <td><code>{{ $mobileDevice->imei_number ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">IMEI 2</th>
                                        <td><code>{{ $mobileDevice->imei_number_2 ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">IMEI 3</th>
                                        <td><code>{{ $mobileDevice->imei_number_3 ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم التسلسلي</th>
                                        <td><code>{{ $mobileDevice->serial_number ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الضريبة</th>
                                        <td>
                                            @if($mobileDevice->tax)
                                            <span class="badge bg-warning fs-6">{{ number_format($mobileDevice->tax, 2) }} ج.م</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ التسجيل</th>
                                        <td>{{ $mobileDevice->registered_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ التفعيل</th>
                                        <td>{{ $mobileDevice->activated_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">المسافر</h5>
                            </div>
                            <div class="card-body">
                                @if($mobileDevice->passenger)
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted">الاسم</th>
                                        <td>{{ $mobileDevice->passenger->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الجنسية</th>
                                        <td>{{ $mobileDevice->passenger->nationality ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">رقم الجواز</th>
                                        <td>{{ $mobileDevice->passenger->passport_no ?? '-' }}</td>
                                    </tr>
                                </table>
                                <a href="{{ route('admin.passengers.show', $mobileDevice->passenger) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye me-1"></i>عرض المسافر
                                </a>
                                @else
                                <div class="text-center text-muted py-4">
                                    <i class="ti ti-user-off fs-1"></i>
                                    <p>لم يتم ربط الجهاز بمسافر</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.main.scripts')
</body>
</html>
