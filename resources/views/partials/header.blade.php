<header class="bg-white border-t shadow-md py-6 text-center text-sm text-gray-600">
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
</script>