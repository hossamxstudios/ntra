<div class="modal fade" id="resolveComplaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="resolveComplaintForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-success"><i class="ti ti-check me-2"></i>حل الشكوى</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">تفاصيل الحل <span class="text-danger">*</span></label>
                        <textarea name="resolution" class="form-control" rows="4" required placeholder="اكتب تفاصيل حل المشكلة..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check me-1"></i>حل الشكوى
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
