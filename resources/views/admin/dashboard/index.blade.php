<!DOCTYPE html>
@include('admin.main.html')

<head>
    <title>لوحة التحكم - NTRA</title>
    @include('admin.main.meta')
</head>

<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                {{-- Welcome Section --}}
                @include('admin.dashboard.welcome')
                {{-- Stats Cards Section --}}
                @include('admin.dashboard.stats-cards')

                {{-- Charts Row --}}
                <div class="row mb-4">
                    {{-- Daily Activity Chart --}}
                    <div class="col-xl-8 col-lg-7">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">النشاط اليومي (آخر 7 أيام)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyActivityChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    {{-- Top Machines --}}
                    <div class="col-xl-4 col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">أكثر الأجهزة نشاطاً</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>الجهاز</th>
                                                <th class="text-center">الفحوصات</th>
                                                <th class="text-center">المدفوعات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topMachines as $machine)
                                            <tr>
                                                <td>
                                                    <span class="fw-medium">{{ $machine->name }}</span>
                                                    <small class="d-block text-muted">{{ $machine->area }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary-subtle text-primary">{{ $machine->imei_checks_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success">{{ $machine->payments_count }}</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">لا توجد بيانات</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Distribution Charts Row --}}
                <div class="row mb-4">
                    {{-- Device Types Distribution --}}
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">توزيع أنواع الأجهزة</h5>
                            </div>
                            <div class="card-body">
                                @forelse($deviceTypes as $type)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-device-mobile fs-20 text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $type->device_type }}</h6>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $totalDevices > 0 ? ($type->count / $totalDevices) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-3">
                                        <span class="badge bg-info-subtle text-info">{{ number_format($type->count) }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-4">لا توجد بيانات</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Nationality Distribution --}}
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">توزيع الجنسيات</h5>
                            </div>
                            <div class="card-body">
                                @forelse($nationalities as $nationality)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-flag fs-20 text-purple"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $nationality->nationality }}</h6>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-purple" role="progressbar"
                                                style="width: {{ $totalPassengers > 0 ? ($nationality->count / $totalPassengers) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-3">
                                        <span class="badge bg-purple-subtle text-purple">{{ number_format($nationality->count) }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-4">لا توجد بيانات</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity Tables Row --}}
                <div class="row mb-4">
                    {{-- Recent IMEI Checks --}}
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">آخر فحوصات IMEI</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>IMEI</th>
                                                <th>الجهاز</th>
                                                <th>الحالة</th>
                                                <th>الوقت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentImeiChecks as $check)
                                            <tr>
                                                <td>
                                                    <code class="text-primary">{{ $check->scanned_imei ?? 'N/A' }}</code>
                                                </td>
                                                <td>{{ $check->machine->name ?? 'N/A' }}</td>
                                                <td>
                                                    @switch($check->status)
                                                        @case('completed')
                                                            <span class="badge bg-success-subtle text-success">مكتمل</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="badge bg-warning-subtle text-warning">قيد الانتظار</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger-subtle text-danger">ملغي</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $check->created_at->diffForHumans() }}</small>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">لا توجد فحوصات حديثة</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Payments --}}
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">آخر المدفوعات</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>المبلغ</th>
                                                <th>المسافر</th>
                                                <th>الجهاز</th>
                                                <th>الوقت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ج.م</span>
                                                </td>
                                                <td>{{ $payment->passenger->full_name ?? 'N/A' }}</td>
                                                <td>{{ $payment->machine->name ?? 'N/A' }}</td>
                                                <td>
                                                    <small class="text-muted">{{ $payment->paid_at?->diffForHumans() ?? 'N/A' }}</small>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">لا توجد مدفوعات حديثة</td>
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

    {{-- Chart.js for Daily Activity --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dailyActivityChart').getContext('2d');
            const dailyStats = @json($dailyStats);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dailyStats.map(s => s.date),
                    datasets: [
                        {
                            label: 'فحوصات IMEI',
                            data: dailyStats.map(s => s.checks),
                            backgroundColor: 'rgba(59, 125, 221, 0.8)',
                            borderColor: 'rgba(59, 125, 221, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'المدفوعات',
                            data: dailyStats.map(s => s.payments),
                            backgroundColor: 'rgba(40, 199, 111, 0.8)',
                            borderColor: 'rgba(40, 199, 111, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
