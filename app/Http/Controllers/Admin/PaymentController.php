<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Machine;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['machine', 'passenger', 'mobileDevice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('pos_reference', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $machines = Machine::orderBy('name')->get();
        
        $totalAmount = $query->where('status', 'completed')->sum('amount');

        return view('admin.payments.index', compact('payments', 'machines', 'totalAmount'));
    }

    public function create()
    {
        $machines = Machine::where('status', 'active')->orderBy('name')->get();
        return view('admin.payments.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:pos,cash,card,other',
            'imei_check_id' => 'nullable|exists:imei_checks,id',
            'mobile_device_id' => 'nullable|exists:mobile_devices,id',
            'passenger_id' => 'nullable|exists:passengers,id',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم إضافة الدفعة بنجاح');
    }

    public function show(Payment $payment)
    {
        $payment->load(['machine', 'passenger', 'mobileDevice', 'imeiCheck']);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:pos,cash,card,other',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم تحديث الدفعة بنجاح');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح');
    }

    public function complete(Request $request, Payment $payment)
    {
        $posReference = $request->input('pos_reference');
        $payment->markAsCompleted($posReference);

        return back()->with('success', 'تم تأكيد الدفع بنجاح');
    }

    public function refund(Payment $payment)
    {
        $payment->refund();

        return back()->with('success', 'تم استرداد المبلغ بنجاح');
    }

    public function receipt(Payment $payment)
    {
        return view('admin.payments.receipt', compact('payment'));
    }

    public function export(Request $request)
    {
        return back()->with('info', 'جاري التصدير...');
    }
}
