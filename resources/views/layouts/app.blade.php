{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
   <script>
    (function () {
        try {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error('Dark mode script error:', e);
        }
    })();
</script>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'WorkPlan')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/plans.js', 'resources/js/header.js', 'resources/js/footer.js', 'resources/js/main.js'])

</head>

<body class="flex flex-col min-h-screen bg-gray-100">
  @include('partials.header')

   <main class="flex-1 w-full px-4 py-4 mx-auto">
    @yield('content')
  </main> 

   @if(empty($noFooter) && !(request()->is('reminders')))
  @include('partials.footer')
  @endif

  {{-- Phần này bạn giữ lại để toast + âm thanh hoạt động xuyên trang --}}
  <audio id="reminder-sound" src="{{ asset('sounds/notificationx3_reminders.mp3') }}" preload="auto"></audio>
  <div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2"></div>

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
  </script>

  {{-- Để các script cụ thể của từng trang (nếu có) --}}
  @stack('scripts') 







  
</body>

</html>