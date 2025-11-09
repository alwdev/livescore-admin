<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\LeagueRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LeagueRankingController extends Controller
{
    public function index(Request $request)
    {
        $rankings = LeagueRanking::with('league')->orderBy('position')->get();
        $selectedLeagueIds = $rankings->pluck('league_id')->all();

        $search = $request->get('search');
        $availableLeagues = League::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('name_th', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('admin.league_rankings.index', compact('rankings', 'availableLeagues', 'selectedLeagueIds', 'search'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'league_ids' => 'array|max:12',
            'league_ids.*' => 'integer|distinct|exists:leagues,id',
        ]);

        $leagueIds = array_values($data['league_ids'] ?? []); // now up to 12

        DB::transaction(function () use ($leagueIds) {
            // Clear existing rankings using DELETE (TRUNCATE causes implicit commit and breaks transactions)
            LeagueRanking::query()->delete();

            // Insert in order
            foreach ($leagueIds as $index => $leagueId) {
                LeagueRanking::create([
                    'league_id' => $leagueId,
                    'position' => $index + 1,
                    'published' => true,
                ]);
            }
        });

        // Invalidate cache for API
        Cache::forget('top_leagues');

        return redirect()->route('admin.league-rankings.index')->with('success', 'อัปเดตอันดับลีกเรียบร้อยแล้ว');
    }

    // Public JSON API for frontend consumption
    public function apiTopLeagues()
    {
        $data = Cache::remember('top_leagues', 600, function () {
            return LeagueRanking::with('league')
                ->orderBy('position')
                ->take(12)
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->league->id,
                        'name' => $r->league->name_th ?? ($r->league->name ?? $r->league->name_en),
                        'name_th' => $r->league->name_th,
                        'name_en' => $r->league->name_en,
                        'country' => $r->league->country,
                        'logo' => $r->league->logo ? asset('storage/' . $r->league->logo) : null,
                        'position' => $r->position,
                    ];
                });
        });

        return response()->json($data);
    }
}
