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
