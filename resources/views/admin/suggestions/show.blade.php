<!DOCTYPE html>
@include('admin.main.html')
<head>
    <title>اقتراح #{{ $suggestion->id }} - الاقتراحات</title>
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
                            <a href="{{ route('admin.suggestions.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                        <div class="col">
                            <h4 class="mb-1">اقتراح #{{ $suggestion->id }}</h4>
                            <div class="text-muted">{{ $suggestion->reason }}</div>
                        </div>
                        <div class="col-auto">
                            @switch($suggestion->status)
                                @case('new')
                                    <span class="badge bg-warning fs-6">جديد</span>
                                    @break
                                @case('reviewed')
                                    <span class="badge bg-info fs-6">تمت المراجعة</span>
                                    @break
                                @case('addressed')
                                    <span class="badge bg-success fs-6">تمت المعالجة</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">معلومات المقترح</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-muted">الاسم</th>
                                        <td>{{ $suggestion->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">البريد الإلكتروني</th>
                                        <td>{{ $suggestion->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الهاتف</th>
                                        <td>{{ $suggestion->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">الرقم القومي</th>
                                        <td>{{ $suggestion->national_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">جهاز الخدمة</th>
                                        <td>{{ $suggestion->machine->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">التاريخ</th>
                                        <td>{{ $suggestion->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 card-title">تفاصيل الاقتراح</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="text-muted mb-2">السبب</h6>
                                <p class="mb-4">{{ $suggestion->reason }}{{ $suggestion->other_reason ? ' - ' . $suggestion->other_reason : '' }}</p>
                                
                                <h6 class="text-muted mb-2">الرسالة</h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $suggestion->message }}
                                </div>

                                @if($suggestion->getMedia('attachments')->count() > 0)
                                <h6 class="text-muted mt-4 mb-2">المرفقات</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($suggestion->getMedia('attachments') as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-file me-1"></i>{{ $media->file_name }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @can('suggestions.edit')
                            <div class="card-footer">
                                <div class="d-flex gap-2">
                                    @if($suggestion->status == 'new')
                                    <form action="{{ route('admin.suggestions.review', $suggestion) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            <i class="ti ti-checks me-1"></i>تمت المراجعة
                                        </button>
                                    </form>
                                    @endif
                                    @if($suggestion->status != 'addressed')
                                    <form action="{{ route('admin.suggestions.address', $suggestion) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="ti ti-check me-1"></i>تمت المعالجة
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
    @include('admin.main.scripts')
</body>
</html>
