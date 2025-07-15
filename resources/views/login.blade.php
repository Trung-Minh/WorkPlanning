{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Đăng nhập – WorkPlan')

@section('content')
    <main class="w-full pt-2 mx-auto mt-5 sm:w-3/4 md:w-5/10 lg:w-5/10 ">
        <div class="grid grid-cols-1 gap-2 p-6 bg-white shadow-md md:grid-cols-1 lg:grid-cols-2 rounded-3xl">
            <!-- Column 1 -->
            <div class="hidden px-4 py-4 mx-auto my-auto rounded-lg md:hiden lg:block">
                <img src="{{ asset('workplanning.png') }}" alt="Workplanning" class="rounded-lg">
            </div>

            <!-- Column 2 -->
            <div class="px-4 py-6 mt-0 rounded-lg lg:mt-8">
                <h1 class="flex items-center justify-center gap-2 mb-4 text-3xl font-bold text-center text-blue-600">Đăng nhập</h1>
                <form method="POST" action="{{ url('/login') }}" class="w-full max-w-md space-y-4 md:max-w-lg">
                    @csrf
                    {{-- Email --}}
                    <div class="items-center justify-center">
                        <label for="email" class="block mb-1 font-medium">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email" required
                            class="w-full px-3 py-2 border rounded" value="{{ old('email') }}" />
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="items-center justify-center">
                        <label for="mat_khau" class="block mb-1 font-medium">Mật khẩu</label>
                        <input type="password" name="mat_khau" id="mat_khau" placeholder="Password" required
                            class="w-full px-3 py-2 border rounded" />
                    </div>

                    {{-- Thông báo lỗi --}}
                    @if (session('error'))
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    @endif

                    <button type="submit" class="w-full py-2 text-lg text-white bg-blue-600 rounded cursor-pointer hover:bg-blue-700">
                        Đăng nhập
                    </button>
                </form>
                <p class="flex items-center justify-center gap-2 mb-4 text-center text-gray-600">
                    <a href="/register" class="hover:text-blue-800">Register</a> |
                    <a href="/repassword" class="hover:text-blue-800">Forgot Password</a>
                </p>

                {{-- Thông báo thành công --}}
                @if (session('success'))
                    <p class="mb-2 text-sm font-medium text-center text-green-600">{{ session('success') }}</p>
                @endif
            </div>
        </div>
    </main>
@endsection

@php($noFooter = true)
