<header class="py-6 text-sm text-center text-gray-600 bg-white border-t shadow-md">
    <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
        <div class="text-2xl font-bold text-blue-600">WorkPlan</div>

        <button id="menuToggle" class="text-2xl text-gray-700 md:hidden focus:outline-none">☰</button>

        <nav id="navMenu" class="absolute left-0 z-50 flex-col hidden w-full px-4 font-medium text-gray-700 bg-white md:flex md:flex-row md:items-center md:space-x-6 md:static md:bg-transparent top-16 md:w-auto md:px-0">
        <a href="{{ url('/') }}" class="block py-2 hover:text-blue-600">Trang chủ</a>
        <a href="{{ route('plans.index') }}" class="block py-2 hover:text-blue-600">Kế hoạch</a>
        <a href="{{ route('reminders') }}" class="block py-2 hover:text-blue-600">Nhắc nhở</a>

        @auth
            <li class="flex items-center gap-2">
            <img src="{{ asset('uploads/' . (Auth::user()->AVATAR ?? 'avt.png')) }}" alt="AVT" class="w-6 h-6 rounded-full">
            <a href="/account">
                <span>{{ Auth::user()->HO_TEN }}</span>
            </a>
            </li>
        @else
            <a href="{{ route('login') }}" class="block py-2 hover:text-blue-600">Đăng nhập</a>
            <a href="{{ route('register') }}" class="block py-2 hover:text-blue-600">Đăng ký</a>
        @endauth
        </nav>
    </div>
</header>


{{-- Script mobile menu toggle --}}
<script>
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
