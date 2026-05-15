<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'league_id',
        'home_team',
        'away_team',
        'tip_content',
        'odds',
        'type',
        'status',
        'match_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'odds' => 'decimal:2',
        'match_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('type', 'free');
    }

    public function scopePremium($query)
    {
        return $query->where('type', 'premium');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
