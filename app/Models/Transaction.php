<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'reference',
        'amount',
        'currency',
        'status',
        'payment_channel',
        'metadata',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'json',
        'gateway_response' => 'json',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }
}
