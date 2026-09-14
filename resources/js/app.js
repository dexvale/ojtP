import './bootstrap';

// ── Instant Navigation (Hover / Touch Page Prefetcher) ──
(function initInstantNav() {
    if (typeof window === 'undefined') return;

    const prefetched = new Set();
    const supportsPrefetch = (() => {
        const link = document.createElement('link');
        return link.relList && link.relList.supports && link.relList.supports('prefetch');
    })();

    function prefetchUrl(url) {
        if (!url || prefetched.has(url)) return;

        try {
            const dest = new URL(url, window.location.href);
            // Only prefetch internal GET links on the same host
            if (dest.origin !== window.location.origin) return;
            // Don't prefetch the exact current page
            if (dest.pathname === window.location.pathname && dest.search === window.location.search) return;
            // Don't prefetch logouts, deletes, downloads, or asset files
            if (dest.pathname.includes('/logout') || dest.pathname.includes('/delete') || dest.pathname.includes('/destroy')) return;
            if (/\.(pdf|docx?|zip|rar|png|jpe?g|gif|svg|webp)$/i.test(dest.pathname)) return;

            prefetched.add(dest.href);

            if (supportsPrefetch) {
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = dest.href;
                link.as = 'document';
                document.head.appendChild(link);
            } else {
                fetch(dest.href, { credentials: 'same-origin', priority: 'low' }).catch(() => {});
            }
        } catch (err) {
            // ignore invalid URLs
        }
    }

    let hoverTimer = null;

    // Desktop: Prefetch on 65ms hover intent
    document.addEventListener('mouseover', (e) => {
        const a = e.target.closest('a');
        if (!a || !a.href || a.target === '_blank' || a.hasAttribute('download') || a.getAttribute('role') === 'button') return;

        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(() => {
            prefetchUrl(a.href);
        }, 65);
    }, { passive: true });

    document.addEventListener('mouseout', (e) => {
        const a = e.target.closest('a');
        if (a) clearTimeout(hoverTimer);
    }, { passive: true });

    // Mobile: Prefetch immediately on touchstart before touchend/click
    document.addEventListener('touchstart', (e) => {
        const a = e.target.closest('a');
        if (!a || !a.href || a.target === '_blank' || a.hasAttribute('download')) return;
        prefetchUrl(a.href);
    }, { passive: true });

    // Also prefetch when link receives keyboard focus
    document.addEventListener('focusin', (e) => {
        const a = e.target.closest('a');
        if (a && a.href) prefetchUrl(a.href);
    }, { passive: true });
})();
