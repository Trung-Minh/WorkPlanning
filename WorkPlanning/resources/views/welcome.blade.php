@extends('layouts.app')

@section('title', 'Trang chủ - WorkPlanning')

@section('content')
    <main class="max-w-6xl mx-auto px-4 py-12">
        <!-- Giới thiệu -->
        <section class="mb-16">
            <h1 class="text-5xl font-bold text-blue-700 mb-6">
                ✨ WorkPlan - Quản lý kế hoạch cực đơn giản
            </h1>
            <p class="text-gray-700 text-lg leading-relaxed mb-4">
                Với WorkPlan, bạn dễ dàng:
            </p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 text-base">
                <li>Tạo và sắp xếp kế hoạch công việc hằng ngày</li>
                <li>Nhận nhắc nhở tự động trước deadline</li>
                <li>Theo dõi tiến độ và quản lý hiệu quả thời gian</li>
            </ul>
            <div class="mt-6">
                <a href="{{ url('/register') }}"
                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md text-base font-medium hover:bg-blue-700 transition">
                    👉 Bắt đầu ngay
                </a>
            </div>
        </section>

        <!-- Các tính năng nổi bật -->
        <section class="grid md:grid-cols-3 gap-6">
            <div class="bg-white border rounded-lg p-6 shadow hover:shadow-md transition">
                <h2 class="text-2xl font-semibold text-blue-600 mb-2">
                    📅 Tạo kế hoạch
                </h2>
                <p class="text-gray-600">
                    Tùy chỉnh lịch trình theo ngày, tuần hoặc tháng.
                </p>
            </div>
            <div class="bg-white border rounded-lg p-6 shadow hover:shadow-md transition">
                <h2 class="text-2xl font-semibold text-blue-600 mb-2">
                    ⏰ Nhắc nhở thông minh
                </h2>
                <p class="text-gray-600">
                    Luôn đúng giờ, luôn đúng việc – tránh trễ hạn quan trọng.
                </p>
            </div>
            <div class="bg-white border rounded-lg p-6 shadow hover:shadow-md transition">
                <h2 class="text-2xl font-semibold text-blue-600 mb-2">
                    📊 Giao diện dễ dùng
                </h2>
                <p class="text-gray-600">
                    Đơn giản, tối ưu cho mọi thiết bị – kể cả điện thoại.
                </p>
            </div>
        </section>
    </main>
@endsection