{{-- resources/views/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng ký – WorkPlan')

@section('content')
  <main class="w-full sm:w-3/4 md:w-2/3 lg:w-3/5 mx-auto mt-1 pt-2 ">
  <div class="grid grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-2 bg-white shadow-md rounded p-6 rounded-3xl"> <!-- Parent container (flex) -->
  <!-- Column 1 -->
  <div class="hidden lg:hiden xl:block mx-auto my-auto px-4 py-4 rounded-lg">
    <img src="{{ asset('workplanning.png') }}" alt="Workplanning" class="rounded-lg">
  </div>  
  <div  class="px-4 py-6 2xl:px-8 lg:py-12" >
    <h1 class="text-center text-blue-600 text-3xl font-bold flex items-center justify-center gap-2 mb-6">Đăng ký</h1>
    <form method="POST" action="{{ url('/register') }}" class="space-y-6">
      @csrf
      {{-- Họ tên --}}
      <div class="md:flex">
      <label for="ho_ten" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Họ tên</label>
      <input id="ho_ten" name="ho_ten" type="text" value="{{ old('ho_ten') }}" placeholder="Username" required
        class="w-full md:w-2/3 w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Email --}}
      <div class="md:flex">
      <label for="email" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Email" required
        class="w-full md:w-2/3 w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Mật khẩu --}}
      <div class="md:flex">
      <label for="mat_khau" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Mật khẩu</label>
      <input id="mat_khau" name="mat_khau" type="password" placeholder="Password" required
        class="w-full md:w-2/3 w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      @error('mat_khau')
      <p class="">{{ $message }}</p>
    @enderror

      {{-- Nhập lại mật khẩu --}}
      <div class="md:flex">
      <label for="mat_khau_confirmation" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Nhập lại mật khẩu</label>
      <input id="mat_khau_confirmation" name="mat_khau_confirmation" type="password" placeholder="Confirm Password"
        required class="w-full md:w-2/3 w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Ngày sinh --}}
      <div class="md:flex">
      <label for="ngay_sinh" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Ngày sinh</label>
      <input id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh') }}" type="date"
        class="w-full md:w-2/3 w-full sm:flex-1 border rounded px-3 py-2" />
      </div>

      {{-- Giới tính --}}
      <div class="md:flex">
      <label for="gioi_tinh" class="sm:w-1/3 font-medium mb-1 sm:mb-0">Giới tính</label>
      <select id="gioi_tinh" name="gioi_tinh" class="w-full md:w-2/3 sm:flex-1 ml-auto border rounded px-3 py-2">
        <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
        <option value="Nữ" {{ old('gioi_tinh') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
        <option value="Khác" {{ old('gioi_tinh') == 'Khác' ? 'selected' : '' }}>Khác</option>
      </select>
      </div>

      {{-- Nút Đăng ký --}}
      <div class="pt-4">
      <button type="submit" class="w-full bg-blue-600 text-lg text-white py-2 rounded hover:bg-blue-700 transition">
        Đăng ký
      </button>
      </div>
    </form>
      <p class="text-center text-gray-600 flex items-center justify-center gap-2 mb-4"><a href="/login" class="hover:text-blue-800">Login</a> | <a href="/repassword" class="hover:text-blue-800">Forgot Password</a></p>

    {{-- Flash messages --}}
    @if(session('success'))
    <p class="mt-4 text-center text-green-600">{{ session('success') }}</p>
    @elseif(session('error'))
    <p class="mt-4 text-center text-red-600">{{ session('error') }}</p>
    @endif

    </div>
  </div>

  </main>
@endsection
