<div class="card">
    <div id="bulkActionsBar" class="m-3 mb-0 alert alert-danger d-none">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-checkbox me-2"></i>
                <span id="selectedCount">0</span> شكوى محددة
            </div>
            <div class="gap-2 d-flex">
                @can('complaints.delete')
                <button type="button" class="btn btn-sm btn-light text-danger" onclick="bulkDelete()">
                    <i class="ti ti-trash me-1"></i>حذف المحدد
                </button>
                @endcan
                <button type="button" class="btn btn-sm btn-light" onclick="clearSelection()">
                    <i class="ti ti-x me-1"></i>إلغاء التحديد
                </button>
            </div>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-message-report me-1"></i>قائمة الشكاوى
        </h5>
        <span class="badge bg-danger">{{ $complaints->total() }} شكوى</span>
    </div>
    <div class="p-0 card-body">
        @if($complaints->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>الأولوية</th>
                        <th>الشاكي</th>
                        <th>السبب</th>
                        <th>الرسالة</th>
                        <th>جهاز الخدمة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                    <tr class="{{ $complaint->priority == 'urgent' ? 'table-danger' : ($complaint->priority == 'high' ? 'table-warning' : '') }}">
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" value="{{ $complaint->id }}">
                        </td>
                        <td>
                            @switch($complaint->priority)
                                @case('urgent')
                                    <span class="badge bg-danger"><i class="ti ti-alert-triangle me-1"></i>عاجل</span>
                                    @break
                                @case('high')
                                    <span class="badge bg-warning text-dark">عالي</span>
                                    @break
                                @case('medium')
                                    <span class="badge bg-info">متوسط</span>
                                    @break
                                @case('low')
                                    <span class="badge bg-secondary">منخفض</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $complaint->name ?? 'غير محدد' }}</div>
                            @if($complaint->phone)
                            <small class="text-muted">{{ $complaint->phone }}</small>
                            @endif
                        </td>
                        <td>{{ $complaint->reason }}</td>
                        <td>
                            <span title="{{ $complaint->message }}">{{ Str::limit($complaint->message, 40) }}</span>
                        </td>
                        <td>{{ $complaint->machine->name ?? '-' }}</td>
                        <td>
                            @switch($complaint->status)
                                @case('new')
                                    <span class="badge bg-danger">جديد</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-warning text-dark">قيد المعالجة</span>
                                    @break
                                @case('resolved')
                                    <span class="badge bg-success">تم الحل</span>
                                    @break
                                @case('closed')
                                    <span class="badge bg-secondary">مغلق</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('complaints.edit')
                                @if($complaint->status == 'new')
                                <form action="{{ route('admin.complaints.start-progress', $complaint) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-warning-subtle text-warning" title="بدء المعالجة">
                                        <i class="ti ti-player-play fs-5"></i>
                                    </button>
                                </form>
                                @endif
                                @if($complaint->status != 'resolved' && $complaint->status != 'closed')
                                <button type="button" class="btn btn-icon btn-sm bg-success-subtle text-success" title="حل" 
                                    onclick="openResolveModal({{ $complaint->id }})">
                                    <i class="ti ti-check fs-5"></i>
                                </button>
                                @endif
                                @if($complaint->status == 'resolved')
                                <form action="{{ route('admin.complaints.close', $complaint) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-secondary" title="إغلاق">
                                        <i class="ti ti-lock fs-5 text-white"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                                @can('complaints.delete')
                                <button type="button" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حذف" 
                                    onclick="confirmDelete({{ $complaint->id }})">
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
                    <i class="ti ti-mood-smile"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد شكاوى</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي شكاوى</p>
        </div>
        @endif
    </div>
    @if($complaints->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $complaints->firstItem() }} إلى {{ $complaints->lastItem() }} من {{ $complaints->total() }} شكوى
            </div>
            {{ $complaints->links() }}
        </div>
    </div>
    @endif
</div>
