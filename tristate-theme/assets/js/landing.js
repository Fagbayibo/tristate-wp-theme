/**
 * Scroll-linked motion for the designed pages and the homepage.
 * Generic reveals, split headings, counters and parallax come from animations.js;
 * this file only drives the effects unique to these pages. Each block is skipped
 * when its markup isn't on the page.
 *
 * Without GSAP or with reduced motion the `anim` class is absent and the CSS
 * shows every element in its final state, so nothing here is required.
 */
(() => {
	const root = document.documentElement;
	const { gsap, ScrollTrigger } = window;

	if (!gsap || !ScrollTrigger || !root.classList.contains('anim')) return;

	gsap.registerPlugin(ScrollTrigger);

	const clamp = (v) => Math.min(1, Math.max(0, v));
	const finePointer = window.matchMedia('(pointer: fine)').matches;

	// Hero image grows from an inset card to full width as it scrolls up -----------
	document.querySelectorAll('[data-expand]').forEach((el) => {
		ScrollTrigger.create({
			trigger: el,
			start: 'top bottom',
			end: 'top 15%',
			scrub: true,
			onUpdate: (self) => el.style.setProperty('--p', self.progress.toFixed(4)),
		});
	});

	// T.R.E.A.T.: the row in the middle of the screen is active; letters fill in up to it --
	const treatItems = [...document.querySelectorAll('.treat__item')];
	const treatLetters = [...document.querySelectorAll('.treat__word span')];
	const setTreat = (index) => {
		treatItems.forEach((item, i) => item.classList.toggle('is-active', i === index));
		treatLetters.forEach((letter, i) => letter.classList.toggle('is-on', i <= index));
	};
	treatItems.forEach((item, i) => {
		ScrollTrigger.create({
			trigger: item,
			start: 'top 55%',
			end: 'bottom 45%',
			onEnter: () => setTreat(i),
			onEnterBack: () => setTreat(i),
		});
	});

	// Service pages: "On this page" marks the section being read --------------------
	document.querySelectorAll('.sd-toc__list a').forEach((link) => {
		const target = document.querySelector(link.getAttribute('href'));
		if (!target) return;
		ScrollTrigger.create({
			trigger: target,
			start: 'top 40%',
			end: 'bottom 40%',
			onToggle: (self) => link.classList.toggle('is-current', self.isActive),
		});
	});

	// Stacked service cards: the card underneath shrinks and dims as the next slides over --
	const cards = [...document.querySelectorAll('.svc-card')];
	if (cards.length > 1) {
		const stackQuery = window.matchMedia('(min-width: 901px)');
		const update = () => {
			cards.forEach((card, i) => {
				const next = cards[i + 1];
				let s = 0;
				if (next && stackQuery.matches) {
					const top = card.getBoundingClientRect().top;
					s = clamp(1 - (next.getBoundingClientRect().top - top) / Math.max(card.offsetHeight, 1));
				}
				card.firstElementChild.style.setProperty('--s', s.toFixed(4));
			});
		};
		ScrollTrigger.create({ trigger: cards[0].parentElement, start: 'top bottom', end: 'bottom top', onUpdate: update, onRefresh: update });
	}

	// Services hero: tiles pop in, then drift with the pointer at different depths -------
	const stage = document.querySelector('[data-tiles]');
	if (stage) {
		requestAnimationFrame(() => stage.classList.add('is-ready'));

		if (finePointer) {
			stage.querySelectorAll('.svc-tile').forEach((tile) => {
				const depth = parseFloat(tile.dataset.depth) || 0.5;
				const x = gsap.quickTo(tile, 'x', { duration: 1.2, ease: 'power3.out' });
				const y = gsap.quickTo(tile, 'y', { duration: 1.2, ease: 'power3.out' });
				tile.moveTo = (px, py) => { x(px * depth * 40); y(py * depth * 40); };
			});
			const tiles = [...stage.querySelectorAll('.svc-tile')];
			stage.addEventListener('pointermove', (e) => {
				const r = stage.getBoundingClientRect();
				const px = (e.clientX - r.left) / r.width - 0.5;
				const py = (e.clientY - r.top) / r.height - 0.5;
				tiles.forEach((tile) => tile.moveTo(px, py));
			});
			stage.addEventListener('pointerleave', () => tiles.forEach((tile) => tile.moveTo(0, 0)));
		}
	}
})();
