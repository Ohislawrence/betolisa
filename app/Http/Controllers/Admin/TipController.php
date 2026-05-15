<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Models\League;
use Illuminate\Http\Request;

class TipController extends Controller
{
    public function index(Request $request)
    {
        $query = Tip::with(['league', 'creator'])->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by league
        if ($request->filled('league_id')) {
            $query->where('league_id', $request->league_id);
        }

        $tips = $query->paginate(15);
        $leagues = League::active()->get();

        return view('admin.tips.index', compact('tips', 'leagues'));
    }

    public function create()
    {
        $leagues = League::active()->get();
        return view('admin.tips.create', compact('leagues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'league_id' => 'required|exists:leagues,id',
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'tip_content' => 'required|string',
            'odds' => 'nullable|numeric|min:0',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:pending,won,lost,void',
            'match_date' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = true;

        Tip::create($validated);

        return redirect()->route('admin.tips.index')
            ->with('success', 'Tip created successfully.');
    }

    public function show(Tip $tip)
    {
        $tip->load(['league', 'creator']);
        return view('admin.tips.show', compact('tip'));
    }

    public function edit(Tip $tip)
    {
        $leagues = League::active()->get();
        return view('admin.tips.edit', compact('tip', 'leagues'));
    }

    public function update(Request $request, Tip $tip)
    {
        $validated = $request->validate([
            'league_id' => 'required|exists:leagues,id',
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'tip_content' => 'required|string',
            'odds' => 'nullable|numeric|min:0',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:pending,won,lost,void',
            'match_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $tip->update($validated);

        return redirect()->route('admin.tips.index')
            ->with('success', 'Tip updated successfully.');
    }

    public function destroy(Tip $tip)
    {
        $tip->delete();

        return redirect()->route('admin.tips.index')
            ->with('success', 'Tip deleted successfully.');
    }

    public function updateStatus(Request $request, Tip $tip)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,won,lost,void',
        ]);

        $tip->update($validated);

        return redirect()->back()
            ->with('success', 'Tip status updated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tips,id',
            'action' => 'required|in:delete,activate,deactivate',
        ]);

        $tips = Tip::whereIn('id', $validated['ids']);

        switch ($validated['action']) {
            case 'delete':
                $tips->delete();
                $message = 'Selected tips deleted successfully.';
                break;
            case 'activate':
                $tips->update(['is_active' => true]);
                $message = 'Selected tips activated successfully.';
                break;
            case 'deactivate':
                $tips->update(['is_active' => false]);
                $message = 'Selected tips deactivated successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}
