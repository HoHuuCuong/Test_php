Bài test được viết bằng framework laravel sử dụng phiên bản php  8.1.10 và database mysql(test.sql)
 Các bước để cài đặt:
 B1: Clone dự án từ repository
 B2: Cài đặt composer ( composer install )
 B3: Sao chép file `.env.example` thành `.env` và cập nhật các cài đặt cơ sở dữ liệu 
 B4:Tạo key ứng dụng ( php artisan key:generate )
 B5: Chạy migrations để thiết lập cơ sở dữ liệu: (php artisan migrate)
 B6: Chạy dự án : (php artisan serve)
