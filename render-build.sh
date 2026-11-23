#!/usr/bin/env bash
# Exit on error
set -e

echo "--- BẮT ĐẦU CÀI ĐẶT DỰ ÁN LARAVEL TRÊN RENDER ---"

# Cài đặt thư viện PHP Composer
composer install --no-dev --optimize-autoloader --no-interaction

# Tạo liên kết lưu trữ ảnh uploads
php artisan storage:link || true

# Tối ưu hóa cache cấu hình và view
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "--- BUILD HOÀN TẤT THÀNH CÔNG ---"
