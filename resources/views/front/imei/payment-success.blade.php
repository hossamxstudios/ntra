<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>تم الدفع بنجاح - NTRA</title>
    @include('front.main.meta')
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #eeeeee 100%); min-height: 100vh; }
        .kiosk-title { color: #1e3a5f; }
        .success-card { border-radius: 20px; border: none; }
        .success-card .card-body { padding: 2rem; }
        .success-icon {font-size: 35px; color: #fff8f8; width: 80px; height: 80px; background: #000000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .success-icon i { font-size: 35px; color: white; width: 80px; height: 80px; }
        .btn { font-size: 0.95rem; padding: 0.6rem 1.25rem; border-radius: 8px; }
        /* Thermal Receipt Styles - 80mm width */
        .thermal-receipt {
            width: 280px;
            background: #fff;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 10px;
            margin: 0 auto;
            border: 1px dashed #ccc;
        }
        .thermal-receipt .receipt-logo { text-align: center; margin-bottom: 8px; }
        .thermal-receipt .receipt-logo img { max-width: 60px; }
        .thermal-receipt .receipt-title { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 4px; }
        .thermal-receipt .receipt-subtitle { text-align: center; font-size: 10px; color: #666; margin-bottom: 8px; }
        .thermal-receipt .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .thermal-receipt .divider-double { border-top: 2px solid #000; margin: 8px 0; }
        .thermal-receipt .receipt-row { display: flex; justify-content: space-between; line-height: 1.6; }
        .thermal-receipt .receipt-row .label { color: #666; }
        .thermal-receipt .receipt-row .value { font-weight: bold; text-align: left; direction: ltr; }
        .thermal-receipt .receipt-center { text-align: center; }
        .thermal-receipt .receipt-total { font-size: 18px; font-weight: bold; text-align: center; padding: 8px 0; }
        .thermal-receipt .receipt-success { text-align: center; padding: 6px; background: #000; color: #fff; font-weight: bold; margin: 8px 0; }
        .thermal-receipt .receipt-footer { text-align: center; font-size: 10px; color: #666; margin-top: 8px; }
        .thermal-receipt .receipt-barcode { text-align: center; font-family: 'Libre Barcode 39', monospace; font-size: 32px; letter-spacing: 2px; margin: 8px 0; }
        .thermal-receipt .receipt-qr { text-align: center; margin: 8px 0; }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html, body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm !important;
                height: auto !important;
                overflow: visible !important;
            }
            .kiosk-page { display: none !important; }
            #receiptPreview { display: none !important; }
            #thermalReceipt {
                display: block !important;
                visibility: visible !important;
                position: static !important;
                width: 76mm !important;
                max-width: 76mm !important;
                border: none !important;
                padding: 2mm !important;
                margin: 0 !important;
                background: white !important;
                font-size: 11px !important;
            }
            #thermalReceipt * { visibility: visible !important; }
            #thermalReceipt .receipt-success { background: #000 !important; color: #fff !important; }
            #thermalReceipt .receipt-logo img { max-width: 50px !important; }
            #thermalReceipt .receipt-title { font-size: 12px !important; }
            #thermalReceipt .receipt-total { font-size: 16px !important; }
        }
        #thermalReceipt { display: none; }
    </style>
</head>
<body>
    <div class="kiosk-page">
        <!-- Header -->
        <header class="px-4 bg-white shadow-sm kiosk-header d-flex align-items-center no-print">
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
        <main class="py-4 kiosk-content no-print">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7 col-12">
                        <div class="shadow-lg card success-card">
                            <div class="card-body">
                                <!-- Success Icon -->
                                <div class="success-icon">
                                    <i data-lucide="check"></i>
                                </div>
                                <h4 class="mb-1 text-center fw-bold text-success">تم الدفع بنجاح!</h4>
                                <p class="mb-4 text-center text-muted">تم تسجيل جهازك وتفعيله بنجاح</p>

                                <!-- Receipt Preview -->
                                <div class="thermal-receipt" id="receiptPreview">
                                    <div class="receipt-logo">
                                        <img src="{{ asset('ntra.webp') }}" alt="NTRA">
                                    </div>
                                    <div class="receipt-title">الجهاز القومي لتنظيم الاتصالات</div>
                                    <div class="receipt-subtitle">إيصال سداد ضريبة الهاتف المحمول</div>
                                    <div class="divider-double"></div>
                                    <div class="receipt-center" style="font-size:10px;">
                                        رقم: <strong>{{ str_pad($payment->id ?? rand(1, 999999), 6, '0', STR_PAD_LEFT) }}</strong>
                                        &nbsp;|&nbsp;
                                        {{ now()->format('Y/m/d H:i') }}
                                    </div>
                                    <div class="divider"></div>
                                    <div class="receipt-row"><span class="label">IMEI:</span><span class="value">{{ $device->imei_number }}</span></div>
                                    <div class="receipt-row"><span class="label">الجهاز:</span><span class="value">{{ $device->brand }} {{ $device->model }}</span></div>
                                    <div class="divider"></div>
                                    <div class="receipt-row"><span class="label">الاسم:</span><span class="value">{{ $passenger->first_name }} {{ $passenger->last_name }}</span></div>
                                    <div class="receipt-row"><span class="label">جواز السفر:</span><span class="value">{{ $passenger->passport_no }}</span></div>
                                    <div class="receipt-row"><span class="label">الجنسية:</span><span class="value">{{ $passenger->nationality }}</span></div>
                                    <div class="divider-double"></div>
                                    <div class="receipt-total">{{ number_format($taxAmount, 2) }} EGP</div>
                                    <div class="receipt-center" style="font-size:10px;">تم الدفع بالبطاقة</div>
                                    <div class="receipt-success">✓ تم التفعيل بنجاح</div>
                                    <div class="divider"></div>
                                    <div class="receipt-center" style="font-size:10px;direction:ltr;">*{{ $device->imei_number }}*</div>
                                    <div class="receipt-footer">
                                        شكراً لاستخدامكم خدماتنا<br>
                                        www.tra.gov.eg
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="gap-2 mt-4 d-grid">
                                    <button type="button" class="btn btn-primary" onclick="window.print()">
                                        <i data-lucide="printer" class="me-2"></i> طباعة الإيصال
                                    </button>
                                    <a href="{{ route('imei.check') }}" class="btn btn-outline-secondary">
                                        <i data-lucide="home" class="me-2"></i> العودة للرئيسية
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <!-- Thermal Receipt for Print Only (outside kiosk-page) -->
    <div class="thermal-receipt" id="thermalReceipt">
            <div class="receipt-logo">
                <img src="{{ asset('ntra.webp') }}" alt="NTRA">
            </div>
            <div class="receipt-title">الجهاز القومي لتنظيم الاتصالات</div>
            <div class="receipt-subtitle">إيصال سداد ضريبة الهاتف المحمول</div>
            <div class="divider-double"></div>
            <div class="receipt-center" style="font-size:10px;">
                رقم: <strong>{{ str_pad($payment->id ?? rand(1, 999999), 6, '0', STR_PAD_LEFT) }}</strong>
                &nbsp;|&nbsp;
                {{ now()->format('Y/m/d H:i') }}
            </div>
            <div class="divider"></div>
            <div class="receipt-row"><span class="label">IMEI:</span><span class="value">{{ $device->imei_number }}</span></div>
            <div class="receipt-row"><span class="label">الجهاز:</span><span class="value">{{ $device->brand }} {{ $device->model }}</span></div>
            <div class="divider"></div>
            <div class="receipt-row"><span class="label">الاسم:</span><span class="value">{{ $passenger->first_name }} {{ $passenger->last_name }}</span></div>
            <div class="receipt-row"><span class="label">جواز السفر:</span><span class="value">{{ $passenger->passport_no }}</span></div>
            <div class="receipt-row"><span class="label">الجنسية:</span><span class="value">{{ $passenger->nationality }}</span></div>
            <div class="divider-double"></div>
            <div class="receipt-total">{{ number_format($taxAmount, 2) }} EGP</div>
            <div class="receipt-center" style="font-size:10px;">تم الدفع بالبطاقة</div>
            <div class="receipt-success">✓ تم التفعيل بنجاح</div>
            <div class="divider"></div>
            <div class="receipt-center" style="font-size:10px;direction:ltr;">*{{ $device->imei_number }}*</div>
            <div class="receipt-footer">
                شكراً لاستخدامكم خدماتنا<br>
                www.tra.gov.eg
            </div>
    </div>

    @include('front.main.scripts')
    <script>
        lucide.createIcons();

        // Auto print after page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>
