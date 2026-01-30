<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index(Request $request)
    {
        $query = Machine::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        $machines = $query->withCount(['imeiChecks', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.machines.index', compact('machines'));
    }

    public function create()
    {
        return view('admin.machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:machines,serial_number',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        Machine::create($validated);

        return redirect()->route('admin.machines.index')
            ->with('success', 'تم إضافة الجهاز بنجاح');
    }

    public function show(Machine $machine)
    {
        $machine->loadCount(['imeiChecks', 'payments', 'suggestions', 'complaints']);
        $machine->load(['imeiChecks' => fn($q) => $q->latest()->take(10)]);
        
        return view('admin.machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('admin.machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:machines,serial_number,' . $machine->id,
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $machine->update($validated);

        return redirect()->route('admin.machines.index')
            ->with('success', 'تم تحديث الجهاز بنجاح');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()->route('admin.machines.index')
            ->with('success', 'تم حذف الجهاز بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:machines,id',
        ]);

        Machine::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.machines.index')
            ->with('success', 'تم حذف الأجهزة المحددة بنجاح');
    }

    public function heartbeat(Machine $machine)
    {
        $machine->updateHeartbeat();

        return response()->json(['success' => true]);
    }
}
