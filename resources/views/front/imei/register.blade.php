<!DOCTYPE html>
@include('front.main.html')
<head>
    <title>تسجيل الجهاز - NTRA</title>
    @include('front.main.meta')
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #eeeeee 100%); min-height: 100vh; }
        .kiosk-title { color: #1e3a5f; }
        .wizard-step { display: none; }
        .wizard-step.active { display: block; }
        .step-indicator {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 18px;
            background: #e9ecef; color: #6c757d;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .step-indicator.active { background: #0d6efd; color: #000; font-weight: 800; box-shadow: 0 4px 12px rgba(13,110,253,0.4); }
        .step-indicator.completed { background: #212529; color: white; }
        .step-line { height: 4px; background: #e9ecef; flex: 1; margin: 0 8px; border-radius: 2px; }
        .step-line.completed { background: #212529; }
        .step-label { font-size: 14px; font-weight: 500; margin-top: 8px; }
        .scanner-area {
            border: 3px dashed #dee2e6;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.3s;
        }
        .scanner-area:hover { border-color: #0d6efd; background: #e7f1ff; }
        .scanner-area.has-data { border-color: #198754; background: #d1e7dd; }
        .scanner-area i { margin-bottom: 15px; }
        .scanner-area p { font-size: 16px; }
        .camera-preview {
            width: 100%; max-width: 320px; height: 240px;
            background: #000; border-radius: 16px;
            margin: 0 auto; overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .camera-preview video { width: 100%; height: 100%; object-fit: cover; }
        .captured-photo { max-width: 200px; max-height: 200px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .wizard-card { border-radius: 20px; border: none; }
        .wizard-card .card-body { padding: 2rem; }
        .section-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem; }
        .info-box { padding: 1.25rem; border-radius: 12px; }
        .info-box .info-row { padding: 0.75rem 0; font-size: 1rem; }
        .form-control { font-size: 1rem; padding: 0.75rem 1rem; border-radius: 10px; }
        .form-label { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .btn { font-size: 0.95rem; padding: 0.6rem 1.25rem; border-radius: 8px; }
        .file-input-hidden { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; top: 0; left: 0; }
        .scanner-area { position: relative; overflow: hidden; }
        .recap-card { background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .recap-title { font-size: 1rem; font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 0.75rem; margin-bottom: 0.75rem; }
        .recap-item { font-size: 0.95rem; padding: 0.4rem 0; }
        .recap-preview { height: 100px; display: flex; align-items: center; justify-content: center; }
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
        <main class="py-3 kiosk-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-12">
                        <div class="shadow-lg card wizard-card">
                            <div class="card-body">
                                <!-- Progress Steps -->
                                <div class="px-3 mb-4 d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <div class="step-indicator active" data-step="1">1</div>
                                        <span class="step-label d-block">الجهاز</span>
                                    </div>
                                    <div class="step-line" data-line="1"></div>
                                    <div class="text-center">
                                        <div class="step-indicator" data-step="2">2</div>
                                        <span class="step-label d-block">الصورة</span>
                                    </div>
                                    <div class="step-line" data-line="2"></div>
                                    <div class="text-center">
                                        <div class="step-indicator" data-step="3">3</div>
                                        <span class="step-label d-block">جواز السفر</span>
                                    </div>
                                    <div class="step-line" data-line="3"></div>
                                    <div class="text-center">
                                        <div class="step-indicator" data-step="4">4</div>
                                        <span class="step-label d-block">ختم الوصول</span>
                                    </div>
                                    <div class="step-line" data-line="4"></div>
                                    <div class="text-center">
                                        <div class="step-indicator" data-step="5">5</div>
                                        <span class="step-label d-block">بطاقة الصعود</span>
                                    </div>
                                    <div class="step-line" data-line="5"></div>
                                    <div class="text-center">
                                        <div class="step-indicator" data-step="6">6</div>
                                        <span class="step-label d-block">التأكيد</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-4 progress" style="height: 8px; border-radius: 4px;">
                                    <div class="progress-bar bg-primary" id="wizardProgress" style="width: 16.66%;"></div>
                                </div>

                                <form action="{{ route('imei.register.submit', ['device' => $device->id]) }}" method="POST" id="registrationForm" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Step 1: Device Info & Serial Number -->
                                    <div class="wizard-step active" data-step="1">
                                        <h5 class="mb-3 text-center section-title"><i data-lucide="smartphone" class="me-2"></i>بيانات الجهاز</h5>
                                        <div class="mb-3 row g-3">
                                            <div class="col-6 col-md-3">
                                                <div class="p-2 text-center rounded bg-light">
                                                    <small class="text-muted d-block">IMEI</small>
                                                    <code class="fw-bold">{{ $device->imei_number }}</code>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-2 text-center rounded bg-light">
                                                    <small class="text-muted d-block">العلامة التجارية</small>
                                                    <span class="fw-semibold">{{ $device->brand }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-2 text-center rounded bg-light">
                                                    <small class="text-muted d-block">الموديل</small>
                                                    <span class="fw-semibold">{{ $device->model }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-2 text-center rounded bg-danger-subtle">
                                                    <small class="text-muted d-block">الضريبة</small>
                                                    <span class="fw-bold text-danger">{{ number_format($taxAmount, 2) }} جنيه</span>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mb-2 text-center"><i data-lucide="scan-barcode" class="me-1"></i>الرقم التسلسلي</h6>
                                        <div class="row justify-content-center">
                                            <div class="col-md-8">
                                                <div class="scanner-area" id="serialScanArea">
                                                    <i data-lucide="scan-line" style="width: 48px; height: 48px;" class="text-muted"></i>
                                                    <p class="mb-1 text-muted">امسح الرقم التسلسلي للجهاز أو أدخله يدوياً</p>
                                                </div>
                                                <input type="text" name="serial_number" id="serialNumber" class="mt-2 text-center form-control" placeholder="الرقم التسلسلي" required>
                                                <input type="hidden" name="imei_number" value="{{ $device->imei_number }}">
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-4 d-flex justify-content-end">
                                            <a href="{{ route('imei.check') }}" class="btn btn-light">إلغاء</a>
                                            <button type="button" class="btn btn-primary" onclick="validateAndNext(1)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
                                        </div>
                                    </div>

                                    <!-- Step 2: Passenger Photo -->
                                    <div class="wizard-step" data-step="2">
                                        <div class="text-center">
                                            <h5 class="section-title"><i data-lucide="camera" class="me-2"></i>صورة المسافر</h5>
                                            <p class="mb-3 text-muted">التقط صورة واضحة للمسافر</p>
                                            <div class="mb-3 camera-preview" id="cameraPreview">
                                                <video id="cameraVideo" autoplay playsinline></video>
                                            </div>
                                            <div id="capturedPhotoContainer" class="mb-3" style="display: none;">
                                                <img id="capturedPhoto" class="border captured-photo">
                                                <input type="hidden" name="passenger_photo" id="passengerPhotoData">
                                            </div>
                                            <div class="gap-2 d-flex justify-content-center">
                                                <button type="button" class="btn btn-success" id="captureBtn" onclick="capturePhoto()">
                                                    <i data-lucide="camera" class="me-1"></i> التقاط الصورة
                                                </button>
                                                <button type="button" class="btn btn-warning" id="retakeBtn" style="display: none;" onclick="retakePhoto()">
                                                    <i data-lucide="refresh-cw" class="me-1"></i> إعادة التقاط
                                                </button>
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
                                            <button type="button" class="btn btn-primary" onclick="validateAndNext(2)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
                                        </div>
                                    </div>

                                    <!-- Step 3: Passport -->
                                    <div class="wizard-step" data-step="3" data-scanner>
                                        <h5 class="mb-3 text-center section-title"><i data-lucide="book-open" class="me-2"></i>مسح جواز السفر</h5>

                                        <!-- Scanner Status -->
                                        <div class="mb-2 text-center">
                                            <span id="scanner-status" class="badge bg-secondary">جاري الاتصال بالماسح...</span>
                                        </div>

                                        <div class="mb-3 row justify-content-center">
                                            <div class="col-md-6">
                                                <div class="scanner-area" id="passportScanArea" onclick="startPassportScan()">
                                                    <input type="hidden" name="passport_image_base64" id="passport_image_base64">
                                                    <div id="scan-preview">
                                                        <i data-lucide="scan" style="width: 48px; height: 48px;" class="text-muted"></i>
                                                        <p class="mb-1 fw-semibold">SecureScan X50</p>
                                                        <p class="mb-0 text-muted small">اضغط لمسح جواز السفر</p>
                                                    </div>
                                                </div>
                                                <div id="scan-modal-status" class="mt-2 text-center small"></div>
                                            </div>
                                        </div>
                                        <h6 class="mb-2 text-center"><i data-lucide="user" class="me-1"></i>بيانات المسافر</h6>
                                        <div class="row g-2" id="passport-form">
                                            <div class="col-6">
                                                <label class="form-label small">الاسم الأول</label>
                                                <input type="text" name="first_name" id="firstName" class="form-control" disabled required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">اسم العائلة</label>
                                                <input type="text" name="last_name" id="lastName" class="form-control" disabled required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">رقم جواز السفر</label>
                                                <input type="text" name="passport_no" id="passportNo" class="form-control" disabled required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">الجنسية</label>
                                                <input type="text" name="nationality" id="nationality" class="form-control" disabled required>
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
                                            <button type="button" class="btn btn-primary" onclick="validateAndNext(3)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
                                        </div>
                                    </div>

                                    <!-- Step 4: Arrival Stamp -->
                                    <div class="wizard-step" data-step="4">
                                        <h5 class="mb-3 text-center section-title"><i data-lucide="stamp" class="me-2"></i>ختم الوصول</h5>
                                        <p class="mb-3 text-center text-muted">امسح صفحة ختم الوصول من جواز السفر</p>
                                        <div class="row justify-content-center">
                                            <div class="col-md-6">
                                                <div class="scanner-area" id="arrivalScanArea">
                                                    <input type="file" name="arrival_file" id="arrivalFile" class="file-input-hidden" accept="image/*" onchange="handleFileSelect(this, 'arrival')">
                                                    <i data-lucide="scan" style="width: 48px; height: 48px;" class="text-muted"></i>
                                                    <p class="mb-1 fw-semibold">SecureScan X50</p>
                                                    <p class="mb-0 text-muted small">اضغط لاختيار ملف أو ضع الختم على الماسح</p>
                                                </div>
                                                <div id="arrivalPreview" class="mt-2 text-center" style="display: none;">
                                                    <img id="arrivalImage" class="rounded img-fluid" style="max-height: 120px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
                                            <button type="button" class="btn btn-primary" onclick="validateAndNext(4)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
                                        </div>
                                    </div>

                                    <!-- Step 5: Boarding Card -->
                                    <div class="wizard-step" data-step="5">
                                        <h5 class="mb-3 text-center section-title"><i data-lucide="ticket" class="me-2"></i>بطاقة الصعود</h5>
                                        <p class="mb-3 text-center text-muted">امسح بطاقة صعود الطائرة</p>
                                        <div class="row justify-content-center">
                                            <div class="col-md-6">
                                                <div class="scanner-area" id="boardingScanArea">
                                                    <input type="file" name="boarding_file" id="boardingFile" class="file-input-hidden" accept="image/*" onchange="handleFileSelect(this, 'boarding')">
                                                    <i data-lucide="scan" style="width: 48px; height: 48px;" class="text-muted"></i>
                                                    <p class="mb-1 fw-semibold">SecureScan X50</p>
                                                    <p class="mb-0 text-muted small">اضغط لاختيار ملف أو ضع البطاقة على الماسح</p>
                                                </div>
                                                <div id="boardingPreview" class="mt-2 text-center" style="display: none;">
                                                    <img id="boardingImage" class="rounded img-fluid" style="max-height: 120px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-2 mt-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
                                            <button type="button" class="btn btn-primary" onclick="validateAndNext(5)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
                                        </div>
                                    </div>

                                    <!-- Step 6: Recap & Submit -->
                                    <div class="wizard-step" data-step="6">
                                        <h5 class="mb-3 text-center section-title"><i data-lucide="clipboard-check" class="me-2"></i>مراجعة البيانات والتأكيد</h5>
                                        <div class="mb-3 row g-2">
                                            <div class="col-6">
                                                <div class="p-2 rounded bg-light h-100">
                                                    <h6 class="pb-1 mb-2 fw-bold border-bottom"><i data-lucide="smartphone" class="me-1" style="width:16px;height:16px;"></i>الجهاز</h6>
                                                    <div class="small"><span class="text-muted">IMEI:</span> <code>{{ $device->imei_number }}</code></div>
                                                    <div class="small"><span class="text-muted">الجهاز:</span> {{ $device->brand }} {{ $device->model }}</div>
                                                    <div class="small"><span class="text-muted">الرقم التسلسلي:</span> <span id="recapSerial" class="fw-semibold">-</span></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 rounded bg-light h-100">
                                                    <h6 class="pb-1 mb-2 fw-bold border-bottom"><i data-lucide="user" class="me-1" style="width:16px;height:16px;"></i>المسافر</h6>
                                                    <div class="small"><span class="text-muted">الاسم:</span> <span id="recapName" class="fw-semibold">-</span></div>
                                                    <div class="small"><span class="text-muted">جواز السفر:</span> <span id="recapPassport" class="fw-semibold">-</span></div>
                                                    <div class="small"><span class="text-muted">الجنسية:</span> <span id="recapNationality" class="fw-semibold">-</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row g-2">
                                            <div class="text-center col-3">
                                                <span class="mb-1 small d-block text-muted">صورة المسافر</span>
                                                <div id="recapPhoto" class="p-2 rounded bg-light" style="height:70px;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="user" class="text-muted" style="width:28px;height:28px;"></i>
                                                </div>
                                            </div>
                                            <div class="text-center col-3">
                                                <span class="mb-1 small d-block text-muted">جواز السفر</span>
                                                <div id="recapPassportImg" class="p-2 rounded bg-light" style="height:70px;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="book-open" class="text-muted" style="width:28px;height:28px;"></i>
                                                </div>
                                            </div>
                                            <div class="text-center col-3">
                                                <span class="mb-1 small d-block text-muted">ختم الوصول</span>
                                                <div id="recapArrival" class="p-2 rounded bg-light" style="height:70px;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="stamp" class="text-muted" style="width:28px;height:28px;"></i>
                                                </div>
                                            </div>
                                            <div class="text-center col-3">
                                                <span class="mb-1 small d-block text-muted">بطاقة الصعود</span>
                                                <div id="recapBoarding" class="p-2 rounded bg-light" style="height:70px;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="ticket" class="text-muted" style="width:28px;height:28px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3 mb-3 text-center rounded bg-danger-subtle">
                                            <span class="text-muted">الضريبة المستحقة</span>
                                            <h3 class="mb-0 fw-bold text-danger">{{ number_format($taxAmount, 2) }} جنيه</h3>
                                        </div>
                                        <div class="gap-2 d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
                                            <button type="submit" class="btn btn-success fw-bold">
                                                <i data-lucide="check-circle" class="me-1"></i> تأكيد التسجيل والسداد
                                            </button>
                                        </div>
                                    </div>

                                </form>
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

        let currentStep = 1;
        const totalSteps = 6;
        let cameraStream = null;

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('wizardProgress').style.width = progress + '%';

            // Update step indicators
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.querySelector(`.step-indicator[data-step="${i}"]`);
                const line = document.querySelector(`.step-line[data-line="${i - 1}"]`);

                if (i < currentStep) {
                    indicator.classList.remove('active');
                    indicator.classList.add('completed');
                    indicator.innerHTML = '<i data-lucide="check" style="width:16px;height:16px;"></i>';
                    if (line) line.classList.add('completed');
                } else if (i === currentStep) {
                    indicator.classList.add('active');
                    indicator.classList.remove('completed');
                    indicator.textContent = i;
                } else {
                    indicator.classList.remove('active', 'completed');
                    indicator.textContent = i;
                }
            }
            lucide.createIcons();
        }

        function showStep(step) {
            document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.wizard-step[data-step="${step}"]`).classList.add('active');
            updateProgress();

            // Start camera on step 2
            if (step === 2) {
                startCamera();
            } else {
                stopCamera();
            }

            // Update recap on step 6
            if (step === 6) {
                updateRecap();
            }

            lucide.createIcons();
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        // Validation function
        function validateAndNext(step) {
            let isValid = true;
            let errorMsg = '';

            switch(step) {
                case 1:
                    if (!document.getElementById('serialNumber').value.trim()) {
                        isValid = false;
                        errorMsg = 'يرجى إدخال الرقم التسلسلي';
                    }
                    break;
                case 2:
                    if (!document.getElementById('passengerPhotoData').value) {
                        isValid = false;
                        errorMsg = 'يرجى التقاط صورة المسافر';
                    }
                    break;
                case 3:
                    if (!document.getElementById('passportFile').files.length) {
                        isValid = false;
                        errorMsg = 'يرجى مسح جواز السفر';
                    }
                    break;
                case 4:
                    if (!document.getElementById('arrivalFile').files.length) {
                        isValid = false;
                        errorMsg = 'يرجى مسح ختم الوصول';
                    }
                    break;
                case 5:
                    if (!document.getElementById('boardingFile').files.length) {
                        isValid = false;
                        errorMsg = 'يرجى مسح بطاقة الصعود';
                    }
                    break;
            }

            if (isValid) {
                nextStep();
            } else {
                alert(errorMsg);
            }
        }

        // File select handler for scanner placeholders
        function handleFileSelect(input, type) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const area = document.getElementById(`${type}ScanArea`);
                    area.classList.add('has-data');

                    const preview = document.getElementById(`${type}Preview`);
                    const image = document.getElementById(`${type}Image`);
                    if (preview && image) {
                        image.src = e.target.result;
                        preview.style.display = 'block';
                    }

                    // Enable passport form fields when passport is scanned
                    if (type === 'passport') {
                        document.getElementById('firstName').disabled = false;
                        document.getElementById('lastName').disabled = false;
                        document.getElementById('passportNo').disabled = false;
                        document.getElementById('nationality').disabled = false;
                        // Simulate auto-fill (placeholder for OCR)
                        document.getElementById('firstName').value = 'محمد';
                        document.getElementById('lastName').value = 'أحمد';
                        document.getElementById('passportNo').value = 'A12345678';
                        document.getElementById('nationality').value = 'مصري';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Camera functions
        async function startCamera() {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                document.getElementById('cameraVideo').srcObject = cameraStream;
                document.getElementById('cameraPreview').style.display = 'block';
                document.getElementById('capturedPhotoContainer').style.display = 'none';
                document.getElementById('captureBtn').style.display = 'inline-block';
                document.getElementById('retakeBtn').style.display = 'none';
            } catch (err) {
                console.error('Camera error:', err);
                alert('لا يمكن الوصول إلى الكاميرا');
            }
        }

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
        }

        function capturePhoto() {
            const video = document.getElementById('cameraVideo');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataUrl = canvas.toDataURL('image/jpeg');
            document.getElementById('capturedPhoto').src = dataUrl;
            document.getElementById('passengerPhotoData').value = dataUrl;

            document.getElementById('cameraPreview').style.display = 'none';
            document.getElementById('capturedPhotoContainer').style.display = 'block';
            document.getElementById('captureBtn').style.display = 'none';
            document.getElementById('retakeBtn').style.display = 'inline-block';

            stopCamera();
        }

        function retakePhoto() {
            startCamera();
        }

        // Scanner placeholder function
        function scanDocument(type) {
            // Placeholder - will be implemented with SecureScan X50 SDK
            alert(`جاري المسح باستخدام SecureScan X50...\nنوع المستند: ${type}`);

            // Simulate scan complete
            const area = document.getElementById(`${type}ScanArea`);
            area.classList.add('has-data');
            area.innerHTML = `
                <i data-lucide="check-circle" style="width: 48px; height: 48px;" class="mb-2 text-success"></i>
                <p class="mb-0 text-success fw-semibold">تم المسح بنجاح</p>
            `;
            lucide.createIcons();
        }

        function updateRecap() {
            document.getElementById('recapSerial').textContent = document.getElementById('serialNumber').value || '-';
            document.getElementById('recapName').textContent =
                (document.getElementById('firstName').value || '') + ' ' +
                (document.getElementById('lastName').value || '');
            document.getElementById('recapPassport').textContent = document.getElementById('passportNo').value || '-';
            document.getElementById('recapNationality').textContent = document.getElementById('nationality').value || '-';

            // Show captured photo in recap
            const photoData = document.getElementById('passengerPhotoData').value;
            if (photoData) {
                document.getElementById('recapPhoto').innerHTML = `<img src="${photoData}" style="max-height:60px;border-radius:4px;">`;
            }

            // Show scanned images in recap
            const passportImg = document.getElementById('passportImage');
            if (passportImg && passportImg.src) {
                document.getElementById('recapPassportImg').innerHTML = `<img src="${passportImg.src}" style="max-height:60px;border-radius:4px;">`;
            }
            const arrivalImg = document.getElementById('arrivalImage');
            if (arrivalImg && arrivalImg.src) {
                document.getElementById('recapArrival').innerHTML = `<img src="${arrivalImg.src}" style="max-height:60px;border-radius:4px;">`;
            }
            const boardingImg = document.getElementById('boardingImage');
            if (boardingImg && boardingImg.src) {
                document.getElementById('recapBoarding').innerHTML = `<img src="${boardingImg.src}" style="max-height:60px;border-radius:4px;">`;
            }
        }

        // Serial number scanner listener
        document.getElementById('serialNumber').addEventListener('focus', function() {
            document.getElementById('serialScanArea').classList.add('has-data');
        });

        // ========================================
        // SecureScan X50 Integration
        // ========================================
        let scannerClient = null;

        function initScanner() {
            scannerClient = new ScannerClient({
                wsUrl: 'ws://localhost:9001',
                onConnected: () => {
                    updateScannerUI(true, true);
                },
                onDisconnected: () => {
                    updateScannerUI(false, false);
                },
                onStatusChange: (status) => {
                    updateScannerUI(status.connected, status.ready);
                },
                onScanning: (message) => {
                    document.getElementById('scan-preview').innerHTML = `
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2" role="status"></div>
                            <p class="mb-0">${message}</p>
                        </div>
                    `;
                },
                onScanResult: (data) => {
                    handlePassportScanResult(data);
                },
                onScanError: (error) => {
                    document.getElementById('scan-preview').innerHTML = `
                        <div class="text-center text-danger">
                            <i data-lucide="alert-circle" style="width:48px;height:48px;"></i>
                            <p class="mb-1">${error}</p>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="startPassportScan()">إعادة المحاولة</button>
                        </div>
                    `;
                    lucide.createIcons();
                }
            });
            scannerClient.connect();
        }

        function updateScannerUI(connected, ready) {
            const statusEl = document.getElementById('scanner-status');
            if (connected && ready) {
                statusEl.className = 'badge bg-success';
                statusEl.innerHTML = '<i data-lucide="check-circle" style="width:12px;height:12px;"></i> الماسح جاهز';
            } else if (connected) {
                statusEl.className = 'badge bg-warning';
                statusEl.innerHTML = '<i data-lucide="loader" style="width:12px;height:12px;"></i> جاري التحضير...';
            } else {
                statusEl.className = 'badge bg-danger';
                statusEl.innerHTML = '<i data-lucide="x-circle" style="width:12px;height:12px;"></i> غير متصل';
            }
            lucide.createIcons();
        }

        function startPassportScan() {
            if (!scannerClient || !scannerClient.isConnected) {
                alert('الماسح غير متصل. تأكد من تشغيل برنامج SecureScan Agent.');
                return;
            }
            scannerClient.scan();
        }

        function handlePassportScanResult(data) {
            // Show scanned image
            document.getElementById('scan-preview').innerHTML = `
                <img src="data:image/jpeg;base64,${data.imageBase64}" class="img-fluid rounded" style="max-height:120px;">
                <p class="mb-0 mt-2 text-success small"><i data-lucide="check-circle" style="width:14px;height:14px;"></i> تم المسح بنجاح</p>
            `;
            lucide.createIcons();

            // Store base64 image
            document.getElementById('passport_image_base64').value = data.imageBase64;

            // Fill form fields
            document.getElementById('firstName').value = data.firstName || '';
            document.getElementById('lastName').value = data.lastName || '';
            document.getElementById('passportNo').value = data.passportNumber || '';
            document.getElementById('nationality').value = data.nationality || '';

            // Enable form fields
            document.querySelectorAll('#passport-form input').forEach(input => {
                input.disabled = false;
            });

            // Mark scanner area as has-data
            document.getElementById('passportScanArea').classList.add('has-data');

            // Update status
            document.getElementById('scan-modal-status').innerHTML = '<span class="text-success">✓ تم قراءة بيانات جواز السفر</span>';
        }

        // Initialize scanner when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (typeof ScannerClient !== 'undefined') {
                    initScanner();
                }
            }, 500);
        });
    </script>
</body>
</html>
