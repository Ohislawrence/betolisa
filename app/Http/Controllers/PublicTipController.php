<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\League;
use Illuminate\Http\Request;

class PublicTipController extends Controller
{
    /**
     * Display all public free tips
     */
    public function index(Request $request)
    {
        $query = Tip::with(['league', 'creator'])
            ->where('type', 'free')
            ->active()
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('home_team', 'like', "%{$search}%")
                  ->orWhere('away_team', 'like', "%{$search}%")
                  ->orWhere('tip_content', 'like', "%{$search}%")
                  ->orWhereHas('league', function($leagueQuery) use ($search) {
                      $leagueQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by league
        if ($request->filled('league_id')) {
            $query->where('league_id', $request->league_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('match_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('match_date', '<=', $request->date_to);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'odds_high':
                $query->orderBy('odds', 'desc');
                break;
            case 'odds_low':
                $query->orderBy('odds', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $tips = $query->paginate(12)->withQueryString();
        $leagues = League::active()->orderBy('name')->get();

        // Statistics for the header
        $stats = [
            'total_tips' => Tip::where('type', 'free')->active()->count(),
            'won_tips' => Tip::where('type', 'free')->active()->where('status', 'won')->count(),
            'today_tips' => Tip::where('type', 'free')->active()->whereDate('created_at', today())->count(),
        ];

        return view('tips.index', compact('tips', 'leagues', 'stats'));
    }

    /**
     * Display a single tip detail
     */
    public function show(Tip $tip)
    {
        // Only show free tips publicly
        if ($tip->type !== 'free') {
            abort(404);
        }

        $tip->load(['league', 'creator']);

        // Get related tips from same league
        $relatedTips = Tip::with('league')
            ->where('league_id', $tip->league_id)
            ->where('id', '!=', $tip->id)
            ->where('type', 'free')
            ->active()
            ->latest()
            ->take(4)
            ->get();

        return view('tips.show', compact('tip', 'relatedTips'));
    }
}
