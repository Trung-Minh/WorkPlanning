{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Nhóm của bạn – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <div class="div flex min-h-screen text-gray-800 bg-gray-100">
        <aside id="groupSidebar" class="aside w-64 p-4 space-y-4 transition-all duration-300 border-r">

            <button onclick="toggleSidebar()" class="flex items-center w-full gap-2 px-3 py-2 text-lg font-semibold text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                <span id="a toggleIcon" class="text-2xl">&#9776;</span>
                <span id="a toggleLabel">Thành viên</span>
            </button>

            <!-- Button -->
            <button class="w-full py-2 font-semibold text-white bg-purple-600 rounded-lg cursor-pointer" onclick="document.getElementById('editGroupModal').showModal()">
                {{ $nhom->TEN_NHOM }}
            </button>

            <div>
                <h2 class="a mb-2 text-sm font-bold">Trưởng nhóm</h2>
                <div class="flex items-center gap-2 text-sm">
                    <img src="{{ asset('uploads/' . ($nhom->truongNhom->AVATAR ?? 'avt.jpg')) }}"
                        alt="avatar"
                        class="object-cover w-8 h-8 rounded-full" />
                    <span class = "a" >{{ $nhom->truongNhom->HO_TEN ?? 'Không rõ' }}</span>
                </div>
            </div>
        </div>


        <div>
            <h2 class="mb-2 text-sm font-bold">Thành viên</h2>
            <ul class="space-y-2">
                @foreach ($thanhVien as $tv)
                    <li class="flex items-center gap-2 text-sm">
                        <img src="{{ asset('uploads/' . ($tv->AVATAR ?? 'avt.jpg')) }}"
                            alt="avatar"
                            class="object-cover w-8 h-8 rounded-full" />
                        <span>{{ $tv->HO_TEN }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
            <!-- Nút thêm thành viên -->
            <a href="{{ url('/members') }}">
                <button type="button"
                    class="w-full py-2 mt-4 font-semibold text-white bg-green-600 rounded-lg cursor-pointer hover:bg-green-700">
                    ➕ Thêm thành viên
                </button>
            </a>


            <!-- Nút xoá nhóm -->
            <form method="POST" action="{{ route('group.delete', $nhom->ID_NHOM) }}"
                onsubmit="return confirm('Bạn có chắc muốn xoá nhóm không? Hành động này không thể hoàn tác!')">

                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 mt-2 font-semibold text-white bg-red-600 rounded-lg cursor-pointer hover:bg-red-700">
                    🗑️ Xoá nhóm
                </button>
            </form>
        @else
            <!-- Nút rời nhóm -->
            <form method="POST" action="{{ route('group.leave', $nhom->ID_NHOM) }}"
                onsubmit="return confirm('Bạn có chắc muốn rời nhóm không?')">

                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2 mt-4 font-semibold text-white bg-red-600 rounded-lg cursor-pointer hover:bg-red-700">
                    🏃 Rời nhóm
                </button>
            </form>
        @endif
    </aside>

    <!-- Nội dung chính -->
    <main class="flex-1 px-4">
        <div class="container py-4 mx-auto animate-fade-in">
            <h1 class="flex items-center gap-2 mb-6 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-purple-600 to-pink-500 animate-bounce">
                <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-3-3v6m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Danh sách Kế hoạch
            </h1>

            <div>
                <h2 class="a mb-2 text-sm font-bold">Thành viên</h2>
                <ul class="space-y-2">
                    @foreach ($thanhVien as $tv)
                        <li class="flex items-center gap-2 text-sm">
                            <img src="{{ asset('uploads/' . ($tv->AVATAR ?? 'avt.jpg')) }}"
                                alt="avatar"
                                class="object-cover w-8 h-8 rounded-full" />
                            <span>{{ $tv->HO_TEN }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                <!-- Nút thêm thành viên -->
                <a href="{{ url('/members') }}">
                    <button type="button"
                        class="w-full py-2 mt-4 font-semibold text-white bg-green-600 rounded-lg cursor-pointer hover:bg-green-700">
                        ➕ Thêm thành viên
                    </button>
                </a>


                <!-- Nút xoá nhóm -->
                <form method="POST" action="{{ route('group.delete', $nhom->ID_NHOM) }}"
                    onsubmit="return confirm('Bạn có chắc muốn xoá nhóm không? Hành động này không thể hoàn tác!')">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 mt-2 font-semibold text-white bg-red-600 rounded-lg cursor-pointer hover:bg-red-700">
                        🗑️ Xoá nhóm
                    </button>
                </form>
            @endif
        </aside>

        <!-- Nội dung chính -->
        <main class="flex-1 px-4">
            <div class="container py-4 mx-auto animate-fade-in">
                <h1 class="flex items-center gap-2 mb-6 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-purple-600 to-pink-500 animate-bounce">
                    <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-3-3v6m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Danh sách Kế hoạch
                </h1>


                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                    <!-- Nút xoá nhóm -->
                    <button onclick="showModal('modalAddPlan')"
                        class="cursor-pointer bg-gradient-to-r
                                from-red-500 to-purple-500
                                hover:from-red-600 hover:to-purple-600
                                text-white px-5 py-2.5 rounded-lg shadow-xl
                                transition-transform transform hover:scale-110">
                        ➕ Thêm Kế hoạch
                    </button>
                @endif

                @foreach($keHoachs as $keHoach)
                    <div class="p-4 mt-6 bg-white border border-blue-200 rounded-lg shadow-lg animate-fade-in">
                        {{-- Tiêu đề & nút xóa kế hoạch --}}
                        <div class="flex items-center justify-between mb-4">
                            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                <h2 class="text-2xl font-semibold text-gray-800 cursor-pointer editable task-title hover:underline">
                                    {{ $keHoach->TEN_KE_HOACH }}
                                </h2>
                            @else
                                <h2 class="text-2xl font-semibold text-gray-800">
                                    {{ $keHoach->TEN_KE_HOACH }}
                                </h2>
                            @endif

                            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                <button onclick="confirmDelete(
                                    '{{ route('group.delete-plan', $keHoach->ID_KH) }}',
                                    'Xoá Kế Hoạch',
                                    'Bạn có chắc chắn muốn xoá kế hoạch này không?')"
                                    class="text-red-600 cursor-pointer hover:text-red-800">
                                        🗑 Xóa Kế Hoạch
                                </button>
                            @endif
                        </div>

                        @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                            {{-- Nút thêm công việc --}}
                            <button onclick="showAddTaskModal('{{ $keHoach->ID_KH }}')"
                                class="cursor-pointer bg-gradient-to-r
                                from-green-500 via-purple-400 to-pink-500
                                hover:from-green-600 hover:via-blue-500 hover:to-pink-600
                                px-5 py-2.5 rounded-lg shadow-xl
                                transition-transform transform hover:scale-110
                                text-sm text-white mb-4 animate-fade-in">
                                + Thêm Công việc
                            </button>
                        @endif

                        @if($keHoach->cong_viec->isEmpty())
                            <p class="italic text-gray-500">Chưa có công việc nào cho kế hoạch này.</p>
                        @else
                            <div class="overflow-x-auto">
                                <div class="flex gap-4 flex-nowrap">
                                    @php
                                        $colors = [
                                            'from-pink-100 to-pink-200',
                                            'from-purple-100 to-purple-200',
                                            'from-orange-100 to-orange-200',
                                            'from-emerald-100 to-emerald-200',
                                            'from-teal-100 to-teal-200',
                                            'from-fuchsia-100 to-fuchsia-200',
                                            'from-indigo-100 to-indigo-200',
                                            'from-blue-100 to-blue-200',
                                            'from-green-100 to-green-200',
                                            'from-yellow-100 to-yellow-200',
                                            'from-rose-100 to-rose-200',
                                            'from-red-100 to-red-200',
                                            'from-sky-100 to-sky-200',
                                            'from-lime-100 to-lime-200',
                                            'from-fuchsia-100 to-fuchsia-200',
                                        ];
                                    @endphp

                                    @foreach($keHoach->cong_viec as $i => $cv)
                                        @php $gradient = $colors[$i % count($colors)]; @endphp

                                        <div class="relative min-w-[320px] w-[400px] border-l-4 border-indigo-500
                                                    bg-gradient-to-br {{ $gradient }} p-4
                                                    rounded-lg shadow-md hover:shadow-xl
                                                    transition duration-300 animate-fade-in">
                                            {{-- Công việc --}}
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                                    <span class="text-indigo-800 cursor-pointer editable task-title hover:underline"
                                                            data-id="{{ $cv->ID_CV }}">
                                                        {{ $cv->TEN_CV }}
                                                    </span>
                                                @else
                                                    <span class="text-indigo-800"
                                                            data-id="{{ $cv->ID_CV }}">
                                                        {{ $cv->TEN_CV }}
                                                    </span>
                                                @endif
                                            </h3>

                                            <div class="flex items-center justify-between mb-2">
                                                @php
                                                    $tienDo = $cv->TIEN_DO ?? 0;
                                                    $color = $tienDo == 100 ? 'text-green-600' : ($tienDo >= 50 ? 'text-yellow-600' : 'text-red-600');
                                                @endphp

                                                <p class="font-semibold {{ $color }}">
                                                    Tiến độ: {{ $tienDo }}%
                                                </p>

                                                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                                    <button onclick="confirmDelete(
                                                        '{{ route('group.delete-task', $cv->ID_CV) }}',
                                                        'Xoá Công Việc',
                                                        'Bạn có chắc chắn muốn xoá?')"
                                                        class="text-sm text-red-500 transition cursor-pointer hover:text-red-700">
                                                        🗑 Xóa Công Việc
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Thư mục --}}
                                            <ul class="mt-2 ml-5 space-y-2 text-sm list-none">
                                                @foreach($cv->muc_cong_viec as $muc)
                                                    @php
                                                        $deadline = \Carbon\Carbon::parse($muc->THOI_HAN_HOAN_THANH);
                                                        $now = \Carbon\Carbon::now();
                                                        $isDone = $muc->TRANG_THAI;
                                                        $bgColor = $isDone ? 'bg-green-300' : ($deadline->lt($now) ? 'bg-red-300' : 'bg-blue-100');
                                                    @endphp

                                                    <li class="relative p-3 rounded shadow-sm transition {{ $bgColor }} hover:brightness-105 hover:scale-105 duration-200 text-left" data-id="{{ $muc->ID_MUC }}">
                                                        {{-- Tên mục --}}
                                                        <div class="w-full mb-1">
                                                            <span class="font-medium text-gray-800 cursor-pointer editable subtask-title hover:text-indigo-700 hover:underline"
                                                                data-id="{{ $muc->ID_MUC }}">
                                                                {{ $muc->TEN_MUC }}
                                                            </span>
                                                        </div>

                                                        {{-- Thời hạn --}}
                                                        <div class="w-full mb-2 text-center text-gray-600">
                                                            <span class="cursor-pointer editable subtask-deadline" data-id="{{ $muc->ID_MUC }}">
                                                                {{ $muc->THOI_HAN_HOAN_THANH }}
                                                            </span>
                                                        </div>

                                                        {{-- Nút hành động --}}
                                                        <div  id="action-buttons-{{ $muc->ID_MUC }}" class="flex justify-start w-full space-x-2 text-xs">
                                                            <button onclick="showViewSubtaskModal_Group('{{ $muc->ID_MUC }}')"
                                                                    class="text-blue-600 underline cursor-pointer hover:text-blue-800">
                                                                👁 Xem
                                                            </button>
                                                            <button onclick="showEditSubtaskModal_Group('{{ $muc->ID_MUC }}')"
                                                                    class="text-yellow-600 underline cursor-pointer hover:text-yellow-800">
                                                                ✏️ Chỉnh Sửa
                                                            </button>
                                                            <button onclick="confirmDelete_Group('{{ route('group.delete-subtask', $muc->ID_MUC) }}', 'Xoá Thư Mục', 'Bạn có chắc chắn?')"
                                                                    class="text-red-600 underline cursor-pointer hover:text-red-800">
                                                                🗑 Xóa
                                                            </button>
                                                        </div>

                                                        <script>
                                                            window.addEventListener('DOMContentLoaded', () => {
                                                                const el = document.getElementById("action-buttons-{{ $muc->ID_MUC }}");
                                                                if (el && el.offsetWidth > 240) {
                                                                    el.classList.remove("justify-start");
                                                                    el.classList.add("justify-center");
                                                                }
                                                            });
                                                        </script>

                                                        {{-- Hiển thị độ ưu tiên --}}
                                                        <div class="absolute px-2 py-1 text-xs font-semibold rounded shadow cursor-pointer priority-display bottom-2 right-3 text-black-700 bg-white/60">
                                                            <span ondblclick="editMcvPriority(this, '{{ $muc->ID_MUC }}')">
                                                                🎯 <span class="priority-muc-value">{{ $muc->DO_UU_TIEN_MUC }}</span>
                                                            </span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            {{-- Thêm mục --}}
                                            <div class="flex justify-center mt-4">
                                                <button onclick="showAddSubTaskModal('{{ $cv->ID_CV }}')"
                                                        class="cursor-pointer w-[60%] bg-gradient-to-r from-green-400 via-green-500 to-emerald-500
                                                            text-white py-2 rounded-lg shadow hover:from-green-500 hover:to-emerald-600 hover:scale-105
                                                            transition-transform duration-300 text-sm">
                                                    ➕ Thêm Mục
                                                </button>
                                            </div>

                                            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                                {{-- Hiển thị độ ưu tiên --}}
                                                <div class="absolute bottom-2 right-3">
                                                    <span class="px-2 py-1 text-sm font-semibold rounded shadow cursor-pointer priority-display bg-white/70 hover:ring hover:ring-indigo-300"
                                                        ondblclick="editCvPriority(this, '{{ $cv->ID_CV }}')">
                                                        ⭐ <span class="priority-value">{{ $cv->DO_UU_TIEN }}</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Thư mục --}}
                                        <ul class="mt-2 ml-5 space-y-2 text-sm list-none">
                                            @foreach($cv->muc_cong_viec as $muc)
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($muc->THOI_HAN_HOAN_THANH);
                                                    $now = \Carbon\Carbon::now();
                                                    $isDone = $muc->TRANG_THAI;
                                                    $bgColor = $isDone ? 'bg-green-300' : ($deadline->lt($now) ? 'bg-red-300' : 'bg-blue-100');
                                                @endphp

                                                <li class="relative p-3 rounded shadow-sm transition {{ $bgColor }} hover:brightness-105 hover:scale-105 duration-200 text-left" data-id="{{ $muc->ID_MUC }}">
                                                    {{-- Tên mục --}}
                                                    <div class="w-full mb-1">
                                                        <span class="font-medium text-gray-800 cursor-pointer editable subtask-title hover:text-indigo-700 hover:underline"
                                                            data-id="{{ $muc->ID_MUC }}">
                                                            {{ $muc->TEN_MUC }}
                                                        </span>
                                                    </div>

                                                    {{-- Thời hạn --}}
                                                    <div class="w-full mb-2 text-center text-gray-600">
                                                        <span class="cursor-pointer editable subtask-deadline" data-id="{{ $muc->ID_MUC }}">
                                                            {{ $muc->THOI_HAN_HOAN_THANH }}
                                                        </span>
                                                    </div>

                                                    {{-- Nút hành động --}}
                                                    <div  id="action-buttons-{{ $muc->ID_MUC }}" class="flex justify-start w-full space-x-2 text-xs">
                                                        <button onclick="showViewSubtaskModal_Group('{{ $muc->ID_MUC }}')"
                                                                class="text-blue-600 underline cursor-pointer hover:text-blue-800">
                                                            👁 Xem
                                                        </button>
                                                        <button onclick="showEditSubtaskModal_Group('{{ $muc->ID_MUC }}')"
                                                                class="text-yellow-600 underline cursor-pointer hover:text-yellow-800">
                                                            ✏️ Chỉnh Sửa
                                                        </button>
                                                        <button onclick="confirmDelete_Group('{{ route('group.delete-subtask', $muc->ID_MUC) }}', 'Xoá Thư Mục', 'Bạn có chắc chắn?')"
                                                                class="text-red-600 underline cursor-pointer hover:text-red-800">
                                                            🗑 Xóa
                                                        </button>
                                                    </div>

                                                    <script>
                                                        window.addEventListener('DOMContentLoaded', () => {
                                                            const el = document.getElementById("action-buttons-{{ $muc->ID_MUC }}");
                                                            if (el && el.offsetWidth > 240) {
                                                                el.classList.remove("justify-start");
                                                                el.classList.add("justify-center");
                                                            }
                                                        });
                                                    </script>

                                                    {{-- Hiển thị độ ưu tiên mục --}}
                                                    <div class="absolute bottom-2 right-3">
                                                        <span class="px-2 py-1 text-sm font-semibold rounded shadow cursor-pointer bg-white/70 hover:ring hover:ring-indigo-300"
                                                            ondblclick="editMcvPriority(this, '{{ $muc->ID_MUC }}')">
                                                            🎯 <span class="priority-muc-value">{{ $muc->DO_UU_TIEN_MUC }}</span>
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{-- Thêm mục --}}
                                        <div class="flex justify-center mt-4">
                                            <button onclick="showAddSubTaskModal('{{ $cv->ID_CV }}')"
                                                    class="cursor-pointer w-[60%] bg-gradient-to-r from-green-400 via-green-500 to-emerald-500
                                                        text-white py-2 rounded-lg shadow hover:from-green-500 hover:to-emerald-600 hover:scale-105
                                                        transition-transform duration-300 text-sm">
                                                ➕ Thêm Mục
                                            </button>
                                        </div>

                                        @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                                            {{-- Hiển thị độ ưu tiên --}}
                                            <div class="absolute bottom-2 right-3">
                                                <span class="px-2 py-1 text-sm font-semibold rounded shadow cursor-pointer priority-display bg-white/70 hover:ring hover:ring-indigo-300"
                                                    ondblclick="editCvPriority(this, '{{ $cv->ID_CV }}')">
                                                    ⭐ <span class="priority-value">{{ $cv->DO_UU_TIEN }}</span>
                                                </span>
                                            </div>
                                        @else
                                            <div class="absolute bottom-2 right-3">
                                                <span class="px-2 py-1 text-sm font-semibold rounded shadow bg-white/70">
                                                    ⭐ <span class="priority-value">{{ $cv->DO_UU_TIEN }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    <!-- Modal -->
    <dialog id="editGroupModal" class="fixed inset-0 z-50 w-full max-w-md p-5 m-auto bg-white rounded-lg shadow-md">
        <form method="POST" action="{{ route('group.update', $nhom->ID_NHOM) }}" enctype="multipart/form-data">
            @csrf
            @method('POST')

            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                <h2 class="mb-4 text-lg font-semibold text-center text-purple-700">Chỉnh sửa nhóm</h2>
            @else
                <h2 class="mb-4 text-lg font-semibold text-center text-gray-700">Thông tin nhóm</h2>
            @endif

            @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Ảnh nhóm</label>
                    <input type="file" name="AVATAR_NHOM" accept="image/*" class="w-full px-3 py-2 border rounded">
                </div>
            @endif

            <div class="mb-4">
                <label class="block mb-1 font-medium">Tên nhóm</label>

                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                    <input type="text" name="TEN_NHOM" value="{{ $nhom->TEN_NHOM }}" class="w-full px-3 py-2 border rounded" required>
                @else
                    <p class="px-3 py-2">{{ $nhom->TEN_NHOM }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Mô tả nhóm</label>

                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                    <textarea name="MO_TA_NHOM" class="w-full px-3 py-2 border rounded">{{ $nhom->MO_TA_NHOM ?? '' }}</textarea>
                @else
                    <p class="px-3 py-2">{{ $nhom->MO_TA_NHOM ?? 'Chưa có mô tả' }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-2">
                @if(Auth::user()->ID_USER === $nhom->ID_NHOM_TRUONG)
                    <button type="submit" class="px-4 py-2 text-white bg-purple-600 rounded cursor-pointer">Lưu</button>
                    <button type="button" onclick="document.getElementById('editGroupModal').close()"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded cursor-pointer">
                        Huỷ
                    </button>
                @else
                    <button type="button" onclick="document.getElementById('editGroupModal').close()"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded cursor-pointer">
                        Đóng
                    </button>
                @endif

            </div>
        </form>
    </dialog>

    <script>
        let isCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('groupSidebar');
            const icon = document.getElementById('toggleIcon');
            const label = document.getElementById('toggleLabel');
            const toggleBtn = icon.closest('button');

            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');

                sidebar.querySelectorAll(':scope > *:not(:first-child)').forEach(el => el.style.display = 'none');

                icon.innerHTML = '☰';
                label.style.display = 'none';

                toggleBtn.classList.remove('justify-start');
                toggleBtn.classList.add('justify-center');
            } else {
                sidebar.classList.add('w-64');
                sidebar.classList.remove('w-16');

                sidebar.querySelectorAll(':scope > *').forEach(el => el.style.display = '');

                icon.innerHTML = '☰';
                label.style.display = 'inline';

                toggleBtn.classList.remove('justify-center');
                toggleBtn.classList.add('justify-start');
            }
        }

        window.subtaskData = @json(
            $keHoachs->flatMap->cong_viec->flatMap->muc_cong_viec->keyBy('ID_MUC')
        );

        document.addEventListener('DOMContentLoaded', function () {
            setupInlineEditing('{{ csrf_token() }}');
        });
    </script>

@include('Plans.modal_group')
@endsection

@php($noFooter = true)
