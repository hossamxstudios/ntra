<div class="card">
    <div id="bulkActionsBar" class="m-3 mb-0 alert alert-primary d-none">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-checkbox me-2"></i>
                <span id="selectedCount">0</span> جهاز محدد
            </div>
            <div class="gap-2 d-flex">
                @can('machines.delete')
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="bulkDelete()">
                    <i class="ti ti-trash me-1"></i>حذف المحدد
                </button>
                @endcan
                <button type="button" class="btn btn-sm btn-light text-primary" onclick="clearSelection()">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-device-desktop me-1"></i>قائمة الأجهزة
        </h5>
        <span class="badge bg-dark">{{ $machines->total() }} جهاز</span>
    </div>
    <div class="p-0 card-body">
        @if($machines->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>الجهاز</th>
                        <th>المنطقة</th>
                        <th>الموقع</th>
                        <th>الرقم التسلسلي</th>
                        <th class="text-center">الفحوصات</th>
                        <th class="text-center">المدفوعات</th>
                        <th>الحالة</th>
                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($machines as $machine)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" value="{{ $machine->id }}">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $machine->name }}</div>
                            <small class="text-muted">{{ $machine->uuid }}</small>
                        </td>
                        <td>{{ $machine->area ?? '-' }}</td>
                        <td>{{ $machine->place ?? '-' }}</td>
                        <td><code>{{ $machine->serial_number ?? '-' }}</code></td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary">{{ $machine->imei_checks_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success">{{ $machine->payments_count }}</span>
                        </td>
                        <td>
                            @switch($machine->status)
                                @case('active')
                                    <span class="badge bg-success">نشط</span>
                                    @break
                                @case('inactive')
                                    <span class="badge bg-secondary">غير نشط</span>
                                    @break
                                @case('maintenance')
                                    <span class="badge bg-warning">صيانة</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.machines.show', $machine) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('machines.edit')
                                <button type="button" class="btn btn-icon btn-sm bg-warning-subtle text-warning" title="تعديل" 
                                    onclick="editMachine({{ $machine->id }}, '{{ $machine->name }}', '{{ $machine->area }}', '{{ $machine->place }}', '{{ $machine->serial_number }}', '{{ $machine->status }}')">
                                    <i class="ti ti-edit fs-5"></i>
                                </button>
                                @endcan
                                @can('machines.delete')
                                <button type="button" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حذف" 
                                    onclick="confirmDelete({{ $machine->id }}, '{{ $machine->name }}')">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-5 text-center">
            <div class="mx-auto mb-3 avatar-lg">
                <span class="avatar-title bg-light text-muted rounded-circle fs-1">
                    <i class="ti ti-device-desktop-off"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد أجهزة</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي أجهزة مطابقة لمعايير البحث</p>
            @can('machines.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMachineModal">
                    <i class="ti ti-plus me-1"></i>إضافة جهاز جديد
                </button>
            @endcan
        </div>
        @endif
    </div>
    @if($machines->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $machines->firstItem() }} إلى {{ $machines->lastItem() }} من {{ $machines->total() }} جهاز
            </div>
            {{ $machines->links() }}
        </div>
    </div>
    @endif
</div>
