@extends('layouts.admin')

@section('title', 'Articles')
@section('header', 'Articles Management')

@section('content')
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Articles</h1>
            <a href="{{ route('admin.articles.create') }}"
                class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition">
                + New Article
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 p-3 rounded-lg mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50 text-gray-700 text-sm uppercase tracking-wide">
                        <th class="px-4 py-3 font-semibold">Title</th>
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Created</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
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
                                        class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">Published</span>
                                @else
                                    <span
                                        class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full font-semibold">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $article->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="inline-block text-indigo-600 hover:text-indigo-800 font-medium transition">Edit</a>

                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this article?')"
                                        class="inline-block text-red-600 hover:text-red-800 font-medium transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">No articles found.</td>
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
