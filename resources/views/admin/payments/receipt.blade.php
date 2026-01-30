<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال الدفع #{{ $payment->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; padding: 20px; background: #f5f5f5; }
        .receipt { max-width: 400px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .title { font-size: 18px; margin-top: 10px; color: #666; }
        .details { margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .label { color: #666; }
        .value { font-weight: 600; }
        .amount { text-align: center; padding: 20px 0; border-top: 2px dashed #ddd; border-bottom: 2px dashed #ddd; margin: 20px 0; }
        .amount-value { font-size: 32px; font-weight: bold; color: #28a745; }
        .amount-label { color: #666; margin-top: 5px; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        @media print {
            body { padding: 0; background: #fff; }
            .receipt { box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="logo">NTRA</div>
            <div class="title">إيصال دفع</div>
        </div>

        <div class="details">
            <div class="row">
                <span class="label">رقم العملية:</span>
                <span class="value">{{ $payment->transaction_id ?? '#' . $payment->id }}</span>
            </div>
            <div class="row">
                <span class="label">التاريخ:</span>
                <span class="value">{{ $payment->paid_at?->format('Y-m-d H:i') ?? $payment->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="row">
                <span class="label">طريقة الدفع:</span>
                <span class="value">
                    @switch($payment->payment_method)
                        @case('pos') نقاط البيع @break
                        @case('cash') نقدي @break
                        @case('card') بطاقة @break
                        @default {{ $payment->payment_method }}
                    @endswitch
                </span>
            </div>
            @if($payment->pos_reference)
            <div class="row">
                <span class="label">مرجع POS:</span>
                <span class="value">{{ $payment->pos_reference }}</span>
            </div>
            @endif
            @if($payment->passenger)
            <div class="row">
                <span class="label">المسافر:</span>
                <span class="value">{{ $payment->passenger->full_name }}</span>
            </div>
            @endif
            @if($payment->mobileDevice)
            <div class="row">
                <span class="label">الجهاز:</span>
                <span class="value">{{ $payment->mobileDevice->brand }} {{ $payment->mobileDevice->model }}</span>
            </div>
            @endif
        </div>

        <div class="amount">
            <div class="amount-value">{{ number_format($payment->amount, 2) }} ج.م</div>
            <div class="amount-label">المبلغ المدفوع</div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <span class="status {{ $payment->status == 'completed' ? 'status-completed' : 'status-pending' }}">
                @switch($payment->status)
                    @case('completed') تم الدفع بنجاح @break
                    @case('pending') قيد الانتظار @break
                    @case('refunded') تم الاسترداد @break
                    @default {{ $payment->status }}
                @endswitch
            </span>
        </div>

        <div class="footer">
            <p>شكراً لاستخدامكم خدماتنا</p>
            <p>NTRA - الجهاز القومي لتنظيم الاتصالات</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; cursor: pointer;">طباعة</button>
    </div>
</body>
</html>
