    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Footer -->
    <footer class="glass-footer">
        <p>&copy; <?php echo date('Y'); ?> IE-Photo Maker — KMITL Faculty of Ind. Ed. & Tech.</p>
    </footer>

    <!-- Bottom Navigation (Mobile) -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <nav class="bottom-nav" id="bottom-nav">
        <div class="bottom-nav-inner">
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="<?php echo $base_url; ?>admin/dashboard.php" class="<?php echo isActive('dashboard');?>">
                    <i class="ph<?php echo isActive('dashboard') ? '-fill' : ''; ?> ph-squares-four"></i>
                    <span>หน้าหลัก</span>
                </a>
                <a href="<?php echo $base_url; ?>admin/bookings.php" class="<?php echo isActive('bookings');?>">
                    <i class="ph<?php echo isActive('bookings') ? '-fill' : ''; ?> ph-list-checks"></i>
                    <span>จองทั้งหมด</span>
                </a>
                <a href="<?php echo $base_url; ?>member/borrow_form.php" class="<?php echo isActive('borrow_form');?>" style="<?php echo isActive('borrow_form') ? '' : 'color:var(--primary);'; ?>">
                    <i class="ph-bold ph-plus-circle" style="font-size:1.6rem"></i>
                    <span>ยืมอุปกรณ์</span>
                </a>
                <a href="<?php echo $base_url; ?>admin/tasks.php" class="<?php echo isActive('tasks');?>">
                    <i class="ph<?php echo isActive('tasks') ? '-fill' : ''; ?> ph-kanban"></i>
                    <span>งาน</span>
                </a>
                <a href="<?php echo $base_url; ?>member/profile.php" class="<?php echo isActive('profile');?>">
                    <i class="ph<?php echo isActive('profile') ? '-fill' : ''; ?> ph-user-circle"></i>
                    <span>โปรไฟล์</span>
                </a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>member/feed.php" class="<?php echo isActive('feed');?>">
                    <i class="ph<?php echo isActive('feed') ? '-fill' : ''; ?> ph-house"></i>
                    <span>ฟีด</span>
                </a>
                <a href="<?php echo $base_url; ?>member/my_bookings.php" class="<?php echo isActive('my_bookings');?>">
                    <i class="ph<?php echo isActive('my_bookings') ? '-fill' : ''; ?> ph-list-dashes"></i>
                    <span>การจอง</span>
                </a>
                <a href="<?php echo $base_url; ?>member/borrow_form.php" class="<?php echo isActive('borrow_form');?>" style="<?php echo isActive('borrow_form') ? '' : 'color:var(--primary);'; ?>">
                    <i class="ph-bold ph-plus-circle" style="font-size:1.6rem"></i>
                    <span>ยืม</span>
                </a>
                <a href="<?php echo $base_url; ?>member/my_tasks.php" class="<?php echo isActive('my_tasks');?>">
                    <i class="ph<?php echo isActive('my_tasks') ? '-fill' : ''; ?> ph-kanban"></i>
                    <span>งาน</span>
                </a>
                <a href="<?php echo $base_url; ?>member/profile.php" class="<?php echo isActive('profile');?>">
                    <i class="ph<?php echo isActive('profile') ? '-fill' : ''; ?> ph-user-circle"></i>
                    <span>โปรไฟล์</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

    <script>var baseUrl = '<?php echo isset($base_url) ? $base_url : "../"; ?>';</script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/main.js"></script>
    <script src="<?php echo isset($base_url) ? $base_url : '../'; ?>assets/js/ajax-handler.js"></script>

    <script>
    /* Toast helper — canonical definition (ใช้ทั้ง 'danger' และ 'error' เป็น ❌) */
    function showToast(msg, type) {
        const c = document.getElementById('toast-container');
        if (!c) return;
        const t = document.createElement('div');
        t.className = 'toast';
        const icons = {success:'✅', error:'❌', danger:'❌', info:'ℹ️', warning:'⚠️'};
        const colors = {success:'var(--success)', error:'var(--danger)', danger:'var(--danger)', warning:'var(--warning)', info:'var(--info)'};
        t.innerHTML = '<span>' + (icons[type] || 'ℹ️') + '</span> <span>' + msg + '</span>';
        if (colors[type]) t.style.borderLeft = '3px solid ' + colors[type];
        c.appendChild(t);
        requestAnimationFrame(() => t.classList.add('show'));
        setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 400); }, 4000);
    }
    </script>
</body>
</html>
