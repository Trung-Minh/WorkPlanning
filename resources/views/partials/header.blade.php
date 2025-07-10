<!-- <header class="bg-white border-t shadow-md py-6 text-center text-sm text-gray-600">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
    <div class="text-2xl font-bold text-blue-600">WorkPlan</div>
    <button id="menuToggle" class="md:hidden text-2xl text-gray-700 focus:outline-none">☰</button>
    <nav id="navMenu" class="hidden md:flex flex-col md:flex-row md:items-center md:space-x-6 text-gray-700 font-medium
                absolute md:static bg-white md:bg-transparent top-16 left-0 w-full md:w-auto px-4 md:px-0 z-50">
      <a href="{{ url('/') }}" class="py-2 block hover:text-blue-600">Trang chủ</a>
      <a href="{{ url('/ke-hoach') }}" class="py-2 block hover:text-blue-600">Kế hoạch</a>
      <a href="{{ url('/reminders') }}" class="py-2 block hover:text-blue-600">Nhắc nhở</a>
      <a href="{{ url('/login') }}" class="py-2 block hover:text-blue-600">Đăng nhập</a>
      <a href="{{ url('/register') }}" class="py-2 block hover:text-blue-600">Đăng ký</a>
    </nav>
  </div>
</header>
{{-- Script mobile menu toggle --}}
<script>
  const menuToggle = document.getElementById('menuToggle');
  const navMenu = document.getElementById('navMenu');
  menuToggle?.addEventListener('click', () => navMenu?.classList.toggle('hidden'));
</script> -->
<header class="bg-white border-t shadow-md py-6 text-center text-sm text-gray-600">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
    {{-- Logo --}}
    <div class="text-2xl font-bold text-blue-600">WorkPlan</div>

    {{-- Nút menu mobile --}}
    <button id="menuToggle" class="md:hidden text-2xl text-gray-700 focus:outline-none">☰</button>

    {{-- Menu điều hướng --}}
    <nav id="navMenu"
      class="hidden md:flex flex-col md:flex-row md:items-center md:space-x-6 text-gray-700 font-medium
      absolute md:static bg-white md:bg-transparent top-16 left-0 w-full md:w-auto px-4 md:px-0 z-50">

      <a href="{{ url('/') }}" class="py-2 block hover:text-blue-600">Trang chủ</a>
      <a href="{{ route('reminders') }}" class="py-2 block hover:text-blue-600">Nhắc nhở</a>
      <a href="{{ route('plans.index') }}" class="py-2 block hover:text-blue-600">Kế hoạch</a>

      @auth
        <div class="relative" id="userDropdownWrapper">
          <button id="userDropdownToggle"
            class="flex items-center text-blue-600 font-bold focus:outline-none hover:text-blue-800">
            {{ Auth::user()->HO_TEN }}
            <svg class="w-4 h-4 ml-1 transition-transform transform" id="dropdownIcon"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div id="userDropdownMenu"
            class="absolute right-0 mt-2 bg-white border rounded shadow-md p-2 w-32 z-50 hidden">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="w-full text-left text-sm text-red-600 hover:underline">
                Đăng xuất
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="py-2 block hover:text-blue-600">Đăng nhập</a>
        <a href="{{ route('register') }}" class="py-2 block hover:text-blue-600">Đăng ký</a>
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
