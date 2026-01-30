<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>شكوى #{{ $complaint->id }} - الشكاوى</title>
    @include('admin.main.meta')
</head>
<body>
    <div class="wrapper">
        @include('admin.main.topbar')
        @include('admin.main.sidebar')
        <div class="content-page">
            <div class="container-fluid">
                <div class="p-2 mt-3 mb-4 bg-white rounded border-0 shadow card">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">شكوى #{{ $complaint->id }}</h4>
                            <div class="text-muted">{{ $complaint->reason }}</div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            @switch($complaint->priority)
                                @case('urgent')
                                    <span class="badge bg-danger fs-6">عاجل</span>
                                    @break
                                @case('high')
                                    <span class="badge bg-warning text-dark fs-6">عالي</span>
                                    @break
                                @case('medium')
                                    <span class="badge bg-info fs-6">متوسط</span>
                                    @break
                                @case('low')
                                    <span class="badge bg-secondary fs-6">منخفض</span>
                                    @break
                            @endswitch
                            @switch($complaint->status)
                                @case('new')
                                    <span class="badge bg-danger fs-6">جديد</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-warning text-dark fs-6">قيد المعالجة</span>
                                    @break
                                @case('resolved')
                                    <span class="badge bg-success fs-6">تم الحل</span>
                                    @break
                                @case('closed')
                                    <span class="badge bg-secondary fs-6">مغلق</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">معلومات الشاكي</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted">الاسم</th>
                                        <td>{{ $complaint->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">البريد الإلكتروني</th>
                                        <td>{{ $complaint->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الهاتف</th>
                                        <td>{{ $complaint->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم القومي</th>
                                        <td>{{ $complaint->national_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">جهاز الخدمة</th>
                                        <td>{{ $complaint->machine->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">تاريخ التقديم</th>
                                        <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    @if($complaint->resolved_at)
                                    <tr>
                                        <th class="text-muted">تاريخ الحل</th>
                                        <td>{{ $complaint->resolved_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">تفاصيل الشكوى</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="text-muted mb-2">السبب</h6>
                                <p class="mb-4">{{ $complaint->reason }}{{ $complaint->other_reason ? ' - ' . $complaint->other_reason : '' }}</p>
                                
                                <h6 class="text-muted mb-2">الرسالة</h6>
                                <div class="p-3 bg-light rounded mb-4">
                                    {{ $complaint->message }}
                                </div>

                                @if($complaint->resolution)
                                <h6 class="text-muted mb-2">الحل</h6>
                                <div class="p-3 bg-success-subtle rounded">
                                    {{ $complaint->resolution }}
                                </div>
                                @endif

                                @if($complaint->getMedia('attachments')->count() > 0)
                                <h6 class="text-muted mt-4 mb-2">المرفقات</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($complaint->getMedia('attachments') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-file me-1"></i>{{ $media->file_name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @can('complaints.edit')
                            <div class="card-footer">
                                <div class="d-flex gap-2">
                                    @if($complaint->status == 'new')
                                    <form action="{{ route('admin.complaints.start-progress', $complaint) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="ti ti-player-play me-1"></i>بدء المعالجة
                                        </button>
                                    </form>
                                    @endif
                                    @if($complaint->status != 'resolved' && $complaint->status != 'closed')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#resolveModal">
                                        <i class="ti ti-check me-1"></i>حل الشكوى
                                    </button>
                                    @endif
                                    @if($complaint->status == 'resolved')
                                    <form action="{{ route('admin.complaints.close', $complaint) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="ti ti-lock me-1"></i>إغلاق الشكوى
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('complaints.edit')
    <div class="modal fade" id="resolveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.complaints.resolve', $complaint) }}" method="POST">
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
    @endcan

    @include('admin.main.scripts')
</body>
</html>
