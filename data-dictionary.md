# Data Dictionary — ระบบสายอนุมัติ (Approval Chain)

---

## ตาราง: `approval_types` — ประเภทการอนุมัติ

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| code | VARCHAR(50) | NO | — | รหัสประเภท เช่น `QUOTATION`, `BOOKING` (unique) |
| name | VARCHAR(100) | NO | — | ชื่อประเภทการอนุมัติ เช่น "อนุมัติใบเสนอราคา" |
| description | TEXT | YES | NULL | คำอธิบายเพิ่มเติม |
| is_active | TINYINT(1) | NO | 1 | สถานะใช้งาน (1=ใช้งาน, 0=ปิด) |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ตาราง: `branches` — สาขา

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| code | VARCHAR(50) | NO | — | รหัสสาขา เช่น `BKK01` (unique) |
| name | VARCHAR(100) | NO | — | ชื่อสาขา เช่น "สาขาบางนา" |
| is_active | TINYINT(1) | NO | 1 | สถานะใช้งาน |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ตาราง: `approval_workflows` — สายอนุมัติ

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| approval_type_id | BIGINT UNSIGNED | NO | — | FK → `approval_types.id` ประเภทการอนุมัติ |
| branch_id | BIGINT UNSIGNED | YES | NULL | FK → `branches.id` สาขาที่ใช้ (NULL = ทุกสาขา) |
| name | VARCHAR(100) | NO | — | ชื่อสายอนุมัติ เช่น "สายอนุมัติใบจอง สาขาบางนา" |
| started_at | DATE | NO | — | วันเริ่มต้นการใช้งาน |
| ended_at | DATE | YES | NULL | วันสิ้นสุดการใช้งาน (NULL = ยังใช้งานอยู่) |
| is_active | TINYINT(1) | NO | 1 | สถานะใช้งาน |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ตาราง: `approval_workflow_conditions` — เงื่อนไขตามช่วงยอดเงิน

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| approval_workflow_id | BIGINT UNSIGNED | NO | — | FK → `approval_workflows.id` สายอนุมัติ |
| min_amount | DECIMAL(15,2) | NO | 0.00 | ยอดเงินขั้นต่ำของช่วง (inclusive) |
| max_amount | DECIMAL(15,2) | YES | NULL | ยอดเงินสูงสุดของช่วง (inclusive, NULL = ไม่จำกัด) |
| required_steps | TINYINT UNSIGNED | NO | — | จำนวนขั้นตอนอนุมัติที่ต้องผ่านในช่วงนี้ |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

**ตัวอย่างข้อมูล:**
| min_amount | max_amount | required_steps |
|---|---|---|
| 0 | 3,000 | 2 |
| 3,000.01 | 5,000 | 3 |
| 5,000.01 | NULL | 4 |

---

## ตาราง: `approval_steps` — ขั้นตอนอนุมัติในสายอนุมัติ

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| approval_workflow_id | BIGINT UNSIGNED | NO | — | FK → `approval_workflows.id` สายอนุมัติที่สังกัด |
| step_order | TINYINT UNSIGNED | NO | — | ลำดับขั้นตอน (1, 2, 3, ...) |
| approver_id | BIGINT UNSIGNED | NO | — | FK → `users.id` ผู้อนุมัติประจำขั้นนี้ |
| label | VARCHAR(100) | YES | NULL | ชื่อขั้นตอน เช่น "ผู้จัดการสาขา", "ผู้อำนวยการ" |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

**หมายเหตุ:** คู่ `(approval_workflow_id, step_order)` ต้อง unique

---

## ตาราง: `approval_requests` — คำขออนุมัติ

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| code | VARCHAR(50) | NO | — | รหัสคำขอ unique เช่น `REQ-20240001` |
| approval_type_id | BIGINT UNSIGNED | NO | — | FK → `approval_types.id` ประเภทการอนุมัติ |
| approval_workflow_id | BIGINT UNSIGNED | NO | — | FK → `approval_workflows.id` สายอนุมัติที่ match |
| requester_id | BIGINT UNSIGNED | NO | — | FK → `users.id` ผู้ยื่นคำขอ |
| amount | DECIMAL(15,2) | YES | NULL | ยอดเงินที่เกี่ยวข้อง (ใช้ match เงื่อนไข) |
| status | ENUM | NO | `pending` | สถานะรวม: `pending`, `approved`, `rejected`, `cancelled` |
| title | VARCHAR(200) | YES | NULL | หัวเรื่องคำขอ |
| description | TEXT | YES | NULL | รายละเอียดคำขอ |
| reference_type | VARCHAR(100) | YES | NULL | ประเภท document ที่อ้างอิง เช่น `App\Models\Quotation` |
| reference_id | BIGINT UNSIGNED | YES | NULL | ID ของ document ที่อ้างอิง |
| current_step | TINYINT UNSIGNED | NO | 1 | ขั้นตอนปัจจุบันที่รออนุมัติ |
| submitted_at | TIMESTAMP | YES | NULL | วันเวลาที่ยื่นคำขอ |
| completed_at | TIMESTAMP | YES | NULL | วันเวลาที่อนุมัติ/ปฏิเสธเสร็จสิ้น |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ตาราง: `approval_request_steps` — ขั้นตอนของคำขออนุมัติ

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| approval_request_id | BIGINT UNSIGNED | NO | — | FK → `approval_requests.id` คำขออนุมัติ |
| step_order | TINYINT UNSIGNED | NO | — | ลำดับขั้นตอน |
| approver_id | BIGINT UNSIGNED | NO | — | FK → `users.id` ผู้อนุมัติประจำขั้นนี้ |
| status | ENUM | NO | `pending` | สถานะขั้นนี้: `pending`, `approved`, `rejected` |
| rejection_reason | TEXT | YES | NULL | เหตุผลการปฏิเสธ (กรอกเมื่อ status = `rejected`) |
| actioned_at | TIMESTAMP | YES | NULL | วันเวลาที่ action (อนุมัติ/ปฏิเสธ) |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

**หมายเหตุ:** คู่ `(approval_request_id, step_order)` ต้อง unique

---

## ตาราง: `approval_histories` — ประวัติการอนุมัติ (Audit Log)

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| approval_request_id | BIGINT UNSIGNED | NO | — | FK → `approval_requests.id` คำขออนุมัติ |
| approval_request_step_id | BIGINT UNSIGNED | YES | NULL | FK → `approval_request_steps.id` ขั้นตอนที่เกี่ยวข้อง |
| actor_id | BIGINT UNSIGNED | NO | — | FK → `users.id` ผู้กระทำ action |
| action | VARCHAR(50) | NO | — | การกระทำ: `submitted`, `approved`, `rejected`, `cancelled` |
| step_order | TINYINT UNSIGNED | YES | NULL | ขั้นตอนที่เกิด action (snapshot) |
| rejection_reason | TEXT | YES | NULL | เหตุผลปฏิเสธ (snapshot ณ เวลานั้น) |
| note | TEXT | YES | NULL | หมายเหตุเพิ่มเติม |
| actioned_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | วันเวลาที่เกิด action |

> **หมายเหตุสำคัญ:** ตารางนี้ห้าม UPDATE หรือ DELETE ทุกกรณี ใช้เพื่อ audit log เท่านั้น ไม่มี `updated_at`

---

## ตาราง: `car_models` — รุ่นรถยนต์ (Part 2)

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| code | VARCHAR(20) | NO | — | รหัสรุ่น unique รูปแบบ `CM-XXXX` เช่น `CM-0001` |
| brand | VARCHAR(50) | NO | — | แบรนด์รถ เช่น Toyota, Honda, Ford |
| name | VARCHAR(100) | NO | — | ชื่อรุ่น เช่น Camry, Civic, Ranger |
| year | SMALLINT UNSIGNED | NO | — | ปีรถ (ค.ศ.) เช่น 2024 |
| body_type | ENUM | NO | — | ประเภทรถ: `Sedan`, `SUV`, `Pickup`, `Hatchback`, `Van` |
| base_price | DECIMAL(12,2) | NO | — | ราคาเริ่มต้น (ต้องมากกว่า 0) |
| is_active | TINYINT(1) | NO | 1 | สถานะใช้งาน (1=ใช้งาน, 0=ปิด) |
| deletion_reason | VARCHAR(100) | YES | NULL | เหตุผลการลบ (กรอกก่อน soft delete) |
| deletion_detail | TEXT | YES | NULL | รายละเอียดเหตุผลการลบเพิ่มเติม |
| deleted_at | TIMESTAMP | YES | NULL | Soft delete timestamp |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ตาราง: `users` — ผู้ใช้งาน

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| name | VARCHAR(100) | NO | — | ชื่อผู้ใช้ |
| email | VARCHAR(150) | NO | — | อีเมล (unique) |
| password | VARCHAR(255) | NO | — | รหัสผ่าน (hashed) |
| email_verified_at | TIMESTAMP | YES | NULL | วันเวลายืนยันอีเมล |
| remember_token | VARCHAR(100) | YES | NULL | Token สำหรับ Remember Me |
| created_at | TIMESTAMP | YES | NULL | วันเวลาที่สร้าง |
| updated_at | TIMESTAMP | YES | NULL | วันเวลาที่แก้ไขล่าสุด |

---

## ความสัมพันธ์ระหว่างตาราง (Relationships)

```
approval_types ──< approval_workflows >── branches
approval_workflows ──< approval_workflow_conditions
approval_workflows ──< approval_steps >── users
approval_requests >── approval_types
approval_requests >── approval_workflows
approval_requests >── users (requester)
approval_requests ──< approval_request_steps >── users (approver)
approval_requests ──< approval_histories >── users (actor)
approval_request_steps ──< approval_histories
```

---

## Enum Values Summary

| ตาราง | Column | ค่าที่รองรับ |
|---|---|---|
| approval_requests | status | `pending`, `approved`, `rejected`, `cancelled` |
| approval_request_steps | status | `pending`, `approved`, `rejected` |
| approval_histories | action | `submitted`, `approved`, `rejected`, `cancelled` |
| car_models | body_type | `Sedan`, `SUV`, `Pickup`, `Hatchback`, `Van` |
