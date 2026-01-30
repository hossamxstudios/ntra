<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>الدفع - NTRA</title>
    @include('front.main.meta')
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #eeeeee 100%); min-height: 100vh; }
        .kiosk-title { color: #1e3a5f; }
        .payment-card { border-radius: 20px; border: none; }
        .payment-card .card-body { padding: 2rem; }
        .form-control { font-size: 1rem; padding: 0.75rem 1rem; border-radius: 10px; }
        .form-label { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .btn { font-size: 0.95rem; padding: 0.6rem 1.25rem; border-radius: 8px; }
        .summary-box { background: #f8f9fa; border-radius: 12px; padding: 1.25rem; }
        .card-input { letter-spacing: 2px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="kiosk-page">
        <!-- Header -->
        <header class="px-4 bg-white shadow-sm kiosk-header d-flex align-items-center">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="gap-2 d-flex align-items-center">
                    <img src="{{ asset('ntra.webp') }}" alt="NTRA" height="40">
                    <h6 class="mb-0 fw-bold kiosk-title">الجهاز القومي لتنظيم الاتصالات</h6>
                </div>
                <div class="gap-2 d-flex align-items-center">
                    <h6 class="mb-0 fw-bold kiosk-title">وزارة الاتصالات وتكنولوجيا المعلومات</h6>
                    <img src="{{ asset('ministry.webp') }}" alt="Ministry" height="40">
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-4 kiosk-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="shadow-lg card payment-card">
                            <div class="card-body">
                                <h4 class="mb-4 text-center fw-bold"><i data-lucide="credit-card" class="me-2"></i>الدفع الإلكتروني</h4>

                                <!-- Summary -->
                                <div class="summary-box mb-4">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">الجهاز</small>
                                            <span class="fw-semibold">{{ $device->brand }} {{ $device->model }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">IMEI</small>
                                            <code>{{ $device->imei_number }}</code>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">المسافر</small>
                                            <span class="fw-semibold">{{ $passenger->first_name }} {{ $passenger->last_name }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">جواز السفر</small>
                                            <span class="fw-semibold">{{ $passenger->passport_no }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <span class="text-muted">المبلغ المستحق</span>
                                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($taxAmount, 2) }} جنيه</h2>
                                    </div>
                                </div>

                                <!-- Payment Form -->
                                <form action="{{ route('imei.payment.submit', ['device' => $device->id]) }}" method="POST" id="paymentForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">رقم البطاقة</label>
                                        <input type="text" name="card_number" class="form-control card-input" placeholder="0000 0000 0000 0000" maxlength="19" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">اسم حامل البطاقة</label>
                                        <input type="text" name="card_holder" class="form-control" placeholder="الاسم كما يظهر على البطاقة" required>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-6">
                                            <label class="form-label">تاريخ الانتهاء</label>
                                            <input type="text" name="expiry_date" class="form-control" placeholder="MM/YY" maxlength="5" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">CVV</label>
                                            <input type="text" name="cvv" class="form-control" placeholder="123" maxlength="4" required>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg fw-bold">
                                            <i data-lucide="lock" class="me-2"></i> دفع {{ number_format($taxAmount, 2) }} جنيه
                                        </button>
                                        <a href="{{ route('imei.check') }}" class="btn btn-outline-secondary">إلغاء</a>
                                    </div>
                                </form>

                                <div class="mt-3 text-center">
                                    <small class="text-muted"><i data-lucide="shield-check" class="me-1" style="width:14px;height:14px;"></i>دفع آمن ومشفر</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-1 text-center bg-white border-top kiosk-footer d-flex align-items-center justify-content-center">
            <p class="mb-0 text-muted small">© {{ date('Y') }} الجهاز القومي لتنظيم الاتصالات</p>
        </footer>
    </div>

    @include('front.main.scripts')
    <script>
        lucide.createIcons();

        // Format card number with spaces
        document.querySelector('input[name="card_number"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
            let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formatted;
        });

        // Format expiry date
        document.querySelector('input[name="expiry_date"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
