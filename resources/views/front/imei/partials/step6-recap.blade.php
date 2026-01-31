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
