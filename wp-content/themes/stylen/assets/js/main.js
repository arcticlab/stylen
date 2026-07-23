(function () {
    'use strict';

    var body = document.body;
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hasIO = 'IntersectionObserver' in window;

    /* ---------- Sticky header shadow ---------- */
    var header = document.querySelector('.site-header');
    if (header) {
        var onScroll = function () { header.classList.toggle('is-stuck', window.scrollY > 6); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ---------- Mobile drawer ---------- */
    var toggle = document.querySelector('.menu-toggle');
    var overlay = document.querySelector('.nav-overlay');
    var nav = document.getElementById('site-navigation');

    function setNav(open) {
        body.classList.toggle('nav-open', open);
        body.style.overflow = open ? 'hidden' : '';
        if (toggle) { toggle.setAttribute('aria-expanded', open ? 'true' : 'false'); }
    }
    if (toggle) { toggle.addEventListener('click', function () { setNav(!body.classList.contains('nav-open')); }); }
    if (overlay) { overlay.addEventListener('click', function () { setNav(false); }); }
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setNav(false); } });
    if (nav) {
        nav.addEventListener('click', function (e) {
            var link = e.target.closest('a');
            if (link && !link.parentNode.classList.contains('menu-item-has-children')) { setNav(false); }
        });
    }
    window.addEventListener('resize', function () {
        if (window.innerWidth > 1024 && body.classList.contains('nav-open')) { setNav(false); }
    });

    /* ---------- FAQ accordion (one open at a time) ---------- */
    var faqItems = Array.prototype.slice.call(document.querySelectorAll('.faq__item'));
    faqItems.forEach(function (item) {
        var btn = item.querySelector('.faq__q');
        if (!btn) { return; }
        btn.addEventListener('click', function () {
            var isOpen = item.classList.contains('is-open');
            faqItems.forEach(function (o) { o.classList.remove('is-open'); var b = o.querySelector('.faq__q'); if (b) { b.setAttribute('aria-expanded', 'false'); } });
            if (!isOpen) { item.classList.add('is-open'); btn.setAttribute('aria-expanded', 'true'); }
        });
    });

    /* ---------- Scroll reveal (only hide elements BELOW the first fold) ---------- */
    var revealEls = Array.prototype.slice.call(document.querySelectorAll('[data-reveal]'));
    if (!reduce && hasIO && revealEls.length) {
        var fold = window.innerHeight * 0.92;
        var io = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { entry.target.classList.add('is-visible'); obs.unobserve(entry.target); }
            });
        }, { rootMargin: '0px 0px -6% 0px', threshold: 0.1 });
        revealEls.forEach(function (el) {
            if (el.getBoundingClientRect().top < fold) { return; } // in the fold → stay visible
            el.classList.add('reveal-init');
            io.observe(el);
        });
    }

    /* ---------- Count-up ---------- */
    var counters = Array.prototype.slice.call(document.querySelectorAll('[data-count]'));
    function runCount(el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        var suffix = el.getAttribute('data-suffix') || '';
        if (reduce) { el.textContent = target + suffix; return; }
        var start = null, dur = 1300;
        function step(ts) {
            if (start === null) { start = ts; }
            var p = Math.min((ts - start) / dur, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))) + suffix;
            if (p < 1) { requestAnimationFrame(step); }
        }
        requestAnimationFrame(step);
    }
    if (counters.length) {
        if (reduce || !hasIO) { counters.forEach(runCount); }
        else {
            var co = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) { if (entry.isIntersecting) { runCount(entry.target); obs.unobserve(entry.target); } });
            }, { threshold: 0.5 });
            counters.forEach(function (el) { co.observe(el); });
        }
    }

    /* ---------- Reading progress bar (single articles) ---------- */
    var bar = document.getElementById('reading-progress');
    var articleBody = document.querySelector('.article-body');
    if (bar && articleBody) {
        var onProgress = function () {
            var rect = articleBody.getBoundingClientRect();
            var total = rect.height - window.innerHeight;
            var passed = -rect.top;
            var pct = total > 0 ? Math.min(Math.max(passed / total, 0), 1) : 0;
            bar.style.width = (pct * 100) + '%';
        };
        window.addEventListener('scroll', onProgress, { passive: true });
        window.addEventListener('resize', onProgress, { passive: true });
        onProgress();
    }

    /* ---------- Homepage configurator → live estimate → CF7 preselect ---------- */
    var chooser = document.querySelector('.chooser');
    if (chooser) {
        var tiles = Array.prototype.slice.call(chooser.querySelectorAll('.chooser__tile'));
        var estDir = document.querySelector('[data-est="dir"]');
        var estTerm = document.querySelector('[data-est="term"]');
        var estDesc = document.querySelector('[data-est="desc"]');
        var cta = document.querySelector('.js-estimate-cta');

        function applyToForm(value) {
            if (!value) { return; }
            var sels = document.querySelectorAll('.wpcf7-form select[name="your-direction"]');
            Array.prototype.forEach.call(sels, function (sel) {
                var found = false;
                Array.prototype.forEach.call(sel.options, function (o) {
                    if (o.value === value) { o.selected = true; found = true; }
                });
                if (found) { sel.dispatchEvent(new Event('change', { bubbles: true })); }
            });
        }

        function select(tile) {
            tiles.forEach(function (t) { t.classList.remove('is-active'); t.setAttribute('aria-checked', 'false'); });
            tile.classList.add('is-active'); tile.setAttribute('aria-checked', 'true');
            var cf7 = tile.getAttribute('data-cf7');
            if (estDir) { estDir.textContent = tile.getAttribute('data-title'); }
            if (estTerm) { estTerm.textContent = tile.getAttribute('data-term'); }
            if (estDesc) { estDesc.textContent = tile.getAttribute('data-desc'); }
            if (cta) { cta.setAttribute('data-cf7', cf7); }
            applyToForm(cf7);
        }

        tiles.forEach(function (tile) {
            tile.addEventListener('click', function () { select(tile); });
        });

        if (cta) {
            cta.addEventListener('click', function () { applyToForm(cta.getAttribute('data-cf7')); });
        }
    }

    /* ---------- CF7: live page-url + progress bar ---------- */
    function resetCf7(wrap) {
        var ok = wrap.querySelector('.form-success');
        if (ok) { ok.parentNode.removeChild(ok); }
        wrap.classList.remove('is-sent');
        wrap.style.minHeight = '';
        var f = wrap.querySelector('.wpcf7-form');
        if (f) { f.style.display = ''; }
    }

    Array.prototype.forEach.call(document.querySelectorAll('.wpcf7-form'), function (form) {
        var hidden = form.querySelector('input[name="page-url"]');
        if (hidden) { hidden.value = window.location.href; }
        var submit = form.querySelector('.wpcf7-submit');
        if (submit && !form.querySelector('.form-progress')) {
            var bar = document.createElement('div');
            bar.className = 'form-progress';
            (submit.parentNode || form).appendChild(bar);
        }
    });

    /* ---------- CF7: replace the form with a success panel (same size) ---------- */
    document.addEventListener('wpcf7mailsent', function (e) {
        var form = e.target;
        var wrap = form && form.closest ? form.closest('.wpcf7') : null;
        if (!wrap) { return; }
        wrap.style.minHeight = wrap.offsetHeight + 'px';
        form.style.display = 'none';
        if (!wrap.querySelector('.form-success')) {
            var ok = document.createElement('div');
            ok.className = 'form-success';
            ok.setAttribute('role', 'status');
            ok.innerHTML =
                '<div class="form-success__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M8 12.5l2.5 2.5L16 9"></path></svg></div>' +
                '<h3 class="form-success__title">Заявка отправлена</h3>' +
                '<p class="form-success__text">Спасибо! Менеджер свяжется с вами в рабочее время, посчитает стоимость и пришлёт макет.</p>';
            wrap.appendChild(ok);
        }
        wrap.classList.add('is-sent');
    });

    /* ---------- Order modal ---------- */
    var orderModal = document.getElementById('order-modal');
    if (orderModal) {
        var lastFocus = null;
        var openModal = function () {
            lastFocus = document.activeElement;
            Array.prototype.forEach.call(orderModal.querySelectorAll('.wpcf7.is-sent'), resetCf7);
            orderModal.setAttribute('aria-hidden', 'false');
            body.classList.add('modal-open');
            var first = orderModal.querySelector('.wpcf7-form input:not([type=hidden]):not([type=checkbox]), .wpcf7-form select, .wpcf7-form textarea');
            if (first) { setTimeout(function () { first.focus(); }, 80); }
        };
        var closeModal = function () {
            orderModal.setAttribute('aria-hidden', 'true');
            body.classList.remove('modal-open');
            if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
        };
        document.addEventListener('click', function (e) {
            var opener = e.target.closest('[data-modal-open="order-modal"], a[href$="#order"]');
            if (opener) { e.preventDefault(); openModal(); return; }
            if (e.target.closest('[data-modal-close]')) { closeModal(); }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && orderModal.getAttribute('aria-hidden') === 'false') { closeModal(); }
        });
    }

    /* ---------- Content tables → horizontal-scroll wrapper (responsive) ---------- */
    Array.prototype.slice.call(document.querySelectorAll('.article-body table, .product-body table')).forEach(function (tbl) {
        if (tbl.parentNode && tbl.parentNode.classList.contains('table-wrap')) { return; }
        var w = document.createElement('div');
        w.className = 'table-wrap';
        tbl.parentNode.insertBefore(w, tbl);
        w.appendChild(tbl);
    });

    /* ---------- Content images → lightbox triggers (decorate before init) ---------- */
    Array.prototype.slice.call(document.querySelectorAll('.article-body img, .product-body img')).forEach(function (img) {
        if (img.closest('a') || img.hasAttribute('data-lightbox')) { return; }
        img.setAttribute('data-lightbox', 'article');
        img.setAttribute('data-full', img.getAttribute('src'));
        var cap = '';
        var fig = img.closest('figure');
        if (fig) { var fc = fig.querySelector('figcaption'); if (fc) { cap = fc.textContent.trim(); } }
        if (!cap) { cap = img.getAttribute('alt') || ''; }
        if (cap) { img.setAttribute('data-caption', cap); }
        img.classList.add('is-zoomable');
        img.setAttribute('tabindex', '0');
        img.setAttribute('role', 'button');
        img.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); img.click(); }
        });
    });

    /* ---------- Gallery lightbox (grouped: arrows / mouse / keyboard) ---------- */
    var lbTriggers = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox]'));
    if (lbTriggers.length) {
        var groups = {};
        lbTriggers.forEach(function (t) {
            var g = t.getAttribute('data-lightbox') || 'default';
            (groups[g] = groups[g] || []).push(t);
        });

        var lb = document.createElement('div');
        lb.className = 'lightbox';
        lb.setAttribute('aria-hidden', 'true');
        lb.setAttribute('role', 'dialog');
        lb.setAttribute('aria-modal', 'true');
        lb.setAttribute('aria-label', 'Просмотр фотографий');
        lb.innerHTML =
            '<button type="button" class="lightbox__btn lightbox__close" aria-label="Закрыть"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>' +
            '<button type="button" class="lightbox__btn lightbox__prev" aria-label="Предыдущее фото"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg></button>' +
            '<button type="button" class="lightbox__btn lightbox__next" aria-label="Следующее фото"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></button>' +
            '<div class="lightbox__stage"><img class="lightbox__img" alt=""><p class="lightbox__caption"></p><span class="lightbox__counter"></span></div>';
        document.body.appendChild(lb);

        var lbImg = lb.querySelector('.lightbox__img');
        var lbCap = lb.querySelector('.lightbox__caption');
        var lbCnt = lb.querySelector('.lightbox__counter');
        var lbList = [];
        var lbIdx = 0;
        var lbLast = null;

        function lbRender() {
            var it = lbList[lbIdx];
            if (!it) { return; }
            lbImg.src = it.full;
            lbImg.alt = it.caption || '';
            lbCap.textContent = it.caption || '';
            lbCap.style.display = it.caption ? '' : 'none';
            lbCnt.textContent = (lbIdx + 1) + ' / ' + lbList.length;
            lb.classList.toggle('is-single', lbList.length < 2);
        }
        function lbOpen(list, i, trigger) {
            lbList = list; lbIdx = i; lbLast = trigger || null;
            lbRender();
            lb.classList.add('is-open');
            lb.setAttribute('aria-hidden', 'false');
            body.classList.add('modal-open');
        }
        function lbClose() {
            lb.classList.remove('is-open');
            lb.setAttribute('aria-hidden', 'true');
            body.classList.remove('modal-open');
            lbImg.src = '';
            if (lbLast && lbLast.focus) { lbLast.focus(); }
        }
        function lbStep(d) {
            if (lbList.length < 2) { return; }
            lbIdx = (lbIdx + d + lbList.length) % lbList.length;
            lbRender();
        }

        lbTriggers.forEach(function (t) {
            t.addEventListener('click', function () {
                var g = t.getAttribute('data-lightbox') || 'default';
                var list = groups[g].map(function (x) {
                    return { full: x.getAttribute('data-full'), caption: x.getAttribute('data-caption') };
                });
                lbOpen(list, groups[g].indexOf(t), t);
            });
        });
        lb.querySelector('.lightbox__close').addEventListener('click', lbClose);
        lb.querySelector('.lightbox__prev').addEventListener('click', function () { lbStep(-1); });
        lb.querySelector('.lightbox__next').addEventListener('click', function () { lbStep(1); });
        lb.addEventListener('click', function (e) { if (e.target === lb) { lbClose(); } });
        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('is-open')) { return; }
            if (e.key === 'Escape') { lbClose(); }
            else if (e.key === 'ArrowLeft') { lbStep(-1); }
            else if (e.key === 'ArrowRight') { lbStep(1); }
        });
    }
})();
