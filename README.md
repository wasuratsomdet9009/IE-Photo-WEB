# IE-Photo-WEB (ส่งต่องานให้มึงอะ อ่านด้วย)

ระบบจองสตู ยืมคืนอุปกรณ์ จัดการงานภายในครบ จบในเว็บเดียว  
ถ้าพังไม่ต้องทักกูทันที ลองอ่านไฟล์นี้ก่อน ไอ้เวร

---

# โครงสร้างหลักของระบบ
## Guest / Public
- จองสตูดิโอ
- สมัครสมาชิก / Login

ไฟล์หลัก:
- `guest/studio_booking.php`
- `auth/register.php`
- `auth/login.php`

---

## Member
ทำได้ประมาณนี้

- แก้โปรไฟล์
- ดูประวัติการจอง
- ดูปฏิทินคิวจอง
- ยืม-คืนอุปกรณ์
- ดูงานที่โดนสั่ง
- ดู Feed / กด Like
- ดูรายชื่อผู้ติดต่อ

ไฟล์หลัก:
- `profile.php`
- `my_bookings.php`
- `calendar.php`
- `borrow_form.php`
- `my_tasks.php`
- `feed.php`

API:
- `api/do_like_feed.php`

---

## Admin
ของแอดมิน

ทำได้:
- ดู Dashboard
- อนุมัติ/ปฏิเสธจองสตู
- จัดการอุปกรณ์
- มอบหมายงาน
- จัดการรายชื่อผู้ติดต่อ

ไฟล์:
- `admin/dashboard.php`
- `admin/bookings.php`
- `admin/inventory.php`
- `admin/tasks.php`
- `admin/contact_manage.php`

---

# Background Services
## Email Notification
ใช้ EmailJS ส่งเมลแจ้งเตือน

ไฟล์:
- `config/emailjs.php`

## Notifications
- `api/get_notifications.php`

## ระบบเรียกตัวฉุกเฉิน
- `api/do_urgent_call.php`

## UI
Glassmorphism CSS

- `assets/css/glassmorphism.css`

---

# Database Config

ไฟล์:

```txt
config/database.php
```

ค่าปัจจุบันของ Server จริง

```php
$host = 'localhost';
$dbname = 'iephotoo_booking';
$username = 'iephotoo_root2';
$password = '2nghJjgwmSdmChnWu37b';
$charset = 'utf8mb4';
```

---

# EmailJS Config

ไฟล์:

```txt
config/emailjs.php
```

```php
define('EMAILJS_SERVICE_ID', 'service_1a47klq');
define('EMAILJS_PUBLIC_KEY', 'kEpZ0GchxY4JmPsjT');
define('EMAILJS_PRIVATE_KEY', 'pY-pMUobnuwEj6FOtDFrq');

define('EMAILJS_TEMPLATE_BOOKING_APPROVED', 'BOOKING_APPROVED45');
define('EMAILJS_TEMPLATE_BOOKING_PENDING', 'BOOKING_PENDING23');
```

ถ้าจะเปลี่ยนบัญชี EmailJS
ก็เปลี่ยน Keys ใหม่เอง อย่ามาถามกูว่า “ทำไมเมลไม่ส่ง” ทั้งที่มึงลบ key ทิ้งเอง ไอสัส

---

# วิธีติดตั้งระบบบน XAMPP (Localhost)

---

# 1️ ดาวน์โหลด XAMPP

โหลดจากเว็บทางการ:

```txt
https://www.apachefriends.org/
```

โหลดตัวล่าสุดมาเลย
Windows ก็กด Windows ไป อย่ากด Linux ถ้ามึงใช้ Windows อยู่ เดี๋ยวจะโง่เกินไป

---

# 2️ ติดตั้ง XAMPP

## ขั้นตอนติดตั้ง

1. ดาวน์โหลดเปิดไฟล์ติดตั้ง
2. กด Next รัวๆ
3. เลือก Component:

   * Apache
   * MySQL
   * phpMyAdmin
4. เลือกที่ติดตั้ง:

```txt
C:\xampp
```

5. กด Install

รอจนเสร็จ

---

# 3️ เปิด XAMPP

เปิด:

```txt
XAMPP Control Panel
```

กด Start:

* Apache
* MySQL

ถ้ามันขึ้นสีเขียวคือใช้ได้

---

# 4️ นำโปรเจกต์เข้า XAMPP

เอาโฟลเดอร์โปรเจกต์ทั้งหมดไปไว้ที่:

```txt
C:\xampp\htdocs\IE-Photo-WEB
```

ถ้าวางผิด path แล้วเว็บไม่ขึ้น
ก็ไม่ต้องตกใจ มึงวางผิดเอง

---

# 5️ สร้าง Database

เปิด Browser ไปที่:

```txt
http://localhost/phpmyadmin
```

---

## สร้างฐานข้อมูลใหม่

ชื่อ: iephotoo_booking.sql

```txt
iephotoo_booking.sql
```

Collation:

```txt
utf8mb4_general_ci
```

---

# 6️ Import Database

1. กดเข้า Database
2. กดเมนู Import
3. เลือกไฟล์ `.sql`
4. กด Import

รอจนเสร็จ

ถ้ามันแดง
อ่าน Error บ้าง ไม่ใช่กดมั่วเหมือนลิงเล่นคอม

---

# 7️ แก้ Config Database สำหรับ Localhost

เข้าไฟล์:

```txt
config/database.php
```

แก้เป็น:

```php
$host = 'localhost';
$dbname = 'iephotoo_booking';
$username = 'root';
$password = '';
$charset = 'utf8mb4';
```

---

# 8️ เปิดใช้งานระบบ
## หน้าแรก

```url
http://localhost/IE-Photo-WEB/
```

---

## หน้า Login

```url
http://localhost/IE-Photo-WEB/auth/login.php
```

---

# วิธีขึ้น Server จริง

---

# สิ่งที่ต้องเช็ค
## Database

แก้ `config/database.php` ให้ตรงกับ Hosting

ของเดิม
```php
$host = 'localhost';
$dbname = 'iephotoo_booking';
$username = 'iephotoo_root2'; // Change this appropriately
$password = '2nghJjgwmSdmChnWu37b';     // Change this appropriately
$charset = 'utf8mb4';
```

---

## EmailJS

เช็ค:

```txt
config/emailjs.php
```

ถ้า Key หมดอายุ
เมลจะไม่ส่ง

---

## File Permission

บาง Hosting ต้อง chmod เอง

---

# จุดที่ชอบพัง
## CSS ไม่โหลด

เช็ค:

* path
* .htaccess
* base url

---

## Login ไม่ได้

เช็ค:

* session
* database
* password hash

---

## Email ไม่ส่ง

เช็ค:

* EmailJS Key
* Internet
* Console Error

---

## รูปไม่ขึ้น

เช็ค:

* uploads/
* permission
* path รูป

---

# โฟลเดอร์สำคัญ

```txt
admin/
auth/
guest/
api/
assets/
config/
uploads/
```

---

# Tech Stack

* PHP
* MySQL
* JavaScript
* HTML/CSS
* EmailJS
* XAMPP

---

# หมายเหตุ

* อย่าแก้ database มั่ว
* Backup ก่อนแก้ระบบ
* อย่าลบไฟล์ใน uploads ถ้ายังมีข้อมูลใน DB
* ถ้าจะแก้ UI เช็ค responsive ด้วย

---

# สุดท้าย

ระบบมันทำงานได้อยู่แล้ว

ถ้าพัง:

* 10% = บัคจริง
* 90% = มึงแก้อะไรแปลกๆ เอง
