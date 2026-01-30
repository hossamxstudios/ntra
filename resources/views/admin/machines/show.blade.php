<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>{{ $machine->name }} - أجهزة الخدمة</title>
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
                            <a href="{{ route('admin.machines.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $machine->name }}</h4>
                            <div class="text-muted">
                                <code>{{ $machine->uuid }}</code>
                            </div>
                        </div>
                        <div class="col-auto">
                            @switch($machine->status)
                                @case('active')
                                    <span class="badge bg-success fs-6">نشط</span>
                                    @break
                                @case('inactive')
                                    <span class="badge bg-secondary fs-6">غير نشط</span>
                                    @break
                                @case('maintenance')
                                    <span class="badge bg-warning fs-6">صيانة</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">معلومات الجهاز</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted">المنطقة</th>
                                        <td>{{ $machine->area ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الموقع</th>
                                        <td>{{ $machine->place ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم التسلسلي</th>
                                        <td><code>{{ $machine->serial_number ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">آخر اتصال</th>
                                        <td>{{ $machine->last_heartbeat_at?->diffForHumans() ?? 'غير متصل' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ الإضافة</th>
                                        <td>{{ $machine->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary-subtle">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary mb-0">{{ $machine->imei_checks_count }}</h3>
                                        <small class="text-muted">فحوصات IMEI</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success-subtle">
                                    <div class="card-body text-center">
                                        <h3 class="text-success mb-0">{{ $machine->payments_count }}</h3>
                                        <small class="text-muted">المدفوعات</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning-subtle">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-0">{{ $machine->suggestions_count }}</h3>
                                        <small class="text-muted">الاقتراحات</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger-subtle">
                                    <div class="card-body text-center">
                                        <h3 class="text-danger mb-0">{{ $machine->complaints_count }}</h3>
                                        <small class="text-muted">الشكاوى</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">آخر الفحوصات</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>IMEI</th>
                                                <th>الحالة</th>
                                                <th>التاريخ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($machine->imeiChecks as $check)
                                            <tr>
                                                <td><code>{{ $check->scanned_imei ?? '-' }}</code></td>
                                                <td>
                                                    @switch($check->status)
                                                        @case('completed')
                                                            <span class="badge bg-success">مكتمل</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="badge bg-warning">قيد الانتظار</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $check->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $check->created_at->diffForHumans() }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">لا توجد فحوصات</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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
