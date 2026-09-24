/**
 * Header: sticky bar after scrolling + mobile drawer toggle.
 */
(() => {
	const header = document.querySelector('[data-header]');
	if (!header) return;

	// Sticky ---------------------------------------------------------------
	// Stick once the full header (top strip + nav) has scrolled out of view.
	const STICK_AFTER = () => header.offsetHeight + 40;
	let stuck = false;

	const onScroll = () => {
		const shouldStick = window.scrollY > STICK_AFTER();
		if (shouldStick !== stuck) {
			stuck = shouldStick;
			header.classList.toggle('is-stuck', stuck);
		}
	};

	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	// Mobile drawer --------------------------------------------------------
	const toggle = header.querySelector('[data-nav-toggle]');
	const nav = document.getElementById('site-nav');
	if (!toggle || !nav) return;

	const setOpen = (open) => {
		document.body.classList.toggle('nav-open', open);
		toggle.setAttribute('aria-expanded', String(open));
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

	// Close the drawer if the viewport grows past the mobile breakpoint.
	window.matchMedia('(min-width: 1081px)').addEventListener('change', (e) => {
		if (e.matches) setOpen(false);
	});
})();
