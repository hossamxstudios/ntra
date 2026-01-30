<div class="modal fade" id="addPassengerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.passengers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-user-plus me-2"></i>إضافة مسافر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الأول</label>
                            <input type="text" name="first_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم العائلة</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تاريخ الميلاد</label>
                            <input type="date" name="birthdate" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الجنس</label>
                            <select name="gender" class="form-select">
                                <option value="">اختر...</option>
                                <option value="male">ذكر</option>
                                <option value="female">أنثى</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">الجنسية</label>
                            <input type="text" name="nationality" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الجواز</label>
                            <input type="text" name="passport_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الرقم القومي</label>
                            <input type="text" name="national_id" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الوثيقة</label>
                            <input type="text" name="document_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">صالح حتى</label>
                            <input type="date" name="valid_until" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">صورة المسافر</label>
                            <input type="file" name="passenger_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">صورة الجواز</label>
                            <input type="file" name="passport_document" class="form-control" accept="image/*,.pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-purple">
                        <i class="ti ti-check me-1"></i>حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
