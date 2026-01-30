<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>فحص IMEI #{{ $imeiCheck->id }} - NTRA</title>
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
                            <a href="{{ route('admin.imei-checks.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">فحص IMEI #{{ $imeiCheck->id }}</h4>
                            <div class="text-muted">
                                <code>{{ $imeiCheck->scanned_imei ?? 'N/A' }}</code>
                            </div>
                        </div>
                        <div class="col-auto">
                            @switch($imeiCheck->status)
                                @case('completed')
                                    <span class="badge bg-success fs-6">مكتمل</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning fs-6">قيد الانتظار</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger fs-6">ملغي</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">تفاصيل الفحص</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">IMEI المفحوص</th>
                                        <td><code>{{ $imeiCheck->scanned_imei ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم التسلسلي</th>
                                        <td><code>{{ $imeiCheck->phone_serial ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">جهاز الخدمة</th>
                                        <td>{{ $imeiCheck->machine->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ الفحص</th>
                                        <td>{{ $imeiCheck->checked_at?->format('Y-m-d H:i') ?? $imeiCheck->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">الجهاز والمسافر</h5>
                            </div>
                            <div class="card-body">
                                @if($imeiCheck->mobileDevice)
                                <h6>الجهاز المحمول</h6>
                                <p>{{ $imeiCheck->mobileDevice->brand }} {{ $imeiCheck->mobileDevice->model }}</p>
                                <a href="{{ route('admin.mobile-devices.show', $imeiCheck->mobileDevice) }}" class="btn btn-sm btn-outline-info">
                                    <i class="ti ti-eye me-1"></i>عرض الجهاز
                                </a>
                                <hr>
                                @endif
                                
                                @if($imeiCheck->passenger)
                                <h6>المسافر</h6>
                                <p>{{ $imeiCheck->passenger->full_name }}</p>
                                <a href="{{ route('admin.passengers.show', $imeiCheck->passenger) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye me-1"></i>عرض المسافر
                                </a>
                                @else
                                <div class="text-center text-muted py-4">
                                    <i class="ti ti-user-off fs-1"></i>
                                    <p>لا يوجد بيانات مرتبطة</p>
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
