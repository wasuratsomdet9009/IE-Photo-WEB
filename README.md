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

# 4️ นำโปรเจกต์เข้า XAMPP (เน้นยํ่า❗️)

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

---

# Changelog — สิ่งที่แก้ไขทั้งหมด

---

## 🔐 Security Fixes

### `admin/users.php`
- เพิ่ม CSRF token ใน **ทั้งสอง form** (Desktop Table + Mobile Cards)
  - ก่อนหน้านี้ Desktop form ขาด `<input type="hidden" name="csrf_token">` ทำให้ได้รับ error "คำขอไม่ถูกต้อง"
- เพิ่มการ generate และ validate CSRF token ด้วย `hash_equals()` + `bin2hex(random_bytes(32))`
- เพิ่มฟีเจอร์ **รีเซ็ตรหัสผ่าน**: admin เปิด modal กรอกรหัสใหม่ + ยืนยัน พร้อม real-time check ตรงกัน
- เพิ่มฟีเจอร์ **ลบบัญชี**: modal ยืนยันก่อนลบ — ไม่สามารถลบบัญชีตัวเองได้
- แสดงสถานะยืนยันอีเมล (badge) ในตารางและ mobile card

### `config/database.php`
- `display_errors` แสดงเฉพาะบน localhost เท่านั้น — production ไม่โชว์ error ให้ user เห็น
- Error message บน production ซ่อน DB details ไว้ แสดงแค่ "ระบบขัดข้องชั่วคราว"

### `admin/bookings.php`
- ป้องกัน XSS: เพิ่ม `htmlspecialchars($success)`
- เปลี่ยนการตรวจสอบ MIME type ของรูปภาพจาก extension เป็น `finfo_file()` (ตรวจ content จริง)
- แก้ logic สถานะอุปกรณ์: reject → **ไม่** เปลี่ยนสถานะอุปกรณ์, คืนของ → available, อนุมัติ → borrowed

### `admin/contact_manage.php`
- เพิ่ม `htmlspecialchars()` ให้ `$custom_msg` ก่อน embed ใน email (ป้องกัน XSS injection)

### `admin/tasks.php`
- ป้องกัน XSS: เพิ่ม `htmlspecialchars($success)`
- แก้ email template: ใช้ student_id จริงของผู้รับงาน แทน hardcode `'Admin'`

### `admin/inventory.php`
- ป้องกัน XSS: เพิ่ม `htmlspecialchars($success)`

### `member/borrow_form.php`
- เปลี่ยนการตรวจสอบ MIME type เป็น `finfo_file()` (ป้องกัน extension spoofing)
- เพิ่มการตรวจ booking conflict: ไม่ให้จองอุปกรณ์ซ้ำช่วงเวลาเดิม

### `member/my_bookings.php`
- เปลี่ยนการตรวจสอบ MIME type เป็น `finfo_file()`
- ลบ dead code: `$newStatus = $return_image ? 'pending_return' : 'pending_return'`

### `member/profile.php`
- ป้องกัน PHP 8.1+ TypeError: เพิ่ม `$user['phone'] ?? ''` (NULL safety)
- เปลี่ยนการตรวจสอบ MIME type เป็น `finfo_file()`

---

## 🐛 Bug Fixes

### `guest/studio_booking.php`
- แก้ query `status = 'open'` → `status = 'available'` (ก่อนหน้าสตูดิโอไม่แสดงเลย เพราะ DB เก็บค่า `'available'`)
- เพิ่มการตรวจ booking conflict ก่อนสร้าง booking ใหม่

### `member/contact_list.php`
- ลบ column `contact_status` ออกจาก query (column ไม่มีใน DB → หน้าพังทันที)
- เพิ่ม `first_name`, `last_name` ใน query แสดงชื่อ-นามสกุลแทน

### `admin/contact_manage.php`
- เปลี่ยนจาก `INNER JOIN` → `LEFT JOIN` (ก่อนหน้าการจองสตูดิโอหายไปจากหน้าจัดการ)

### `auth/logout.php`
- แก้ redirect path `../auth/login.php` → `login.php` (path ซ้ำซ้อน)

### `includes/footer.php`
- แก้ `showToast()` ให้รองรับทั้ง `'danger'` และ `'error'` → แสดง ❌ และ border สีแดง

### `assets/js/main.js`
- ลบ `showToast()` ที่ define ซ้ำออก (canonical อยู่ใน `footer.php` แล้ว)

---

## ✨ New Features

### `auth/register.php`
- เพิ่มช่อง **ชื่อจริง** และ **นามสกุล** (แสดงแบบ side-by-side ด้วย `form-row`)
- เพิ่มปุ่ม show/hide password (`togglePwd()`)
- เพิ่ม validation: `mb_strlen()` สำหรับชื่อ-นามสกุล, phone regex `/^0[0-9]{8,9}$/`, password max 255
- Sticky values: ค่าที่กรอกไว้จะยังอยู่เมื่อ form มี error

### `auth/login.php`
- เพิ่ม validation: ถ้ากรอก email (มี `@`) ต้องเป็น `@kmitl.ac.th` เท่านั้น
- เพิ่ม JS real-time check: พิมพ์ email ผิด domain → ขึ้น hint + disable ปุ่ม login ทันที
- อัปเดต placeholder ให้ชัดเจนขึ้น
- Sticky value สำหรับ identifier field

---

## 📱 UI / CSS Fixes

### `assets/css/glassmorphism.css`
- แก้ z-index ทั้งระบบ (nav-overlay เคยบัง glass-navbar ทำให้กดลิงก์ไม่ได้):
  ```
  bg-orbs: -1
  bottom-nav: 1050
  glass-navbar: 1056
  nav-overlay: 900
  nav-links (mobile): 1060
  mobile-toggle: 1060
  toast: 9999
  ```
- เปลี่ยน nav-overlay จาก `display:none/block` → `visibility/opacity` (รองรับ CSS transition)
- ย้าย `overflow-x:hidden` จาก `body` → `html` (แก้ iOS Safari กับ `position:fixed`)
- เพิ่ม global touch fix:
  ```css
  a, button, [role="button"], input, select, textarea, label {
      touch-action: manipulation;
  }
  ```
- Nav hover transform ทำงานเฉพาะ desktop (`@media min-width: 951px`)

### `includes/header.php`
- เขียน JavaScript navbar ใหม่ทั้งหมดเป็น IIFE
- รองรับ `touchend` บน overlay, `stopPropagation` บนลิงก์ใน nav
- ล็อก body scroll เมื่อเมนู mobile เปิดอยู่
- รองรับ Escape key และ orientationchange

---

## 🌐 Deployment (Local Tunnel)

### ngrok → Cloudflare Tunnel
- เปลี่ยนจาก ngrok free tier ไปใช้ **Cloudflare Tunnel (cloudflared)**
- ngrok free tier มี browser warning interstitial — CSS/JS ไม่โหลดบน Instagram/LINE in-app browser และ iOS Safari
- cloudflared ไม่มี warning page → CSS โหลดได้ทุก browser ทุก device

**วิธีรัน Cloudflare Tunnel:**
```powershell
# เปิด Apache
Start-Process "C:\xampp\apache\bin\httpd.exe" -WindowStyle Hidden

# เปิด MySQL
Start-Process "C:\xampp\mysql\bin\mysqld.exe" -WorkingDirectory "C:\xampp\mysql\bin" -WindowStyle Hidden

# เปิด Cloudflare Tunnel
C:\Users\weerapat\cloudflared.exe tunnel --url http://localhost:80
```

> URL จะเปลี่ยนทุกครั้งที่รีสตาร์ท ถ้าอยากได้ URL คงที่ให้สมัคร Cloudflare account ฟรีแล้วสร้าง Named Tunnel

---

## 📋 สรุปไฟล์ที่ถูกแก้ไข

| ไฟล์ | สิ่งที่แก้ |
|---|---|
| `assets/css/glassmorphism.css` | z-index, touch fix, overflow, nav-overlay |
| `includes/header.php` | Navbar JS rewrite |
| `includes/footer.php` | showToast รองรับ danger/error |
| `assets/js/main.js` | ลบ showToast ซ้ำ |
| `config/database.php` | display_errors, production error message |
| `auth/login.php` | @kmitl.ac.th validation, sticky value |
| `auth/register.php` | เพิ่มชื่อ-นามสกุล, password toggle |
| `auth/logout.php` | แก้ redirect path |
| `guest/studio_booking.php` | status fix, conflict check |
| `member/contact_list.php` | ลบ column ที่ไม่มีใน DB |
| `member/borrow_form.php` | MIME check, conflict check |
| `member/my_bookings.php` | MIME check, ลบ dead code |
| `member/profile.php` | NULL safety, MIME check |
| `admin/users.php` | CSRF token ครบทั้ง 2 form |
| `admin/bookings.php` | XSS, MIME check, equipment status |
| `admin/contact_manage.php` | LEFT JOIN, XSS fix |
| `admin/tasks.php` | XSS, email student_id |
| `admin/inventory.php` | XSS fix |
