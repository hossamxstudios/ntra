<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-barcode me-1"></i>سجل الفحوصات
        </h5>
        <span class="badge bg-primary">{{ $imeiChecks->total() }} فحص</span>
    </div>
    <div class="p-0 card-body">
        @if($imeiChecks->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>IMEI</th>
                        <th>الجهاز</th>
                        <th>المسافر</th>
                        <th>جهاز الخدمة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th class="text-center" style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($imeiChecks as $check)
                    <tr>
                        <td>{{ $check->id }}</td>
                        <td><code class="text-primary">{{ $check->scanned_imei ?? '-' }}</code></td>
                        <td>
                            @if($check->mobileDevice)
                            <a href="{{ route('admin.mobile-devices.show', $check->mobileDevice) }}">
                                {{ $check->mobileDevice->brand }} {{ $check->mobileDevice->model }}
                            </a>
                            @else
                            <span class="text-muted">غير مرتبط</span>
                            @endif
                        </td>
                        <td>
                            @if($check->passenger)
                            <a href="{{ route('admin.passengers.show', $check->passenger) }}">
                                {{ $check->passenger->full_name }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $check->machine->name ?? '-' }}</td>
                        <td>
                            @switch($check->status)
                                @case('completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">ملغي</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $check->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.imei-checks.show', $check) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @if($check->status == 'pending')
                                @can('imei-checks.edit')
                                <form action="{{ route('admin.imei-checks.complete', $check) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-success-subtle text-success" title="إكمال">
                                        <i class="ti ti-check fs-5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.imei-checks.cancel', $check) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="إلغاء">
                                        <i class="ti ti-x fs-5"></i>
                                    </button>
                                </form>
                                @endcan
                                @endif
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
                    <i class="ti ti-barcode-off"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد فحوصات</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي فحوصات IMEI</p>
        </div>
        @endif
    </div>
    @if($imeiChecks->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $imeiChecks->firstItem() }} إلى {{ $imeiChecks->lastItem() }} من {{ $imeiChecks->total() }} فحص
            </div>
            {{ $imeiChecks->links() }}
        </div>
    </div>
    @endif
</div>
