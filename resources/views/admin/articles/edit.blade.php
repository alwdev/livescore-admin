@extends('layouts.admin')

@section('title', 'แก้ไขบทความ')
@section('header', 'แก้ไขบทความ')
@section('description', 'อัปเดตรายละเอียดบทความ และเผยแพร่เมื่อพร้อม')

@section('content')
    <div class="space-y-8">

        <!-- 🔙 Header Actions -->
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
                    บันทึกเป็นฉบับร่าง
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
                        เผยแพร่บทความ
                    </button>
                @else
                    <button type="button" id="publish-btn"
                        class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-2 rounded-lg transition shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        อัปเดตบทความ
                    </button>
                @endif
            </div>
        </div>

        <!-- 🧩 Main Content -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                {{-- Debug: Check variables in edit view --}}
                <script>
                    console.log('🔥 EDIT PAGE DEBUG:');
                    console.log('🔥 Article exists:', {{ isset($article) ? 'true' : 'false' }});
                    console.log('🔥 fixturesData exists:', {{ isset($fixturesData) ? 'true' : 'false' }});
                    @if (isset($fixturesData))
                        console.log('🔥 fixturesData count:', {{ count($fixturesData) }});
                    @endif
                </script>
                @php
                    // Ensure fixturesData is available for the form
                    if (!isset($fixturesData)) {
                        $fixturesData = \App\Models\Fixture::with(['homeTeam', 'awayTeam', 'league'])
                            ->where('match_date', '>=', now()->toDateString())
                            ->orderBy('match_date', 'asc')
                            ->limit(100)
                            ->get()
                            ->map(function ($fixture) {
                                return [
                                    'id' => $fixture->id,
                                    'home_team' => [
                                        'name_th' => $fixture->homeTeam->name_th ?? null,
                                        'name_en' => $fixture->homeTeam->name_en ?? null,
                                    ],
                                    'away_team' => [
                                        'name_th' => $fixture->awayTeam->name_th ?? null,
                                        'name_en' => $fixture->awayTeam->name_en ?? null,
                                    ],
                                    'league' => [
                                        'name_th' => $fixture->league->name_th ?? null,
                                        'name' => $fixture->league->name ?? null,
                                        'name_en' => $fixture->league->name_en ?? null,
                                    ],
                                    'match_date' => $fixture->match_date ? $fixture->match_date->format('Y-m-d') : null,
                                    'display_text' =>
                                        ($fixture->homeTeam->name_th ?? ($fixture->homeTeam->name_en ?? 'ทีมเหย้า')) .
                                        ' vs ' .
                                        ($fixture->awayTeam->name_th ?? ($fixture->awayTeam->name_en ?? 'ทีมเยือน')) .
                                        ' (' .
                                        ($fixture->match_date
                                            ? $fixture->match_date->format('d/m/Y')
                                            : 'ไม่ระบุวันที่') .
                                        ')' .
                                        ($fixture->league && ($fixture->league->name_th ?? $fixture->league->name)
                                            ? ' - ' . ($fixture->league->name_th ?? $fixture->league->name)
                                            : ''),
                                ];
                            });
                    }
                @endphp
                @include('admin.articles._form')
            </div>

            <div>
                @include('admin.articles._sidebar')
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
