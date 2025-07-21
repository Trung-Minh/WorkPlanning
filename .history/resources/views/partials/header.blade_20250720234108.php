<header class="py-6 text-gray-600 bg-white border-t shadow-md dark-card text-md">
    <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
        <div class="text-2xl font-bold text-blue-600 dark-card">
            <a href="{{route('welcome')  }}">
                WorkPlanning
            </a>
        </div>
        <button id="menuToggle" class="text-2xl text-gray-700 md:hidden focus:outline-none">☰</button>

        <nav id="navMenu" class="absolute left-0 z-50 flex-col hidden w-full px-4 font-medium text-gray-700 bg-white md:flex md:flex-row md:items-center md:space-x-6 md:static md:bg-transparent top-16 md:w-auto md:px-0">
            @auth
            <a href="#" onclick="event.preventDefault(); document.getElementById('post-form').submit();">Tạo Nhóm</a>

            <form id="post-form" method="POST" action="{{ route('addgroup') }}" style="display:none;">
            @csrf
            <input type="hidden" name="id_user" value="{{ Auth::user()->ID_USER }}">
            </form>
            @endauth
            <a href="{{ url('/') }}" class="block py-2 hover:text-blue-600">Trang chủ</a>
            <a href="{{ route('plans.index') }}" class="block py-2 hover:text-blue-600">Kế hoạch</a>
            <a href="{{ route('reminders') }}" class="block py-2 hover:text-blue-600">Nhắc nhở</a>

            @auth
                <!-- Thêm Alpine.js (nếu chưa có) -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

              <!-- Bọc toàn bộ popup và trigger trong 1 x-data -->
<div x-data="{ show: false }">

    <!-- Thẻ A để mở popup -->
    <a href="#" @click.prevent="show = true" class="text-blue-600 underline">Mở popup</a>

    <!-- Popup -->
    <div x-show="show"
         x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity- z-50">

        <!-- Nội dung popup -->
        <div @click.away="show = false"
             class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
            <h2 class="text-lg font-semibold mb-4">Popup tiêu đề</h2>
            <p class="text-gray-700 mb-4">Đây là nội dung popup.</p>

            <button @click="show = false"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Đóng
            </button>
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

                        @if(isset($notifications) && count($notifications))
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                        @endif
                    </button>

                    {{-- Popover --}}
                    <div class="absolute right-0 z-10 hidden p-3 text-sm text-gray-700 bg-white border border-gray-200 rounded-md shadow-md w-80 group-hover:block top-10">
                        <h4 class="mb-2 font-semibold text-blue-600">Thông báo sắp đến hạn</h4>
                        <ul class="space-y-2 overflow-auto max-h-64">
                            @forelse($notifications ?? [] as $tenCV => $mucs)
                                <li class="mb-1">
                                    <div class="font-bold text-blue-700">📂 {{ $tenCV }}</div>
                                    <ul class="pl-4 mt-1 space-y-1">
                                        @foreach($mucs as $muc)
                                            <li onclick="window.location.href='{{ route('plans.index') }}'">
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
<script>
    const toggle = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const html = document.documentElement;

    // Load dark mode từ localStorage
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
    }

    toggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    menuToggle?.addEventListener('click', () => navMenu?.classList.toggle('hidden'));

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('userDropdownToggle');
        const dropdownMenu = document.getElementById('userDropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');

        toggleBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
        dropdownIcon.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function (e) {
        if (!document.getElementById('userDropdownWrapper')?.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            dropdownIcon.classList.remove('rotate-180');
        }
        });
    });
</script>
