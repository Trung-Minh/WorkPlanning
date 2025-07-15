{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Tài Khoản – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
<main class="w-full pt-2 mx-auto mt-1 sm:w-3/4 md:w-5/10 lg:w-4/5">
    <div class="relative w-full">
        {{-- Ảnh bìa --}}
        <form id="anhBiaForm" action="{{ route('profile.upload_anhbia') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="anhBiaInput" style="cursor: pointer;">
            <img
            src="{{ asset('uploads/' . ($user->ANH_BIA ?? 'anhbia.jpg')) }}"
            alt="Ảnh bìa"
            class="object-cover w-full h-48"
            />
        </label>
        <input type="file" name="anh_bia" id="anhBiaInput" accept="image/*" class="hidden">
        </form>

        <script>
        document.getElementById('anhBiaInput')?.addEventListener('change', function () {
            document.getElementById('anhBiaForm')?.submit();
        });
        </script>

        {{-- Avatar --}}
        <div class="absolute -translate-x-1/2 left-1/2 -bottom-12">
        <form id="avatarForm" action="{{ route('profile.upload_avatar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="avatarInput" style="cursor: pointer;">
            <img
                src="{{ asset('uploads/' . ($user->AVATAR ?? 'avt.png')) }}"
                alt="Avatar"
                class="border-4 border-white rounded-full shadow-md w-30 h-30"
            />
            </label>
            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden">
        </form>
        <script>
            document.getElementById('avatarInput')?.addEventListener('change', function () {
            document.getElementById('avatarForm')?.submit();
            });
        </script>
        </div>
    </div>

    {{-- Thông tin người dùng --}}
    <div class="w-3/5 mx-auto mt-16 text-center">
        <h2 class="inline-flex items-center justify-center gap-2 text-3xl font-bold">
        {{ $user->HO_TEN }}
        <a href="javascript:void(0)" id="openEditModal" class="hover:text-blue-600" title="Chỉnh sửa thông tin">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-500 w-7 h-7 hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
        </a>
        </h2>

        @if (!empty($user->MO_TA))
            <p class="text-xl text-gray-600">Mô tả: {{ $user->MO_TA }}</p>
        @endif
        <p class="text-xl text-gray-600">Email: {{ $user->EMAIL }}</p>
        <p class="text-xl text-gray-600">Ngày sinh: {{ $user->NGAY_SINH }}</p>
        <p class="text-xl text-gray-600">Giới tính: {{ $user->GIOI_TINH }}</p>

        <br>
        <a href="{{ route('logout') }}" class="text-xl text-red-500">Đăng xuất</a>
    </div>
</main>

    {{-- Modal chỉnh sửa --}}
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden backdrop-blur-sm bg-white/30">
    <div class="relative w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h3 class="mb-4 text-xl font-semibold">Chỉnh sửa thông tin</h3>
        <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium">Họ tên</label>
            <input type="text" name="ho_ten" value="{{ $user->HO_TEN }}" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Mô tả</label>
            <textarea name="mo_ta" class="w-full p-2 border rounded">{{ $user->MO_TA }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ $user->EMAIL }}" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Ngày sinh</label>
            <input type="date" name="ngay_sinh" value="{{ $user->NGAY_SINH }}" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Giới tính</label>
            <select name="gioi_tinh" class="w-full p-2 border rounded">
            <option value="Nam" {{ $user->GIOI_TINH == 'Nam' ? 'selected' : '' }}>Nam</option>
            <option value="Nữ" {{ $user->GIOI_TINH == 'Nữ' ? 'selected' : '' }}>Nữ</option>
            <option value="Khác" {{ $user->GIOI_TINH == 'Khác' ? 'selected' : '' }}>Khác</option>
            </select>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Hủy</button>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Lưu</button>
        </div>
        </form>
        <button id="closeModalX" class="absolute text-2xl text-gray-600 top-2 right-2 hover:text-black">&times;</button>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('openEditModal');
        const modal = document.getElementById('editModal');
        const closeBtn = document.getElementById('closeModal');
        const closeX = document.getElementById('closeModalX');

        openBtn?.addEventListener('click', () => {
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
        });

        closeBtn?.addEventListener('click', () => {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        });

        closeX?.addEventListener('click', () => {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
        });

        window.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        });
    });
</script>
@endsection
