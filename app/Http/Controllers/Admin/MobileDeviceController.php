<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileDevice;
use Illuminate\Http\Request;

class MobileDeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = MobileDevice::with('passenger');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('imei_number', 'like', "%{$search}%")
                  ->orWhere('imei_number_2', 'like', "%{$search}%")
                  ->orWhere('imei_number_3', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_paid')) {
            $query->where('is_paid', $request->is_paid === '1');
        }

        if ($request->filled('is_activated')) {
            $query->where('is_activated', $request->is_activated === '1');
        }

        if ($request->filled('is_locked')) {
            $query->where('is_locked', $request->is_locked === '1');
        }

        $mobileDevices = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.mobile-devices.index', compact('mobileDevices'));
    }

    public function create()
    {
        return view('admin.mobile-devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'imei_number' => 'nullable|string|max:20',
            'imei_number_2' => 'nullable|string|max:20',
            'imei_number_3' => 'nullable|string|max:20',
            'serial_number' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
        ]);

        MobileDevice::create($validated);

        return redirect()->route('admin.mobile-devices.index')
            ->with('success', 'تم إضافة الجهاز المحمول بنجاح');
    }

    public function show(MobileDevice $mobileDevice)
    {
        $mobileDevice->load(['passenger', 'imeiChecks', 'payments']);
        
        return view('admin.mobile-devices.show', compact('mobileDevice'));
    }

    public function edit(MobileDevice $mobileDevice)
    {
        return view('admin.mobile-devices.edit', compact('mobileDevice'));
    }

    public function update(Request $request, MobileDevice $mobileDevice)
    {
        $validated = $request->validate([
            'device_type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'imei_number' => 'nullable|string|max:20',
            'imei_number_2' => 'nullable|string|max:20',
            'imei_number_3' => 'nullable|string|max:20',
            'serial_number' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $mobileDevice->update($validated);

        return redirect()->route('admin.mobile-devices.index')
            ->with('success', 'تم تحديث الجهاز المحمول بنجاح');
    }

    public function destroy(MobileDevice $mobileDevice)
    {
        $mobileDevice->delete();

        return redirect()->route('admin.mobile-devices.index')
            ->with('success', 'تم حذف الجهاز المحمول بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:mobile_devices,id',
        ]);

        MobileDevice::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.mobile-devices.index')
            ->with('success', 'تم حذف الأجهزة المحددة بنجاح');
    }

    public function activate(MobileDevice $mobileDevice)
    {
        $mobileDevice->activate();

        return back()->with('success', 'تم تفعيل الجهاز بنجاح');
    }

    public function lock(MobileDevice $mobileDevice)
    {
        $mobileDevice->update(['is_locked' => true]);

        return back()->with('success', 'تم حظر الجهاز بنجاح');
    }

    public function unlock(MobileDevice $mobileDevice)
    {
        $mobileDevice->update(['is_locked' => false]);

        return back()->with('success', 'تم إلغاء حظر الجهاز بنجاح');
    }

    public function export(Request $request)
    {
        // Export logic here
        return back()->with('info', 'جاري التصدير...');
    }
}
