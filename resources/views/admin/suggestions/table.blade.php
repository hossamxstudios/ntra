<div class="card">
    <div id="bulkActionsBar" class="m-3 mb-0 alert alert-warning d-none">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-checkbox me-2"></i>
                <span id="selectedCount">0</span> اقتراح محدد
            </div>
            <div class="gap-2 d-flex">
                @can('suggestions.delete')
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
            <i class="ti ti-bulb me-1"></i>قائمة الاقتراحات
        </h5>
        <span class="badge bg-warning">{{ $suggestions->total() }} اقتراح</span>
    </div>
    <div class="p-0 card-body">
        @if($suggestions->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>المقترح</th>
                        <th>السبب</th>
                        <th>الرسالة</th>
                        <th>جهاز الخدمة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th class="text-center" style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suggestions as $suggestion)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" value="{{ $suggestion->id }}">
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $suggestion->name ?? 'غير محدد' }}</div>
                            @if($suggestion->email)
                            <small class="text-muted">{{ $suggestion->email }}</small>
                            @endif
                        </td>
                        <td>{{ $suggestion->reason }}</td>
                        <td>
                            <span title="{{ $suggestion->message }}">{{ Str::limit($suggestion->message, 50) }}</span>
                        </td>
                        <td>{{ $suggestion->machine->name ?? '-' }}</td>
                        <td>
                            @switch($suggestion->status)
                                @case('new')
                                    <span class="badge bg-warning">جديد</span>
                                    @break
                                @case('reviewed')
                                    <span class="badge bg-info">تمت المراجعة</span>
                                    @break
                                @case('addressed')
                                    <span class="badge bg-success">تمت المعالجة</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $suggestion->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.suggestions.show', $suggestion) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('suggestions.edit')
                                @if($suggestion->status == 'new')
                                <form action="{{ route('admin.suggestions.review', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-info-subtle text-info" title="مراجعة">
                                        <i class="ti ti-checks fs-5"></i>
                                    </button>
                                </form>
                                @endif
                                @if($suggestion->status != 'addressed')
                                <form action="{{ route('admin.suggestions.address', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-success-subtle text-success" title="معالجة">
                                        <i class="ti ti-check fs-5"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                                @can('suggestions.delete')
                                <button type="button" class="btn btn-icon btn-sm bg-danger-subtle text-danger" title="حذف" 
                                    onclick="confirmDelete({{ $suggestion->id }})">
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
                    <i class="ti ti-bulb-off"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد اقتراحات</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي اقتراحات</p>
        </div>
        @endif
    </div>
    @if($suggestions->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $suggestions->firstItem() }} إلى {{ $suggestions->lastItem() }} من {{ $suggestions->total() }} اقتراح
            </div>
            {{ $suggestions->links() }}
        </div>
    </div>
    @endif
</div>
