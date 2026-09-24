/**
 * Home hero: entrance animation + auto-advancing slider with progress tabs.
 * Requires GSAP (enqueued as a dependency).
 */
(() => {
	const hero = document.querySelector('[data-hero]');
	if (!hero || !window.gsap) return;

	const { gsap } = window;
	const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	const slides = [...hero.querySelectorAll('[data-hero-slide]')];
	const images = [...hero.querySelectorAll('[data-hero-image]')];
	const tabs = [...hero.querySelectorAll('[data-hero-tab]')];
	const bars = tabs.map((tab) => tab.querySelector('.hero__tab-progress'));

	const SLIDE_DURATION = 7; // seconds per slide
	let current = 0;
	let progressTween = null;
	let transition = null;

	const words = (slide) => slide.querySelectorAll('.hero__word > span');
	const text = (slide) => slide.querySelector('.hero__text');

	// Slow zoom on the active image ("Ken Burns").
	const zoomImage = (img) => {
		if (reduceMotion) return;
		gsap.fromTo(img, { scale: 1.08 }, { scale: 1, duration: SLIDE_DURATION + 1.2, ease: 'none', overwrite: 'auto' });
	};

	const setTabState = (index) => {
		tabs.forEach((tab, i) => {
			const active = i === index;
			tab.classList.toggle('is-active', active);
			tab.setAttribute('aria-selected', String(active));
			// Tabs before the current one stay full; later ones reset.
			if (!active) gsap.set(bars[i], { scaleX: 0 });
		});
	};

	const startProgress = () => {
		if (progressTween) progressTween.kill();
		if (reduceMotion || slides.length < 2) {
			gsap.set(bars[current], { scaleX: 1 });
			return;
		}
		progressTween = gsap.fromTo(
			bars[current],
			{ scaleX: 0 },
			{ scaleX: 1, duration: SLIDE_DURATION, ease: 'none', onComplete: () => goTo((current + 1) % slides.length) }
		);
	};

	const goTo = (next) => {
		if (next === current) return;
		const prev = current;
		current = next;

		if (transition) transition.progress(1);
		setTabState(next);

		const prevSlide = slides[prev];
		const nextSlide = slides[next];

		prevSlide.setAttribute('aria-hidden', 'true');
		nextSlide.removeAttribute('aria-hidden');

		transition = gsap.timeline({ defaults: { ease: 'power3.out' } });
		transition
			.to(words(prevSlide), { yPercent: -110, duration: 0.5, stagger: 0.03, ease: 'power2.in' }, 0)
			.to(text(prevSlide), { opacity: 0, y: -12, duration: 0.4 }, 0)
			.add(() => {
				prevSlide.classList.remove('is-active');
				nextSlide.classList.add('is-active');
			})
			.fromTo(words(nextSlide), { yPercent: 110 }, { yPercent: 0, duration: 0.9, stagger: 0.05 })
			.fromTo(text(nextSlide), { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.8 }, '<0.2');

		// Cross-fade images (they may be identical today; this keeps it ready for real slides).
		if (images[prev] !== images[next]) {
			gsap.to(images[prev], { opacity: 0, duration: 1.2, ease: 'power2.inOut', onComplete: () => images[prev].classList.remove('is-active') });
			images[next].classList.add('is-active');
			gsap.fromTo(images[next], { opacity: 0 }, { opacity: 1, duration: 1.2, ease: 'power2.inOut' });
			zoomImage(images[next]);
		}

		startProgress();
	};

	tabs.forEach((tab, i) => tab.addEventListener('click', () => goTo(i)));

	// Pause the timer while the pointer is over the tabs or copy.
	hero.querySelectorAll('.hero__copy, .hero__tabs').forEach((el) => {
		el.addEventListener('mouseenter', () => progressTween && progressTween.pause());
		el.addEventListener('mouseleave', () => progressTween && progressTween.resume());
	});

	// Pause when the hero is off-screen or the tab is hidden.
	new IntersectionObserver(([entry]) => {
		if (!progressTween) return;
		entry.isIntersecting ? progressTween.resume() : progressTween.pause();
	}).observe(hero);

	// Hide inactive slides' words so the first transition-in starts from below.
	slides.forEach((slide, i) => {
		if (i !== 0) gsap.set(words(slide), { yPercent: 110 });
	});

	hero.classList.add('is-ready');

	if (reduceMotion) {
		startProgress();
		return;
	}

	// Entrance ----------------------------------------------------------------
	const first = slides[0];
	const headerBits = document.querySelectorAll('.site-header--overlay .topbar, .site-header--overlay .navbar__inner > *');

	// CSS transitions on transform (e.g. button hover lift) fight GSAP's tweens,
	// so switch them off for the intro and hand control back to CSS afterwards.
	document.documentElement.classList.add('is-intro');
	const introTargets = [...headerBits, ...hero.querySelectorAll('.hero__eyebrow, .hero__buttons > *, [data-hero-card], [data-hero-tab]')];

	gsap.timeline({
		defaults: { ease: 'power3.out' },
		onComplete: () => {
			gsap.set(introTargets, { clearProps: 'transform,opacity' });
			document.documentElement.classList.remove('is-intro');
			startProgress();
		},
	})
		.from(images[0], { scale: 1.15, duration: 2.2, ease: 'power2.out' }, 0)
		.from(headerBits, { y: -20, opacity: 0, duration: 0.8, stagger: 0.06 }, 0.1)
		.from(hero.querySelector('.hero__eyebrow'), { opacity: 0, x: -20, duration: 0.8 }, 0.3)
		.from(words(first), { yPercent: 110, duration: 1, stagger: 0.07 }, 0.4)
		.from(text(first), { opacity: 0, y: 20, duration: 0.9 }, 0.9)
		.from(hero.querySelectorAll('.hero__buttons > *'), { opacity: 0, y: 20, duration: 0.8, stagger: 0.1 }, 1.05)
		.from(hero.querySelector('[data-hero-card]'), { opacity: 0, x: 40, duration: 1 }, 1.1)
		.from(tabs, { opacity: 0, y: 20, duration: 0.8, stagger: 0.1 }, 1.2);
})();
