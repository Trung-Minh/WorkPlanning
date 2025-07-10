{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng nhập – WorkPlan')

@section('content')
  <main class="w-full sm:w-3/4 md:w-5/10 lg:w-5/10 mx-auto mt-1 pt-2 ">
   
  <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-2 bg-white shadow-md rounded p-6 rounded-3xl"> <!-- Parent container (flex) -->
  <!-- Column 1 -->
  <div class="hidden md:hiden lg:block mx-auto my-auto px-4 py-4 rounded-lg">
    <img src="{{ asset('workplanning.png') }}" alt="Workplanning" class="rounded-lg">
  </div>  
  
  <!-- Column 2 -->
  <div class=" px-4 py-6 rounded-lg mt-0 lg:mt-8">

    <h1 class="text-center text-blue-600 text-3xl font-bold flex items-center justify-center gap-2 mb-4">Đăng nhập</h1>

    <form method="POST" action="{{ url('/login') }}" class="space-y-4 w-full max-w-md md:max-w-lg">
    @csrf
    {{-- Email --}}
    <div class="items-center justify-center">
      <label for="email" class="block font-medium mb-1">Email</label>
      <input type="text" name="email" id="email" placeholder="Email" required class="w-full border px-3 py-2 rounded"
      value="{{ old('email') }}" />
    </div>


    {{-- Mật khẩu --}}
    <div class="items-center justify-center">
      <label for="mat_khau" class="block font-medium mb-1">Mật khẩu</label>
      <input type="password" name="mat_khau" id="mat_khau" placeholder="Password" required
      class="w-full border px-3 py-2 rounded" />
    </div>
    {{-- Thông báo lỗi --}}
    @if (session('error'))
    <p class="text-red-600 text-sm">{{ session('error') }}</p>
    @endif
    
    <button type="submit" class="w-full bg-blue-600 text-lg text-white py-2 rounded hover:bg-blue-700">
      Đăng nhập
    </button>
    </form>
    <p class="text-center text-gray-600 flex items-center justify-center gap-2 mb-4"><a href="/register" class="hover:text-blue-800">Register</a> | <a href="/repassword" class="hover:text-blue-800">Forgot Password</a></p>
  </div>
  </div>
    {{-- Thông báo thành công --}}
    @if (session('success'))
    <p class="text-green-600 text-sm font-medium text-center mb-2">{{ session('success') }}</p>
    @endif
  </main>
@endsection