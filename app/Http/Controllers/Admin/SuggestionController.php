<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Suggestion::with('machine');

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

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $suggestions = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function create()
    {
        return view('admin.suggestions.create');
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
            'machine_id' => 'nullable|exists:machines,id',
        ]);

        $suggestion = Suggestion::create($validated);

        if ($request->hasFile('attachment')) {
            $suggestion->addMediaFromRequest('attachment')->toMediaCollection('attachments');
        }

        return redirect()->route('admin.suggestions.index')
            ->with('success', 'تم إضافة الاقتراح بنجاح');
    }

    public function show(Suggestion $suggestion)
    {
        $suggestion->load('machine');
        
        return view('admin.suggestions.show', compact('suggestion'));
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewed,addressed',
        ]);

        $suggestion->update($validated);

        return redirect()->route('admin.suggestions.index')
            ->with('success', 'تم تحديث الاقتراح بنجاح');
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();

        return redirect()->route('admin.suggestions.index')
            ->with('success', 'تم حذف الاقتراح بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:suggestions,id',
        ]);

        Suggestion::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.suggestions.index')
            ->with('success', 'تم حذف الاقتراحات المحددة بنجاح');
    }

    public function review(Suggestion $suggestion)
    {
        $suggestion->markAsReviewed();

        return back()->with('success', 'تم تحديث حالة الاقتراح');
    }

    public function address(Suggestion $suggestion)
    {
        $suggestion->markAsAddressed();

        return back()->with('success', 'تم معالجة الاقتراح');
    }

    public function export(Request $request)
    {
        return back()->with('info', 'جاري التصدير...');
    }
}
