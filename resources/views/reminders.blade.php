@extends('layouts.app')

@section('title', 'Nhắc nhở – WorkPlan')

@section('content')
<!--Danh sách nhắc nhở đã thiết lập-->
<h2 class="text-2xl font-bold text-blue-700 mb-4">🔔 Danh sách nhắc nhở đã thiết lập</h2>

@if($thongBaos->isEmpty())
<div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow-md">
  <p>Chưa có nhắc nhở nào được thiết lập.</p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
  @foreach($thongBaos as $tb)
  <div class="bg-white border-l-4 border-blue-500 shadow p-4 rounded-lg">
    <div class="mb-2">
      <p class="text-base font-semibold text-gray-800">
        ⏰ {{ $tb->mucCongViec->TEN_MUC ?? 'Không xác định' }}
      </p>
      <p class="text-sm text-gray-600">
        Thông báo lúc: {{ \Carbon\Carbon::parse($tb->THOI_DIEM_THONG_BAO)->format('d/m/Y H:i') }}
      </p>
    </div>

    <div class="flex justify-end space-x-3">
      <!-- Button Sửa -->
      <button onclick="openEditForm('{{ $tb->ID_CAUHINH }}', '{{ $tb->THOI_DIEM_THONG_BAO }}')"
        class="text-blue-600 hover:text-blue-800 text-sm font-medium">🛠 Sửa</button>

      <!-- Form Xóa -->
      <form action="{{ route('reminders.delete', $tb->ID_CAUHINH) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá nhắc nhở này?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">🗑 Xoá</button>
      </form>
    </div>
  </div>
  @endforeach
</div>
@endif

<!--<P>Danh sách kế hoạch - công việc</P>-->
{{-- Header --}}
<h2 class="text-2xl font-bold text-blue-700 mb-6">📋 Danh sách kế hoạch - công việc</h2>

{{-- Dropdown chọn kế hoạch --}}
<div class="mb-8 flex items-center space-x-4">
  <label for="select-ke-hoach" class="text-sm font-semibold text-gray-700 whitespace-nowrap"> Chọn kế hoạch:</label>
  <div class="relative w-full max-w-xs">
    <select id="select-ke-hoach" onchange="filterKeHoach()"
      class="appearance-none w-full bg-white border border-gray-300 text-gray-800 text-sm rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 px-4 py-2 pr-10">
      <option value="">-- --</option>
      @foreach ($reminders as $reminder)
      <option value="kh-{{ $reminder->ID_KH }}">{{ $reminder->TEN_KE_HOACH }}</option>
      @endforeach
    </select>
    <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
      <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>
  </div>
</div>

{{-- Danh sách kế hoạch --}}
@foreach ($reminders as $reminder)
<div id="kh-{{ $reminder->ID_KH }}" class="kehoach-block space-y-4 mb-10 hidden">
  <div class="w-full max-w-7xl mx-auto rounded-lg shadow border border-gray-200 overflow-hidden bg-white">
    {{-- Tên kế hoạch --}}
    <div class="bg-indigo-600 text-white px-6 py-3 rounded-t-lg shadow text-xl font-bold tracking-wide">
      <h3 class="text-xl font-bold tracking-wide">
        {{ $reminder->TEN_KE_HOACH }}
      </h3>
    </div>

    {{-- Nội dung kế hoạch --}}
    <div class="bg-white border border-gray-200 rounded-b-xl p-6 shadow space-y-6">

      @foreach ($reminder->congviecs as $congviec)
      <div class="border border-blue-100 rounded-lg bg-blue-50 p-4 shadow-inner space-y-2">
        <h4 class="text-lg font-semibold text-blue-800"> {{ $congviec->TEN_CV }}</h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="text-gray-700">
            <strong>Tiến độ:</strong> {{ $congviec['TIEN_DO'] }} %
          </div>
          <div class="text-gray-700">
            <strong>Ưu tiên:</strong> {{ $congviec['DO_UU_TIEN'] }}
          </div>
        </div>

        {{-- Các mục công việc --}}
        @foreach ($congviec->mucCongViecs as $muc)
        <div class="bg-white border border-gray-200 rounded p-4 shadow-sm mt-3 flex justify-between items-start">
          <div>
            <p class="font-semibold text-gray-800">{{ $muc->TEN_MUC }}</p>
            <p class="text-sm text-gray-600">{{ $muc->NOI_DUNG_CHI_TIET }}</p>
            <p class="text-xs text-gray-500 mt-1">
              📅 <strong>Hạn:</strong>
              @if ($muc->THOI_HAN_HOAN_THANH)
              {{ $muc->THOI_HAN_HOAN_THANH->format('d/m/Y H:i') }}
              @else
              <em>Chưa cập nhật</em>
              @endif
            </p>
          </div>
          <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs shadow transition"
            onclick="openReminderForm('{{ $muc->ID_MUC }}')">
            ⏰ Nhắc nhở
          </button>
        </div>
        @endforeach

      </div>
      @endforeach
    </div>
  </div>
</div>
@endforeach

{{-- Modal tạo nhắc nhở --}}
<div id="reminder-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-8 rounded-xl shadow-lg w-11/12 max-w-5xl transition-all duration-300">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">📅 Thiết lập thông báo</h2>
    <form method="POST" action="{{ route('reminders.set') }}">
      @csrf
      <input type="hidden" name="id_muc" id="modal-id-muc">
      <label class="block mb-2 text-sm">Thời gian thông báo</label>
      <input type="datetime-local" name="thoi_gian" required class="border rounded w-full px-3 py-2 mb-4">
      <div class="text-right">
        <button type="button" onclick="closeReminderForm()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- Thông báo khi tạo nhắc nhở--}}
@if (session('success'))
@php $reminder = session('success'); @endphp
<div id="reminder-notice" class="fixed bottom-6 left-6 bg-gray-800 text-white border-l-4 border-blue-500 shadow-xl p-4 w-full max-w-sm rounded z-50">
  <div class="flex justify-between items-start">
    <div>
      <h3 class="text-white text-lg font-semibold mb-1">🔔 Tạo nhắc nhở thành công</h3>
      <p class="text-sm mb-1">
        <strong>Tên mục công việc:</strong> {{ $reminder->mucCongViec->TEN_MUC ?? 'Chưa có tên mục' }}
      </p>
      <p class="text-sm">
        <strong>Thời gian nhắc:</strong>
        {{ \Carbon\Carbon::parse($reminder['THOI_DIEM_THONG_BAO'])->format('d/m/Y H:i') }}
      </p>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-gray-300 hover:text-white text-xl leading-none">&times;</button>
  </div>
</div>
@endif
{{-- Thông báo khi xóa nhắc nhở--}}
@if (session('success_delete'))
@php $reminder_tb = session('success_delete'); @endphp
<div id="reminder-notice" class="fixed bottom-6 left-6 bg-gray-800 text-white border-l-4 border-blue-500 shadow-xl p-4 w-full max-w-sm rounded z-50">
  <div class="flex justify-between items-start">
    <div>
      <h3 class="text-white text-lg font-semibold mb-1">🔔 Đã xóa nhắc nhở </h3>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-gray-300 hover:text-white text-xl leading-none">&times;</button>
  </div>
</div>
@endif
{{-- Thông báo khi sửa nhắc nhở--}}
@if (session('success_update'))
@php $reminder_tb = session('success_update'); @endphp
<div id="reminder-notice" class="fixed bottom-6 left-6 bg-gray-800 text-white border-l-4 border-blue-500 shadow-xl p-4 w-full max-w-sm rounded z-50">
  <div class="flex justify-between items-start">
    <div>
      <h3 class="text-white text-lg font-semibold mb-1">🔔 Sửa nhắc nhở thành công</h3>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-gray-300 hover:text-white text-xl leading-none">&times;</button>
  </div>
</div>
@endif



<script>
  function filterKeHoach() {
    const selected = document.getElementById('select-ke-hoach').value;
    const blocks = document.querySelectorAll('.kehoach-block');

    blocks.forEach(block => {
      block.style.display = (block.id === selected) ? 'block' : 'none';
    });
  }

  // Khi load trang, ẩn hết block kế hoạch
  window.addEventListener('DOMContentLoaded', () => {
    const blocks = document.querySelectorAll('.kehoach-block');
    blocks.forEach(block => block.style.display = 'none');
  });
</script>

{{-- JavaScript --}}
<script>
  function openReminderForm(idMuc) {
    document.getElementById('modal-id-muc').value = idMuc;
    document.getElementById('reminder-modal').classList.remove('hidden');
    document.getElementById('reminder-modal').classList.add('flex');
  }

  function closeReminderForm() {
    document.getElementById('reminder-modal').classList.add('hidden');
    document.getElementById('reminder-modal').classList.remove('flex');
  }
</script>
<script>
  function closeReminderNotice() {
    const box = document.getElementById('reminder-notice');
    if (box) {
      box.style.transition = 'opacity 0.5s ease';
      box.style.opacity = 0;
      setTimeout(() => box.remove(), 500);
    }
  }

  window.addEventListener('DOMContentLoaded', () => {
    const notice = document.getElementById('reminder-notice');
    if (notice) {
      // Delay 300ms để chắc chắn DOM đã render
      setTimeout(() => {
        notice.classList.remove('opacity-0');
        notice.classList.add('opacity-100');
      }, 300);

      // Tự động ẩn sau 10 giây
      setTimeout(() => {
        closeReminderNotice();
      }, 10000);
    }
  });
</script>
<script>
  function filterKeHoach() {
    const selected = document.getElementById('select-ke-hoach').value;
    const blocks = document.querySelectorAll('.kehoach-block');
    blocks.forEach(block => {
      block.style.display = (selected === 'all' || block.id === selected) ? 'block' : 'none';
    });
  }
</script>

<!-- Form chỉnh sửa -->
<div id="edit-form-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-3xl">
    <h2 class="text-lg font-bold mb-4">Chỉnh sửa nhắc nhở</h2>
    <form id="edit-form" method="POST">
      @csrf
      @method('PATCH') <!-- hoặc PATCH nếu bạn dùng -->

      <label class="block mb-2 text-sm">Thời gian thông báo mới</label>
      <input type="datetime-local" name="thoi_gian" id="edit-thoi-gian" required class="border rounded w-full px-3 py-2 mb-4">

      <div class="text-right">
        <button type="button" onclick="closeEditForm()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openEditForm(id, thoiGian) {
    const form = document.getElementById('edit-form');
    const input = document.getElementById('edit-thoi-gian');
    const modal = document.getElementById('edit-form-modal');

    // Gán giá trị thời gian cũ
    input.value = thoiGian.replace(' ', 'T');

    // Gán action đúng route với id
    form.action = '/reminders/update/' + id;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeEditForm() {
    const modal = document.getElementById('edit-form-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>

<!-- Âm thanh nhắc nhở -->
<audio id="reminder-sound" src="/sounds/notificationx3_reminders.mp3" preload="auto"></audio>

<!-- Container để hiện popup -->
<div id="toast-container" class="fixed top-5 right-5 space-y-4 z-50"></div>

<script>
  const reminders = @json($reminderData);
</script>

<style>
  .toast {
    animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-in 4.5s forwards;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(100%);
    }

    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes fadeOut {
    to {
      opacity: 0;
      transform: translateX(100%);
    }
  }
</style>

<script>
  const sound = document.getElementById('reminder-sound');
  const toastContainer = document.getElementById('toast-container');
  const notified = new Set();

  function showReminderToast(reminder) {
    const toast = document.createElement('div');
    toast.className = "toast bg-blue-600 text-white px-4 py-3 rounded shadow w-80";
    toast.innerHTML = `
      <p class="font-semibold">🔔 Nhắc nhở</p>
      <p>${reminder.noi_dung}</p>
      <p class="text-sm opacity-80 mt-1">${new Date(reminder.thoidiem_thongbao).toLocaleString()}</p>
    `;

    toastContainer.appendChild(toast);

    // Tự ẩn sau 16s
    setTimeout(() => {
      toast.remove();
    }, 16000);
  }

  function checkReminders() {
    const now = new Date();

    reminders.forEach(reminder => {
      const notifyTime = new Date(reminder.thoidiem_thongbao);
      const deadline = reminder.thoihan_hoanthanh ? new Date(reminder.thoihan_hoanthanh) : null;

      if (notified.has(reminder.id)) return;

      const diff = Math.abs(now - notifyTime); // chênh lệch mili giây
      // Chỉ thông báo khi đúng thời điểm (trong khoảng 30s), và chưa quá hạn nếu có deadline
      if (diff <= 30000 && (!deadline || now < deadline)) {
        // Báo
        showReminderToast(reminder);
        sound.play();
        notified.add(reminder.id);
      }
    });
  }

  // Kiểm tra mỗi 30 giây
  setInterval(checkReminders, 30000);
  window.addEventListener('load', checkReminders);
</script>

@endsection