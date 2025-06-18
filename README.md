# 📅 WorkPlan - Quản lý Kế Hoạch & Nhắc Nhở Deadline

> Dự án web quản lý công việc cá nhân, lập kế hoạch và nhắc deadline thông minh.
> Frontend: HTML, Tailwind CSS, JavaScript

---

## 🚀 Cách chạy dự án

### 1. Clone về máy

```bash
git clone https://github.com/ten-ban/workplan.git
cd workplan
```

### 2. Cài đặt thư viện

> Dùng Node.js (khuyên dùng bản LTS)

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 3. Tạo các file cần thiết

- Tạo thư mục `src/` chứa file `input.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

- Chỉnh `tailwind.config.js`:

```js
content: ["./*.html"],
```

- Tạo thư mục `dist/` để chứa file `output.css`

### 4. Build Tailwind (chế độ xem thay đổi theo thời gian)

```bash
npx tailwindcss -i ./src/input.css -o ./dist/output.css --watch
```

> Sau đó mở `index.html` trong trình duyệt để xem kết quả ✨

---

## 🌿 Quy trình làm việc với Git

### Tạo nhánh mới để bắt đầu code

```bash
git checkout -b <tên-nhánh>
```

### Khi code xong, commit và đẩy lên GitHub

```bash
git add .
git commit -m "Nội dung commit"
git push -u origin <tên-nhánh>
```

---

## 📁 Cấu trúc thư mục

```
workplan/
├── index.html
├── src/
│   └── input.css
├── dist/
│   └── output.css (được build)
├── tailwind.config.js
├── postcss.config.js
├── package.json
└── README.md
```

---

## 👨‍💻 Thành viên nhóm

- Anh (dev chính 🧠💻)
- \[Tên bạn anh] (hỗ trợ UI/UX hoặc logic 🎨🧩)

---

## 📌 Ghi chú

- Chạy `npm install` nếu bạn clone về và thấy thiếu `node_modules`
- Dùng VS Code + extension "Live Server" để xem nhanh trang HTML

---

## ❤️ Made with love by Anh & team
