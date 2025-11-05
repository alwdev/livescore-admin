@extends('layouts.admin')

@section('title', 'จัดการทีม')
@section('header', 'จัดการทีม')
@section('description', 'จัดการข้อมูลทีมฟุตบอล')

@section('content')
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">รายการทีม</h1>
            <div class="text-sm text-gray-600">
                ทั้งหมด {{ $teams->total() }} รายการ
            </div>
        </div>

        {{-- Alert --}}
        <div id="alert-message" class="hidden mb-4">
            <div class="bg-green-50 border border-green-300 text-green-800 p-3 rounded-lg">
                <span id="alert-text">✅ อัปเดตข้อมูลเรียบร้อยแล้ว</span>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.teams.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Search Box -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาทีม (ชื่อภาษาอังกฤษ, ภาษาไทย, UUID)..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Search Button -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        🔍 ค้นหา
                    </button>
                    <a href="{{ route('admin.teams.index') }}"
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
                        <th class="px-4 py-3 font-semibold">โลโก้</th>
                        <th class="px-4 py-3 font-semibold">ชื่อทีม (EN)</th>
                        <th class="px-4 py-3 font-semibold">ชื่อภาษาไทย</th>
                        <th class="px-4 py-3 font-semibold">UUID</th>
                        <th class="px-4 py-3 font-semibold text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($teams as $team)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                @if ($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name_en }}"
                                        class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">👕</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $team->name_en }}</td>
                            <td class="px-4 py-3">{{ $team->name_th ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $team->uuid ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <button onclick="openEditModal({{ $team->id }})"
                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    แก้ไข
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>ไม่พบข้อมูลทีม</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($teams->hasPages())
            <div class="mt-6">
                {{ $teams->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">แก้ไขข้อมูลทีม</h2>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        {{-- Logo Preview --}}
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img id="logoPreview" src="" alt="Logo"
                                    class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                <label for="logo"
                                    class="absolute -bottom-1 -right-1 bg-indigo-600 text-white rounded-full p-1 cursor-pointer hover:bg-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>
                            <input type="file" id="logo" name="logo" accept="image/*" class="hidden">
                            <p class="text-xs text-gray-500 mt-1">คลิกเพื่อเปลี่ยนโลโก้</p>
                        </div>

                        {{-- Name EN --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อทีม (ภาษาอังกฤษ) <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name_en" name="name_en" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Name TH --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อทีม (ภาษาไทย)</label>
                            <input type="text" id="name_th" name="name_th"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- UUID (Read-only) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">UUID</label>
                            <input type="text" id="uuid" name="uuid" readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                            <p class="text-xs text-gray-500 mt-1">UUID ไม่สามารถแก้ไขได้</p>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeEditModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            ยกเลิก
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentTeamId = null;
        const teams = @json($teams->items());

        function openEditModal(teamId) {
            currentTeamId = teamId;
            const team = teams.find(t => t.id === teamId);

            if (team) {
                // Fill form fields
                document.getElementById('name_en').value = team.name_en || '';
                document.getElementById('name_th').value = team.name_th || '';
                document.getElementById('uuid').value = team.uuid || '';

                // Set logo preview
                const logoPreview = document.getElementById('logoPreview');
                if (team.logo) {
                    logoPreview.src = `/storage/${team.logo}`;
                } else {
                    logoPreview.src =
                        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjY0IiBoZWlnaHQ9IjY0IiByeD0iMzIiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB4PSIyMCIgeT0iMjAiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM5Q0EzQUYiIHN0cm9rZS13aWR0aD0iMiI+CjxjaXJjbGUgY3g9IjEyIiBjeT0iMTIiIHI9IjEwIi8+Cjwvc3ZnPgo8L3N2Zz4K';
                }

                // Show modal
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
            currentTeamId = null;
        }

        // Handle logo preview
        document.getElementById('logo').addEventListener('change', function(e) {
            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentTeamId) return;

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');

            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'กำลังบันทึก...';

            fetch(`/admin/teams/${currentTeamId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alertMessage = document.getElementById('alert-message');
                        const alertText = document.getElementById('alert-text');
                        alertText.textContent = '✅ ' + data.message;
                        alertMessage.classList.remove('hidden');

                        // Close modal
                        closeEditModal();

                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitButton.disabled = false;
                    submitButton.textContent = 'บันทึก';
                });
        });

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
@endpush
