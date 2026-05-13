# Toyota Test — Hands-on Project Assessment

## Tech Stack
- Laravel 12 + Filament v4 + Livewire v3 + Tailwind CSS v4
- MariaDB / MySQL 8.4
- Laravel Reverb (WebSocket Broadcast)
- Cloudflare Tunnel

## Setup

```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

สร้าง database ชื่อ `toyota_test` ใน MySQL/MariaDB แล้วตั้งค่าใน `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toyota_test
DB_USERNAME=root
DB_PASSWORD=
```

จากนั้นรัน:

```bash
php artisan migrate --seed
```

## รัน Development Server

เปิด 3 terminal แยกกัน:

```bash
# Terminal 1
php artisan serve

# Terminal 2
php artisan reverb:start

# Terminal 3
php artisan queue:work
```

## เข้าใช้งาน

URL: http://localhost:8000/admin

| Name | Email | Password |
|------|-------|----------|
| mikkee | mikkee@test.com | 123456 |
| Admin Toyota | admin@toyota.com | 123456 |
| Manager | manager@toyota.com | 123456 |

## Features
- CRUD รุ่นรถยนต์ + Search / Filter / Sort
- Delete with Nested Modal 2 ขั้น + Soft Delete
- Broadcast Notification real-time ผ่าน Laravel Reverb
- บันทึก Notification ลง Database
- Approval Chain Database Design (ER Diagram + Data Dictionary)