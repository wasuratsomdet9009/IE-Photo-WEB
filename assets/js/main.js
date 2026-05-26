// assets/js/main.js — Global UX Behaviors
// showToast ถูก define ใน includes/footer.php แล้ว

document.addEventListener('DOMContentLoaded', function () {

    /* ─── 1. Active nav highlight ────────────────────────────────── */
    var currentPath = window.location.pathname;
    document.querySelectorAll('.nav-links a').forEach(function (link) {
        var href = link.getAttribute('href');
        if (!href) return;
        var normalized = href.replace(/^(\.\.\/)+/, '');
        if (currentPath.endsWith(normalized) || currentPath.includes('/' + normalized)) {
            link.style.color = 'var(--primary)';
            link.style.fontWeight = '700';
        }
    });

    /* ─── 2. Form submit → loading button ───────────────────────── */
    document.querySelectorAll('form').forEach(function (form) {
        if (form.dataset.noLoading) return;
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[type="submit"]');
            if (!btn || btn.classList.contains('btn-loading')) return;
            if (!btn.querySelector('.btn-text')) {
                btn.innerHTML = '<span class="btn-text">' + btn.innerHTML + '</span>';
            }
            btn.classList.add('btn-loading');
            setTimeout(function () { btn.classList.remove('btn-loading'); }, 10000);
        });
    });

    /* ─── 3. Auto-dismiss success alerts (5s) ───────────────────── */
    document.querySelectorAll('.alert-success').forEach(function (alert) {
        var bar = document.createElement('div');
        bar.className = 'alert-dismiss-bar';
        alert.appendChild(bar);
        setTimeout(function () {
            alert.style.transition = 'opacity .4s,transform .4s,max-height .5s,margin .4s,padding .4s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-4px)';
            alert.style.maxHeight = '0';
            alert.style.margin = '0';
            alert.style.padding = '0';
            alert.style.overflow = 'hidden';
            setTimeout(function () { alert.remove(); }, 500);
        }, 5000);
    });

    /* ─── 4. Scroll to first error ──────────────────────────────── */
    var firstError = document.querySelector('.alert-danger, .field-error');
    if (firstError) {
        setTimeout(function () {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 120);
    }

    /* ─── 5. Lazy image loading ─────────────────────────────────── */
    if ('IntersectionObserver' in window) {
        var imgObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var img = entry.target;
                if (img.dataset.src) { img.src = img.dataset.src; img.classList.add('loaded'); }
                imgObserver.unobserve(img);
            });
        }, { rootMargin: '100px' });
        document.querySelectorAll('img.lazy').forEach(function (img) { imgObserver.observe(img); });
    }

    /* ─── 6. Ripple effect on buttons ───────────────────────────── */
    document.querySelectorAll('.btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (btn.classList.contains('btn-loading')) return;
            var rect = btn.getBoundingClientRect();
            var size = Math.max(rect.width, rect.height);
            var ripple = document.createElement('span');
            ripple.style.cssText = 'position:absolute;border-radius:50%;pointer-events:none;' +
                'width:' + size + 'px;height:' + size + 'px;' +
                'top:' + (e.clientY - rect.top - size / 2) + 'px;' +
                'left:' + (e.clientX - rect.left - size / 2) + 'px;' +
                'background:rgba(255,255,255,.22);transform:scale(0);animation:rippleAnim .55s ease;';
            if (getComputedStyle(btn).position === 'static') btn.style.position = 'relative';
            btn.style.overflow = 'hidden';
            btn.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 600);
        });
    });

    /* ─── 7. Password strength meter ───────────────────────────── */
    document.querySelectorAll('input[name="password"],input[name="new_password"]').forEach(function (input) {
        var wrap = input.closest('.form-group') || input.parentElement;
        var bar = wrap.querySelector('.pwd-strength');
        if (!bar) { bar = document.createElement('div'); bar.className = 'pwd-strength'; input.parentElement.appendChild(bar); }
        input.addEventListener('input', function () {
            var v = input.value; bar.className = 'pwd-strength';
            if (!v) return;
            var s = 0;
            if (v.length >= 8) s++;
            if (/[A-Za-z]/.test(v)) s++;
            if (/[0-9]/.test(v)) s++;
            if (/[^A-Za-z0-9]/.test(v)) s++;
            bar.classList.add(s <= 1 ? 'pwd-weak' : s <= 3 ? 'pwd-medium' : 'pwd-strong');
        });
    });

    /* ─── 8. data-confirm on forms ──────────────────────────────── */
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    /* ─── 9. Page entrance animation ───────────────────────────── */
    var mc = document.querySelector('.main-content');
    if (mc) mc.classList.add('page-enter');

});

/* Inject ripple keyframe once */
(function () {
    if (document.getElementById('ripple-style')) return;
    var s = document.createElement('style');
    s.id = 'ripple-style';
    s.textContent = '@keyframes rippleAnim{to{transform:scale(3.5);opacity:0}}';
    document.head.appendChild(s);
})();
