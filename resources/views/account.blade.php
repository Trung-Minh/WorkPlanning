{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Tài Khoản – WorkPlan')
@php $user = session('user'); @endphp

@section('content')
  <main class="w-full sm:w-3/4 md:w-5/10 lg:w-4/5 mx-auto mt-1 pt-2 ">
   <div class="relative w-full">
  <!-- Ảnh bìa -->
  <!-- <img
    src="uploads\anhbia.jpg"
    alt="Ảnh bìa"
    class="w-full h-48 object-cover"
  /> -->
  <form id="anhBiaForm" action="{{ route('profile.upload_anhbia') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <label for="anhBiaInput" style="cursor: pointer;">
    <img
      src="{{ asset('uploads/' . ($user->ANH_BIA ?? 'anhbia.jpg')) }}"
      alt="Ảnh bìa"
      class="w-full h-48 object-cover"
    />
  </label>
  <input type="file" name="anh_bia" id="anhBiaInput" accept="image/*" class="hidden">
  </form>

  <script>
    document.getElementById('anhBiaInput').addEventListener('change', function () {
      document.getElementById('anhBiaForm').submit();
    });
  </script>


  <!-- Avatar nổi lên -->
  <div class="absolute left-1/2 -translate-x-1/2 -bottom-12">
   <form id="avatarForm" action="{{ route('profile.upload_avatar') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <label for="avatarInput" style="cursor: pointer;">
    <img
      src="{{ asset('uploads/' . ($user->AVATAR ?? 'avt.png')) }}"
      alt="Avatar"
      class="w-30 h-30 rounded-full border-4 border-white shadow-md"
    />
  </label>
  <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden">
  </form>
    <script>
      document.getElementById('avatarInput').addEventListener('change', function () {
        document.getElementById('avatarForm').submit();
      });
    </script>
  </div>
</div>

<!-- Nội dung bên dưới -->
<div class="mt-16 text-center w-3/5 mx-auto">
   <h2 class="text-3xl font-bold inline-flex items-center justify-center gap-2">
  {{$user->HO_TEN }}
    <a href="javascript:void(0)" id="openEditModal" class="hover:text-blue-600" title="Chỉnh sửa thông tin">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-500 hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
      </svg>
    </a>

  </h2>

  @if (!empty($user->MO_TA))
  <p class="text-gray-600 text-xl">Mô tả: {{ $user->MO_TA }}</p>
  @endif  
  <p class="text-gray-600 text-xl">Email: {{ $user->EMAIL }}</p>
  <p class="text-gray-600 text-xl">Ngày sinh: {{ $user->NGAY_SINH }}</p>
  <p class="text-gray-600 text-xl">Giới tính: {{ $user->GIOI_TINH }}</p>
  <br>
  <a href="/logout" class="text-red-500 text-xl">  Đăng xuất</a>   
</div>
  </main>
<!-- Modal -->
<div id="editModal" class="fixed inset-0 backdrop-blur-sm bg-white/30 z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
    <h3 class="text-xl font-semibold mb-4">Chỉnh sửa thông tin</h3>
    <form action="{{ route('profile.update') }}" method="POST">
      @csrf
      <div class="mb-4">
        <label class="block text-sm font-medium">Họ tên</label>
        <input type="text" name="ho_ten" value="{{ $user->HO_TEN }}" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium">Mô tả</label>
        <textarea name="mo_ta" class="w-full border p-2 rounded">{{ $user->MO_TA }}</textarea>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ $user->EMAIL }}" class="w-full border p-2 rounded" required>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium">Ngày sinh</label>
        <input type="date" name="ngay_sinh" value="{{ $user->NGAY_SINH }}" class="w-full border p-2 rounded">
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium">Giới tính</label>
        <select name="gioi_tinh" class="w-full border p-2 rounded">
          <option value="Nam" {{ $user->GIOI_TINH == 'Nam' ? 'selected' : '' }}>Nam</option>
          <option value="Nữ" {{ $user->GIOI_TINH == 'Nữ' ? 'selected' : '' }}>Nữ</option>
          <option value="Khác" {{ $user->GIOI_TINH == 'Khác' ? 'selected' : '' }}>Khác</option>
        </select>
      </div>
      <div class="flex justify-end space-x-2">
        <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Hủy</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lưu</button>
      </div>
    </form>
    <button id="closeModalX" class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl">&times;</button>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('openEditModal');
    const modal = document.getElementById('editModal');
    const closeBtn = document.getElementById('closeModal');
    const closeX = document.getElementById('closeModalX');

    if (openBtn && modal) {
      openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      });
    }

    if (closeBtn && modal) {
      closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      });
    }

    if (closeX && modal) {
      closeX.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      });
    }

    // Click ngoài modal để đóng
    window.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    });
  });
</script>

@endsection
