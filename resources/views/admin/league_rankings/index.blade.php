@extends('layouts.admin')

@section('title', 'จัดอันดับลีก (Top 12)')
@section('header', 'จัดอันดับลีก (Top 12)')
@section('description', 'เลือก 12 ลีกยอดนิยมเพื่อแสดงในหน้าเว็บไซต์ และจัดเรียงลำดับด้วยการลากวาง')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 rounded bg-green-50 text-green-700 border border-green-200">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="ค้นหาลีก..."
                class="flex-1 px-4 py-2 border rounded-lg" />
            <button class="px-4 py-2 rounded-lg bg-gray-100">ค้นหา</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border rounded-xl p-4">
                <h3 class="font-semibold mb-3">ลีกทั้งหมด</h3>
                <ul id="availableList" class="space-y-2 min-h-40">
                    @foreach ($availableLeagues as $league)
                        <li class="flex items-center justify-between gap-3 p-2 border rounded-lg"
                            data-id="{{ $league->id }}">
                            <div class="flex items-center gap-3">
                                @if ($league->logo)
                                    <img src="{{ $league->logo }}" class="w-8 h-8 object-contain" />
                                @else
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $league->name_th ?? ($league->name ?? $league->name_en) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $league->country }}</div>
                                </div>
                            </div>
                            <button type="button" class="add-btn px-3 py-1 text-sm rounded bg-indigo-600 text-white"
                                data-id="{{ $league->id }}"
                                {{ in_array($league->id, $selectedLeagueIds) ? 'disabled' : '' }}>เพิ่ม</button>
                        </li>
                    @endforeach
                </ul>
                <p class="text-xs text-gray-400 mt-2">แสดงสูงสุด 50 รายการ</p>
            </div>

            <div class="bg-white border rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">12 ลีกยอดนิยม (ลากเพื่อเรียง)</h3>
                    <span class="text-sm text-gray-500" id="selectedCount"></span>
                </div>
                <form id="rankingForm" method="POST" action="{{ route('admin.league-rankings.update') }}" class="mt-3">
                    @csrf
                    <ul id="selectedList" class="space-y-2 min-h-40">
                        @forelse($rankings as $r)
                            <li class="flex items-center justify-between gap-3 p-2 border rounded-lg cursor-move bg-white"
                                data-id="{{ $r->league->id }}">
                                <div class="flex items-center gap-3">
                                    @if ($r->league->logo)
                                        <img src="{{ $r->league->logo }}" class="w-8 h-8 object-contain" />
                                    @else
                                        <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    @endif
                                    <div>
                                        <div class="font-medium">
                                            {{ $r->league->name_th ?? ($r->league->name ?? $r->league->name_en) }}</div>
                                        <div class="text-xs text-gray-500">อันดับ <span class="pos"></span></div>
                                    </div>
                                </div>
                                <button type="button" class="remove-btn px-2 py-1 text-sm rounded bg-red-50 text-red-600"
                                    data-id="{{ $r->league->id }}">ลบ</button>
                            </li>
                        @empty
                            <li class="p-3 text-gray-500 border rounded">ยังไม่ได้เลือกลีก</li>
                        @endforelse
                    </ul>

                    <div id="hiddenInputs"></div>
                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-sm text-gray-500">บันทึกได้สูงสุด 12 ลีก</p>
                        <button
                            class="px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white disabled:opacity-50"
                            id="saveBtn">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectedList = document.getElementById('selectedList');
            const availableList = document.getElementById('availableList');
            const hiddenInputs = document.getElementById('hiddenInputs');
            const saveBtn = document.getElementById('saveBtn');
            const selectedCount = document.getElementById('selectedCount');

            function recalc() {
                // update positions and hidden inputs
                hiddenInputs.innerHTML = '';
                const items = [...selectedList.querySelectorAll('li[data-id]')];
                items.forEach((li, idx) => {
                    const posEl = li.querySelector('.pos');
                    if (posEl) posEl.textContent = idx + 1;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'league_ids[]';
                    input.value = li.dataset.id;
                    hiddenInputs.appendChild(input);
                });
                selectedCount.textContent = `เลือกแล้ว ${items.length}/12`;
                saveBtn.disabled = items.length === 0 || items.length > 12;
            }

            // init sortable
            new Sortable(selectedList, {
                animation: 150,
                onEnd: recalc
            });

            // add buttons
            availableList.addEventListener('click', (e) => {
                const btn = e.target.closest('.add-btn');
                if (!btn) return;
                const id = btn.dataset.id;
                if (selectedList.querySelector(`li[data-id="${id}"]`)) return;
                if (selectedList.querySelectorAll('li[data-id]').length >= 12) {
                    alert('เลือกได้สูงสุด 12 ลีก');
                    return;
                }

                const source = btn.closest('li');
                const clone = source.cloneNode(true);
                clone.classList.add('cursor-move');
                clone.querySelector('.add-btn')?.remove();
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-btn px-2 py-1 text-sm rounded bg-red-50 text-red-600';
                removeBtn.textContent = 'ลบ';
                clone.appendChild(removeBtn);
                selectedList.appendChild(clone);
                btn.disabled = true;
                recalc();
            });

            // remove buttons
            selectedList.addEventListener('click', (e) => {
                const btn = e.target.closest('.remove-btn');
                if (!btn) return;
                const li = btn.closest('li');
                const id = li.dataset.id;
                li.remove();
                // enable corresponding add button
                const addBtn = availableList.querySelector(`.add-btn[data-id="${id}"]`);
                if (addBtn) addBtn.disabled = false;
                recalc();
            });

            recalc();
        });
    </script>
@endpush
