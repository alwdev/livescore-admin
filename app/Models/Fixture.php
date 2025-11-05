<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'match_date',
        'match_time',
        'league_id',
        'home_team_id',
        'away_team_id',
        'home_score',
        'away_score',
        'status_id',
        'venue_id',
        'referee_id',
        'neutral',
        'note',
        'home_scores',
        'away_scores',
        'home_position',
        'away_position',
        'round',
        'environment',
        'coverage',
        'ended_at',
        'season_id'
    ];

    protected $casts = [
        'match_date' => 'date',
        'home_scores' => 'array',
        'away_scores' => 'array',
        'round' => 'array',
        'environment' => 'array',
        'coverage' => 'array',
        'ended_at' => 'datetime',
        'neutral' => 'boolean',
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
