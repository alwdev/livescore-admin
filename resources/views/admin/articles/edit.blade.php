@extends('layouts.admin')

@section('title', 'Edit Article')
@section('header', 'Edit Article')
@section('description', 'Update your article details and publish when ready')

@section('content')
    <div class="space-y-8">

        <!-- 🔙 Header Actions -->
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('admin.articles.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Articles
            </a>

            <div class="flex gap-3">
                <!-- Save Draft -->
                <button type="button" id="save-draft-btn"
                    class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Save as Draft
                </button>

                <!-- Publish / Update -->
                @if ($article->status === 'draft')
                    <button type="button" id="publish-btn"
                        class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg transition shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Publish Article
                    </button>
                @else
                    <button type="button" id="publish-btn"
                        class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-2 rounded-lg transition shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Article
                    </button>
                @endif
            </div>
        </div>

        <!-- 🧩 Main Content -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @include('admin.articles._form', ['article' => $article])
            </div>

            <div>
                @include('admin.articles._sidebar', ['article' => $article])
            </div>
        </div>
    </div>

    <!-- ✅ Scripts -->
    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/u9vz8kusq5hg61gv179c7mko5rebti16fqxk4y9pfaek9uw6/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ✅ ฟังก์ชันสำหรับส่งฟอร์ม
                function submitForm(status) {
                    tinymce.triggerSave(); // sync content กลับไป textarea
                    const form = document.getElementById('article-form');
                    const statusInput = form.querySelector('input[name="status"]');
                    statusInput.value = status;
                    form.submit();
                }

                document.getElementById('save-draft-btn')
                    ?.addEventListener('click', () => submitForm('draft'));

                document.getElementById('publish-btn')
                    ?.addEventListener('click', () => submitForm('published'));
            });
        </script>
    @endpush
@endsection
