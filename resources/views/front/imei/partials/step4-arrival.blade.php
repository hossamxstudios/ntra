<!-- Step 4: Arrival Stamp -->
<div class="wizard-step" data-step="4" data-scanner>
    <h5 class="mb-3 text-center section-title"><i data-lucide="stamp" class="me-2"></i>ختم الوصول</h5>

    <!-- Scanner Status -->
    <div class="mb-2 text-center">
        <span id="arrival-scanner-status" class="badge bg-secondary">جاري الاتصال بالماسح...</span>
    </div>

    <p class="mb-3 text-center text-muted">امسح صفحة ختم الوصول من جواز السفر</p>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="scanner-area" id="arrivalScanArea" onclick="startArrivalScan()">
                <input type="hidden" name="arrival_image_base64" id="arrival_image_base64">
                <div id="arrival-preview">
                    <i data-lucide="scan" style="width: 48px; height: 48px;" class="text-muted"></i>
                    <p class="mb-1 fw-semibold">SecureScan X50</p>
                    <p class="mb-0 text-muted small">اضغط لمسح ختم الوصول</p>
                </div>
            </div>
        </div>
    </div>
    <div class="gap-2 mt-4 d-flex justify-content-between">
        <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
        <button type="button" class="btn btn-primary" onclick="validateAndNext(4)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
    </div>
</div>
