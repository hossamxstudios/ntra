<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        $query = Passenger::with('mobileDevices');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('passport_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('nationality')) {
            $query->where('nationality', $request->nationality);
        }

        $passengers = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.passengers.index', compact('passengers'));
    }

    public function create()
    {
        return view('admin.passengers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'document_number' => 'nullable|string|max:255',
            'valid_until' => 'nullable|date',
            'national_id' => 'nullable|string|max:20',
            'passport_no' => 'nullable|string|max:50',
        ]);

        $passenger = Passenger::create($validated);

        if ($request->hasFile('passenger_photo')) {
            $passenger->addMediaFromRequest('passenger_photo')->toMediaCollection('passenger_photo');
        }

        if ($request->hasFile('passport_document')) {
            $passenger->addMediaFromRequest('passport_document')->toMediaCollection('passport_document');
        }

        return redirect()->route('admin.passengers.index')
            ->with('success', 'تم إضافة المسافر بنجاح');
    }

    public function show(Passenger $passenger)
    {
        $passenger->load(['mobileDevices', 'payments', 'imeiChecks']);
        
        return view('admin.passengers.show', compact('passenger'));
    }

    public function edit(Passenger $passenger)
    {
        return view('admin.passengers.edit', compact('passenger'));
    }

    public function update(Request $request, Passenger $passenger)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'document_number' => 'nullable|string|max:255',
            'valid_until' => 'nullable|date',
            'national_id' => 'nullable|string|max:20',
            'passport_no' => 'nullable|string|max:50',
        ]);

        $passenger->update($validated);

        if ($request->hasFile('passenger_photo')) {
            $passenger->clearMediaCollection('passenger_photo');
            $passenger->addMediaFromRequest('passenger_photo')->toMediaCollection('passenger_photo');
        }

        if ($request->hasFile('passport_document')) {
            $passenger->clearMediaCollection('passport_document');
            $passenger->addMediaFromRequest('passport_document')->toMediaCollection('passport_document');
        }

        return redirect()->route('admin.passengers.index')
            ->with('success', 'تم تحديث بيانات المسافر بنجاح');
    }

    public function destroy(Passenger $passenger)
    {
        $passenger->delete();

        return redirect()->route('admin.passengers.index')
            ->with('success', 'تم حذف المسافر بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:passengers,id',
        ]);

        Passenger::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.passengers.index')
            ->with('success', 'تم حذف المسافرين المحددين بنجاح');
    }

    public function export(Request $request)
    {
        return back()->with('info', 'جاري التصدير...');
    }
}
