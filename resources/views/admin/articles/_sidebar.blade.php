<!-- _sidebar.blade.php -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Publication Settings
        </h3>
    </div>

    <div class="p-6 space-y-4">
        <!-- Status -->
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">Status</span>
            <span
                class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ ($article->status ?? 'draft') === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ ucfirst($article->status ?? 'draft') }}
            </span>
        </div>

        <!-- Author -->
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">Author</span>
            <span class="text-sm text-gray-600">{{ $article->author->name ?? auth()->user()->name }}</span>
        </div>

        <!-- Created Date -->
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">Created</span>
            <span class="text-sm text-gray-600">
                {{ isset($article->created_at) ? $article->created_at->format('M d, Y') : now()->format('M d, Y') }}
            </span>
        </div>
    </div>
</div>

<!-- ✨ Writing Tips -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-3">
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            Writing Tips
        </h3>
    </div>

    <div class="p-6">
        <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start gap-2">
                <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-2 flex-shrink-0"></div>
                Use clear, engaging headlines that capture attention.
            </li>
            <li class="flex items-start gap-2">
                <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-2 flex-shrink-0"></div>
                Keep paragraphs short and easy to read.
            </li>
            <li class="flex items-start gap-2">
                <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-2 flex-shrink-0"></div>
                Include keywords naturally for better SEO.
            </li>
            <li class="flex items-start gap-2">
                <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full mt-2 flex-shrink-0"></div>
                Optimize meta titles and descriptions for visibility.
            </li>
        </ul>
    </div>
</div>
