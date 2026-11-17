# OWEN STUDIO — Website Thời Trang Nam Local Brand (Laravel)

Đồ án website thương mại điện tử thời trang nam Local Brand xây dựng bằng **Laravel 8**, **Bootstrap 5**, **MySQL**.

---

## Hướng dẫn cài đặt khi Clone về máy mới

### Yêu cầu môi trường
- **PHP** >= 7.4 hoặc 8.x
- **Composer**
- **MySQL** (XAMPP, Laragon, WAMP hoặc MySQL Server đang chạy ở cổng `3306`)

### Cách 1: Cài đặt tự động 1 chạm (Khuyên dùng)
1. Bật **MySQL** (trong XAMPP hoặc Laragon).
2. Clone dự án về máy:
   ```bash
   git clone https://github.com/Khoale1610/WebLocalBrand.git
   cd WebLocalBrand
   ```
3. Chạy file cài đặt tự động:
   - Trên **Windows**: click đúp vào file `setup.bat` hoặc chạy trong terminal:
     ```bat
     setup.bat
     ```
   - Trên **macOS / Linux**:
     ```bash
     chmod +x setup.sh && ./setup.sh
     ```
   *(Script sẽ tự tạo `.env`, chạy `composer install`, tạo `APP_KEY`, tự tạo database `laravel` trong MySQL, tạo toàn bộ bảng và nạp sẵn 12 danh mục, 36 sản phẩm, 287 biến thể, mã giảm giá, đơn hàng mẫu và tài khoản Admin).*
4. Khởi động server:
   ```bash
   php artisan serve
   ```
   Truy cập: `http://127.0.0.1:8000`

---

### Cách 2: Cài đặt thủ công từng bước
```bash
cp .env.example .env
composer install
php artisan key:generate
# Đảm bảo MySQL đang chạy và đã có database tên "laravel" (hoặc sửa DB_DATABASE trong .env)
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

> **Lưu ý:** Nếu bạn đã chạy `php artisan migrate` mà quên `--seed`, hệ thống cũng sẽ tự động nạp dữ liệu mẫu ngay trong lần truy cập web đầu tiên khi phát hiện bảng sản phẩm và danh mục đang trống.

---

## Tài khoản đăng nhập mẫu
- **Admin (Quản trị viên):**
  - Email: `admin@gmail.com`
  - Mật khẩu: `12345678`
  - Đường dẫn quản trị: `/admin`
- **Khách hàng:**
  - Email: `khachhang@gmail.com` hoặc `maitran@gmail.com`
  - Mật khẩu: `12345678`

## Mã giảm giá (Voucher) nạp sẵn
- `OWEN50`: Giảm 50.000đ cho đơn từ 499.000đ
- `LOCAL15`: Giảm 15% (tối đa 200.000đ) cho đơn từ 999.000đ
- `FREESHIP`: Giảm 30.000đ cho đơn từ 300.000đ
- `WELCOME10`: Giảm 10% (tối đa 100.000đ) cho đơn từ 200.000đ
