-- ========================================
-- IE-Photo-WEB Demo Data
-- รันใน phpMyAdmin หรือ mysql CLI
-- ========================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- อัปเดตชื่อจริงสมาชิก
UPDATE users SET first_name='วีรภัทร',  last_name='อำมะเกษ'  WHERE student_id='68030263';
UPDATE users SET first_name='สมชาย',    last_name='ใจดี'      WHERE student_id='68030260';
UPDATE users SET first_name='สุดาวรรณ', last_name='แก้วมณี'  WHERE student_id='68030262';
UPDATE users SET first_name='ธนพล',     last_name='รักเรียน'  WHERE student_id='68030266';

-- เพิ่มอุปกรณ์
INSERT INTO equipments (name, type, status) VALUES
('Sony A7 III',         'camera',    'available'),
('Canon EOS R5',        'camera',    'available'),
('Nikon Z6 II',         'camera',    'available'),
('DJI Ronin-S',         'accessory', 'available'),
('Godox AD200 Pro',     'accessory', 'available'),
('Tripod Manfrotto',    'accessory', 'available'),
('Lens 50mm f/1.8',     'lens',      'available'),
('Lens 24-70mm f/2.8',  'lens',      'available')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- เพิ่มสตูดิโอ
INSERT INTO studios (name, status) VALUES
('Studio A - White Cyc',     'available'),
('Studio B - Dark Theme',    'available'),
('Studio C - Outdoor Look',  'available')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- booking ตัวอย่าง (อุปกรณ์)
INSERT INTO bookings (booking_type, item_id, user_id, start_datetime, end_datetime, status, usage_reason, created_at) VALUES
('equipment', 1, (SELECT id FROM users WHERE student_id='68030260'), NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 4 DAY, 'returned',  'ถ่ายภาพพอร์ตเทรต', NOW() - INTERVAL 6 DAY),
('equipment', 2, (SELECT id FROM users WHERE student_id='68030262'), NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 2 DAY, 'approved',  'ถ่าย MV ชุมนุม',    NOW() - INTERVAL 4 DAY),
('equipment', 3, (SELECT id FROM users WHERE student_id='68030266'), NOW() + INTERVAL 1 DAY, NOW() + INTERVAL 2 DAY, 'pending',   'โปรเจกต์วิชาการ',   NOW() - INTERVAL 1 DAY),
('equipment', 5, (SELECT id FROM users WHERE student_id='68030260'), NOW() + INTERVAL 3 DAY, NOW() + INTERVAL 4 DAY, 'pending',   'ถ่ายสินค้า',         NOW()),
('equipment', 7, (SELECT id FROM users WHERE student_id='68030262'), NOW() - INTERVAL 1 DAY, NOW() + INTERVAL 1 DAY, 'approved',  'ทดสอบ lens ใหม่',   NOW() - INTERVAL 2 DAY),
('equipment', 2, (SELECT id FROM users WHERE student_id='68030266'), NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 7 DAY, 'rejected',  'ไม่ระบุเหตุผล',     NOW() - INTERVAL 9 DAY);

-- booking ตัวอย่าง (สตูดิโอ)
INSERT INTO bookings (booking_type, item_id, user_id, start_datetime, end_datetime, status, usage_reason, created_at) VALUES
('studio', 1, (SELECT id FROM users WHERE student_id='68030260'), NOW() + INTERVAL 2 DAY, NOW() + INTERVAL 2 DAY + INTERVAL 3 HOUR, 'pending',  'ถ่ายพอร์ตเทรต',      NOW()),
('studio', 2, (SELECT id FROM users WHERE student_id='68030266'), NOW() + INTERVAL 4 DAY, NOW() + INTERVAL 4 DAY + INTERVAL 2 HOUR, 'approved', 'Concept Dark Theme',  NOW() - INTERVAL 1 DAY),
('studio', 1, (SELECT id FROM users WHERE student_id='68030262'), NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY + INTERVAL 2 HOUR, 'returned', 'ถ่าย Product',        NOW() - INTERVAL 3 DAY);

-- Guest booking สตูดิโอ
INSERT INTO bookings (booking_type, item_id, guest_name, guest_email, start_datetime, end_datetime, status, usage_reason, created_at) VALUES
('studio', 1, 'ประยุทธ์ ดีมาก', 'guest@example.com', NOW() + INTERVAL 6 DAY, NOW() + INTERVAL 6 DAY + INTERVAL 2 HOUR, 'pending', 'ถ่ายรูปครอบครัว', NOW());

-- feeds
INSERT INTO feeds (booking_id, message, created_at) VALUES
(1, '📸 สมชาย ใจดี ยืม Sony A7 III สำเร็จ — ถ่ายภาพพอร์ตเทรต',  NOW() - INTERVAL 5 DAY),
(2, '🎬 สุดาวรรณ แก้วมณี ได้รับอนุมัติ Canon EOS R5 — ถ่าย MV', NOW() - INTERVAL 3 DAY),
(5, '🔭 สุดาวรรณ แก้วมณี กำลังใช้ Lens 50mm f/1.8',             NOW() - INTERVAL 1 DAY),
(8, '🏠 ธนพล รักเรียน จอง Studio B สำเร็จ — Dark Theme',         NOW() - INTERVAL 1 DAY),
(9, '✅ สุดาวรรณ คืนสตูดิโอ Studio A เรียบร้อย',                  NOW() - INTERVAL 2 DAY);

-- tasks
INSERT INTO tasks (title, description, assigned_by, assigned_to, status, due_date, created_at) VALUES
('เตรียมอุปกรณ์งานกีฬา',      'เช็คสภาพกล้องทุกตัวก่อนงาน',          (SELECT id FROM users WHERE student_id='68030263'), (SELECT id FROM users WHERE student_id='68030260'), 'pending',     NOW() + INTERVAL 7 DAY,  NOW() - INTERVAL 1 DAY),
('ถ่ายภาพกิจกรรมชมรม',        'ถ่ายภาพและจัดทำ album ลง drive',       (SELECT id FROM users WHERE student_id='68030263'), (SELECT id FROM users WHERE student_id='68030262'), 'in_progress', NOW() + INTERVAL 3 DAY,  NOW() - INTERVAL 2 DAY),
('ทำ catalog อุปกรณ์ใหม่',    'ถ่ายรูปอุปกรณ์ทุกชิ้นเพื่ออัปเดตระบบ', (SELECT id FROM users WHERE student_id='68030263'), (SELECT id FROM users WHERE student_id='68030266'), 'pending',     NOW() + INTERVAL 14 DAY, NOW()),
('คืนอุปกรณ์หลังใช้งาน',      'ตรวจสอบและส่งคืน Canon EOS R5',        (SELECT id FROM users WHERE student_id='68030263'), (SELECT id FROM users WHERE student_id='68030262'), 'completed',   NOW() - INTERVAL 1 DAY,  NOW() - INTERVAL 4 DAY);

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'Demo data inserted successfully!' AS result;
