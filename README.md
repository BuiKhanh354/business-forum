# business-forum

> **KẾT NỐI - Business Community Platform**  
> Nền tảng chia sẻ góc nhìn chiến lược, kiến thức điều hành & đầu tư thực chiến dành cho cộng đồng doanh nghiệp và khởi nghiệp.

---

## 🌟 Tính năng nổi bật

- **Giao diện chuẩn Median UI / Executive Business**:
  - Hỗ trợ chế độ **Sáng / Tối toàn diện (Obsidian Dark Mode)** mượt mà, lưu trạng thái tự động.
  - Phối màu xanh doanh nghiệp (Executive Blue) kết hợp điểm nhấn ánh kim (Gold Accent).
- **Trang chủ & Diễn đàn bài viết**:
  - Tự động lấy **Top 5 Bài viết nổi bật (yêu thích nhất)** theo số lượt thả tim (`likes_count`).
  - Bộ lọc sắp xếp bài viết theo: Mới nhất, Xem nhiều nhất, Nhiều bình luận nhất, Nổi bật (Nhiều tim nhất).
  - Thanh cuộn danh mục chuyên đề gọn gàng, tự động gom nhóm `+n chuyên mục`.
- **Khu vực Quản trị Admin chuyên sâu**:
  - Quản lý bài viết (Thêm, sửa, xoá, đổi trạng thái, tải nhiều ảnh).
  - Quản lý danh mục chuyên đề & phân trang chuẩn.
  - Quản lý bình luận độc giả (Duyệt, thích, xoá).
  - Tiếp nhận đề xuất / Thư ngỏ hợp tác từ đối tác doanh nghiệp.
  - Cài đặt giao diện & nhận diện thương hiệu động (Logo, Favicon, Ảnh bìa Desktop & Mobile, Màu chủ đạo).
  - Lối vào quản trị bảo mật kín đáo (Phím tắt `Ctrl + Shift + A` hoặc double-click logo).
- **Trang lỗi 404 độc lập**:
  - Bố cục căn giữa toàn màn hình sang trọng, không phụ thuộc header/sidebar, hỗ trợ Dark Mode và ô tìm kiếm bài viết nhanh.

---

## 🚀 Hướng dẫn Cài đặt & Triển khai (Deployment Guide)

### 1. Yêu cầu hệ thống
- **PHP**: >= 8.1 (Khuyến nghị PHP 8.2)
- **Composer**: >= 2.x
- **Node.js & NPM**: >= 18.x
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.3

---

### 2. Các bước cài đặt

```bash
# 1. Clone repository về máy / server
git clone https://github.com/BuiKhanh354/business-forum.git
cd business-forum

# 2. Cài đặt các gói PHP dependencies
composer install --optimize-autoloader --no-dev

# 3. Cài đặt các gói Node.js và biên dịch assets
npm install
npm run build

# 4. Thiết lập file cấu hình môi trường (.env)
cp .env.example .env

# Cập nhật thông số Database trong file .env:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=vlog_forum
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Tạo Application Key
php artisan key:generate

# 6. Khởi tạo cơ sở dữ liệu
# Cách 1: Chạy migrations & seeders mẫu
php artisan migrate --seed

# Cách 2: Hoặc import trực tiếp file SQL đã xuất sẵn
# mysql -u root -p vlog_forum < database/vlog_forum.sql

# 7. Tạo symbolic link cho thư mục lưu trữ (nếu cần upload)
php artisan storage:link

# 8. Khởi chạy server
php artisan serve
```

---

## 📄 Bản quyền
Phát triển bởi cộng đồng **KẾT NỐI - Business Community**. Bản quyền bảo lưu.
