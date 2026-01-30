<div class="modal fade" id="addMachineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.machines.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-plus me-2"></i>إضافة جهاز جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم الجهاز <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="مثال: جهاز صالة 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المنطقة</label>
                        <input type="text" name="area" class="form-control" placeholder="مثال: مطار القاهرة الدولي">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الموقع</label>
                        <input type="text" name="place" class="form-control" placeholder="مثال: صالة الوصول 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرقم التسلسلي</label>
                        <input type="text" name="serial_number" class="form-control" placeholder="الرقم التسلسلي للجهاز">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                            <option value="maintenance">صيانة</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
