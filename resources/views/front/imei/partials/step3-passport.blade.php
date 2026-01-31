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
                <input type="hidden" name="passport_mrz" id="passportMRZ">
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
    <div class="row g-1" id="passport-form">
        <div class="col-6">
            <label class="mb-0 form-label small">الاسم الأول</label>
            <input type="text" name="given_name" id="givenName" class="form-control form-control-sm" placeholder="SHERIF MOUSSA" required>
        </div>
        <div class="col-6">
            <label class="mb-0 form-label small">اسم العائلة</label>
            <input type="text" name="family_name" id="familyName" class="form-control form-control-sm" placeholder="SELIM" required>
        </div>
        <div class="col-6">
            <label class="mb-0 form-label small">رقم جواز السفر</label>
            <input type="text" name="document_no" id="documentNo" class="form-control form-control-sm" placeholder="A31206202" required>
        </div>
        <div class="col-6">
            <label class="mb-0 form-label small">الجنسية</label>
            <input type="text" name="nationality" id="nationality" class="form-control form-control-sm" placeholder="EGY" required>
        </div>
        <div class="col-4">
            <label class="mb-0 form-label small">تاريخ الميلاد</label>
            <input type="date" name="birthday" id="birthday" class="form-control form-control-sm" required>
        </div>
        <div class="col-4">
            <label class="mb-0 form-label small">الجنس</label>
            <select name="sex" id="sex" class="form-select form-select-sm" required>
                <option value="">اختر</option>
                <option value="ذكر">ذكر</option>
                <option value="أنثى">أنثى</option>
            </select>
        </div>
        <div class="col-4">
            <label class="mb-0 form-label small">تاريخ الانتهاء</label>
            <input type="date" name="expiry_date" id="expiryDate" class="form-control form-control-sm" required>
        </div>
        <div class="col-6">
            <label class="mb-0 form-label small">دولة الإصدار</label>
            <input type="text" name="issue_state" id="issueState" class="form-control form-control-sm" placeholder="EGY" required>
        </div>
        <div class="col-6">
            <label class="mb-0 form-label small">نوع الوثيقة</label>
            <input type="text" name="document_type" id="documentType" class="form-control form-control-sm" placeholder="جواز سفر" required>
        </div>
    </div>
    <div class="gap-2 mt-4 d-flex justify-content-between">
        <button type="button" class="btn btn-outline-primary" onclick="prevStep()"><i data-lucide="arrow-right" class="me-1"></i> السابق</button>
        <button type="button" class="btn btn-primary" onclick="validateAndNext(3)">التالي <i data-lucide="arrow-left" class="ms-1"></i></button>
    </div>
</div>
