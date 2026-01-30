<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>الدفعة #{{ $payment->id }} - المدفوعات</title>
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
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">الدفعة #{{ $payment->id }}</h4>
                            <div class="text-muted">
                                <code>{{ $payment->transaction_id ?? 'N/A' }}</code>
                            </div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            @switch($payment->status)
                                @case('completed')
                                    <span class="badge bg-success fs-6">مكتمل</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning fs-6">قيد الانتظار</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger fs-6">فشل</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-secondary fs-6">مسترد</span>
                                    @break
                            @endswitch
                            <a href="{{ route('admin.payments.receipt', $payment) }}" class="btn btn-primary" target="_blank">
                                <i class="ti ti-printer me-1"></i>طباعة الإيصال
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">تفاصيل الدفع</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">المبلغ</th>
                                        <td><span class="fs-4 fw-bold text-success">{{ number_format($payment->amount, 2) }} {{ $payment->currency ?? 'EGP' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">طريقة الدفع</th>
                                        <td>
                                            @switch($payment->payment_method)
                                                @case('pos') نقاط البيع @break
                                                @case('cash') نقدي @break
                                                @case('card') بطاقة @break
                                                @default {{ $payment->payment_method }}
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">مرجع POS</th>
                                        <td><code>{{ $payment->pos_reference ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ الدفع</th>
                                        <td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">ملاحظات</th>
                                        <td>{{ $payment->notes ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">البيانات المرتبطة</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">المسافر</th>
                                        <td>
                                            @if($payment->passenger)
                                            <a href="{{ route('admin.passengers.show', $payment->passenger) }}">{{ $payment->passenger->full_name }}</a>
                                            @else - @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الجهاز المحمول</th>
                                        <td>
                                            @if($payment->mobileDevice)
                                            <a href="{{ route('admin.mobile-devices.show', $payment->mobileDevice) }}">{{ $payment->mobileDevice->model }}</a>
                                            @else - @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">جهاز الخدمة</th>
                                        <td>{{ $payment->machine->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">فحص IMEI</th>
                                        <td>
                                            @if($payment->imeiCheck)
                                            <a href="{{ route('admin.imei-checks.show', $payment->imeiCheck) }}">#{{ $payment->imeiCheck->id }}</a>
                                            @else - @endif
                                        </td>
                                    </tr>
                                </table>
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
