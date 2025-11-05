@extends('layouts.admin')

@section('title', 'สร้างบทความ')
@section('header', 'สร้างบทความใหม่')
@section('description', 'เพิ่มบทความใหม่เข้าสู่คลังเนื้อหา')

@section('content')
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('admin.articles.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                กลับไปหน้าบทความ
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
                    บันทึกร่าง
                </button>

                <!-- Publish -->
                <button type="button" id="publish-btn"
                    class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg transition shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    เผยแพร่
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @include('admin.articles._form')
            </div>
            <div>
                @include('admin.articles._sidebar')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('article-form');
            const statusInput = form.querySelector('input[name="status"]');
            const saveDraftBtn = document.getElementById('save-draft-btn');
            const publishBtn = document.getElementById('publish-btn');

            // ✅ อัปเดต content จาก TinyMCE ก่อน submit
            function syncContent() {
                if (window.tinymce) {
                    const editor = tinymce.get('content');
                    if (editor) {
                        document.getElementById('content').value = editor.getContent();
                    }
                }
            }

            // ✅ ฟังก์ชัน Submit
            function submitForm(status) {
                syncContent();
                statusInput.value = status;
                form.submit();
            }

            // ✅ Event Listener
            saveDraftBtn.addEventListener('click', () => submitForm('draft'));
            publishBtn.addEventListener('click', () => submitForm('published'));
        });
    </script>
@endpush
