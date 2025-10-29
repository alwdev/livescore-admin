<form id="article-form" method="POST"
    action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    enctype="multipart/form-data">

    @csrf
    @if (isset($article))
        @method('PUT')
    @endif

    <input type="hidden" name="status" value="{{ old('status', $article->status ?? 'draft') }}">

    <!-- 📝 ARTICLE CONTENT -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Article Content
            </h2>
        </div>

        <div class="p-6 space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span
                        class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter article title...">
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Content <span
                        class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="12" class="w-full border border-gray-300 rounded-lg">{{ old('content', $article->content ?? '') }}</textarea>
                <div class="text-xs text-gray-400 mt-1" id="char-count">
                    {{ strlen(old('content', $article->content ?? '')) }} characters
                </div>
            </div>
        </div>
    </div>

    <!-- 🖼️ FEATURED IMAGE -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-3">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Featured Image
            </h2>
        </div>

        <div class="p-6 space-y-4">
            <input id="thumbnail-input" type="file" name="thumbnail" accept="image/*"
                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

            @php
                $thumbnailUrl =
                    isset($article) && $article->thumbnail
                        ? (Str::startsWith($article->thumbnail, 'http')
                            ? $article->thumbnail
                            : asset('storage/' . $article->thumbnail))
                        : 'https://placehold.co/600x400?text=Preview';
            @endphp

            <div id="thumbnail-preview-wrapper"
                class="mt-3 border border-gray-200 rounded-lg bg-gray-50 p-3 flex items-center justify-center aspect-video overflow-hidden relative max-w-md">
                <img id="thumbnail-preview" src="{{ $thumbnailUrl }}"
                    class="max-h-64 object-contain rounded-lg transition-all duration-300" alt="Preview">
                <button type="button" id="remove-thumbnail"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded hidden">
                    Remove
                </button>
            </div>
        </div>
    </div>

    <!-- 🔍 SEO -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-3">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                SEO Optimization
            </h2>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $article->seo_title ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                <textarea name="seo_description" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none">{{ old('seo_description', $article->seo_description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords</label>
                <input type="text" name="seo_keywords"
                    value="{{ old('seo_keywords', $article->seo_keywords ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/u9vz8kusq5hg61gv179c7mko5rebti16fqxk4y9pfaek9uw6/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Init TinyMCE
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: false,
                plugins: 'code anchor autolink charmap codesample emoticons image link lists media table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table codesample | code removeformat',
                branding: false,
                content_style: "body { font-family: 'Inter', sans-serif; font-size: 15px; color: #333; line-height: 1.6; }",
                placeholder: "Write your article content...",

                /* ✅ ปิด relative URLs */
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,

                /* ✅ การอัปโหลดภาพ */
                images_upload_url: '{{ route('admin.articles.upload') }}',
                automatic_uploads: true,
                file_picker_types: 'image',
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function() {
                        var file = this.files[0];
                        var formData = new FormData();
                        formData.append('file', file);
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('admin.articles.upload') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(result => {
                                cb(result.location, {
                                    title: file.name
                                });
                            })
                            .catch(() => alert('Image upload failed.'));
                    };

                    input.click();
                },
            });


            // ✅ Thumbnail Preview
            const fileInput = document.getElementById('thumbnail-input');
            const previewImg = document.getElementById('thumbnail-preview');
            const removeBtn = document.getElementById('remove-thumbnail');

            fileInput?.addEventListener('change', e => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        previewImg.src = ev.target.result;
                        removeBtn.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn?.addEventListener('click', () => {
                previewImg.src = 'https://placehold.co/600x400?text=Preview';
                fileInput.value = '';
                removeBtn.classList.add('hidden');
            });
        });
    </script>
@endpush
