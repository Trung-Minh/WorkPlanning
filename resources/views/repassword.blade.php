{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Đổi mật khẩu – WorkPlan')

@section('content')
  <main class="w-full sm:w-3/4 md:w-2/3 lg:w-3/5 mx-auto mt-5 pt-6 p-6 bg-white shadow-md rounded">
    <h1 class="text-center text-blue-600 text-2xl font-bold flex items-center justify-center gap-2 mb-4">Đổi mật khẩu</h1>

    <form method="POST" action="{{ url('/repassword') }}" class="space-y-4">
    @csrf
    {{-- Email --}}
    <div>
      <label for="email" class="block font-medium mb-1">Email</label>
      <input type="text" name="email" id="email" placeholder="Email" required class="w-full border px-3 py-2 rounded"
      value="{{ old('email') }}" />
    </div>

    {{-- Thông báo lỗi --}}
    @if (session('error'))
    <p class="text-red-600 text-sm">{{ session('error') }}</p>
    @endif

    {{-- Mật khẩu --}}
    <div>
      <label for="mat_khau_moi" class="block font-medium mb-1">Mật khẩu mới</label>
      <input type="password" name="mat_khau_moi" id="mat_khau_moi" placeholder="New Password" required
      class="w-full border px-3 py-2 rounded" />
    </div>
    <div>
      <label for="cm_mat_khau_moi" class="block font-medium mb-1">Nhập lại mật khẩu mới</label>
      <input type="password" name="cm_mat_khau_moi" id="cm_mat_khau_moi" placeholder=" Comfirm New Password" required
      required class="w-full border px-3 py-2 rounded" />
    </div>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
      Đổi mật khẩu
    </button>
    </form>

    {{-- Thông báo thành công --}}
    @if (session('success'))
    <p class="text-green-600 text-sm font-medium text-center mb-2">{{ session('success') }}</p>
    @endif
  </main>
@endsection