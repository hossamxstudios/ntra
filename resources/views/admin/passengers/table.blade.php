<div class="card">
    <div id="bulkActionsBar" class="m-3 mb-0 alert alert-purple d-none" style="background-color: rgba(124, 58, 237, 0.1); border-color: #7c3aed;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-checkbox me-2"></i>
                <span id="selectedCount">0</span> مسافر محدد
            </div>
            <div class="gap-2 d-flex">
                @can('passengers.delete')
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="bulkDelete()">
                    <i class="ti ti-trash me-1"></i>حذف المحدد
                </button>
                @endcan
                <button type="button" class="btn btn-sm btn-light text-purple" onclick="clearSelection()">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-users me-1"></i>قائمة المسافرين
        </h5>
        <span class="badge bg-purple-subtle text-purple">{{ $passengers->total() }} مسافر</span>
    </div>
    <div class="p-0 card-body">
        @if($passengers->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>المسافر</th>
                        <th>الجنسية</th>
                        <th>رقم الجواز</th>
                        <th>الرقم القومي</th>
                        <th class="text-center">الأجهزة</th>
                        <th>تاريخ التسجيل</th>
                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($passengers as $passenger)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" value="{{ $passenger->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @if($passenger->getFirstMediaUrl('passenger_photo', 'thumb'))
                                    <img src="{{ $passenger->getFirstMediaUrl('passenger_photo', 'thumb') }}" class="rounded-circle">
                                    @else
                                    <span class="avatar-title bg-purple-subtle text-purple rounded-circle">
                                        {{ substr($passenger->first_name ?? 'م', 0, 1) }}
                                    </span>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $passenger->full_name ?: 'غير محدد' }}</div>
                                    @if($passenger->gender)
                                    <small class="text-muted">{{ $passenger->gender == 'male' ? 'ذكر' : 'أنثى' }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $passenger->nationality ?? '-' }}</td>
                        <td><code>{{ $passenger->passport_no ?? '-' }}</code></td>
                        <td><code>{{ $passenger->national_id ?? '-' }}</code></td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info">{{ $passenger->mobileDevices->count() }}</span>
                        </td>
                        <td>{{ $passenger->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.passengers.show', $passenger) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('passengers.edit')
                                <button type="button" class="btn btn-icon btn-sm bg-warning-subtle text-warning" title="تعديل" 
                                    onclick="editPassenger({{ json_encode($passenger) }})">
                                    <i class="ti ti-edit fs-5"></i>
                                </button>
                                @endcan
                                @can('passengers.delete')
                                <button type="button" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حذف" 
                                    onclick="confirmDelete({{ $passenger->id }}, '{{ $passenger->full_name }}')">
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
                    <i class="ti ti-users-minus"></i>
                </span>
            </div>
            <h5 class="text-muted">لا يوجد مسافرين</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي مسافرين</p>
        </div>
        @endif
    </div>
    @if($passengers->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $passengers->firstItem() }} إلى {{ $passengers->lastItem() }} من {{ $passengers->total() }} مسافر
            </div>
            {{ $passengers->links() }}
        </div>
    </div>
    @endif
</div>
