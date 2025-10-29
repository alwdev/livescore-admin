<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use App\Models\SiteSetting;

class DashboardController extends Controller
{
    public function index()
    {
        $articlesCount = Article::count();
        $activeUsers = User::where('status', 1)->count();
        $settings = SiteSetting::first();
        $recentArticles = Article::latest()->take(5)->get();

        return view('admin.dashboard', [
            'articlesCount' => $articlesCount,
            'activeUsers' => $activeUsers,
            'settingsConfigured' => $settings ? true : false,
            'seoReady' => $settings && $settings->seo_title && $settings->seo_description,
            'recentArticles' => $recentArticles,
        ]);
    }
}
