{{-- resources/views/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng ký – WorkPlan')

@section('content')
  <main class="flex flex-col items-center">
    <div class="w-full max-w-md bg-white shadow-md rounded p-4">
    <h1 class="text-center text-blue-600 text-2xl font-bold flex items-center justify-center gap-2 mb-6">Đăng ký</h1>
    <form method="POST" action="{{ url('/register') }}" class="space-y-6">
      @csrf
      {{-- Họ tên --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="ho_ten" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Họ tên</label>
      <input id="ho_ten" name="ho_ten" type="text" value="{{ old('ho_ten') }}" placeholder="Username" required
        class="w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Email --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="email" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email" required
        class="w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Mật khẩu --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="mat_khau" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Mật khẩu</label>
      <input id="mat_khau" name="mat_khau" type="password" placeholder="Password" required
        class="w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      @error('mat_khau')
      <p class="text-red-600 text-sm">{{ $message }}</p>
    @enderror

      {{-- Nhập lại mật khẩu --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="mat_khau_confirmation" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Nhập lại mật khẩu</label>
      <input id="mat_khau_confirmation" name="mat_khau_confirmation" type="password" placeholder="Confirm Password"
        required class="w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Ngày sinh --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="ngay_sinh" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Ngày sinh</label>
      <input id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh') }}" type="date"
        class="w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Giới tính --}}
      <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
      <label for="gioi_tinh" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Giới tính</label>
      <select id="gioi_tinh" name="gioi_tinh" class="w-full sm:flex-1 border rounded px-3 py-2">
        <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
        <option value="Nữ" {{ old('gioi_tinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
        <option value="Khác" {{ old('gioi_tinh') == 'Khác' ? 'selected' : '' }}>Khác</option>
      </select>
      </div>

      {{-- Nút Đăng ký --}}
      <div class="pt-4">
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
        Đăng ký
      </button>
      </div>
    </form>

    {{-- Flash messages --}}
    @if(session('success'))
    <p class="mt-4 text-center text-green-600">{{ session('success') }}</p>
    @elseif(session('error'))
    <p class="mt-4 text-center text-red-600">{{ session('error') }}</p>
    @endif

    </div>
  </main>
@endsection

@php
  $noFooter = true;
@endphp