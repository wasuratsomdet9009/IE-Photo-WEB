// assets/js/main.js
// showToast ถูก define ใน includes/footer.php แล้ว — ไม่ต้อง define ซ้ำที่นี่

document.addEventListener('DOMContentLoaded', function() {
    // Highlight active nav link based on current path
    var currentPath = window.location.pathname;
    document.querySelectorAll('.nav-links a').forEach(function(link) {
        var href = link.getAttribute('href');
        if (!href) return;
        // normalize href: strip leading '../' sequences
        var normalized = href.replace(/^(\.\.\/)+/, '');
        if (currentPath.endsWith(normalized) || currentPath.includes('/' + normalized)) {
            link.style.color = 'var(--primary)';
            link.style.fontWeight = '700';
        }
    });
});
