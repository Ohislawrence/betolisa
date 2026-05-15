<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = League::latest()->paginate(10);
        return view('admin.leagues.index', compact('leagues'));
    }

    public function create()
    {
        return view('admin.leagues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        League::create($validated);

        return redirect()->route('admin.leagues.index')
            ->with('success', 'League created successfully.');
    }

    public function edit(League $league)
    {
        return view('admin.leagues.edit', compact('league'));
    }

    public function update(Request $request, League $league)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $league->update($validated);

        return redirect()->route('admin.leagues.index')
            ->with('success', 'League updated successfully.');
    }

    public function destroy(League $league)
    {
        $league->delete();

        return redirect()->route('admin.leagues.index')
            ->with('success', 'League deleted successfully.');
    }

    public function toggleStatus(League $league)
    {
        $league->update([
            'is_active' => !$league->is_active
        ]);

        $status = $league->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.leagues.index')
            ->with('success', "League {$status} successfully.");
    }
}
