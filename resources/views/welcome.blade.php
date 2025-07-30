@extends('layouts.app')

@section('title', 'Trang chủ - WorkPlanning')

@section('content')
    <main class="max-w-6xl px-4 py-12 mx-auto">
        <!-- Giới thiệu -->
        <section class="mb-16">
            <h1 class=" mb-6 text-5xl font-bold text-blue-700">
                ✨ WorkPlan - Quản lý kế hoạch cực đơn giản
            </h1>
            <p class="a mb-4 text-lg leading-relaxed text-gray-700">
                Với WorkPlan, bạn dễ dàng:
            </p>
            <ul class="space-y-1 text-base text-gray-700 list-disc list-inside">
                <li class= "a" >Tạo và sắp xếp kế hoạch công việc hằng ngày</li>
                <li class= "a">Nhận nhắc nhở tự động trước deadline</li>
                <li class= "a">Theo dõi tiến độ và quản lý hiệu quả thời gian</li>
            </ul>
            <div class="mt-6">
                <a href="{{ url('/register') }}"
                    class="inline-block px-6 py-3 text-base font-medium text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
                    👉 Bắt đầu ngay
                </a>
            </div>
        </section>

        <!-- Các tính năng nổi bật -->
        <section class="grid gap-6 md:grid-cols-3">
            <div class="p-6 transition bg-white border rounded-lg shadow hover:shadow-md">
                <h2 class="mb-2 text-2xl font-semibold text-blue-600">
                    📅 Tạo kế hoạch
                </h2>
                <p class="text-gray-600">
                    Tùy chỉnh lịch trình theo ngày, tuần hoặc tháng.
                </p>
            </div>
            <div class="p-6 transition bg-white border rounded-lg shadow hover:shadow-md">
                <h2 class="mb-2 text-2xl font-semibold text-blue-600">
                    ⏰ Nhắc nhở thông minh
                </h2>
                <p class="text-gray-600">
                    Luôn đúng giờ, luôn đúng việc – tránh trễ hạn quan trọng.
                </p>
            </div>
            <div class="p-6 transition bg-white border rounded-lg shadow hover:shadow-md">
                <h2 class="mb-2 text-2xl font-semibold text-blue-600">
                    📊 Giao diện dễ dùng
                </h2>
                <p class="text-gray-600">
                    Đơn giản, tối ưu cho mọi thiết bị – kể cả điện thoại.
                </p>
            </div>
        </section>
    </main>
@endsection
