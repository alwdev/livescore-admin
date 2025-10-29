<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        // สร้าง slug
        $data['slug'] = Str::slug($data['title']);
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
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $data['slug'] = Str::slug($data['title']);

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
}
