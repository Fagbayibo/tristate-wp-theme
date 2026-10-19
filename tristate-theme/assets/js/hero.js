/**
 * Home hero entrance: the photo settles, the header drops in, the headline
 * rises word by word, then the copy, buttons and hospital strip follow.
 * Requires GSAP (enqueued as a dependency). Content is visible without it.
 */
(() => {
	const hero = document.querySelector('[data-hero]');
	if (!hero || !window.gsap) return;

	const { gsap } = window;
	hero.classList.add('is-ready');
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

	const headerBits = document.querySelectorAll('.site-header--overlay .topbar, .site-header--overlay .navbar__inner > *');
	const intro = [...hero.querySelectorAll('[data-hero-intro]')];

	// CSS transitions on transform (e.g. button hover lift) fight GSAP's tweens,
	// so switch them off for the intro and hand control back to CSS afterwards.
	document.documentElement.classList.add('is-intro');

	gsap.timeline({
		defaults: { ease: 'power3.out' },
		onComplete: () => {
			gsap.set([...headerBits, ...intro], { clearProps: 'transform,opacity' });
			document.documentElement.classList.remove('is-intro');
		},
	})
		.from(hero.querySelector('[data-hero-image]'), { scale: 1.12, duration: 2.2, ease: 'power2.out' }, 0)
		.from(headerBits, { y: -20, opacity: 0, duration: 0.8, stagger: 0.06 }, 0.1)
		.from(hero.querySelectorAll('.hero__word > span'), { yPercent: 110, duration: 1, stagger: 0.07 }, 0.35)
		.from(intro, { opacity: 0, y: 20, duration: 0.8, stagger: 0.1 }, 0.7);
})();
