<div class="modal fade" id="editMachineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMachineForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>تعديل الجهاز</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم الجهاز <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المنطقة</label>
                        <input type="text" name="area" id="edit_area" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الموقع</label>
                        <input type="text" name="place" id="edit_place" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرقم التسلسلي</label>
                        <input type="text" name="serial_number" id="edit_serial_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                            <option value="maintenance">صيانة</option>
                        </select>
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
