<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Tip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_scope_returns_only_active_free_tips(): void
    {
        $user = User::factory()->create();

        $league = League::create([
            'name' => 'Premier League',
            'country' => 'England',
            'is_active' => true,
        ]);

        Tip::create([
            'league_id' => $league->id,
            'home_team' => 'Liverpool',
            'away_team' => 'Manchester United',
            'tip_content' => 'Liverpool to win',
            'odds' => 1.85,
            'type' => 'free',
            'status' => 'pending',
            'match_date' => now(),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        Tip::create([
            'league_id' => $league->id,
            'home_team' => 'Chelsea',
            'away_team' => 'Arsenal',
            'tip_content' => 'Arsenal to score',
            'odds' => 2.10,
            'type' => 'premium',
            'status' => 'pending',
            'match_date' => now(),
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        Tip::create([
            'league_id' => $league->id,
            'home_team' => 'Everton',
            'away_team' => 'Leicester',
            'tip_content' => 'Draw',
            'odds' => 3.20,
            'type' => 'free',
            'status' => 'pending',
            'match_date' => now(),
            'is_active' => false,
            'created_by' => $user->id,
        ]);

        $freeTips = Tip::free()->active()->get();

        $this->assertCount(1, $freeTips);
        $this->assertEquals('Liverpool', $freeTips->first()->home_team);
    }
}
