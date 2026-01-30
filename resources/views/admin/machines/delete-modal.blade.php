<div class="modal fade" id="deleteMachineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteMachineForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="ti ti-trash me-2"></i>حذف الجهاز</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mx-auto mb-3 avatar-lg">
                            <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-1">
                                <i class="ti ti-alert-triangle"></i>
                            </span>
                        </div>
                        <h5>هل أنت متأكد؟</h5>
                        <p class="text-muted">سيتم حذف الجهاز <strong id="delete_machine_name"></strong> نهائياً.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>حذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="bulkDeleteForm" action="{{ route('admin.machines.bulk-delete') }}" method="POST">
                @csrf
                <div id="bulkDeleteIds"></div>
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="ti ti-trash me-2"></i>حذف الأجهزة المحددة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mx-auto mb-3 avatar-lg">
                            <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-1">
                                <i class="ti ti-alert-triangle"></i>
                            </span>
                        </div>
                        <h5>هل أنت متأكد؟</h5>
                        <p class="text-muted">سيتم حذف <strong id="bulk_delete_count"></strong> جهاز نهائياً.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>حذف الكل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
