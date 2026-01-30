<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImeiCheck;
use App\Models\MobileDevice;
use App\Models\Machine;
use Illuminate\Http\Request;

class ImeiCheckController extends Controller
{
    public function index(Request $request)
    {
        $query = ImeiCheck::with(['machine', 'mobileDevice', 'passenger']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('scanned_imei', 'like', "%{$search}%")
                  ->orWhere('phone_serial', 'like', "%{$search}%");
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

        $imeiChecks = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $machines = Machine::orderBy('name')->get();

        return view('admin.imei-checks.index', compact('imeiChecks', 'machines'));
    }

    public function create()
    {
        $machines = Machine::where('status', 'active')->orderBy('name')->get();
        return view('admin.imei-checks.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'scanned_imei' => 'nullable|string|max:20',
            'phone_serial' => 'nullable|string|max:255',
        ]);

        $validated['status'] = 'pending';

        ImeiCheck::create($validated);

        return redirect()->route('admin.imei-checks.index')
            ->with('success', 'تم إضافة فحص IMEI بنجاح');
    }

    public function show(ImeiCheck $imeiCheck)
    {
        $imeiCheck->load(['machine', 'mobileDevice', 'passenger', 'payments']);
        
        return view('admin.imei-checks.show', compact('imeiCheck'));
    }

    public function update(Request $request, ImeiCheck $imeiCheck)
    {
        $validated = $request->validate([
            'scanned_imei' => 'nullable|string|max:20',
            'phone_serial' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $imeiCheck->update($validated);

        return redirect()->route('admin.imei-checks.index')
            ->with('success', 'تم تحديث فحص IMEI بنجاح');
    }

    public function destroy(ImeiCheck $imeiCheck)
    {
        $imeiCheck->delete();

        return redirect()->route('admin.imei-checks.index')
            ->with('success', 'تم حذف فحص IMEI بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:imei_checks,id',
        ]);

        ImeiCheck::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.imei-checks.index')
            ->with('success', 'تم حذف الفحوصات المحددة بنجاح');
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'imei' => 'required|string|max:20',
        ]);

        // Check if device exists
        $device = MobileDevice::byImei($validated['imei'])->first();

        $imeiCheck = ImeiCheck::create([
            'machine_id' => $validated['machine_id'],
            'scanned_imei' => $validated['imei'],
            'mobile_device_id' => $device?->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'imei_check' => $imeiCheck->load('mobileDevice'),
            'device' => $device,
            'tax_amount' => $device?->tax ?? 0,
            'is_paid' => $device?->is_paid ?? false,
        ]);
    }

    public function complete(ImeiCheck $imeiCheck)
    {
        $imeiCheck->complete();

        return back()->with('success', 'تم إكمال الفحص بنجاح');
    }

    public function cancel(ImeiCheck $imeiCheck)
    {
        $imeiCheck->cancel();

        return back()->with('success', 'تم إلغاء الفحص');
    }

    public function export(Request $request)
    {
        return back()->with('info', 'جاري التصدير...');
    }
}
