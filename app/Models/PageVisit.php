<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_hash',
        'path',
        'user_agent',
        'referer',
    ];

    /**
     * Scope to check for existing visit within cooldown period (default 30 min).
     */
    public function scopeRecentForIp(Builder $query, string $ipHash, int $minutes = 30): Builder
    {
        return $query->where('ip_hash', $ipHash)
            ->where('created_at', '>=', now()->subMinutes($minutes));
    }
}
