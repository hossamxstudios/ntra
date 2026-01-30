<div class="modal fade" id="addMobileDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.mobile-devices.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-device-mobile-plus me-2"></i>إضافة جهاز محمول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع الجهاز <span class="text-danger">*</span></label>
                            <select name="device_type" class="form-select" required>
                                <option value="smartphone">هاتف ذكي</option>
                                <option value="tablet">جهاز لوحي</option>
                                <option value="smartwatch">ساعة ذكية</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العلامة التجارية</label>
                            <input type="text" name="brand" class="form-control" placeholder="مثال: Apple, Samsung">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الموديل <span class="text-danger">*</span></label>
                            <input type="text" name="model" class="form-control" required placeholder="مثال: iPhone 15 Pro">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرقم التسلسلي</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="الرقم التسلسلي للجهاز">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 1</label>
                            <input type="text" name="imei_number" class="form-control" maxlength="20" placeholder="رقم IMEI الأول">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 2</label>
                            <input type="text" name="imei_number_2" class="form-control" maxlength="20" placeholder="رقم IMEI الثاني">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 3</label>
                            <input type="text" name="imei_number_3" class="form-control" maxlength="20" placeholder="رقم IMEI الثالث">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الضريبة (ج.م)</label>
                            <input type="number" name="tax" class="form-control" step="0.01" min="0" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-info">
                        <i class="ti ti-check me-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
