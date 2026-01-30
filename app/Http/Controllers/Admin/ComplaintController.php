<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('machine');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $complaints = $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('admin.complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'reason' => 'required|string|max:255',
            'message' => 'required|string',
            'other_reason' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'machine_id' => 'nullable|exists:machines,id',
        ]);

        $complaint = Complaint::create($validated);

        if ($request->hasFile('attachment')) {
            $complaint->addMediaFromRequest('attachment')->toMediaCollection('attachments');
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', 'تم إضافة الشكوى بنجاح');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('machine');
        
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'resolution' => 'nullable|string',
        ]);

        $complaint->update($validated);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'تم تحديث الشكوى بنجاح');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'تم حذف الشكوى بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:complaints,id',
        ]);

        Complaint::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.complaints.index')
            ->with('success', 'تم حذف الشكاوى المحددة بنجاح');
    }

    public function startProgress(Complaint $complaint)
    {
        $complaint->startProgress();

        return back()->with('success', 'تم بدء معالجة الشكوى');
    }

    public function resolve(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'resolution' => 'required|string',
        ]);

        $complaint->resolve($validated['resolution']);

        return back()->with('success', 'تم حل الشكوى بنجاح');
    }

    public function close(Complaint $complaint)
    {
        $complaint->close();

        return back()->with('success', 'تم إغلاق الشكوى');
    }

    public function setPriority(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $complaint->setPriority($validated['priority']);

        return back()->with('success', 'تم تحديث أولوية الشكوى');
    }

    public function export(Request $request)
    {
        return back()->with('info', 'جاري التصدير...');
    }
}
