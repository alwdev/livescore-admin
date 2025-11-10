<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\LeagueRanking;
use App\Models\League;
use Illuminate\Support\Facades\DB;

echo "=== CURRENT LEAGUE RANKINGS STATUS ===\n";
echo "Total rankings: " . LeagueRanking::count() . "\n";
echo "Max position: " . LeagueRanking::max('position') . "\n\n";

echo "Current rankings (ordered by position):\n";
$rankings = LeagueRanking::with('league')->orderBy('position')->get();
foreach ($rankings as $ranking) {
    echo "Position {$ranking->position}: {$ranking->league->name} (ID: {$ranking->league_id})\n";
}

echo "\n=== VERIFICATION ===\n";
if (LeagueRanking::where('position', 12)->exists()) {
    echo "✅ Position 12 exists!\n";
} else {
    echo "❌ Position 12 not found\n";
}

echo "Total available leagues: " . League::count() . "\n";
echo "Used league IDs: " . LeagueRanking::count() . " out of " . League::count() . "\n";
