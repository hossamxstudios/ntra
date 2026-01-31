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
