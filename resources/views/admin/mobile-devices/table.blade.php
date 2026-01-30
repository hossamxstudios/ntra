<div class="card">
    <div id="bulkActionsBar" class="m-3 mb-0 alert alert-info d-none">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-checkbox me-2"></i>
                <span id="selectedCount">0</span> جهاز محدد
            </div>
            <div class="gap-2 d-flex">
                @can('mobile-devices.delete')
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="bulkDelete()">
                    <i class="ti ti-trash me-1"></i>حذف المحدد
                </button>
                @endcan
                <button type="button" class="btn btn-sm btn-light text-info" onclick="clearSelection()">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-device-mobile me-1"></i>قائمة الأجهزة المحمولة
        </h5>
        <span class="badge bg-info">{{ $mobileDevices->total() }} جهاز</span>
    </div>
    <div class="p-0 card-body">
        @if($mobileDevices->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>الجهاز</th>
                        <th>IMEI</th>
                        <th>الرقم التسلسلي</th>
                        <th class="text-center">الضريبة</th>
                        <th class="text-center">الدفع</th>
                        <th class="text-center">التفعيل</th>
                        <th class="text-center">الحظر</th>
                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobileDevices as $device)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" value="{{ $device->id }}">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $device->brand ?? '' }} {{ $device->model }}</div>
                            <small class="text-muted">{{ $device->device_type }}</small>
                        </td>
                        <td>
                            <code class="text-primary">{{ $device->imei_number ?? '-' }}</code>
                            @if($device->imei_number_2)
                            <br><code class="text-muted small">{{ $device->imei_number_2 }}</code>
                            @endif
                        </td>
                        <td><code>{{ $device->serial_number ?? '-' }}</code></td>
                        <td class="text-center">
                            @if($device->tax)
                            <span class="badge bg-warning-subtle text-warning">{{ number_format($device->tax, 2) }} ج.م</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($device->is_paid)
                            <span class="badge bg-success"><i class="ti ti-check"></i></span>
                            @else
                            <span class="badge bg-danger"><i class="ti ti-x"></i></span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($device->is_activated)
                            <span class="badge bg-success"><i class="ti ti-check"></i></span>
                            @else
                            <span class="badge bg-secondary"><i class="ti ti-x"></i></span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($device->is_locked)
                            <span class="badge bg-danger"><i class="ti ti-lock"></i></span>
                            @else
                            <span class="badge bg-success"><i class="ti ti-lock-open"></i></span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.mobile-devices.show', $device) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('mobile-devices.edit')
                                <button type="button" class="btn btn-icon btn-sm bg-warning-subtle text-warning" title="تعديل" 
                                    onclick="editDevice({{ json_encode($device) }})">
                                    <i class="ti ti-edit fs-5"></i>
                                </button>
                                @if(!$device->is_locked)
                                <form action="{{ route('admin.mobile-devices.lock', $device) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حظر">
                                        <i class="ti ti-lock fs-5"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.mobile-devices.unlock', $device) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-success-subtle text-success" title="إلغاء الحظر">
                                        <i class="ti ti-lock-open fs-5"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                                @can('mobile-devices.delete')
                                <button type="button" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حذف" 
                                    onclick="confirmDelete({{ $device->id }}, '{{ $device->model }}')">
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
                    <i class="ti ti-device-mobile-off"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد أجهزة</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي أجهزة محمولة</p>
            @can('mobile-devices.create')
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addMobileDeviceModal">
                    <i class="ti ti-plus me-1"></i>إضافة جهاز جديد
                </button>
            @endcan
        </div>
        @endif
    </div>
    @if($mobileDevices->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $mobileDevices->firstItem() }} إلى {{ $mobileDevices->lastItem() }} من {{ $mobileDevices->total() }} جهاز
            </div>
            {{ $mobileDevices->links() }}
        </div>
    </div>
    @endif
</div>
