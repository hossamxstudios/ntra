<div class="modal fade" id="editMobileDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editMobileDeviceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>تعديل الجهاز المحمول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع الجهاز <span class="text-danger">*</span></label>
                            <select name="device_type" id="edit_device_type" class="form-select" required>
                                <option value="smartphone">هاتف ذكي</option>
                                <option value="tablet">جهاز لوحي</option>
                                <option value="smartwatch">ساعة ذكية</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العلامة التجارية</label>
                            <input type="text" name="brand" id="edit_brand" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الموديل <span class="text-danger">*</span></label>
                            <input type="text" name="model" id="edit_model" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرقم التسلسلي</label>
                            <input type="text" name="serial_number" id="edit_serial_number" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 1</label>
                            <input type="text" name="imei_number" id="edit_imei_number" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 2</label>
                            <input type="text" name="imei_number_2" id="edit_imei_number_2" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">IMEI 3</label>
                            <input type="text" name="imei_number_3" id="edit_imei_number_3" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الضريبة (ج.م)</label>
                            <input type="number" name="tax" id="edit_tax" class="form-control" step="0.01" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i>تحديث
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
