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
<div class="grid gap-4">
  @foreach($thongBaos as $tb)
  <div class="bg-white border-l-4 border-blue-500 shadow-md p-4 rounded-lg flex justify-between items-center">
    <div>
      <p class="text-lg font-semibold text-gray-800">
        {{ $tb->mucCongViec->TEN_MUC ?? 'Không xác định' }}
      </p>
      <p class="text-sm text-gray-600">
        Nhắc lúc: {{ \Carbon\Carbon::parse($tb->THOI_DIEM_THONG_BAO)->format('d/m/Y H:i') }}
      </p>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 8 7.388 8 9v5.159c0 .538-.214 1.055-.595 1.436L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>
  </div>
  @endforeach
</div>
@endif
<!--<P>=============================================================</P>-->
<h2 class="text-2xl font-bold text-blue-700 mb-4">🔔 Danh sách kế hoạch - công việc</h2>

{{-- Dropdown chọn kế hoạch --}}
<label for="select-ke-hoach" class="block mb-2 text-sm font-medium text-gray-700">Chọn kế hoạch:</label>
<select id="select-ke-hoach" onchange="filterKeHoach()" class="mb-6 p-2 border border-gray-300 rounded">
  <option value="">-- Chọn kế hoạch để hiển thị --</option>
  @foreach ($reminders as $reminder)
  <option value="kh-{{ $reminder->ID_KH }}">{{ $reminder->TEN_KE_HOACH }}</option>
  @endforeach
</select>
{{-- Vòng lặp kế hoạch --}}
@foreach ($reminders as $reminder)
<div id="kh-{{ $reminder->ID_KH }}" class="kehoach-block mb-6" style="display: none;">
  <span class="bg-[#4f46e5]/60 text-white px-4 py-2 rounded shadow inline-block mb-2">
    <strong class="text-white text-xl font-bold tracking-wide">
      {{ $reminder->TEN_KE_HOACH }}
    </strong>
  </span>

  <div class="bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-6 text-white max-w-7xl mb-8" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7)">
    @foreach ($reminder->congviecs as $congviec)
    <p class="text-lg font-semibold text-gray-100">
      📁 {{ $congviec->TEN_CV }}
    </p>
    <div class="bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-4 text-white mt-2 mb-6" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5)">
      🕓 <strong>Tiến độ:</strong> {{ $congviec['TIEN_DO'] }} <br>
      🎯 <strong>Ưu tiên:</strong> {{ $congviec['DO_UU_TIEN'] }}
    </div>

    @foreach ($congviec->mucCongViecs as $muc)
    <div class="mb-4 border border-gray-300 p-4 rounded bg-gray-50 text-gray-800">
      <div class="flex justify-between items-center">
        <div>
          <p class="font-semibold">{{ $muc->TEN_MUC }}</p>
          <p class="text-sm text-gray-600">{{ $muc->NOI_DUNG_CHI_TIET }}</p>
          <p class="text-sm text-gray-500 mt-1">
            📅 <strong>Hạn hoàn thành:</strong>
            @if ($muc->THOI_HAN_HOAN_THANH)
            {{ $muc->THOI_HAN_HOAN_THANH->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
            @else
            <em>Chưa cập nhật</em>
            @endif
          </p>
        </div>
        <button
          class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded text-sm shadow"
          onclick="openReminderForm('{{ $muc->ID_MUC }}')">
          ⏰ Thiết lập nhắc nhở
        </button>
      </div>
    </div>
    @endforeach
    @endforeach
  </div>
</div>
@endforeach

{{-- Modal tạo nhắc nhở --}}
<div id="reminder-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-lg font-bold mb-4">Thiết lập thông báo</h2>
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

{{-- Thông báo khi tạo nhắc nhở thành công --}}
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

<!--Form popup - thiet lap nhac nho-->
<div id="reminder-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-lg font-bold mb-4">Thiết lập thông báo</h2>
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
@endsection