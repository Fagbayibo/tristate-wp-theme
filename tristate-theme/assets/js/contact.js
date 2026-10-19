/**
 * Contact page: message form (inline validation + submit without reloading)
 * and the location list that switches the map. Without JS the form still
 * posts normally (see inc/contact.php) and the first centre stays on the map.
 */
(() => {
	/* Locations: choose a centre to show it on the map --------------------- */
	const places = document.querySelector('[data-places]');
	const frame = document.querySelector('[data-map-frame]');

	if (places && frame) {
		places.addEventListener('click', (e) => {
			const pick = e.target.closest('.ct-place__pick');
			if (!pick || pick.getAttribute('aria-pressed') === 'true') return;

			places.querySelectorAll('.ct-place').forEach((place) => {
				const active = place.contains(pick);
				place.classList.toggle('is-active', active);
				place.querySelector('.ct-place__pick').setAttribute('aria-pressed', String(active));
			});
			frame.src = pick.dataset.map;

			// Single-column layout puts the map above the list: bring it into view.
			if (window.matchMedia('(max-width: 900px)').matches) {
				const top = frame.closest('.ct-map').getBoundingClientRect().top;
				if (top < 0) {
					const behavior = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';
					window.scrollBy({ top: top - 100, behavior });
				}
			}
		});
	}

	/* Message form ------------------------------------------------------------ */
	const form = document.querySelector('[data-message-form]');
	if (!form) return;

	const wrap = document.querySelector('[data-message-form-wrap]');
	const success = document.querySelector('[data-message-success]');
	const errorBox = form.querySelector('[data-message-error]');
	const submit = form.querySelector('[type="submit"]');
	const label = submit.querySelector('[data-submit-label]');
	const validationMessage = errorBox.textContent;

	const rules = {
		name: (v) => v.trim().length > 1,
		email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
		phone: (v) => v.trim() === '' || /^[0-9+()\s-]{7,20}$/.test(v.trim()),
		topic: (v) => v !== '',
		message: (v) => v.trim().length >= 10,
	};

	const validate = () => {
		let firstInvalid = null;
		Object.entries(rules).forEach(([name, test]) => {
			const field = form.elements[name];
			const ok = test(field.value);
			field.closest('.field').classList.toggle('is-invalid', !ok);
			field.setAttribute('aria-invalid', String(!ok));
			if (!ok && !firstInvalid) firstInvalid = field;
		});
		return firstInvalid;
	};

	// Clear an error as soon as the visitor fixes that field.
	const recheck = (e) => {
		const field = e.target.closest('.field');
		const test = rules[e.target.name];
		if (field && test && field.classList.contains('is-invalid') && test(e.target.value)) {
			field.classList.remove('is-invalid');
			e.target.setAttribute('aria-invalid', 'false');
		}
	};
	form.addEventListener('input', recheck);
	form.addEventListener('change', recheck);

	const showSuccess = () => {
		wrap.hidden = true;
		success.hidden = false;
		success.focus({ preventScroll: true });

		if (window.gsap && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			window.gsap.timeline({ defaults: { ease: 'power3.out' } })
				.from(success.querySelector('.ct-success__icon'), { scale: 0, rotation: -90, duration: 0.8, ease: 'back.out(2)' })
				.from([...success.children].slice(1), { opacity: 0, y: 16, duration: 0.6, stagger: 0.08 }, '-=0.4');
		}
	};

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const invalid = validate();
		if (invalid) {
			errorBox.textContent = validationMessage;
			errorBox.hidden = false;
			invalid.focus();
			return;
		}
		errorBox.hidden = true;

		const original = label.textContent;
		submit.disabled = true;
		label.textContent = submit.dataset.loading || 'Sending…';

		try {
			// getAttribute: `form.action` would return the hidden <input name="action"> WordPress needs.
			const response = await fetch(form.getAttribute('action'), {
				method: 'POST',
				body: new FormData(form),
				headers: { Accept: 'application/json' },
				credentials: 'same-origin',
			});
			const data = await response.json();
			if (!data.success) throw new Error('Rejected');
			form.reset();
			showSuccess();
		} catch (err) {
			errorBox.textContent = `Sorry, something went wrong. Please try again or email ${form.dataset.email}.`;
			errorBox.hidden = false;
		} finally {
			submit.disabled = false;
			label.textContent = original;
		}
	});
})();
