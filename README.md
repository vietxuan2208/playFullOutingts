# playFullOutingts

## Mô tả
Ứng dụng Laravel cho hệ thống bán hàng / quản lý tour (frontend + admin).

## Yêu cầu

- PHP >= 8.1 (khuyến nghị) — nhiều package yêu cầu PHP 8.1+
- Composer
- MySQL (hoặc MariaDB)
- Node.js + npm

## Cài đặt (môi trường phát triển)

1. Clone repository (nếu chưa):

```bash
git clone https://github.com/vietxuan2208/playFullOutingts.git
cd playFullOutingts/doan
```

2. Cài PHP package:

```bash
composer install
```

3. Tạo file môi trường và key:

```bash
copy .env.example .env     # Windows
# hoặc
# cp .env.example .env    # Linux / macOS
php artisan key:generate
```

4. Cấu hình database trong `.env` (chỉnh `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Sau đó tạo database nếu cần.

```sql
-- ví dụ (mysql cli):
CREATE DATABASE doan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Chạy migration và seeder:

```bash
php artisan migrate --seed
```

6. Cài npm và build assets (dùng Laravel Mix):

```bash
npm install
npm run dev     # phát triển
# hoặc
npm run prod    # cho production
```

7. Tạo symbolic link cho storage:

```bash
php artisan storage:link
```

8. Chạy server phát triển:

```bash
php artisan serve
```

## Cấu hình PayPal

- Thiết lập cấu hình PayPal trong `config/services.php` hoặc `.env` (client id, secret, mode sandbox/live, currency).
- Trên môi trường development, nếu gặp lỗi SSL (cURL error 60) bạn có thể tạm dùng `withoutVerifying()` trong HTTP client — **chỉ dùng development**, không dùng production.

Ví dụ `.env`:

```
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_secret
PAYPAL_MODE=sandbox
PAYPAL_CURRENCY=USD
```

## Lưu ý bảo mật

- KHÔNG commit file `.env` (chứa secrets). Nếu nhỡ push secrets lên remote, hãy thay đổi key/secret ngay và xóa lịch sử git nếu cần.

## Các lệnh hữu ích

```bash
# Chạy tests
./vendor/bin/phpunit

# Kiểm tra logs
tail -f storage/logs/laravel.log
```

## Góp ý / Liên hệ
- Nếu cần tôi có thể giúp: nâng cấp PHP trên Windows/WSL, sửa secrets, hoặc deploy lên server.

---
README tạo bởi developer trong dự án.
