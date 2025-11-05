<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        // Search by title or content
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('seo_title', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $articles = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $fixturesData = Fixture::with(['homeTeam', 'awayTeam', 'league'])
            ->where('match_date', '>=', now()->toDateString())
            ->orderBy('match_date', 'asc')
            ->limit(100)
            ->get()
            ->map(function ($fixture) {
                return [
                    'id' => $fixture->id,
                    'home_team' => [
                        'name_th' => $fixture->homeTeam->name_th ?? null,
                        'name_en' => $fixture->homeTeam->name_en ?? null,
                    ],
                    'away_team' => [
                        'name_th' => $fixture->awayTeam->name_th ?? null,
                        'name_en' => $fixture->awayTeam->name_en ?? null,
                    ],
                    'league' => [
                        'name_th' => $fixture->league->name_th ?? null,
                        'name' => $fixture->league->name ?? null,
                        'name_en' => $fixture->league->name_en ?? null,
                    ],
                    'match_date' => $fixture->match_date ? $fixture->match_date->format('Y-m-d') : null,
                    'display_text' => ($fixture->homeTeam->name_th ?? $fixture->homeTeam->name_en ?? 'ทีมเหย้า') .
                        ' vs ' .
                        ($fixture->awayTeam->name_th ?? $fixture->awayTeam->name_en ?? 'ทีมเยือน') .
                        ' (' . ($fixture->match_date ? $fixture->match_date->format('d/m/Y') : 'ไม่ระบุวันที่') . ')' .
                        ($fixture->league && ($fixture->league->name_th ?? $fixture->league->name) ? ' - ' . ($fixture->league->name_th ?? $fixture->league->name) : '')
                ];
            });

        return view('admin.articles.create', compact('fixturesData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|in:sports_news,match_analysis,football_tips',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'fixture_id' => 'nullable|exists:fixtures,id',
        ]);

        // สร้าง slug
        // $data['slug'] = Str::slug($data['title']);
        $data['created_by'] = Auth::id();

        // ✅ Upload thumbnail ถ้ามี
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('uploads/articles', 'public');
            $data['thumbnail'] = asset('storage/' . $path);
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        // Debug: Log fixture query
        \Log::info('Edit method called for article ID: ' . $article->id);

        $fixtures = Fixture::with(['homeTeam', 'awayTeam', 'league'])
            ->where('match_date', '>=', now()->toDateString())
            ->orderBy('match_date', 'asc')
            ->limit(100)
            ->get();

        \Log::info('Found fixtures count: ' . $fixtures->count());

        $fixturesData = $fixtures->map(function ($fixture) {
            return [
                'id' => $fixture->id,
                'home_team' => [
                    'name_th' => $fixture->homeTeam->name_th ?? null,
                    'name_en' => $fixture->homeTeam->name_en ?? null,
                ],
                'away_team' => [
                    'name_th' => $fixture->awayTeam->name_th ?? null,
                    'name_en' => $fixture->awayTeam->name_en ?? null,
                ],
                'league' => [
                    'name_th' => $fixture->league->name_th ?? null,
                    'name' => $fixture->league->name ?? null,
                    'name_en' => $fixture->league->name_en ?? null,
                ],
                'match_date' => $fixture->match_date ? $fixture->match_date->format('Y-m-d') : null,
                'display_text' => ($fixture->homeTeam->name_th ?? $fixture->homeTeam->name_en ?? 'ทีมเหย้า') .
                    ' vs ' .
                    ($fixture->awayTeam->name_th ?? $fixture->awayTeam->name_en ?? 'ทีมเยือน') .
                    ' (' . ($fixture->match_date ? $fixture->match_date->format('d/m/Y') : 'ไม่ระบุวันที่') . ')' .
                    ($fixture->league && ($fixture->league->name_th ?? $fixture->league->name) ? ' - ' . ($fixture->league->name_th ?? $fixture->league->name) : '')
            ];
        });

        return view('admin.articles.edit', compact('article', 'fixturesData'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|in:sports_news,match_analysis,football_tips',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'fixture_id' => 'nullable|exists:fixtures,id',
        ]);

        // $data['slug'] = Str::slug($data['title']);

        // ✅ อัปโหลดรูปใหม่ถ้ามี
        if ($request->hasFile('thumbnail')) {
            // ลบรูปเก่าถ้ามี
            if ($article->thumbnail && str_contains($article->thumbnail, '/storage/')) {
                $oldPath = str_replace(asset('storage') . '/', '', $article->thumbnail);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('thumbnail')->store('uploads/articles', 'public');
            $data['thumbnail'] = asset('storage/' . $path);
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        // ลบรูปเก่าด้วย
        if ($article->thumbnail && str_contains($article->thumbnail, '/storage/')) {
            $oldPath = str_replace(asset('storage') . '/', '', $article->thumbnail);
            Storage::disk('public')->delete($oldPath);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }

    /**
     * ✅ ใช้สำหรับ TinyMCE image upload (response JSON)
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if (!in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return response()->json(['error' => 'Invalid image type.'], 422);
            }

            $path = $file->store('uploads/articles', 'public');
            $url = asset('storage/' . $path);

            return response()->json(['location' => $url]); // ✅ TinyMCE ต้องใช้ key "location"
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    /**
     * ✅ API สำหรับดึงข้อมูล fixtures
     */
    public function getFixtures(Request $request)
    {
        try {
            Log::info('getFixtures called with search: ' . $request->get('search', 'none'));

            $query = Fixture::with(['homeTeam', 'awayTeam', 'league'])
                ->where('match_date', '>=', now()->toDateString());

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($mainQuery) use ($search) {
                    $mainQuery->whereHas('homeTeam', function ($q) use ($search) {
                        $q->where('name_en', 'LIKE', "%{$search}%")
                            ->orWhere('name_th', 'LIKE', "%{$search}%");
                    })->orWhereHas('awayTeam', function ($q) use ($search) {
                        $q->where('name_en', 'LIKE', "%{$search}%")
                            ->orWhere('name_th', 'LIKE', "%{$search}%");
                    })->orWhereHas('league', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('name_th', 'LIKE', "%{$search}%");
                    });
                });
            }

            $fixtures = $query->orderBy('match_date', 'asc')
                ->limit(50)
                ->get();

            Log::info('Found fixtures count: ' . $fixtures->count());

            $mappedFixtures = $fixtures->map(function ($fixture) {
                return [
                    'id' => $fixture->id,
                    'home_team' => $fixture->homeTeam->name_th ?? ($fixture->homeTeam->name_en ?? 'ทีมเหย้า'),
                    'away_team' => $fixture->awayTeam->name_th ?? ($fixture->awayTeam->name_en ?? 'ทีมเยือน'),
                    'match_date' => $fixture->match_date ? $fixture->match_date->format('d/m/Y') : 'ไม่ระบุวันที่',
                    'league' => $fixture->league ? ($fixture->league->name_th ?? ($fixture->league->name ?? 'ไม่ระบุลีก')) : 'ไม่ระบุลีก',
                    'display_text' => ($fixture->homeTeam->name_th ?? ($fixture->homeTeam->name_en ?? 'ทีมเหย้า')) .
                        ' vs ' .
                        ($fixture->awayTeam->name_th ?? ($fixture->awayTeam->name_en ?? 'ทีมเยือน')) .
                        ' (' . ($fixture->match_date ? $fixture->match_date->format('d/m/Y') : 'ไม่ระบุวันที่') . ')' .
                        ($fixture->league && ($fixture->league->name_th ?? $fixture->league->name) ? ' - ' . ($fixture->league->name_th ?? $fixture->league->name) : '')
                ];
            });

            return response()->json($mappedFixtures);
        } catch (\Exception $e) {
            Log::error('getFixtures error: ' . $e->getMessage());
            return response()->json(['error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Debug method to test fixtures API
     */
    public function testFixtures()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Test API working',
            'fixtures_count' => Fixture::count(),
            'today' => now()->toDateString()
        ]);
    }
}
