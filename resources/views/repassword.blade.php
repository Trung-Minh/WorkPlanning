{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Đổi mật khẩu – WorkPlan')

@section('content')
  <main class="w-full sm:w-3/4 md:w-2/3 lg:w-3/5 mx-auto mt-5 pt-6 p-6 ">
  <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-2 bg-white shadow-md rounded p-6 rounded-3xl"> <!-- Parent container (flex) -->
  <!-- Column 1 -->
  <div class="hidden md:hiden lg:block mx-auto my-auto px-4 py-4 rounded-lg">
    <img src="{{ asset('workplanning.png') }}" alt="Workplanning" class="rounded-lg">
  </div>
  <!-- Column 2 -->
  <div class="px-4 py-6 2xl:px-8 lg:py-12">
    <!-- <h1 class="text-center text-blue-600 text-2xl font-bold flex items-center justify-center gap-2 mb-4">Đổi mật khẩu</h1>

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
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 cursor-pointer">
      Đổi mật khẩu
    </button>
    </form>
    <p class="text-center text-gray-600 flex items-center justify-center gap-2 mb-4"><a href="/login" class="hover:text-blue-800">Login</a> | <a href="/register" class="hover:text-blue-800">Register</a></p>

    {{-- Thông báo thành công --}}
    @if (session('success'))
    <p class="text-green-600 text-sm font-medium text-center mb-2">{{ session('success') }}</p>
    @endif
  </div>   -->
 <h1 class="text-center text-blue-600 text-2xl font-bold flex items-center justify-center gap-2 mb-4">
    Đổi mật khẩu
</h1>

{{-- Form xác minh email --}}
<form id="check-email-form" class="space-y-4   {{ old('email') && $errors->any() ? 'hidden' : '' }}">
    @csrf

    <div>
        <label for="email" class="block font-medium mb-1">Email</label>
        <input type="email" name="email" id="email" placeholder="Email" required
            class="w-full border px-3 py-2 rounded"
            value="{{ old('email') }}" />
    </div>

    {{-- Thông báo lỗi email --}}
    <p id="email-error" class="text-red-600 text-sm hidden">Email không tồn tại.</p>

    <button type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 cursor-pointer">
        Xác minh email
    </button>
</form>

{{-- Form nhập mật khẩu mới --}}
<form method="POST" action="{{ url('/repassword') }}"
    id="reset-password-form" class="space-y-4 {{ old('email') && $errors->any() ? '' : 'hidden' }}">
    @csrf

    <input type="hidden" name="email" id="confirmed-email">

    {{-- Mật khẩu --}}
    <div>
        <label for="mat_khau_moi" class="block font-medium mb-1">Mật khẩu mới</label>
        <input type="password" name="mat_khau_moi" id="mat_khau_moi" placeholder="New Password" required
            class="w-full border px-3 py-2 rounded" />
            @error('mat_khau_moi')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

    </div>

    <div>
        <label for="mat_khau_moi_confirmation" class="block font-medium mb-1">Nhập lại mật khẩu mới</label>
        <input type="password" name="mat_khau_moi_confirmation" id="mat_khau_moi_confirmation" placeholder="Confirm New Password" required
            class="w-full border px-3 py-2 rounded" />
            
    </div>

    <button type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 cursor-pointer">
        Đổi mật khẩu
    </button>
</form>
    <p class="text-center text-gray-600 flex items-center justify-center gap-2 mb-4"><a href="/login" class="hover:text-blue-800">Login</a> | <a href="/register" class="hover:text-blue-800">Register</a></p>



  </div>  

 <script>
document.addEventListener('DOMContentLoaded', function () {
    const emailForm = document.getElementById('check-email-form');
    const resetForm = document.getElementById('reset-password-form');
    const confirmedEmail = document.getElementById('confirmed-email');
    const emailError = document.getElementById('email-error');

    if (!emailForm || !resetForm || !confirmedEmail) {
        console.error("Phần tử không tồn tại trong DOM");
        return;
    }

    emailForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const token = document.querySelector('[name=_token]').value;

        const res = await fetch('/check-email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email })
        });

        const data = await res.json();

        if (data.exists) {
            confirmedEmail.value = email;
            emailForm.classList.add('hidden');
            resetForm.classList.remove('hidden');
            emailError.classList.add('hidden');
        } else {
            emailError.classList.remove('hidden');
        }
    });
});
</script>


  </main>

@endsection
@php($noFooter = true)
