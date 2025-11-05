@extends('layouts.admin')

@section('title', 'บทความ')
@section('header', 'จัดการบทความ')

@section('content')
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">บทความ</h1>
            <a href="{{ route('admin.articles.create') }}"
                class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition">
                + สร้างบทความ
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 p-3 rounded-lg mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Search Box -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาบทความ (ชื่อ, เนื้อหา)..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Type Filter -->
                <div class="md:w-48">
                    <select name="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">ทุกประเภท</option>
                        <option value="sports_news" {{ request('type') == 'sports_news' ? 'selected' : '' }}>📰 ข่าวกีฬา
                        </option>
                        <option value="match_analysis" {{ request('type') == 'match_analysis' ? 'selected' : '' }}>⚽
                            วิเคราะห์ผลบอล</option>
                        <option value="football_tips" {{ request('type') == 'football_tips' ? 'selected' : '' }}>🎯
                            ทีเด็ดบอล</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="md:w-32">
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">ทุกสถานะ</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>เผยแพร่</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>ฉบับร่าง</option>
                    </select>
                </div>

                <!-- Search Button -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        🔍 ค้นหา
                    </button>
                    <a href="{{ route('admin.articles.index') }}"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                        ล้าง
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50 text-gray-700 text-sm uppercase tracking-wide">
                        <th class="px-4 py-3 font-semibold">หัวข้อ</th>
                        <th class="px-4 py-3 font-semibold">ประเภท</th>
                        <th class="px-4 py-3 font-semibold">สถานะ</th>
                        <th class="px-4 py-3 font-semibold">วันที่สร้าง</th>
                        <th class="px-4 py-3 font-semibold text-right">การทำงาน</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium">{{ $article->title }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                    {{ $article->type_icon }}
                                    {{ $article->type_name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($article->status === 'published')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">เผยแพร่</span>
                                @else
                                    <span
                                        class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full font-semibold">ฉบับร่าง</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $article->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="inline-block text-indigo-600 hover:text-indigo-800 font-medium transition">แก้ไข</a>

                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('ยืนยันลบบทความนี้หรือไม่?')"
                                        class="inline-block text-red-600 hover:text-red-800 font-medium transition">
                                        ลบ
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">ไม่พบบทความ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $articles->links('pagination::tailwind') }}
        </div>

    </div>
@endsection
