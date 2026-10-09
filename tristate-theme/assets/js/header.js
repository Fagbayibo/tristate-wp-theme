/**
 * Header: sticky bar after scrolling, the desktop mega dropdown (one white
 * backdrop that morphs between panels, Stripe-style) and the mobile drawer
 * with accordion sub-menus.
 */
(() => {
	const header = document.querySelector('[data-header]');
	if (!header) return;

	const desktop = window.matchMedia('(min-width: 1081px)');
	const lenis = () => window.tristateLenis; // smooth-scroll.js, when loaded

	// Sticky ---------------------------------------------------------------
	// Stick once the full header (top strip + nav) has scrolled out of view.
	const STICK_AFTER = () => header.offsetHeight + 40;
	let stuck = false;

	const onScroll = () => {
		const shouldStick = window.scrollY > STICK_AFTER();
		if (shouldStick !== stuck) {
			stuck = shouldStick;
			header.classList.toggle('is-stuck', stuck);
			closeMega(true); // the bar changes height, so panels would be misplaced
		}
	};

	// Mega dropdown (desktop) ----------------------------------------------
	const inner = header.querySelector('.navbar__inner');
	const parents = [...header.querySelectorAll('.nav-menu > .menu-item-has-children')].filter((li) => li.querySelector(':scope > [data-mega]'));
	let morph = null;
	let active = null;
	let closeTimer = 0;

	const place = (li) => {
		const mega = li.querySelector(':scope > [data-mega]');
		const panel = mega.querySelector('.mega__inner');
		const link = li.querySelector(':scope > a');
		const box = inner.getBoundingClientRect();
		const pad = parseFloat(getComputedStyle(inner).paddingLeft) || 0;
		const linkBox = link.getBoundingClientRect();
		const centre = linkBox.left + linkBox.width / 2 - box.left;
		const w = panel.offsetWidth;
		const x = Math.round(Math.min(Math.max(centre - w / 2, pad), box.width - pad - w));

		mega.style.setProperty('--mega-x', `${x}px`);
		return {
			x,
			y: mega.offsetTop + panel.offsetTop,
			w,
			h: panel.offsetHeight,
			arrow: Math.round(centre - x),
		};
	};

	const setMorph = (geo) => {
		morph.style.setProperty('--morph-x', `${geo.x}px`);
		morph.style.setProperty('--morph-y', `${geo.y}px`);
		morph.style.setProperty('--morph-w', `${geo.w}px`);
		morph.style.setProperty('--morph-h', `${geo.h}px`);
		morph.style.setProperty('--arrow-x', `${geo.arrow}px`);
	};

	const openMega = (li) => {
		clearTimeout(closeTimer);
		if (li === active) return;

		const prev = active;
		const mega = li.querySelector(':scope > [data-mega]');
		const geo = place(li);

		if (prev) {
			// Content slides out one way and the new panel in from the other.
			const dir = parents.indexOf(li) > parents.indexOf(prev) ? 1 : -1;
			const prevMega = prev.querySelector(':scope > [data-mega]');
			prevMega.style.setProperty('--mega-from', `${-dir * 48}px`);
			prevMega.classList.remove('is-active');
			prev.classList.remove('is-open');

			mega.classList.add('is-instant');
			mega.style.setProperty('--mega-from', `${dir * 48}px`);
			mega.offsetWidth; // eslint-disable-line no-unused-expressions -- commit the start position
			mega.classList.remove('is-instant');
			morph.classList.remove('is-instant');
		} else {
			// From closed: the backdrop jumps to size and only fades/grows in.
			mega.style.setProperty('--mega-from', '0px');
			morph.classList.add('is-instant');
		}

		setMorph(geo);
		morph.offsetWidth; // eslint-disable-line no-unused-expressions
		morph.classList.add('is-visible');
		mega.classList.add('is-active');
		li.classList.add('is-open');
		li.querySelector(':scope > a').setAttribute('aria-expanded', 'true');
		active = li;
	};

	function closeMega(now) {
		clearTimeout(closeTimer);
		const close = () => {
			if (!active) return;
			const mega = active.querySelector(':scope > [data-mega]');
			mega.style.setProperty('--mega-from', '0px');
			mega.classList.remove('is-active');
			active.classList.remove('is-open');
			active.querySelector(':scope > a').setAttribute('aria-expanded', 'false');
			morph.classList.remove('is-visible');
			active = null;
		};
		if (now === true) close();
		else closeTimer = setTimeout(close, 160); // grace period for diagonal mouse paths
	}

	const setupMorph = () => {
		if (morph || !inner || !parents.length) return;
		morph = document.createElement('div');
		morph.className = 'nav-morph';
		morph.setAttribute('aria-hidden', 'true');
		const arrow = document.createElement('span');
		arrow.className = 'nav-morph__arrow';
		morph.appendChild(arrow);
		inner.appendChild(morph);
		inner.classList.add('has-morph');

		parents.forEach((li) => {
			const link = li.querySelector(':scope > a');
			link.setAttribute('aria-expanded', 'false');
			li.addEventListener('pointerenter', (e) => e.pointerType === 'mouse' && desktop.matches && openMega(li));
			li.addEventListener('pointerleave', (e) => e.pointerType === 'mouse' && closeMega());
			li.addEventListener('focusin', () => desktop.matches && openMega(li));
			li.addEventListener('focusout', (e) => {
				if (!li.contains(e.relatedTarget)) closeMega(true);
			});
			// Touch on a laptop screen: first tap opens, second follows the link.
			link.addEventListener('click', (e) => {
				if (desktop.matches && active !== li && !li.matches(':hover')) {
					e.preventDefault();
					openMega(li);
				}
			});
		});

		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && active) {
				const link = active.querySelector(':scope > a');
				closeMega(true);
				link.focus();
			}
		});
		document.addEventListener('pointerdown', (e) => {
			if (active && !active.contains(e.target)) closeMega(true);
		});
		window.addEventListener('resize', () => closeMega(true));
	};

	setupMorph();
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	// Mobile drawer --------------------------------------------------------
	const toggle = header.querySelector('[data-nav-toggle]');
	const nav = document.getElementById('site-nav');
	if (!toggle || !nav) return;

	const bar = header.querySelector('.navbar__bar');

	const setOpen = (open) => {
		if (open) {
			// The drawer's solid top band ends under the bar (below the top strip
			// unless the bar is stuck), and the reveal circle grows from the toggle.
			const t = toggle.getBoundingClientRect();
			nav.style.setProperty('--drawer-top', `${Math.round(bar.getBoundingClientRect().bottom)}px`);
			nav.style.setProperty('--toggle-y', `${Math.round(t.top + t.height / 2)}px`);
		}
		document.body.classList.toggle('nav-open', open);
		toggle.setAttribute('aria-expanded', String(open));
		if (lenis()) open ? lenis().stop() : lenis().start();
		if (open) {
			const firstLink = nav.querySelector('a');
			if (firstLink) setTimeout(() => firstLink.focus({ preventScroll: true }), 300);
		}
	};

	toggle.addEventListener('click', () => {
		setOpen(toggle.getAttribute('aria-expanded') !== 'true');
	});

	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && document.body.classList.contains('nav-open')) {
			setOpen(false);
			toggle.focus();
		}
	});

	nav.addEventListener('click', (e) => {
		if (e.target.closest('a')) setOpen(false);
	});

	// Accordion: one dropdown open at a time.
	const subToggles = [...nav.querySelectorAll('.submenu-toggle')];
	subToggles.forEach((btn) => {
		btn.addEventListener('click', () => {
			const expand = btn.getAttribute('aria-expanded') !== 'true';
			subToggles.forEach((other) => {
				const on = other === btn && expand;
				other.setAttribute('aria-expanded', String(on));
				other.parentElement.classList.toggle('is-expanded', on);
			});
		});
	});

	// Close the drawer if the viewport grows past the mobile breakpoint.
	desktop.addEventListener('change', (e) => {
		if (e.matches) setOpen(false);
		else closeMega(true);
	});
})();
