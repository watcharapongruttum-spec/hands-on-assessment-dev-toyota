# Toyota Test — Hands-on Project Assessment

## Tech Stack
- Laravel 12 + Filament v4 + Livewire v3 + Tailwind CSS v4
- MariaDB / MySQL 8.4
- Laravel Reverb (WebSocket Broadcast)
- Cloudflare Tunnel

---

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

---

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

---

## เข้าใช้งาน

URL: `http://localhost:8000/admin`

| Name         | Email               | Password |
|--------------|---------------------|----------|
| mikkee       | mikkee@test.com     | 123456   |
| Admin Toyota | admin@toyota.com    | 123456   |
| Manager      | manager@toyota.com  | 123456   |

---

## Features

- CRUD รุ่นรถยนต์ + Search / Filter / Sort
- Delete with Nested Modal 2 ขั้น + Soft Delete
- Broadcast Notification real-time ผ่าน Laravel Reverb
- บันทึก Notification ลง Database
- Approval Chain Database Design (ER Diagram + Data Dictionary)

---

## Part 1: Database Design — ระบบสายอนุมัติ

### ER Diagram

```
branches ─────────────────────────────────────────────────┐
                                                           ▼
approval_types ──────────────────────────► approval_workflows ──► approval_workflow_conditions
                  │                                │
                  │                                └──────────────► approval_steps
                  │                                                       │
                  ▼                                                        │ (approver_id)
users ◄───────────────────────────────────────────────────────────────────┘
  │                 │
  │ (requester_id)  │ (actor_id)
  ▼                 ▼
approval_requests ──────────────────► approval_request_steps
        │                                       │
        └──────────────────────────────────────►│
                                                ▼
                                      approval_histories (Immutable)
```

---

### Data Dictionary

---

#### 1. `branches` — สาขา

| Column     | Type            | Null | Default | Description                        |
|------------|-----------------|------|---------|------------------------------------|
| id         | bigint unsigned | NO   | AI      | Primary Key                        |
| code       | varchar(50)     | NO   | —       | รหัสสาขา (Unique)                  |
| name       | varchar(100)    | NO   | —       | ชื่อสาขา                           |
| is_active  | tinyint(1)      | NO   | 1       | สถานะ (1=ใช้งาน, 0=ปิด)           |
| created_at | timestamp       | YES  | NULL    | วันที่สร้าง                        |
| updated_at | timestamp       | YES  | NULL    | วันที่แก้ไขล่าสุด                 |

---

#### 2. `approval_types` — ประเภทการอนุมัติ

| Column      | Type            | Null | Default | Description                                        |
|-------------|-----------------|------|---------|----------------------------------------------------|
| id          | bigint unsigned | NO   | AI      | Primary Key                                        |
| code        | varchar(50)     | NO   | —       | รหัสประเภท (Unique) เช่น QUOTATION, BOOKING       |
| name        | varchar(100)    | NO   | —       | ชื่อประเภทอนุมัติ เช่น อนุมัติใบเสนอราคา         |
| description | text            | YES  | NULL    | คำอธิบายเพิ่มเติม                                 |
| is_active   | tinyint(1)      | NO   | 1       | สถานะ (1=ใช้งาน, 0=ปิด)                           |
| created_at  | timestamp       | YES  | NULL    | วันที่สร้าง                                       |
| updated_at  | timestamp       | YES  | NULL    | วันที่แก้ไขล่าสุด                                |

---

#### 3. `approval_workflows` — สายอนุมัติ (R1.1)

| Column           | Type            | Null | Default | Description                                     |
|------------------|-----------------|------|---------|-------------------------------------------------|
| id               | bigint unsigned | NO   | AI      | Primary Key                                     |
| approval_type_id | bigint unsigned | NO   | —       | FK → approval_types.id (ประเภทอนุมัติ)          |
| branch_id        | bigint unsigned | YES  | NULL    | FK → branches.id (สาขาที่ใช้งาน)               |
| name             | varchar(100)    | NO   | —       | ชื่อสายอนุมัติ                                 |
| started_at       | date            | NO   | —       | วันเริ่มต้นใช้งาน                              |
| ended_at         | date            | YES  | NULL    | วันสิ้นสุดการใช้งาน (NULL = ไม่มีกำหนด)       |
| is_active        | tinyint(1)      | NO   | 1       | สถานะ (1=ใช้งาน, 0=ปิด)                        |
| created_at       | timestamp       | YES  | NULL    | วันที่สร้าง                                    |
| updated_at       | timestamp       | YES  | NULL    | วันที่แก้ไขล่าสุด                             |

---

#### 4. `approval_workflow_conditions` — เงื่อนไขตามยอดเงิน (R1.1)

| Column               | Type             | Null | Default | Description                                                  |
|----------------------|------------------|------|---------|--------------------------------------------------------------|
| id                   | bigint unsigned  | NO   | AI      | Primary Key                                                  |
| approval_workflow_id | bigint unsigned  | NO   | —       | FK → approval_workflows.id                                   |
| min_amount           | decimal(15,2)    | NO   | 0.00    | ยอดเงินขั้นต่ำของช่วงนี้                                   |
| max_amount           | decimal(15,2)    | YES  | NULL    | ยอดเงินสูงสุด (NULL = ไม่จำกัด / มากกว่า X)               |
| required_steps       | tinyint unsigned | NO   | —       | จำนวนขั้นตอนอนุมัติที่ต้องผ่านในช่วงยอดเงินนี้           |
| created_at           | timestamp        | YES  | NULL    | วันที่สร้าง                                                 |
| updated_at           | timestamp        | YES  | NULL    | วันที่แก้ไขล่าสุด                                          |

> ตัวอย่าง: 0–3,000 → 2 ขั้น / 3,000–5,000 → 3 ขั้น / >5,000 → 4 ขั้น

---

#### 5. `approval_steps` — ขั้นตอนอนุมัติ (R1.2)

| Column               | Type             | Null | Default | Description                                              |
|----------------------|------------------|------|---------|----------------------------------------------------------|
| id                   | bigint unsigned  | NO   | AI      | Primary Key                                              |
| approval_workflow_id | bigint unsigned  | NO   | —       | FK → approval_workflows.id                               |
| step_order           | tinyint unsigned | NO   | —       | ลำดับขั้นตอน (1, 2, 3, ...) — Sequential เท่านั้น      |
| approver_id          | bigint unsigned  | NO   | —       | FK → users.id (ผู้อนุมัติของขั้นนี้ 1 คน)              |
| label                | varchar(100)     | YES  | NULL    | ชื่อขั้นตอน เช่น "ผู้จัดการสาขา", "ผู้อำนวยการ"       |
| created_at           | timestamp        | YES  | NULL    | วันที่สร้าง                                             |
| updated_at           | timestamp        | YES  | NULL    | วันที่แก้ไขล่าสุด                                      |

---

#### 6. `approval_requests` — คำขออนุมัติ (R1.3)

| Column               | Type                                              | Null | Default | Description                                    |
|----------------------|---------------------------------------------------|------|---------|------------------------------------------------|
| id                   | bigint unsigned                                   | NO   | AI      | Primary Key                                    |
| code                 | varchar(50)                                       | NO   | —       | รหัสคำขอ (Unique ทั้งระบบ)                    |
| approval_type_id     | bigint unsigned                                   | NO   | —       | FK → approval_types.id                         |
| approval_workflow_id | bigint unsigned                                   | NO   | —       | FK → approval_workflows.id (สายที่ match)      |
| requester_id         | bigint unsigned                                   | NO   | —       | FK → users.id (ผู้ยื่นคำขอ)                   |
| amount               | decimal(15,2)                                     | YES  | NULL    | ยอดเงินของคำขอ                                |
| status               | enum('pending','approved','rejected','cancelled') | NO   | pending | สถานะรวมของคำขอ                               |
| title                | varchar(200)                                      | YES  | NULL    | หัวข้อคำขอ                                    |
| description          | text                                              | YES  | NULL    | รายละเอียด                                    |
| reference_type       | varchar(100)                                      | YES  | NULL    | Polymorphic type (เชื่อมกับเอกสารอื่น)        |
| reference_id         | bigint unsigned                                   | YES  | NULL    | Polymorphic id                                 |
| current_step         | tinyint unsigned                                  | NO   | 1       | ขั้นตอนปัจจุบันที่รออนุมัติ                  |
| submitted_at         | timestamp                                         | YES  | NULL    | เวลายื่นคำขอ                                  |
| completed_at         | timestamp                                         | YES  | NULL    | เวลาที่อนุมัติ/ปฏิเสธเสร็จสิ้น              |
| created_at           | timestamp                                         | YES  | NULL    | วันที่สร้าง                                   |
| updated_at           | timestamp                                         | YES  | NULL    | วันที่แก้ไขล่าสุด                            |

---

#### 7. `approval_request_steps` — ขั้นตอนของคำขอ (R1.4)

| Column              | Type                                  | Null | Default | Description                                  |
|---------------------|---------------------------------------|------|---------|----------------------------------------------|
| id                  | bigint unsigned                       | NO   | AI      | Primary Key                                  |
| approval_request_id | bigint unsigned                       | NO   | —       | FK → approval_requests.id                    |
| step_order          | tinyint unsigned                      | NO   | —       | ลำดับขั้น (ต้องผ่านทีละขั้น ไม่ข้ามขั้น)  |
| approver_id         | bigint unsigned                       | NO   | —       | FK → users.id (ผู้อนุมัติขั้นนี้)           |
| status              | enum('pending','approved','rejected') | NO   | pending | สถานะของขั้นนี้                             |
| rejection_reason    | text                                  | YES  | NULL    | เหตุผลปฏิเสธ (กรณี rejected)               |
| actioned_at         | timestamp                             | YES  | NULL    | เวลาที่ดำเนินการ                            |
| created_at          | timestamp                             | YES  | NULL    | วันที่สร้าง                                 |
| updated_at          | timestamp                             | YES  | NULL    | วันที่แก้ไขล่าสุด                          |

---

#### 8. `approval_histories` — ประวัติการอนุมัติ / Audit Log (R1.5)

> ⛔ **Immutable** — ห้าม UPDATE และ DELETE ทุกกรณี

| Column                   | Type             | Null | Default           | Description                                      |
|--------------------------|------------------|------|-------------------|--------------------------------------------------|
| id                       | bigint unsigned  | NO   | AI                | Primary Key                                      |
| approval_request_id      | bigint unsigned  | NO   | —                 | FK → approval_requests.id                        |
| approval_request_step_id | bigint unsigned  | YES  | NULL              | FK → approval_request_steps.id                   |
| actor_id                 | bigint unsigned  | NO   | —                 | FK → users.id (ผู้ดำเนินการ)                    |
| action                   | varchar(50)      | NO   | —                 | การกระทำ เช่น submitted, approved, rejected      |
| step_order               | tinyint unsigned | YES  | NULL              | ขั้นตอนที่เกิด action                           |
| rejection_reason         | text             | YES  | NULL              | เหตุผลปฏิเสธ (ถ้ามี)                           |
| note                     | text             | YES  | NULL              | หมายเหตุเพิ่มเติม                              |
| actioned_at              | timestamp        | NO   | CURRENT_TIMESTAMP | เวลาที่เกิด action (บันทึกอัตโนมัติ)           |
| created_at               | timestamp        | NO   | CURRENT_TIMESTAMP | วันที่สร้าง record (บันทึกอัตโนมัติ)           |

> หมายเหตุ: ตารางนี้ไม่มี `updated_at` เพื่อยืนยันความเป็น Immutable

---

#### 9. `car_models` — รุ่นรถยนต์ (R2.2)

| Column          | Type                                                              | Null | Default | Description                                        |
|-----------------|-------------------------------------------------------------------|------|---------|----------------------------------------------------|
| id              | bigint unsigned                                                   | NO   | AI      | Primary Key                                        |
| code            | varchar(255)                                                      | NO   | —       | รหัสรุ่น (Unique) รูปแบบ CM-XXXX                  |
| brand           | varchar(255)                                                      | NO   | —       | แบรนด์รถ เช่น Toyota, Honda, Ford                 |
| name            | varchar(255)                                                      | NO   | —       | ชื่อรุ่น เช่น Camry, Civic, Ranger                |
| year            | int                                                               | NO   | —       | ปี ค.ศ. ที่ผลิต                                   |
| body_type       | enum('Sedan','SUV','Pickup','Hatchback','Van',...)                | NO   | —       | ประเภทตัวถัง                                      |
| base_price      | decimal(12,2)                                                     | NO   | —       | ราคาตั้งต้น (ต้องมากกว่า 0)                       |
| is_active       | tinyint(1)                                                        | NO   | 1       | สถานะ (1=ใช้งาน, 0=ปิด)                           |
| deletion_reason | varchar(255)                                                      | YES  | NULL    | เหตุผลการลบ (R2.3)                                |
| deletion_detail | varchar(255)                                                      | YES  | NULL    | รายละเอียดเพิ่มเติมการลบ (R2.3)                  |
| deleted_at      | timestamp                                                         | YES  | NULL    | Soft Delete timestamp                              |
| created_at      | timestamp                                                         | YES  | NULL    | วันที่สร้าง                                       |
| updated_at      | timestamp                                                         | YES  | NULL    | วันที่แก้ไขล่าสุด                                |

---

#### 10. `notifications` — การแจ้งเตือน (R2.4)

| Column          | Type            | Null | Default | Description                                       |
|-----------------|-----------------|------|---------|---------------------------------------------------|
| id              | char(36)        | NO   | —       | UUID Primary Key                                  |
| type            | varchar(255)    | NO   | —       | Class ของ Notification                            |
| notifiable_type | varchar(255)    | NO   | —       | Polymorphic type (App\Models\User)                |
| notifiable_id   | bigint unsigned | NO   | —       | ID ของผู้รับ (users.id)                           |
| data            | text            | NO   | —       | JSON ข้อมูล notification                         |
| read_at         | timestamp       | YES  | NULL    | เวลาที่อ่าน (NULL = ยังไม่อ่าน)                 |
| created_at      | timestamp       | YES  | NULL    | วันที่สร้าง                                      |
| updated_at      | timestamp       | YES  | NULL    | วันที่แก้ไขล่าสุด                               |

---

*Generated for Hands-on Project Assessment: Dev — Toyota Test*