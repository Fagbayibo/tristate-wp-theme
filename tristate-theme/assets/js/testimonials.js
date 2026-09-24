/**
 * Testimonials slider: tabs + arrows + autoplay, words drift in on change.
 */
(() => {
	const root = document.querySelector('[data-testimonials]');
	if (!root) return;

	const { gsap } = window;
	const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	const animate = gsap && !reduceMotion;

	const slides = [...root.querySelectorAll('[data-testimonial]')];
	const tabs = [...root.querySelectorAll('[data-testimonial-tab]')];
	const mark = root.querySelector('.testimonials__mark');
	const AUTOPLAY_MS = 8000;

	let current = 0;
	let timer = null;
	let paused = false;
	let busy = null;

	// Split each quote into word spans once, so they can animate individually.
	slides.forEach((slide) => {
		const p = slide.querySelector('.testimonial__quote p');
		const words = p.textContent.trim().split(/\s+/);
		p.textContent = '';
		words.forEach((word, i) => {
			const span = document.createElement('span');
			span.className = 't-word';
			span.textContent = word;
			p.append(span, i < words.length - 1 ? ' ' : '');
		});
	});

	const parts = (slide) => ({
		words: slide.querySelectorAll('.t-word'),
		author: slide.querySelector('.testimonial__author'),
	});

	const setTabs = (index) => {
		tabs.forEach((tab, i) => {
			tab.classList.toggle('is-active', i === index);
			tab.setAttribute('aria-selected', String(i === index));
		});
	};

	const goTo = (next) => {
		next = (next + slides.length) % slides.length;
		if (next === current) return;

		const prev = slides[current];
		const incoming = slides[next];
		current = next;
		setTabs(next);

		const swap = () => {
			prev.classList.remove('is-active');
			prev.setAttribute('aria-hidden', 'true');
			incoming.classList.add('is-active');
			incoming.removeAttribute('aria-hidden');
		};

		if (!animate) {
			swap();
			return;
		}

		if (busy) busy.progress(1);

		const out = parts(prev);
		const inn = parts(incoming);

		busy = gsap.timeline({ defaults: { ease: 'power3.out' } })
			.to(out.words, { opacity: 0, y: -14, duration: 0.35, stagger: 0.008, ease: 'power2.in' }, 0)
			.to(out.author, { opacity: 0, y: -10, duration: 0.3 }, 0)
			.to(mark, { rotate: -12, scale: 0.85, duration: 0.35, ease: 'power2.in' }, 0)
			.add(swap)
			.fromTo(inn.words, { opacity: 0, y: 18, filter: 'blur(6px)' }, { opacity: 1, y: 0, filter: 'blur(0px)', duration: 0.7, stagger: 0.018 })
			.fromTo(inn.author, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.6 }, '<0.25')
			.to(mark, { rotate: 0, scale: 1, duration: 0.8, ease: 'elastic.out(1, 0.5)' }, '<');
	};

	const schedule = () => {
		clearTimeout(timer);
		if (paused || reduceMotion) return;
		timer = setTimeout(() => {
			goTo(current + 1);
			schedule();
		}, AUTOPLAY_MS);
	};

	const userGoTo = (index) => {
		goTo(index);
		schedule();
	};

	tabs.forEach((tab, i) => tab.addEventListener('click', () => userGoTo(i)));
	root.querySelector('[data-testimonial-prev]').addEventListener('click', () => userGoTo(current - 1));
	root.querySelector('[data-testimonial-next]').addEventListener('click', () => userGoTo(current + 1));

	// Arrow keys move between tabs.
	root.querySelector('[role="tablist"]').addEventListener('keydown', (e) => {
		if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
		e.preventDefault();
		userGoTo(current + (e.key === 'ArrowRight' ? 1 : -1));
		tabs[current].focus();
	});

	// Pause while hovered/focused or off-screen.
	const pause = () => { paused = true; clearTimeout(timer); };
	const resume = () => { paused = false; schedule(); };
	root.addEventListener('mouseenter', pause);
	root.addEventListener('mouseleave', resume);
	root.addEventListener('focusin', pause);
	root.addEventListener('focusout', (e) => { if (!root.contains(e.relatedTarget)) resume(); });

	new IntersectionObserver(([entry]) => {
		entry.isIntersecting ? resume() : pause();
	}).observe(root);
})();
