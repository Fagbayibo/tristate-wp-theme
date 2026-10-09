/**
 * Smooth (inertia) scrolling with Lenis, driven by GSAP's ticker so ScrollTrigger
 * animations stay in sync. Native scrolling is kept for reduced motion and touch
 * (Lenis leaves touch alone by default). In-page anchors glide too, stopping
 * below the sticky bar via base.css's scroll-margin-top.
 */
(() => {
	if (!window.Lenis || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

	const lenis = new window.Lenis({
		lerp: 0.1,
		anchors: true,
	});
	window.tristateLenis = lenis; // header.js pauses it while the drawer is open

	if (window.gsap) {
		if (window.ScrollTrigger) lenis.on('scroll', window.ScrollTrigger.update);
		window.gsap.ticker.add((time) => lenis.raf(time * 1000));
		window.gsap.ticker.lagSmoothing(0);
	} else {
		const raf = (time) => {
			lenis.raf(time);
			requestAnimationFrame(raf);
		};
		requestAnimationFrame(raf);
	}
})();
