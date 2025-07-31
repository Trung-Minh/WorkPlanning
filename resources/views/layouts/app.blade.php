 {{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'WorkPlan')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-gray-100  ">
  @include('partials.header')

  <main class="flex-1 w-full px-4 py-4 mx-auto " >
    @y@include('partials.footer')
  @endif

  {{-- Phần này bạn giữ lại để toast + âm thanh hoạt động xuyên trang --}}
  <audio id="reminder-sound" src="{{ asset('sounds/notificationx3_reminders.mp3') }}" preload="auto"></audio>
  <div id="toast-container" class="fixed z-50 space-y-2 bottom-5 right-5"></div>

<div id="loading-overlay"
     class="fixed inset-0 bg-white z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-500 ease-in-out">
    <div class="text-2xl font-bold animate-bounce text-blue-600">
        Đang chuyển trang...
    </div>
</div>
  {{-- Biến JavaScript từ PHP --}}
  <script>
    window.reminders = @json($js_reminders ?? []);
  </script>

  {{-- Script thông báo nhắc nhở --}}
  <script>
    const sound = document.getElementById('reminder-sound');
    const toastContainer = document.getElementById('toast-container');
    const notified = new Set();

    function showReminderToast(reminder) {
      const toast = document.createElement('div');
      toast.className = "bg-blue-600 text-white px-4 py-3 rounded shadow w-80";
      toast.innerHTML = `
        <p class="font-semibold">🔔 Nhắc nhở</p>
        <p>${reminder.noi_dung}</p>
        <p class="mt-1 text-sm text-white/80">${new Date(reminder.thoidiem_thongbao).toLocaleString()}</p>
      `;
      toastContainer.appendChild(toast);
      setTimeout(() => toast.remove(), 15000);
    }

    function checkReminders() {
      const now = new Date();
      if (!Array.isArray(window.reminders)) return;

      window.reminders.forEach(reminder => {
        const notifyTime = new Date(reminder.thoidiem_thongbao);
        const deadline = reminder.thoihan_hoanthanh ? new Date(reminder.thoihan_hoanthanh) : null;

        if (notified.has(reminder.id)) return;

        const diff = Math.abs(now - notifyTime);
        if (diff <= 30000 && (!deadline || now < deadline)) {
          showReminderToast(reminder);
          sound.play().catch(() => {
            console.warn("Âm thanh không thể tự động phát do trình duyệt chặn autoplay.");
          });
          notified.add(reminder.id);
        }
      });
    }

    setInterval(checkReminders, 30000);
    window.addEventListener('load', checkReminders);

    if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
}

  </script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
          const html = document.documentElement;
          const toggleBtn = document.getElementById('darkModeToggle');

          // Bật dark nếu đã lưu trước đó
          if (localStorage.getItem('theme') === 'dark') {
              html.classList.add('dark');
          }

          // Toggle khi click
          toggleBtn?.addEventListener('click', () => {
              html.classList.toggle('dark');
              const isDark = html.classList.contains('dark');
              localStorage.setItem('theme', isDark ? 'dark' : 'light');
          });
      }); 

      
  </script>
<script>
    window.addEventListener('load', () => {
        const overlay = document.getElementById('loading-overlay');

        // Event delegation: lắng nghe click trên toàn trang
        document.body.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]');
            if (!link) return;

            const href = link.getAttribute('href');

            // Kiểm tra các link không cần xử lý
            if (!href || href.startsWith('#') || href.startsWith('http') || link.hasAttribute('target')) {
                return;
            }

            // ✅ In ra để debug
            console.log("Đã click vào link:", href);

            e.preventDefault();

            // Hiện overlay loading
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');

            setTimeout(() => {
                window.location.href = href;
            }, 1000); // Delay 1 giây
        });
    });
</script>

ield('content')
  </main>

  @if(empty($noFooter) && !(request()->is('reminders')))
  

  {{-- Để các script cụ thể của từng trang (nếu có) --}}
  @stack('scripts')

</body> 

</html> 


