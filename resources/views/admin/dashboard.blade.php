@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')
@section('description', 'Quick summary of your website performance and admin activities')

@section('content')
    <div class="space-y-8">

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Articles -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 flex items-center justify-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Articles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $articlesCount ?? 0 }}</p>
                </div>
            </div>

            <!-- Users -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 flex items-center justify-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-1a4 4 0 00-5-4M12 12a5 5 0 100-10 5 5 0 000 10zM6 20H1v-1a4 4 0 015-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeUsers ?? 0 }}</p>
                </div>
            </div>

            <!-- Settings -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-green-100 text-green-600 flex items-center justify-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Site Settings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $settingsConfigured ? 'Configured' : 'Pending' }}</p>
                </div>
            </div>

            <!-- SEO -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 flex items-center justify-center rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">SEO Ready</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seoReady ? '✅ Yes' : '⚠️ No' }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Articles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c.832 0 1.5.668 1.5 1.5S12.832 11 12 11 10.5 10.332 10.5 9.5 11.168 8 12 8zm0 4c1.104 0 2 .896 2 2v2H10v-2c0-1.104.896-2 2-2z" />
                    </svg>
                    Recent Articles
                </h2>
            </div>
            <div class="p-6">
                @if ($recentArticles->count())
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentArticles as $article)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $article->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $article->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm">No recent articles yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
