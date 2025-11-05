<form id="article-form" method="POST"
    action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    enctype="multipart/form-data">

    @csrf
    @if (isset($article))
        @method('PUT')
    @endif

    <input type="hidden" name="status" value="{{ old('status', $article->status ?? 'draft') }}">

    <!-- 📝 เนื้อหาบทความ -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                เนื้อหาบทความ
            </h2>
        </div>

        <div class="p-6 space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อบทความ <span
                        class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="กรอกหัวข้อบทความ...">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">ลิงก์ (Slug)<span
                        class="text-red-500">*</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="กรอกลิงก์บทความ...">
            </div>

            <!-- ประเภทบทความ -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">ประเภทบทความ <span
                        class="text-red-500">*</span></label>
                <select id="type" name="type"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <option value="">เลือกประเภทบทความ...</option>
                    <option value="sports_news"
                        {{ old('type', $article->type ?? '') == 'sports_news' ? 'selected' : '' }}>
                        📰 ข่าวกีฬา
                    </option>
                    <option value="match_analysis"
                        {{ old('type', $article->type ?? '') == 'match_analysis' ? 'selected' : '' }}>
                        ⚽ วิเคราะห์ผลบอล
                    </option>
                    <option value="football_tips"
                        {{ old('type', $article->type ?? '') == 'football_tips' ? 'selected' : '' }}>
                        🎯 ทีเด็ดบอล
                    </option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- เลือกแมตช์ (แสดงเฉพาะเมื่อเลือก วิเคราะห์ผลบอล) -->
            <div id="match-selector"
                class="transition-all duration-300 {{ old('type', $article->type ?? '') == 'match_analysis' ? 'block' : 'hidden' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">เลือกแมตช์ที่ต้องการวิเคราะห์</label>

                <!-- Search input for fixtures -->
                <div class="relative mb-2">
                    <input type="text" id="fixture-search" placeholder="ค้นหาแมตช์ (ชื่อทีม, ลีก)..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                        🔍
                    </div>
                </div>

                <select id="fixture_id" name="fixture_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <option value="">เลือกแมตช์...</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
                @error('fixture_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- เนื้อหา -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">เนื้อหา <span
                        class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="12" class="w-full border border-gray-300 rounded-lg">{{ old('content', $article->content ?? '') }}</textarea>
                <div class="text-xs text-gray-400 mt-1" id="char-count">
                    {{ strlen(old('content', $article->content ?? '')) }} ตัวอักษร
                </div>
            </div>
        </div>
    </div>

    <!-- 🖼️ รูปภาพหน้าปก -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-3">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                รูปภาพหน้าปก
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
                    ลบรูป
                </button>
            </div>
        </div>
    </div>

    <!-- 🔍 ปรับแต่ง SEO -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-3">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                ปรับแต่ง SEO
            </h2>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อ SEO</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $article->seo_title ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">คำอธิบาย (Meta Description)</label>
                <textarea name="seo_description" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none">{{ old('seo_description', $article->seo_description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">คีย์เวิร์ด (Keywords)</label>
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
                placeholder: "เขียนเนื้อหาบทความที่นี่...",

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

            // ✅ Auto-generate slug from title
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            titleInput?.addEventListener('input', function() {
                if (slugInput) {
                    const slug = this.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^\u0e00-\u0e7fa-z0-9\s-]/g, '') // รองรับภาษาไทย
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugInput.value = slug;
                }
            });

            // ✅ Show/Hide Match Selector based on article type
            const typeSelector = document.getElementById('type');
            const matchSelector = document.getElementById('match-selector');
            const fixtureSelect = document.getElementById('fixture_id');
            const fixtureSearchInput = document.getElementById('fixture-search');

            // Store all fixtures from server for client-side filtering (moved here for early access)
            const allFixtures = @json($fixturesData ?? []);
            console.log('🚀 Debug: All fixtures loaded:', allFixtures.length);
            console.log('🚀 Debug: Sample fixture data:', allFixtures[0]);
            console.log('🚀 Debug: Current page URL:', window.location.href);

            // Debug: Check if we're in edit mode and if fixturesData exists
            console.log('🚀 Debug: $fixturesData variable exists:', typeof @json($fixturesData ?? null));
            @if (isset($fixturesData))
                console.log('🚀 Debug: PHP - $fixturesData is SET with', {{ count($fixturesData) }}, 'items');
            @else
                console.log('🚀 Debug: PHP - $fixturesData is NOT SET');
            @endif

            @isset($article)
                console.log('🚀 Debug: We are in EDIT mode for article ID:', '{{ $article->id ?? 'unknown' }}');
            @else
                console.log('🚀 Debug: We are in CREATE mode');
            @endisset

            // Store the initially selected fixture ID from server-side
            const initialFixtureId = '{{ old('fixture_id', $article->fixture_id ?? '') }}';
            console.log('🔥 Initial fixture ID from server:', initialFixtureId);

            // Define searchFixtures function before using it
            function searchFixtures(searchTerm) {
                console.log('🔍 searchFixtures called with:', searchTerm);
                console.log('🔍 allFixtures available:', allFixtures.length);

                if (allFixtures.length === 0) {
                    console.error('🔍 No fixtures data available!');
                    fixtureSelect.innerHTML = '<option value="">ไม่มีข้อมูลแมตช์</option>';
                    return;
                }

                // Remember current selected value
                const currentValue = fixtureSelect.value;
                console.log('🔍 Current selected value:', currentValue);

                // Clear existing options
                fixtureSelect.innerHTML = '<option value="">เลือกแมตช์...</option>';

                // Filter fixtures based on search term
                const filteredFixtures = allFixtures.filter(fixture => {
                    if (!searchTerm) return true; // Show all if no search term

                    const searchLower = searchTerm.toLowerCase();
                    const homeTeam = (fixture.home_team?.name_th || fixture.home_team?.name_en || '')
                        .toLowerCase();
                    const awayTeam = (fixture.away_team?.name_th || fixture.away_team?.name_en || '')
                        .toLowerCase();
                    const league = (fixture.league?.name_th || fixture.league?.name || fixture.league
                        ?.name_en || '').toLowerCase();
                    return homeTeam.includes(searchLower) ||
                        awayTeam.includes(searchLower) ||
                        league.includes(searchLower);
                });

                console.log('Filtered fixtures:', filteredFixtures.length);

                // Add filtered options
                if (filteredFixtures.length > 0) {
                    filteredFixtures.forEach(fixture => {
                        const option = document.createElement('option');
                        option.value = fixture.id;

                        // Use actual team names from the fixture data with multiple fallback methods
                        const homeTeam = fixture.home_team?.name_th ||
                            fixture.home_team?.name_en ||
                            fixture.homeTeam?.name_th ||
                            fixture.homeTeam?.name_en ||
                            'ทีมเหย้า';
                        const awayTeam = fixture.away_team?.name_th ||
                            fixture.away_team?.name_en ||
                            fixture.awayTeam?.name_th ||
                            fixture.awayTeam?.name_en ||
                            'ทีมเยือน';
                        const matchDate = fixture.match_date ? new Date(fixture.match_date)
                            .toLocaleDateString('th-TH') : 'ไม่ระบุวันที่';
                        const league = fixture.league?.name_th ||
                            fixture.league?.name ||
                            fixture.league?.name_en ||
                            'ไม่ระบุลีก';

                        option.textContent = `${homeTeam} vs ${awayTeam} (${matchDate}) - ${league}`;

                        // Restore selection if this was previously selected
                        if (fixture.id == currentValue || fixture.id == initialFixtureId) {
                            option.selected = true;
                            console.log('🔍 Selected fixture restored:', fixture.id, homeTeam, 'vs',
                                awayTeam);
                        }

                        fixtureSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'ไม่พบแมตช์ที่ตรงกับคำค้นหา';
                    fixtureSelect.appendChild(option);
                }

                console.log('🔍 searchFixtures completed. Total options:', fixtureSelect.options.length);
                console.log('🔍 Current selected value after populate:', fixtureSelect.value);
            }

            typeSelector?.addEventListener('change', function() {
                if (this.value === 'match_analysis') {
                    matchSelector.classList.remove('hidden');
                    matchSelector.classList.add('block');
                    // Always load fixtures with JavaScript data for consistency
                    searchFixtures('');
                } else {
                    matchSelector.classList.add('hidden');
                    matchSelector.classList.remove('block');
                    // Clear the fixture selection when not match analysis
                    fixtureSelect.value = '';
                }
            });

            // Load initial fixtures if match_analysis is already selected
            if (typeSelector && typeSelector.value === 'match_analysis') {
                console.log('🔥 Initial load: Type is match_analysis, loading fixtures...');
                console.log('🔥 Initial load: allFixtures count:', allFixtures.length);
                console.log('🔥 Initial load: initialFixtureId:', initialFixtureId);

                // Show match selector immediately
                matchSelector.classList.remove('hidden');
                matchSelector.classList.add('block');

                // Always reload with JavaScript data to ensure consistency
                searchFixtures('');

                // Restore the initially selected fixture with a longer timeout
                if (initialFixtureId) {
                    setTimeout(() => {
                        console.log('🔥 Restoring fixture selection:', initialFixtureId);
                        fixtureSelect.value = initialFixtureId;
                        console.log('🔥 Fixture select value after restore:', fixtureSelect.value);
                    }, 500); // Increased timeout
                }
            }

            // ✅ Search fixtures functionality
            let searchTimeout;
            fixtureSearchInput?.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                console.log('Search term:', searchTerm);

                searchTimeout = setTimeout(() => {
                    if (searchTerm.length >= 2) {
                        console.log('Searching for:', searchTerm);
                        searchFixtures(searchTerm);
                    } else if (searchTerm.length === 0) {
                        console.log('Loading all fixtures');
                        // Load all fixtures when search is cleared
                        searchFixtures('');
                    }
                }, 300);
            });



            // Add a secondary check to ensure fixtures are loaded properly
            setTimeout(() => {
                if (typeSelector && typeSelector.value === 'match_analysis') {
                    if (fixtureSelect.options.length <= 1) {
                        console.log('🔥 Secondary check: Fixtures not loaded, retrying...');
                        searchFixtures('');

                        if (initialFixtureId) {
                            setTimeout(() => {
                                fixtureSelect.value = initialFixtureId;
                                console.log('🔥 Secondary restore fixture:', initialFixtureId);
                            }, 300);
                        }
                    } else {
                        console.log('🔥 Secondary check: Fixtures already loaded, count:', fixtureSelect
                            .options.length);
                    }
                }
            }, 1000);

            // ✅ Form validation
            const form = document.getElementById('article-form');
            form?.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const type = document.getElementById('type').value;
                const content = tinymce.get('content').getContent();

                if (!title) {
                    e.preventDefault();
                    alert('กรุณาใส่ชื่อบทความ');
                    document.getElementById('title').focus();
                    return;
                }

                if (!type) {
                    e.preventDefault();
                    alert('กรุณาเลือกประเภทข่าว');
                    document.getElementById('type').focus();
                    return;
                }

                if (!content.trim()) {
                    e.preventDefault();
                    alert('กรุณาใส่เนื้อหาบทความ');
                    tinymce.get('content').focus();
                    return;
                }
            });
        });

        // ✅ Global functions for form submission
        window.saveAsDraft = function() {
            const statusField = document.querySelector('input[name="status"]');
            if (statusField) {
                statusField.value = 'draft';
            }
            document.getElementById('article-form').submit();
        }

        window.publishArticle = function() {
            const statusField = document.querySelector('input[name="status"]');
            if (statusField) {
                statusField.value = 'published';
            }
            document.getElementById('article-form').submit();
        }
    </script>
@endpush
