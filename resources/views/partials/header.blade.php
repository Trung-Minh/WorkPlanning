<header class="py-6 text-gray-600 bg-white border-t shadow-md dark-card text-md">
    <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
        <div class="text-2xl font-bold text-blue-600 dark-card">
            <a href="{{route('welcome')  }}">
                WorkPlanning
            </a>
        </div>
        <button id="menuToggle" class="text-2xl text-gray-700 md:hidden focus:outline-none">☰</button>

        <nav id="navMenu"
            class="absolute left-0 z-50 flex-col hidden w-full px-4 font-medium text-gray-700 bg-white md:flex md:flex-row md:items-center md:space-x-6 md:static md:bg-transparent top-16 md:w-auto md:px-0">


            @auth
                <a href="#" class="cursor-pointer hover:text-blue-600" onclick="event.preventDefault(); document.getElementById('post-form').submit();">Tạo Nhóm</a>

                <form id="post-form" method="POST" action="{{ route('addgroup') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ Auth::user()->ID_USER }}">
                </form>
                {{-- <a href="{{ route('showLeader') }}" class="hover:text-blue-600 hover:underline">Tạo nhóm</a> --}}
            @endauth

            <a href="{{ url('/') }}" class="block py-2 hover:text-blue-600">Trang chủ</a>
            <a href="{{ route('plans.index') }}" class="block py-2 hover:text-blue-600">Kế hoạch</a>
            <a href="{{ route('reminders') }}" class="block py-2 hover:text-blue-600">Nhắc nhở</a>

            @auth
                <!-- Thêm Alpine.js (nếu chưa có) -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                <!-- Bọc toàn bộ popup và trigger trong 1 x-data -->
                <div x-data="{ show: false }">

                    <!-- Nút mở popup -->
                    <a href="#" @click.prevent="show = true" class="hover:text-blue-600 hover:underline">Nhóm của bạn</a>

                    <!-- Overlay popup với nền trong suốt và mờ -->
                    <div x-show="show"
                        x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/20">

                        <!-- Nội dung popup -->
                        <div @click.away="show = false"
                            class="relative p-6 bg-white rounded-lg shadow-lg w-96">

                            <!-- Nút đóng dấu X -->
                            <button @click="show = false"
                                        class="absolute text-xl font-bold text-red-500 top-2 right-2 hover:text-red-700 focus:outline-none">
                                &times;
                            </button>

                            <h2 class="mb-4 text-lg font-semibold">Nhóm của bạn</h2>

                            @php
                                $userId = Auth::user()->ID_USER;
                                $dsNhom = \App\Models\Nhom::where('ID_NHOM_TRUONG', $userId)
                                    ->select('ID_NHOM', 'TEN_NHOM', 'AVATAR_NHOM')
                                    ->get();

                            @endphp

                            @php
                                $userId = Auth::user()->ID_USER;
                                $dsNhomThamGia = \App\Models\Nhom::join('nhom_thanh_vien', 'nhom_lam_viec.ID_NHOM', '=', 'nhom_thanh_vien.ID_NHOM')
                                    ->where('nhom_thanh_vien.ID_USER', $userId)
                                    ->where('nhom_lam_viec.ID_NHOM_TRUONG', '!=', $userId)
                                    ->select('nhom_lam_viec.ID_NHOM', 'nhom_lam_viec.TEN_NHOM', 'nhom_lam_viec.AVATAR_NHOM')
                                    ->get();
                            @endphp

                            <h2 class="mb-4 text-lg font-semibold">Các nhóm bạn quản lý:</h2>

                            {{-- Hiển thị danh sách nhóm quản lý --}}
                            @if($dsNhom->count())
                                <ul class="pr-2 space-y-2 overflow-y-auto max-h-64">
                                    @foreach($dsNhom as $nhom)
                                        <li>
                                            <form method="POST" action="{{ route('doGroup') }}">
                                                @csrf
                                                <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                                                <button type="submit" class="flex items-center w-full gap-3 p-2 text-left transition bg-white border rounded cursor-pointer hover:bg-gray-100">
                                                    <img src="{{ asset($nhom->AVATAR_NHOM ?? 'upload_group/avt.jpg') }}" class="flex-shrink-0 object-cover rounded-full w-9 h-9" />
                                                    <span class="text-sm font-medium">{{ $nhom->TEN_NHOM }}</span>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500">Bạn chưa quản lý nhóm nào.</p>
                            @endif

                            <hr class="my-4 border-t" />
                            <h2 class="mb-4 text-lg font-semibold">Các nhóm bạn đã tham gia:</h2>

                            {{-- Hiển thị danh sách nhóm tham gia --}}
                            @if($dsNhomThamGia->count())
                                <ul class="pr-2 space-y-2 overflow-y-auto max-h-64">
                                    @foreach($dsNhomThamGia as $nhom)
                                        <li>
                                            <form method="POST" action="{{ route('doGroup') }}">
                                                @csrf
                                                <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                                                <button type="submit" class="flex items-center w-full gap-3 p-2 text-left transition border rounded cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                    <img src="{{ asset($nhom->AVATAR_NHOM ?? 'upload_group/avt.jpg') }}" class="flex-shrink-0 object-cover rounded-full w-9 h-9" />
                                                    <span class="text-sm font-medium">{{ $nhom->TEN_NHOM }}</span>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500">Bạn chưa tham gia nhóm nào.</p>
                            @endif
                        </div>
                    </div>
                </div>


                <a href="/account" class="flex items-center gap-2 py-2 hover:text-blue-600">
                    <img src="{{ asset('uploads/' . (Auth::user()->AVATAR ?? 'avt.jpg')) }}" alt="AVT" class="w-6 h-6 rounded-full">
                    <span>{{ Auth::user()->HO_TEN ?? 'Không có tên' }}</span>
                </a>

                {{-- 🔔 Chuông thông báo --}}
                <div class="relative group">
                    <button class="relative p-2 rounded-full hover:bg-gray-200 focus:outline-none">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>

                        @if(isset($notifications) && count($invitations))
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                        @endif
                    </button>

                    {{-- Popover --}}
                    <div class="absolute right-0 z-10 hidden p-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-md shadow-md w-80 group-hover:block top-10">
                        {{-- 🔔 Lời mời vào nhóm --}}
                        <h4 class="mb-1 font-semibold text-blue-600">📨 Lời mời vào nhóm</h4>
                        <ul class="space-y-1">
                            @forelse($invitations ?? [] as $invitation)
                                @if(is_object($invitation))
                                    <li class="px-2 py-2 text-sm rounded bg-blue-50 hover:bg-blue-100">
                                        👥 Nhóm: <strong>{{ $invitation->TEN_NHOM }}</strong>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">
                                                Người mời: {{ $invitation->NGUOI_MOI }}
                                            </span>

                                            <div class="flex gap-2">
                                                {{-- ✅ Chấp nhận --}}
                                                <form action="{{ route('leader.chapnhan', ['id' => $invitation->ID_NHOM]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Chấp nhận" class="text-xl text-green-600 cursor-pointer hover:scale-120">✔️</button>
                                                </form>

                                                {{-- ❌ Từ chối --}}
                                                <form action="{{ route('leader.tuchoi', ['id' => $invitation->ID_NHOM]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Từ chối" class="text-xl text-red-600 cursor-pointer hover:scale-120">❌</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @empty
                                <li class="text-gray-500">Không có lời mời nào</li>
                            @endforelse
                        </ul>


                        <hr class="my-2 border-gray-200">

                        {{-- 🔔 Thông báo sắp hết hạn --}}
                        <h4 class="mb-2 font-semibold text-blue-600">Thông báo sắp đến hạn</h4>
                        <ul class="space-y-2 overflow-auto max-h-64">
                            @forelse($notifications ?? [] as $tenCV => $mucs)
                                <li class="mb-1">
                                    <div class="font-bold text-blue-700">📂 {{ $tenCV }}</div>
                                    <ul class="pl-4 mt-1 space-y-1">
                                        @foreach($mucs as $muc)
                                            <li onclick="window.location.href='{{ route('plans.index') }}'"
                                                class="px-2 py-1 transition rounded cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                📌 <strong>{{ $muc->TEN_MUC }}</strong><br>
                                                <span class="block text-xs text-gray-500">
                                                    Hạn: {{ \Carbon\Carbon::parse($muc->THOI_HAN_HOAN_THANH)->format('d/m/Y H:i') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @empty
                                <li class="text-gray-500">Không có thông báo nào</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- 🌙 Nút Dark Mode --}}
                    <button id="darkModeToggle"
                            class="p-2 ml-2 transition rounded-full cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700">
                        <svg id="darkModeIcon" class="w-6 h-6 text-gray-700 dark:text-yellow-300" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m8.66-8.66h-1M4.34 12h-1m15.36-4.95l-.7.7M6.34 17.66l-.7.7m0-13.72l.7.7M17.66 17.66l.7.7M12 5a7 7 0 000 14a7 7 0 000-14z" />
                        </svg>
                    </button>



            @else
                <a href="{{ route('login') }}" class="block py-2 hover:text-blue-600">Đăng nhập</a>
                <a href="{{ route('register') }}" class="block py-2 hover:text-blue-600">Đăng ký</a>
            @endauth
        </nav>
    </div>
</header>

{{-- Script mobile menu toggle --}}
<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Load từ localStorage
        if (localStorage.getItem('theme') === 'dark') {
            html.classList.add('dark');
        }

        toggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            fetch('/toggle-dark', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            });
        });
    });
</script> -->
