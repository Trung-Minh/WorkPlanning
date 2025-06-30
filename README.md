# WorkPlan - Quản lý Kế Hoạch & Nhắc Nhở Deadline

> Dự án web quản lý công việc cá nhân, lập kế hoạch và nhắc deadline thông minh.
> Frontend: HTML, Tailwind CSS, JavaScript

---

# ✨ Cách chạy dự án

## 1. Clone về máy

```bash
git clone https://github.com/ten-ban/workplan.git
cd workplan
```

## 2. Cài đặt Tailwind CSS

> Dùng Node.js v22.1.0
> Tham khảo hướng dẫn từ Tailwind: [https://tailwindcss.com/docs/installation/tailwind-cli](https://tailwindcss.com/docs/installation/tailwind-cli)

```bash
npm run dev
```

---

# Chuyển project sang framework Laravel

## 1. Cài đặt composer

> Link tải .exe [https://getcomposer.org/download/](https://getcomposer.org/download/)

## 2. Chạy lệnh tạo project Laravel

```bash
composer create-project laravel/laravel workplanning
cd workplanning
```

## 3. Cấu hình .env

> Chỉnh sửa đoạn mã trong .env theo như bên dưới

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workplanning
DB_USERNAME=root
DB_PASSWORD=
```

## 4. Tạo model và controller

```bash
php artisan make:model NguoiDungCaNhan
php artisan make:controller AuthController
```

## 5. Chạy dự án

```bash
php artisan serve
```

---

# Cách có database để chạy không lỗi

## 1. Dán code sql vào myPHPAdmin

## 2. Chạy lệnh để sinh ra các bảng còn thiếu

```bash
php artisan migrate
```
