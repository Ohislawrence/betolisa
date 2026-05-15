<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'country',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tips()
    {
        return $this->hasMany(Tip::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
