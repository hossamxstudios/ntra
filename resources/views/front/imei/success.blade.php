<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>تم التسجيل بنجاح - NTRA</title>
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
                        <div class="p-5 card-body">
                            <div class="text-center">
                                <!-- Success Icon -->
                                <div class="mx-auto mb-4 rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i data-lucide="check-circle" class="text-success" style="width: 60px; height: 60px;"></i>
                                </div>

                                <h3 class="mb-2 fw-bold text-success">تم التسجيل والسداد بنجاح!</h3>
                                <p class="mb-4 text-muted">تم تسجيل الجهاز وسداد الضريبة الجمركية بنجاح</p>

                                <!-- Receipt Card -->
                                <div class="p-4 mb-4 bg-light rounded-3 text-start">
                                    <h5 class="pb-2 mb-3 text-center fw-bold border-bottom">
                                        <i data-lucide="receipt" class="me-2"></i>
                                        إيصال السداد
                                    </h5>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">رقم IMEI:</span>
                                        <code class="fw-semibold">{{ $device->imei_number }}</code>
                                    </div>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">الجهاز:</span>
                                        <span class="fw-semibold">{{ $device->brand }} {{ $device->model }}</span>
                                    </div>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">اسم المسافر:</span>
                                        <span class="fw-semibold">{{ $passenger->first_name }} {{ $passenger->last_name }}</span>
                                    </div>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">رقم جواز السفر:</span>
                                        <span class="fw-semibold">{{ $passenger->passport_no }}</span>
                                    </div>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">الجنسية:</span>
                                        <span class="fw-semibold">{{ $passenger->nationality }}</span>
                                    </div>

                                    <div class="py-2 d-flex justify-content-between border-bottom">
                                        <span class="text-muted">تاريخ السداد:</span>
                                        <span class="fw-semibold">{{ now()->format('Y-m-d H:i') }}</span>
                                    </div>

                                    <div class="px-3 py-3 mt-3 rounded d-flex justify-content-between bg-success-subtle">
                                        <span class="fw-bold fs-5">المبلغ المدفوع:</span>
                                        <span class="fw-bold fs-5 text-success">{{ number_format($taxAmount, 2) }} جنيه</span>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="mb-4">
                                    <span class="px-4 py-2 badge bg-success fs-5">
                                        <i data-lucide="shield-check" class="me-2"></i>
                                        الجهاز مسجل ومفعل
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="gap-3 d-grid">
                                    <a href="{{ route('imei.check') }}" class="py-3 btn btn-primary btn-lg fw-bold">
                                        <i data-lucide="search" class="me-2"></i>
                                        استعلام عن جهاز آخر
                                    </a>
                                    <a href="{{ route('welcome') }}" class="btn btn-light btn-lg">
                                        <i data-lucide="home" class="me-2"></i>
                                        العودة للرئيسية
                                    </a>
                                </div>
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
