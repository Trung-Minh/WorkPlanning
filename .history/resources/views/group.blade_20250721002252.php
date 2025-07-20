{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head><meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

                @php
                    $nhom = session('group')
                @endphp
                <div class="flex min-h-screen bg-gray-100 text-gray-800">

            <!-- Sidebar trái -->
            <aside class="w-64 bg-white border-r p-4 space-y-4">
                <button class="w-full bg-purple-600 text-white py-2 rounded-lg font-semibold">+ Create community</button>

                <div>
                <h2 class="text-sm font-bold mb-2">Your pins</h2>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm">
                    <img src="https://via.placeholder.com/32" class="rounded-full w-8 h-8" />
                    <span>Phạm Hà Ngân - Master Coach</span>
                    </li>
                    <li class="flex items-center gap-2 text-sm">
                    <img src="https://via.placeholder.com/32" class="rounded-full w-8 h-8" />
                    <span>Kenny Tran (Founder)</span>
                    </li>
                    <!-- Thêm các mục khác -->
                </ul>
                </div>
            </aside>

            <!-- Nội dung chính -->
            <main class="flex-1">
                <!-- Navbar -->
                <header class="flex justify-between items-center bg-white px-6 py-4 shadow">
                <div class="flex items-center gap-6">
                    <img src="https://via.placeholder.com/32" class="rounded-full w-8 h-8" />
                    <nav class="flex gap-4">
                    <button class="font-medium text-purple-600 border-b-2 border-purple-600 pb-1">Home</button>
                    <button class="font-medium text-gray-600 hover:text-purple-600">Quick Post</button>
                    <button class="font-medium text-gray-600 hover:text-purple-600">Write Article</button>
                    <button class="font-medium text-gray-600 hover:text-purple-600">Create Series</button>
                    </nav>
                </div>

                <!-- Search + Profile -->
                <div class="flex items-center gap-4">
                    <input type="text" placeholder="Search content" class="border rounded px-3 py-1 text-sm" />
                    <button class="rounded-full bg-gray-200 w-8 h-8"></button>
                    <button class="rounded-full bg-purple-600 text-white px-3 py-1 text-xs">BIC</button>
                    <button class="rounded-full bg-gray-300 w-8 h-8">W</button>
                </div>
                </header>

                <!-- Nội dung bài viết -->
                <section class="p-6 space-y-6">
                <!-- Một bài viết -->
                <article class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                    <div class="bg-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">BIC</div>
                    <div>
                        <p class="text-sm font-semibold">Dat Light · 1d</p>
                        <p class="text-xs text-gray-500">Posted to Hành Trình Tri Thức</p>
                    </div>
                    </div>

                    <p class="text-base font-medium mb-2">Tôi là ai - Đây là đâu? Chúng ta ở đây làm gì?</p>
                    <div class="aspect-video bg-gray-200 mb-2 rounded"></div>
                    <p class="text-sm text-gray-600">Chào bạn! Dù bạn đang là thành viên của cộng đồng...</p>

                    <button class="text-purple-600 text-sm mt-2 font-semibold">Mark as read</button>
                </article>
                </section>
            </main>

            <!-- Sidebar phải -->
            <aside class="w-72 bg-white border-l p-4 space-y-4">
                <div>
                <h3 class="text-sm font-bold mb-2">Welcome to Beincom (BIC)</h3>
                <ul class="text-sm space-y-1 text-gray-700">
                    <li>Quick Introductions</li>
                    <li>Culture and Guidelines</li>
                </ul>
                </div>

                <div>
                <h3 class="text-sm font-bold mb-2">Suggested</h3>
                <div class="bg-gray-100 p-3 rounded-md">
                    <p class="text-sm font-semibold">Tâm Lý Học Ứng Dụng</p>
                    <p class="text-xs text-gray-500">2 contents/week</p>
                    <button class="mt-2 bg-purple-600 text-white text-xs px-3 py-1 rounded">Join</button>
                </div>
                </div>
            </aside>
            </div>

  

@endsection

@php($noFooter = true)
