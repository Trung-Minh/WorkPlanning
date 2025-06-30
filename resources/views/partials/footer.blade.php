<footer class="border-t border-gray-300 shadow-md bg-white text-center text-sm text-gray-600">
  <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row justify-between gap-6">
    <div class="flex-1">
      <h2 class="font-semibold mb-2">WorkPlan 💼</h2>
      <p>Quản lý kế hoạch thông minh, nhắc nhở đúng lúc.</p>
      <p>Made with team</p>
    </div>
    <div class="flex-1">
      <h2 class="font-semibold mb-2">Liên hệ</h2>
      <p>Email: <a href="mailto:anh@workplan.vn" class="hover:underline">anh@workplan.vn</a></p>
      <p>Hotline: 0123 456 789</p>
    </div>
    <div class="flex-1">
      <h2 class="font-semibold mb-2">Liên kết</h2>
      <ul class="space-y-1">
        <li><a href="{{ url('/') }}" class="hover:underline">Trang chủ</a></li>
        <li><a href="{{ url('/plans') }}" class="hover:underline">Kế hoạch</a></li>
        <li><a href="{{ url('/reminders') }}" class="hover:underline">Nhắc nhở</a></li>
      </ul>
    </div>
  </div>
  <div class="bg-gray-200 py-2 text-center">© 2025 WorkPlan. All rights reserved.</div>
  <button id="scrollToTopBtn" class="fixed bottom-6 right-6 p-3 rounded-full bg-blue-600 text-white shadow-md
                hover:bg-blue-700 transition-opacity opacity-0 pointer-events-none z-50">
    ↑
  </button>
  <script>
    const btnTop = document.getElementById('scrollToTopBtn');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) btnTop.classList.remove('opacity-0', 'pointer-events-none');
      else btnTop.classList.add('opacity-0', 'pointer-events-none');
    });
    btnTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  </script>
</footer>