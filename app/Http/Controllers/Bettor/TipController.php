<?php

namespace App\Http\Controllers\Bettor;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use App\Models\League;
use Illuminate\Http\Request;

class TipController extends Controller
{
    /**
     * Display free tips
     */
    public function freeTips(Request $request)
    {
        $query = Tip::with(['league', 'creator'])
            ->where('type', 'free')
            ->active()
            ->latest();

        // Apply filters
        if ($request->filled('league_id')) {
            $query->where('league_id', $request->league_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('home_team', 'like', "%{$search}%")
                  ->orWhere('away_team', 'like', "%{$search}%")
                  ->orWhere('tip_content', 'like', "%{$search}%");
            });
        }

        $tips = $query->paginate(15);
        $leagues = League::active()->get();

        return view('bettor.tips.free', compact('tips', 'leagues'));
    }

    /**
     * Display premium tips (requires subscription)
     */
    public function premiumTips(Request $request)
    {
        $user = auth()->user();

        // Check if user has active subscription or is admin
        if (!$user->hasActiveSubscription() && !$user->hasRole('admin')) {
            return redirect()->route('bettor.plans')
                ->with('error', 'Subscribe to access premium tips.');
        }

        $query = Tip::with(['league', 'creator'])
            ->where('type', 'premium')
            ->active()
            ->latest();

        // Apply filters
        if ($request->filled('league_id')) {
            $query->where('league_id', $request->league_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('home_team', 'like', "%{$search}%")
                  ->orWhere('away_team', 'like', "%{$search}%")
                  ->orWhere('tip_content', 'like', "%{$search}%");
            });
        }

        $tips = $query->paginate(15);
        $leagues = League::active()->get();

        return view('bettor.tips.premium', compact('tips', 'leagues'));
    }

    /**
     * Show tip details
     */
    public function show(Tip $tip)
    {
        $user = auth()->user();

        // Check access for premium tips
        if ($tip->type === 'premium') {
            if (!$user->hasActiveSubscription() && !$user->hasRole('admin')) {
                return redirect()->route('bettor.plans')
                    ->with('error', 'Subscribe to view this premium tip.');
            }
        }

        $tip->load(['league', 'creator']);

        // Get related tips
        $relatedTips = Tip::where('league_id', $tip->league_id)
            ->where('id', '!=', $tip->id)
            ->active()
            ->latest()
            ->take(5)
            ->get();

        return view('bettor.tips.show', compact('tip', 'relatedTips'));
    }
}
