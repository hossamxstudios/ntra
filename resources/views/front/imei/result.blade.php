<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>نتيجة الاستعلام - NTRA</title>
    @include('front.main.meta')
</head>
<body>
    <!-- Header -->
    <header class="px-4 py-3 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div class="gap-3 d-flex align-items-center">
                <img src="{{ asset('ntra.webp') }}" alt="NTRA" height="50">
                <h5 class="mb-0 fw-bold" style="color: #1e3a5f;">الجهاز القومي لتنظيم الاتصالات</h5>
            </div>
            <div class="gap-3 d-flex align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #1e3a5f;">وزارة الاتصالات وتكنولوجيا المعلومات</h5>
                <img src="{{ asset('ministry.webp') }}" alt="Ministry" height="50">
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="py-5 align-items-center d-flex" style="min-height: calc(100vh - 150px);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="shadow-lg card">
                        <div class="p-4 card-body">
                            @if($apiResponse['success'] && isset($apiResponse['data']) && $mobileDevice)
                                @php
                                    $data = $apiResponse['data'];
                                    $result = $data['result'] ?? null;
                                @endphp

                                <!-- Device Info Section -->
                                <div class="text-center">
                                    @if($mobileDevice->is_paid)
                                        <div class="gap-3 d-flex align-items-center justify-content-center">
                                            <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i data-lucide="check-circle" class="text-success" style="width: 25px; height: 25px;"></i>
                                            </div>
                                            <div class="text-start">
                                                <h5 class="mb-0 fw-bold text-success">الجهاز مسجل ومدفوع</h5>
                                                <small class="text-muted">تم سداد الضريبة الجمركية</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="gap-3 d-flex align-items-center justify-content-center">
                                            <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i data-lucide="alert-circle" class="text-warning" style="width: 25px; height: 25px;"></i>
                                            </div>
                                            <div class="text-start">
                                                <h5 class="mb-0 fw-bold text-warning">مطلوب سداد الضريبة</h5>
                                                <small class="text-muted">{{ $isNewDevice ? 'تم تسجيل الجهاز' : 'الجهاز مسجل مسبقاً' }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="my-4">
                                        <code class="px-4 py-2 text-center fs-4 bg-light rounded-3 text-dark" style="letter-spacing: 3px;">{{ $imei }}</code>
                                    </div>

                                    <!-- Device Details -->
                                    @if($result)
                                        <div class="p-4 text-start bg-light rounded-3">
                                            <div class="py-2 d-flex justify-content-between border-bottom">
                                                <span class="text-muted">العلامة التجارية</span>
                                                <span class="fw-semibold">{{ $result['brand_name'] ?? $result['brand'] ?? $mobileDevice->brand ?? '-' }}</span>
                                            </div>
                                            <div class="py-2 d-flex justify-content-between border-bottom">
                                                <span class="text-muted">الموديل</span>
                                                <span class="fw-semibold">{{ $result['model'] ?? $mobileDevice->model ?? '-' }}</span>
                                            </div>
                                            <div class="py-2 d-flex justify-content-between">
                                                <span class="text-muted">حالة التسجيل</span>
                                                @if($mobileDevice->is_paid)
                                                    <span class="badge bg-success">مسجل ومدفوع</span>
                                                @else
                                                    <span class="badge bg-warning">غير مدفوع</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tax Calculation -->
                                    <div class="p-4 mt-3 text-start {{ $mobileDevice->is_paid ? 'bg-success-subtle' : 'bg-warning-subtle' }} rounded-3">
                                        <h5 class="mb-3 fw-bold {{ $mobileDevice->is_paid ? 'text-success' : 'text-warning' }}">
                                            <i data-lucide="calculator" class="me-2"></i>
                                            حساب الضريبة الجمركية
                                        </h5>
                                        <div class="py-2 d-flex justify-content-between">
                                            <span class="text-dark fw-bold">الضريبة المستحقة</span>
                                            <span class="{{ $mobileDevice->is_paid ? 'text-success' : 'text-danger' }} fw-bold fs-5">{{ number_format($taxAmount, 2) }} جنيه</span>
                                        </div>
                                        @if($mobileDevice->is_paid)
                                            <div class="mt-3 text-center">
                                                <span class="px-4 py-2 badge bg-success fs-6">
                                                    <i data-lucide="check" class="me-1"></i>
                                                    تم السداد
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Payment Button (if not paid) -->
                                    @if(!$mobileDevice->is_paid)
                                        <div class="p-4 mt-4 bg-primary-subtle rounded-3">
                                            <p class="mb-3 text-primary fw-semibold">
                                                <i data-lucide="info" class="me-2"></i>
                                                يجب سداد الضريبة الجمركية لتفعيل الجهاز
                                            </p>
                                            <a href="{{ route('imei.register', ['device' => $mobileDevice->id]) }}" class="py-2 btn btn-primary fw-bold">
                                                <i data-lucide="credit-card" class="me-2 ms-2"></i>
                                                متابعة عملية التسجيل والسداد
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            @elseif($apiResponse['is_pending'] ?? false)
                                <!-- Pending Response -->
                                <div class="text-center">
                                    <div class="mx-auto mb-3 rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i data-lucide="loader" class="text-warning" style="width: 50px; height: 50px;"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold text-warning">جاري المعالجة</h4>
                                    <p class="text-muted">طلبك قيد المعالجة، يرجى المحاولة مرة أخرى بعد قليل</p>

                                    <div class="my-4">
                                        <code class="px-4 py-2 fs-4 bg-light rounded-3 text-dark" style="letter-spacing: 3px;">{{ $imei }}</code>
                                    </div>
                                </div>

                            @else
                                <!-- Error Response -->
                                <div class="text-center">
                                    <div class="mx-auto mb-3 rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i data-lucide="x-circle" class="text-danger" style="width: 50px; height: 50px;"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold text-danger">فشل الاستعلام</h4>
                                    <p class="text-muted">{{ $apiResponse['error'] ?? 'حدث خطأ أثناء الاستعلام' }}</p>

                                    <div class="my-4">
                                        <code class="px-4 py-2 fs-4 bg-light rounded-3 text-dark" style="letter-spacing: 3px;">{{ $imei }}</code>
                                    </div>

                                    <div class="p-3 bg-danger-subtle rounded-3">
                                        <i data-lucide="info" class="text-danger me-2"></i>
                                        <span class="text-danger">يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني</span>
                                    </div>
                                </div>
                            @endif

                             <div class="gap-2 mt-3 d-flex">
                                <a href="{{ route('imei.check') }}" class="py-2 btn btn-primary fw-semibold w-50">
                                    <i data-lucide="search" class="me-1"></i>
                                    استعلام
                                </a>
                                <a href="{{ route('welcome') }}" class="py-2 btn btn-light w-50">
                                    <i data-lucide="arrow-right" class="me-1"></i>
                                    العودة
                                </a>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 mb-0 text-center text-muted">
                        &copy; {{ date('Y') }} الجهاز القومي لتنظيم الاتصالات - جميع الحقوق محفوظة
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('front.main.scripts')
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
