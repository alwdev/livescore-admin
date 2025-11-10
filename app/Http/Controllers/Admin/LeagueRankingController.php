<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\LeagueRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeagueRankingController extends Controller
{
    public function index(Request $request)
    {
        $rankings = LeagueRanking::with('league')->orderBy('position')->get();
        $selectedLeagueIds = $rankings->pluck('league_id')->all();

        $search = $request->get('search');
        $availableLeagues = League::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_th', 'like', "%{$search}%");
            })
            ->orderBy('name_en')
            ->limit(50)
            ->get();

        return view('admin.league_rankings.index', compact('rankings', 'availableLeagues', 'selectedLeagueIds', 'search'));
    }

    public function update(Request $request)
    {
        // Debug: ดูข้อมูลที่ส่งมา
        Log::info('Raw request data:', $request->all());

        // กรองข้อมูลก่อน validate
        $leagueIds = $request->input('league_ids', []);
        $leagueIds = array_filter($leagueIds, function ($value) {
            return !is_null($value) && $value !== '' && is_numeric($value);
        });
        $leagueIds = array_values($leagueIds); // reindex

        Log::info('Filtered league IDs:', $leagueIds);
        Log::info('Count: ' . count($leagueIds)); // แก้ไขตรงนี้

        // Validate หลังจากกรองแล้ว
        $data = $request->merge(['league_ids' => $leagueIds])->validate([
            'league_ids' => 'required|array|min:1|max:12',
            'league_ids.*' => 'integer|exists:leagues,id',
        ]);

        if (empty($data['league_ids'])) {
            return redirect()->route('admin.league-rankings.index')->with('error', 'กรุณาเลือกลีกอย่างน้อย 1 ลีก');
        }

        DB::transaction(function () use ($data) {
            // Clear existing rankings
            LeagueRanking::query()->delete();

            // Insert in order
            foreach ($data['league_ids'] as $index => $leagueId) {
                LeagueRanking::create([
                    'league_id' => $leagueId,
                    'position' => $index + 1,
                    'published' => true,
                ]);
            }
        });

        // Invalidate cache for API
        Cache::forget('top_leagues');

        return redirect()->route('admin.league-rankings.index')->with('success', "อัปเดตอันดับลีกเรียบร้อยแล้ว (" . count($data['league_ids']) . " ลีก)");
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
