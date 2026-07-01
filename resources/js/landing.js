/*
 * Landing pages — lightweight scroll-reveal + CTA smooth-scroll.
 * No libraries. One IntersectionObserver toggles `.is-visible`; CSS does the
 * actual fade/translate. Honours prefers-reduced-motion.
 */
function initLanding() {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const animated = document.querySelectorAll('[data-animate]');

    if (reduce || !('IntersectionObserver' in window)) {
        animated.forEach((el) => el.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
        );
        animated.forEach((el) => observer.observe(el));
    }

    // CTA buttons smooth-scroll to the final order / phone section.
    document.querySelectorAll('[data-scroll-to]').forEach((btn) => {
        btn.addEventListener('click', (event) => {
            const target = document.querySelector(btn.getAttribute('data-scroll-to'));
            if (!target) return;
            event.preventDefault();
            target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
        });
    });
}

if (document.readyState !== 'loading') {
    initLanding();
} else {
    document.addEventListener('DOMContentLoaded', initLanding);
}
