<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'position',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}
