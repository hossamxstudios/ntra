<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="ti ti-credit-card me-1"></i>سجل المدفوعات
        </h5>
        <div>
            <span class="badge bg-success me-2">إجمالي: {{ number_format($totalAmount, 2) }} ج.م</span>
            <span class="badge bg-secondary">{{ $payments->total() }} عملية</span>
        </div>
    </div>
    <div class="p-0 card-body">
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>رقم العملية</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th>المسافر</th>
                        <th>جهاز الخدمة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th class="text-center" style="width: 150px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>
                            <code>{{ $payment->transaction_id ?? '#' . $payment->id }}</code>
                            @if($payment->pos_reference)
                            <br><small class="text-muted">POS: {{ $payment->pos_reference }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-success">{{ number_format($payment->amount, 2) }}</span>
                            <small class="text-muted">{{ $payment->currency ?? 'EGP' }}</small>
                        </td>
                        <td>
                            @switch($payment->payment_method)
                                @case('pos')
                                    <span class="badge bg-info-subtle text-info">نقاط البيع</span>
                                    @break
                                @case('cash')
                                    <span class="badge bg-success-subtle text-success">نقدي</span>
                                    @break
                                @case('card')
                                    <span class="badge bg-primary-subtle text-primary">بطاقة</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                            @endswitch
                        </td>
                        <td>
                            @if($payment->passenger)
                            <a href="{{ route('admin.passengers.show', $payment->passenger) }}">
                                {{ $payment->passenger->full_name }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $payment->machine->name ?? '-' }}</td>
                        <td>
                            @switch($payment->status)
                                @case('completed')
                                    <span class="badge bg-success">مكتمل</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger">فشل</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-secondary">مسترد</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $payment->paid_at?->format('Y-m-d H:i') ?? $payment->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <div class="gap-1 d-flex justify-content-center">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-icon btn-sm bg-info-subtle text-info" title="عرض">
                                    <i class="ti ti-eye fs-5"></i>
                                </a>
                                @can('payments.receipt')
                                <a href="{{ route('admin.payments.receipt', $payment) }}" class="btn btn-icon btn-sm bg-primary-subtle text-primary" title="إيصال" target="_blank">
                                    <i class="ti ti-printer fs-5"></i>
                                </a>
                                @endcan
                                @if($payment->status == 'pending')
                                @can('payments.edit')
                                <form action="{{ route('admin.payments.complete', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-success-subtle text-success" title="تأكيد الدفع">
                                        <i class="ti ti-check fs-5"></i>
                                    </button>
                                </form>
                                @endcan
                                @endif
                                @if($payment->status == 'completed')
                                @can('payments.refund')
                                <form action="{{ route('admin.payments.refund', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من استرداد هذا المبلغ؟')">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-sm bg-warning-subtle text-warning" title="استرداد">
                                        <i class="ti ti-arrow-back-up fs-5"></i>
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
                    <i class="ti ti-credit-card-off"></i>
                </span>
            </div>
            <h5 class="text-muted">لا توجد مدفوعات</h5>
            <p class="mb-3 text-muted">لم يتم العثور على أي مدفوعات</p>
        </div>
        @endif
    </div>
    @if($payments->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted fs-sm">
                عرض {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من {{ $payments->total() }} عملية
            </div>
            {{ $payments->links() }}
        </div>
    </div>
    @endif
</div>
