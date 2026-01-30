<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>{{ $passenger->full_name }} - المسافرين</title>
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
                            <a href="{{ route('admin.passengers.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col-auto">
                            <div class="avatar avatar-lg">
                                @if($passenger->getFirstMediaUrl('passenger_photo'))
                                <img src="{{ $passenger->getFirstMediaUrl('passenger_photo') }}" class="rounded-circle">
                                @else
                                <span class="avatar-title bg-purple-subtle text-purple rounded-circle fs-2">
                                    {{ substr($passenger->first_name ?? 'م', 0, 1) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $passenger->full_name ?: 'مسافر غير محدد' }}</h4>
                            <div class="text-muted">
                                {{ $passenger->nationality ?? '-' }} | {{ $passenger->passport_no ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">البيانات الشخصية</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">الاسم الكامل</th>
                                        <td>{{ $passenger->full_name ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ الميلاد</th>
                                        <td>{{ $passenger->birthdate?->format('Y-m-d') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الجنس</th>
                                        <td>{{ $passenger->gender == 'male' ? 'ذكر' : ($passenger->gender == 'female' ? 'أنثى' : '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الجنسية</th>
                                        <td>{{ $passenger->nationality ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">العنوان</th>
                                        <td>{{ $passenger->address ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">بيانات الوثائق</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted" width="40%">رقم الجواز</th>
                                        <td><code>{{ $passenger->passport_no ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم القومي</th>
                                        <td><code>{{ $passenger->national_id ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">رقم الوثيقة</th>
                                        <td>{{ $passenger->document_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">صالح حتى</th>
                                        <td>{{ $passenger->valid_until?->format('Y-m-d') ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">الأجهزة المسجلة</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>الجهاز</th>
                                                <th>IMEI</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($passenger->mobileDevices as $device)
                                            <tr>
                                                <td>{{ $device->brand }} {{ $device->model }}</td>
                                                <td><code>{{ $device->imei_number }}</code></td>
                                                <td>
                                                    @if($device->is_paid)
                                                    <span class="badge bg-success">مدفوع</span>
                                                    @else
                                                    <span class="badge bg-danger">غير مدفوع</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">لا توجد أجهزة مسجلة</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">المدفوعات</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>المبلغ</th>
                                                <th>الحالة</th>
                                                <th>التاريخ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($passenger->payments as $payment)
                                            <tr>
                                                <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                                                <td>
                                                    @if($payment->status == 'completed')
                                                    <span class="badge bg-success">مكتمل</span>
                                                    @else
                                                    <span class="badge bg-warning">{{ $payment->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment->paid_at?->format('Y-m-d') ?? '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">لا توجد مدفوعات</td>
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

<style>
.text-purple { color: #7c3aed !important; }
.bg-purple-subtle { background-color: rgba(124, 58, 237, 0.1) !important; }
</style>
