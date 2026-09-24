/**
 * Declarative scroll animations (GSAP + ScrollTrigger).
 *
 * Mark up elements in templates; no per-section JS needed:
 *   data-reveal="up|fade|pop|clip-up|clip-left"  animate in when scrolled into view
 *   data-delay="0.2"                          optional delay (seconds) for data-reveal
 *   data-reveal-stagger                       children animate up one after another
 *   data-parallax="0.06"                      drift while scrolling (fraction of own height)
 *   data-count="5000" data-suffix="+"         count up from 0 when visible
 *   data-words-scrub                          words brighten as the text scrolls through
 */
(() => {
	const root = document.documentElement;
	const { gsap, ScrollTrigger } = window;

	if (!gsap || !ScrollTrigger || !root.classList.contains('anim')) {
		root.classList.remove('anim');
		return;
	}

	gsap.registerPlugin(ScrollTrigger);

	const EASE = 'power3.out';
	const START = 'top 85%';

	// Reveal ------------------------------------------------------------------
	const revealFrom = {
		up: { opacity: 0, y: 40 },
		fade: { opacity: 0 },
		pop: { opacity: 0, scale: 0.4, rotation: -30 },
		'clip-up': { clipPath: 'inset(100% 0% 0% 0%)' },
		'clip-left': { clipPath: 'inset(0% 100% 0% 0%)' },
	};

	gsap.utils.toArray('[data-reveal]').forEach((el) => {
		const type = el.dataset.reveal || 'up';
		const from = revealFrom[type] || revealFrom.up;
		const isClip = type.startsWith('clip');
		const delay = parseFloat(el.dataset.delay) || 0;

		const to = {
			opacity: 1,
			duration: isClip ? 1.3 : 1,
			delay,
			ease: isClip ? 'expo.out' : EASE,
			scrollTrigger: { trigger: el, start: START, once: true },
		};

		if (isClip) {
			gsap.set(el, { opacity: 1 });
			to.clipPath = 'inset(0% 0% 0% 0%)';
			to.clearProps = 'clipPath';
		} else if (type === 'up') {
			to.y = 0;
			to.clearProps = 'transform';
		} else if (type === 'pop') {
			Object.assign(to, { scale: 1, rotation: 0, duration: 1.1, ease: 'back.out(1.7)', clearProps: 'transform' });
		}

		gsap.fromTo(el, from, to);
	});

	gsap.utils.toArray('[data-reveal-stagger]').forEach((group) => {
		gsap.fromTo(group.children, { opacity: 0, y: 30 }, {
			opacity: 1,
			y: 0,
			duration: 0.9,
			stagger: 0.1,
			ease: EASE,
			clearProps: 'transform',
			scrollTrigger: { trigger: group, start: START, once: true },
		});
	});

	// Parallax ------------------------------------------------------------------
	gsap.utils.toArray('[data-parallax]').forEach((el) => {
		const speed = parseFloat(el.dataset.parallax) || 0.06;
		gsap.fromTo(el, { yPercent: -speed * 100 }, {
			yPercent: speed * 100,
			ease: 'none',
			scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
		});
	});

	// Count up ------------------------------------------------------------------
	const format = new Intl.NumberFormat('en-US');

	const fontsReady = document.fonts ? document.fonts.ready : Promise.resolve();

	fontsReady.then(() => {
		gsap.utils.toArray('[data-count]').forEach((el) => {
			const target = parseFloat(el.dataset.count) || 0;
			const suffix = el.dataset.suffix || '';
			const counter = { value: 0 };

			// Reserve the final number's width so neighbouring text doesn't shift while counting.
			el.style.display = 'inline-block';
			el.style.minWidth = `${el.getBoundingClientRect().width}px`;

			gsap.to(counter, {
				value: target,
				duration: target > 100 ? 2.2 : 1.6,
				ease: 'power2.out',
				scrollTrigger: { trigger: el, start: 'top 90%', once: true },
				onUpdate: () => {
					el.textContent = format.format(Math.round(counter.value)) + suffix;
				},
			});
		});
	});

	// Word scrub ------------------------------------------------------------------
	// Wrap every word in a span (keeping <strong>/<em> intact), then scrub opacity.
	const splitWords = (node) => {
		[...node.childNodes].forEach((child) => {
			if (child.nodeType === Node.TEXT_NODE) {
				const parts = child.textContent.split(/(\s+)/);
				const frag = document.createDocumentFragment();
				parts.forEach((part) => {
					if (!part) return;
					if (/^\s+$/.test(part)) {
						frag.appendChild(document.createTextNode(part));
					} else {
						const span = document.createElement('span');
						span.className = 'scrub-word';
						span.textContent = part;
						frag.appendChild(span);
					}
				});
				child.replaceWith(frag);
			} else if (child.nodeType === Node.ELEMENT_NODE) {
				splitWords(child);
			}
		});
	};

	gsap.utils.toArray('[data-words-scrub]').forEach((el) => {
		splitWords(el);
		gsap.fromTo(el.querySelectorAll('.scrub-word'), { opacity: 0.15 }, {
			opacity: 1,
			stagger: 0.1,
			ease: 'none',
			scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: 0.6 },
		});
	});

	// Images load lazily and change layout heights; keep trigger positions accurate.
	window.addEventListener('load', () => ScrollTrigger.refresh());
})();
