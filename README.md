<!--
Nếu có làm reposive cho mobile
- Chung mạng wifi
- "dev": "vite --host=192.168.1.7"
- php artisan serve --host=0.0.0.0
- .env
    # VITE_DEV_SERVER_URL=http://192.168.1.7:5173
    # APP_URL=http://192.168.1.7:8000
- vite.config
    // server: {
    //     host: "0.0.0.0",
    //     port: 5173,
    //     origin: process.env.VITE_DEV_SERVER_URL,
    // },
 -->

# WorkPlan - Quản lý Kế Hoạch & Nhắc Nhở Deadline

> Dự án web quản lý công việc cá nhân, lập kế hoạch và nhắc deadline thông minh.
> Frontend: HTML, Tailwind CSS, JavaScript

---

## Cài composer

> Link tải .exe [https://getcomposer.org/download/](https://getcomposer.org/download/)

# Hướng Dẫn Cài Đặt Môi Trường & Tạo Project Laravel

Dưới đây là các bước chi tiết để cài đặt Node.js, PHP và thiết lập Laravel project trên Windows 🚀

---

## 1. Cài NVM for Windows

1. Truy cập: [https://github.com/coreybutler/nvm-windows/releases](https://github.com/coreybutler/nvm-windows/releases)
2. Tải về bản **nvm-setup.exe (phiên bản 1.2.2)**
3. Chạy **installer** và làm theo hướng dẫn.

```powershell
nvm version
```

## 2. Cài & Sử dụng Node.js v22.1.0

```bash
nvm install 22.1.0
nvm use 22.1.0

npm -v
```

---

## 3. Cài PHP 8.2.12 (Chocolatey)

### 3.1 Cài Chocolatey

Mở **PowerShell (Admin)** và chạy:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force;
[System.Net.ServicePointManager]::SecurityProtocol =
  [System.Net.ServicePointManager]::SecurityProtocol -bor 3072;
iex ((New-Object System.Net.WebClient).DownloadString(
  'https://community.chocolatey.org/install.ps1'
))
```

### 3.2 Cài PHP 8.2.12

```powershell
choco install php --version=8.2.12
```

### 3.3 Thiết lập `PATH` & `php.ini`

1. Mở **System Environment Variables** → **Edit Path**.
2. Di chuyển đường dẫn đến PHP 8.2.12 lên **đầu danh sách** (trên PHP XAMPP nếu có).
3. Mở file `php.ini` của PHP 8.2.12 (xem `php --ini`).
4. Bật các extension cần thiết:

```ini
extension=fileinfo
extension=openssl
extension=mbstring
extension=pdo_mysql
extension=mysqli
```

5. Lưu và **restart** terminal.

---

## 4. Tạo Laravel Project

1. Mở **VS Code** tại thư mục bạn muốn chứa project.

2. Chạy:

```bash
composer create-project laravel/laravel workplanning
```

3. Mở lại VS Code vào thư mục `workplanning` vừa tạo.

---

## 5. Thiết Lập Git & Đồng Bộ Với Remote Repo

```bash
cd workplanning

git init

git remote add origin https://github.com/Trung-Minh/WorkPlanning.git

git fetch origin
git reset --hard origin/main

git checkout main
```

---

## 6. Tạo Database & Chạy Migration & tải vite

1. Mở file **`database/sql/create_database_and_tables.sql`**, copy toàn bộ nội dung.
2. Đăng nhập vào **phpMyAdmin** (hoặc kết nối MySQL client), dán và chạy SQL để **tạo database và các bảng**.
3. Chạy lệnh migration của Laravel (nếu có thêm migration mới trong code):

```bash
php artisan migrate
```

4. Chạy lệnh cập nhật kiểu dữ liệu bảng session

```bash
ALTER TABLE sessions MODIFY COLUMN user_id CHAR(8) NULL;
```

5. tải vite

```bash
npm install vite --save-dev
```

> **Lưu ý:** Phải hoàn tất bước tạo database & chạy migrations trước khi khởi động frontend để tránh lỗi kết nối hoặc thiếu bảng.

---

## 7. Cài Đặt Frontend & Chạy Dev

> Chạy lệnh bên dưới để cài node_modules và setup vite

```bash
npm install
```

> Chạy đồng thời 2 lệnh dưới bằng cách chia terminal làm 2

```bash
npm run dev

php artisan serve
```

# Chúc các mày cài thành công
