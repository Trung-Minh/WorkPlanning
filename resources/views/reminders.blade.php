@extends('layouts.app')
@php use Carbon\Carbon; @endphp
@section('title', 'Nhắc nhở – WorkPlan')

@section('content')
<h2 class=" mb-4 text-2xl font-bold text-blue-700">🔔 Danh sách nhắc nhở đã thiết lập</h2>

@if($thongBaos->isEmpty())
<div class="p-4 text-yellow-800 bg-yellow-100 rounded shadow-md">
  <p>Chưa có nhắc nhở nào được thiết lập.</p>
</div>
@else
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
  @foreach($thongBaos as $tb)
  <div
    class="p-4 border-l-4 rounded-lg shadow reminder-item"
    data-thoidiem="{{ $tb->THOI_DIEM_THONG_BAO }}">
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
        class="text-sm font-medium text-blue-600 hover:text-blue-800"
        style="cursor: pointer;">🛠 Sửa</button>

      <!-- Form Xóa -->
      <form action="{{ route('reminders.delete', $tb->ID_CAUHINH) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá nhắc nhở này?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800"
          style="cursor: pointer;">🗑 Xoá</button>
      </form>
    </div>
  </div>
  @endforeach
</div>
@endif

<!--<P>Danh sách kế hoạch - công việc</P>-->
{{-- Header --}}
<h2 class="mb-6 text-2xl font-bold text-blue-700">📋 Danh sách kế hoạch - công việc</h2>

{{-- Dropdown chọn kế hoạch --}}
<div class="flex items-center mb-8 space-x-4">
  <label for="select-ke-hoach" class="a text-sm font-semibold text-gray-700 whitespace-nowrap"> Chọn kế hoạch:</label>
  <div class="relative w-full max-w-xs">
    <select id="select-ke-hoach" onchange="filterKeHoach()"
      class="w-full px-4 py-2 pr-10 text-sm text-gray-800 bg-white border border-gray-300 rounded-lg shadow-sm appearance-none cursor-pointer focus:ring-2 focus:ring-blue-500">
      <option value="">-- Ẩn kế hoạch --</option>

      <optgroup label="👤 Kế hoạch cá nhân">
        @foreach ($keHoachCaNhan as $kehoach)
        <option value="kh-{{ $kehoach->ID_KH }}">{{ $kehoach->TEN_KE_HOACH }}</option>
        @endforeach
      </optgroup>

      <optgroup label="👥 Kế hoạch nhóm">
        @foreach ($keHoachNhom as $kehoach)
        <option value="kh-{{ $kehoach->ID_KH }}">{{ $kehoach->TEN_KE_HOACH }}</option>
        @endforeach
      </optgroup>
    </select>
    <div class="absolute inset-y-0 flex items-center pointer-events-none right-2">
      <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>
  </div>
</div>

{{-- Danh sách kế hoạch --}}
@foreach (['keHoachCaNhan' => $keHoachCaNhan, 'keHoachNhom' => $keHoachNhom] as $type => $keHoachGroup)
@if (count($keHoachGroup) > 0)
    @if ($type === 'keHoachCaNhan')
      <h2 id="title-kehoach-canhan" class="mb-4 text-xl font-semibold text-indigo-700 hidden">👤 Kế hoạch cá nhân</h2>
    @else
      <h2 id="title-kehoach-nhom" class="mb-4 text-xl font-semibold text-green-700 hidden">👥 Kế hoạch nhóm</h2>
    @endif
  @foreach ($keHoachGroup as $reminder)
    <div id="kh-{{ $reminder->ID_KH }}" class="hidden mb-10 space-y-4 kehoach-block">
      <div class="w-full mx-auto overflow-hidden bg-white border border-gray-200 rounded-lg shadow max-w-7xl">
        {{-- Tên kế hoạch --}}
        <div class="px-6 py-3 text-xl font-bold tracking-wide text-white bg-indigo-600 rounded-t-lg shadow">
          <h3 class="text-xl font-bold tracking-wide">
            {{ $reminder->TEN_KE_HOACH }}
          </h3>
        </div>

        {{-- Nội dung kế hoạch --}}
        <div class="p-6 space-y-6 bg-white border border-gray-200 shadow rounded-b-xl">

          @foreach ($reminder->congviecs as $congviec)
          <div class="p-4 space-y-2 border border-blue-100 rounded-lg shadow-inner bg-blue-50">
            <h4 class="text-lg font-semibold text-blue-800"> {{ $congviec->TEN_CV }}</h4>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="text-gray-700">
                <strong>Tiến độ:</strong> {{ $congviec['TIEN_DO'] }} %
              </div>
              <div class="text-gray-700">
                <strong>Ưu tiên:</strong> {{ $congviec['DO_UU_TIEN'] }}
              </div>
            </div>

            {{-- Các mục công việc --}}
            @foreach ($congviec->mucCongViecs as $muc)
            <div class="flex items-start justify-between p-4 mt-3 bg-white border border-gray-200 rounded shadow-sm">
              <div>
                <p class="font-semibold text-gray-800">{{ $muc->TEN_MUC }}</p>
                <p class="text-sm text-gray-600">{{ $muc->NOI_DUNG_CHI_TIET }}</p>
                <p class="mt-1 text-xs text-gray-500">
                  📅 <strong>Hạn:</strong>
                  @if ($muc->THOI_HAN_HOAN_THANH)
                    {{ ($thoi_gian_het_han = $muc->THOI_HAN_HOAN_THANH) ? $thoi_gian_het_han->format('d/m/Y H:i') : '' }}
                  @else
                    <em>Chưa cập nhật</em>
                  @endif
                </p>
              </div>
              <button
                class="px-3 py-2 text-xs text-white transition bg-blue-600 rounded shadow cursor-pointer hover:bg-blue-700"
                onclick="openReminderForm('{{ $muc->ID_MUC }}', '{{ optional($muc->THOI_HAN_HOAN_THANH)->format('Y-m-d\TH:i') }}')">
                Nhắc nhở
              </button>
            </div>
            @endforeach

          </div>
          @endforeach
        </div>
      </div>
    </div>
  @endforeach
  @endif
@endforeach

<script>
  const selectBox = document.getElementById('select-kehoach');
  const titleCaNhan = document.getElementById('title-kehoach-canhan');
  const titleNhom = document.getElementById('title-kehoach-nhom');

  selectBox.addEventListener('change', function () {
    const selected = this.value;
    const type = this.options[this.selectedIndex].dataset.type;

    // Ẩn hết các block kế hoạch
    document.querySelectorAll('.kehoach-block').forEach(div => div.classList.add('hidden'));

    // Ẩn cả 2 tiêu đề
    titleCaNhan.classList.add('hidden');
    titleNhom.classList.add('hidden');

    if (selected) {
      // Hiện block kế hoạch được chọn
      const block = document.getElementById(selected);
      if (block) block.classList.remove('hidden');

      // Hiện đúng tiêu đề theo loại
      if (type === 'canhan') {
        titleCaNhan.classList.remove('hidden');
      } else if (type === 'nhom') {
        titleNhom.classList.remove('hidden');
      }
    }
  });
</script>

{{-- Modal tạo nhắc nhở --}}
<div id="reminder-modal" class="fixed inset-0 z-50 items-center justify-center hidden">
  <div class="w-11/12 max-w-5xl p-8 transition-all duration-300 bg-white shadow-lg rounded-xl">
    <h2 class="pb-2 mb-6 text-2xl font-semibold text-gray-800 border-b">📅 Thiết lập thông báo</h2>
    <form id="reminder-tltb" method="POST" action="{{ route('reminders.set') }}">
      @csrf
      <input type="hidden" name="id_muc" id="modal-id-muc">
      <label class="block mb-2 text-sm">Thời gian thông báo</label>
      <input type="datetime-local" name="thoi_gian" required class="w-full px-3 py-2 mb-4 border rounded cursor-pointer">
      <p id="thoi-gian-error" class="hidden mb-3 text-sm text-red-600"></p>
      <div class="text-right">
        <button type="button" onclick="closeReminderForm()" class="px-4 py-2 mr-2 bg-gray-300 rounded" style="cursor: pointer;">Hủy</button>
        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded" style="cursor: pointer;">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- Thông báo khi tạo nhắc nhở--}}
@if (session('success'))
@php $reminder = session('success'); @endphp
<div id="reminder-notice" class="fixed z-50 w-full max-w-sm p-4 text-white bg-gray-800 border-l-4 border-blue-500 rounded shadow-xl bottom-6 left-6">
  <div class="flex items-start justify-between">
    <div>
      <h3 class="mb-1 text-lg font-semibold text-white">🔔 Tạo nhắc nhở thành công</h3>
      <p class="mb-1 text-sm">
        <strong>Tên mục công việc:</strong> {{ $reminder->mucCongViec->TEN_MUC ?? 'Chưa có tên mục' }}
      </p>
      <p class="text-sm">
        <strong>Thời gian nhắc:</strong>
        {{ \Carbon\Carbon::parse($reminder['THOI_DIEM_THONG_BAO'])->format('d/m/Y H:i') }}
      </p>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-xl leading-none text-gray-300 hover:text-white" style="cursor: pointer;">&times;</button>
  </div>
</div>
@endif
{{-- Thông báo khi xóa nhắc nhở--}}
@if (session('success_delete'))
@php $reminder_tb = session('success_delete'); @endphp
<div id="reminder-notice" class="fixed z-50 w-full max-w-sm p-4 text-white bg-gray-800 border-l-4 border-blue-500 rounded shadow-xl bottom-6 left-6">
  <div class="flex items-start justify-between">
    <div>
      <h3 class="mb-1 text-lg font-semibold text-white">🔔 Đã xóa nhắc nhở </h3>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-xl leading-none text-gray-300 hover:text-white" style="cursor: pointer;">&times;</button>
  </div>
</div>
@endif
{{-- Thông báo khi sửa nhắc nhở--}}
@if (session('success_update'))
@php $reminder_tb = session('success_update'); @endphp
<div id="reminder-notice" class="fixed z-50 w-full max-w-sm p-4 text-white bg-gray-800 border-l-4 border-blue-500 rounded shadow-xl bottom-6 left-6">
  <div class="flex items-start justify-between">
    <div>
      <h3 class="mb-1 text-lg font-semibold text-white">🔔 Sửa nhắc nhở thành công</h3>
    </div>
    <button onclick="closeReminderNotice()" class="ml-4 text-xl leading-none text-gray-300 hover:text-white" style="cursor: pointer;">&times;</button>
  </div>
</div>
@endif



{{-- JavaScript - Form thiết lập thông báo --}}
<script>
  function openReminderForm(idMuc, thoiGianHetHan) {
    document.getElementById('modal-id-muc').value = idMuc;
    document.getElementById('reminder-modal').classList.remove('hidden');
    document.getElementById('reminder-modal').classList.add('flex');

    window.THOI_GIAN_HET_HAN = thoiGianHetHan ? new Date(thoiGianHetHan) : null;

    //Ẩn lỗi cũ khi mở lại form
    const errorElement = document.getElementById('thoi-gian-error');
    if (errorElement) {
      errorElement.classList.add('hidden');
      errorElement.textContent = '';
    }
  }

  function closeReminderForm() {
    document.getElementById('reminder-modal').classList.add('hidden');
    document.getElementById('reminder-modal').classList.remove('flex');
  }

  document.getElementById('reminder-tltb').addEventListener('submit', function(event) {
    const input = this.querySelector('input[name="thoi_gian"]');
    const value = new Date(input.value);
    const now = new Date();

    // Giới hạn trên từ biến PHP đưa vào
    const hetHan = window.THOI_GIAN_HET_HAN;

    // Tìm thẻ báo lỗi
    const errorElement = document.getElementById('thoi-gian-error');
    errorElement.classList.add('hidden'); // Ẩn lỗi trước nếu có

    if (value < now || value > hetHan) {
      event.preventDefault(); // Ngăn submit

      // Format lại ngày hết hạn
      const ngay = hetHan.getDate().toString().padStart(2, '0');
      const thang = (hetHan.getMonth() + 1).toString().padStart(2, '0');
      const nam = hetHan.getFullYear();
      const gio = hetHan.getHours().toString().padStart(2, '0');
      const phut = hetHan.getMinutes().toString().padStart(2, '0');
      const hetHanFormat = `${ngay}/${thang}/${nam} ${gio}:${phut}`;

      // Hiển thị lỗi dưới input
      errorElement.textContent = `Vui lòng chọn thời gian từ hiện tại đến trước hạn: ${hetHanFormat}`;
      errorElement.classList.remove('hidden');
    }
  });
  document.querySelector('input[name="thoi_gian"]').addEventListener('input', function() {
    document.getElementById('thoi-gian-error').classList.add('hidden');
  });
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
<div id="edit-form-modal" class="fixed inset-0 z-50 items-center justify-center hidden">
  <div class="w-full max-w-3xl p-8 bg-white rounded shadow-md">
    <h2 class="mb-4 text-lg font-bold">Chỉnh sửa nhắc nhở</h2>
    <form id="edit-form" method="POST">
      @csrf
      @method('PATCH') <!-- hoặc PATCH nếu bạn dùng -->

      <label class="block mb-2 text-sm">Thời gian thông báo mới</label>
      <input type="datetime-local" name="thoi_gian" id="edit-thoi-gian" required class="w-full px-3 py-2 mb-4 border rounded cursor-pointer">

      <div class="text-right">
        <button type="button" onclick="closeEditForm()" class="px-4 py-2 mr-2 bg-gray-300 rounded" style="cursor: pointer;">Hủy</button>
        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded" style="cursor: pointer;">Lưu</button>
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

    // Gọi API lấy hạn hoàn thành
    fetch('/reminders/deadline/' + id)
      .then(response => response.json())
      .then(data => {
        window.hetHanEdit = data.deadline ? new Date(data.deadline) : null;
      });

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    //Ẩn lỗi cũ khi mở lại form
    const errorElement = document.getElementById('thoi-gian-error-csnn');
    if (errorElement) {
      errorElement.classList.add('hidden');
      errorElement.textContent = '';
    }
  }

  function closeEditForm() {
    const modal = document.getElementById('edit-form-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  document.getElementById('edit-form').addEventListener('submit', function(event) {
    const input = this.querySelector('input[name="thoi_gian"]');
    const value = new Date(input.value);
    const now = new Date();
    const hetHan = window.hetHanEdit;


    // Tìm thẻ báo lỗi
    const errorElement = document.getElementById('thoi-gian-error-csnn') || createErrorElement(input);
    errorElement.classList.add('hidden'); // Ẩn lỗi trước nếu có

    if (value < now || value > hetHan) {
      event.preventDefault(); // Ngăn submit

      // Format lại ngày hết hạn
      const hetHanFormat = hetHan ?
        `${hetHan.getDate().toString().padStart(2, '0')}/${(hetHan.getMonth() + 1).toString().padStart(2, '0')}/${hetHan.getFullYear()} ${hetHan.getHours().toString().padStart(2, '0')}:${hetHan.getMinutes().toString().padStart(2, '0')}` :
        'không xác định';

      // Hiển thị lỗi dưới input
      errorElement.textContent = `Vui lòng chọn thời gian từ hiện tại đến trước hạn: ${hetHanFormat}`;
      errorElement.classList.remove('hidden');
    }
  });
  document.querySelector('input[name="thoi_gian"]').addEventListener('input', function() {
    document.getElementById('thoi-gian-error-csnn').classList.add('hidden');
  });

  function createErrorElement(afterInput) {
    const p = document.createElement('p');
    p.id = 'thoi-gian-error-csnn';
    p.className = 'text-sm text-red-600 mb-3';
    afterInput.parentNode.insertBefore(p, afterInput.nextSibling);
    return p;
  }
</script>

<!-- Âm thanh nhắc nhở -->
<audio id="reminder-sound" src="/sounds/notificationx3_reminders.mp3" preload="auto"></audio>

<!-- Container để hiện popup -->
<div id="toast-container" class="fixed z-50 space-y-4 top-5 right-5"></div>



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
  document.addEventListener('DOMContentLoaded', () => {
    const reminderElements = document.querySelectorAll('.reminder-item');
    const now = new Date();

    reminderElements.forEach(el => {
      const thoidiemStr = el.getAttribute('data-thoidiem');
      if (!thoidiemStr) return;

      const thoidiem = new Date(thoidiemStr);

      // Nếu thời điểm thông báo đã qua thì đổi màu nền
      if (thoidiem < now) {
        el.classList.add('bg-red-50', 'border-red-500');
        el.classList.remove('bg-white', 'border-blue-500');
      } else {
        el.classList.add('bg-white', 'border-blue-500');
        el.classList.remove('bg-red-50', 'border-red-500');
      }
    });
  });
</script>

@endsection