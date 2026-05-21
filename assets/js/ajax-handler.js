// assets/js/ajax-handler.js

document.addEventListener('DOMContentLoaded', () => {

    // Handle Feed Like Button
    document.querySelectorAll('.btn-like').forEach(btn => {
        btn.addEventListener('click', function() {
            const feedId = this.dataset.feedId;
            const isLiked = this.classList.contains('liked');
            this.disabled = true;

            fetch(`${baseUrl}api/do_like_feed.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `feed_id=${feedId}&action=${isLiked ? 'unlike' : 'like'}`
            })
            .then(r => r.json())
            .then(data => {
                this.disabled = false;
                if (data.success) {
                    this.classList.toggle('liked');
                    this.querySelector('.like-count').textContent = data.new_count;
                    this.style.color = this.classList.contains('liked') ? 'var(--danger)' : 'var(--text-muted)';
                } else {
                    showToast(data.message || 'เกิดข้อผิดพลาด', 'danger');
                }
            })
            .catch(() => {
                this.disabled = false;
                showToast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'danger');
            });
        });
    });

    // Handle Urgent Call Button
    document.querySelectorAll('.btn-urgent-call').forEach(btn => {
        btn.addEventListener('click', function() {
            const receiverId = this.dataset.receiverId;
            if (!confirm('ยืนยันการส่งการติดต่อด่วนถึงสมาชิกนี้?')) return;

            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="ph-bold ph-spinner"></i> กำลังส่ง...';

            fetch(`${baseUrl}api/do_urgent_call.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `receiver_id=${receiverId}`
            })
            .then(r => r.json())
            .then(data => {
                this.disabled = false;
                this.innerHTML = originalHTML;
                if (data.success) {
                    showToast('ส่งการติดต่อด่วนสำเร็จ', 'success');
                } else {
                    showToast(data.message || 'ส่งไม่สำเร็จ กรุณาลองอีกครั้ง', 'danger');
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = originalHTML;
                showToast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'danger');
            });
        });
    });
});
