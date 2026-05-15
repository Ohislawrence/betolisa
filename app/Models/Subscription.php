<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'starts_at',
        'ends_at',
        'status',
        'is_active',
        'transaction_ref',
        'amount_paid',
        'payment_method',
        'payment_details',
        'admin_notes',
        'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'payment_details' => 'json',
        'amount_paid' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('status', 'active')
                    ->where('ends_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->is_active
            && $this->status === 'active'
            && $this->ends_at > now();
    }

    public function daysRemaining(): int
    {
        if (!$this->isActive()) {
            return 0;
        }
        return max(0, (int) now()->diffInDays($this->ends_at, false));
    }
}
