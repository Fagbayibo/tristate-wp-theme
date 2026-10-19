/**
 * Appointment form: inline validation + submit without reloading the page.
 * Without JS the form still posts normally (see inc/appointments.php).
 * Entries are kept when sending fails, so nothing has to be typed again.
 */
(() => {
	const form = document.querySelector('[data-appointment-form]');
	if (!form) return;

	const wrap = document.querySelector('[data-appointment-form-wrap]');
	const success = document.querySelector('[data-appointment-success]');
	const errorBox = form.querySelector('[data-appointment-error]');
	const submit = form.querySelector('[type="submit"]');
	const label = submit.querySelector('[data-submit-label]');
	const dateField = form.querySelector('[data-date-field]');
	const validationMessage = errorBox.textContent;

	// The whole date box opens the picker.
	if (dateField) {
		const input = dateField.querySelector('input');
		input.addEventListener('click', () => {
			try { input.showPicker(); } catch (e) { /* not supported: native behaviour */ }
		});
	}

	// Links elsewhere on the page (?service=… or ?visit=…#appointment) preselect
	// the form; the server does this on load, this covers same-page links.
	const preselect = (url) => {
		const params = new URL(url, window.location.href).searchParams;
		const service = params.get('service');
		const visit = params.get('visit') || params.get('hospital');
		if (service && form.elements.service.querySelector(`option[value="${CSS.escape(service)}"]`)) {
			form.elements.service.value = service;
			form.elements.service.dispatchEvent(new Event('change', { bubbles: true })); // updates the styled dropdown
		}
		if (visit) {
			const radio = form.querySelector(`input[name="visit"][value="${CSS.escape(visit)}"]`);
			if (radio) radio.checked = true;
		}
	};
	document.addEventListener('click', (e) => {
		const link = e.target.closest('a[href*="#appointment"]');
		if (link && new URL(link.href).pathname === window.location.pathname) preselect(link.href);
	});

	const rules = {
		name: (v) => v.trim().length > 1,
		phone: (v) => /^[0-9+()\s-]{7,20}$/.test(v.trim()),
		service: (v) => v !== '',
		visit: (v) => v !== '',
		email: (v) => v.trim() === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
	};

	const validate = () => {
		let firstInvalid = null;
		Object.entries(rules).forEach(([name, test]) => {
			// Radio groups come back as a RadioNodeList: its .value is the checked option.
			const control = form.elements[name];
			const inputs = control instanceof RadioNodeList ? [...control] : [control];
			const ok = test(control.value);
			inputs[0].closest('.field').classList.toggle('is-invalid', !ok);
			inputs.forEach((input) => input.setAttribute('aria-invalid', String(!ok)));
			if (!ok && !firstInvalid) firstInvalid = inputs[0];
		});
		return firstInvalid;
	};

	// Clear an error as soon as the visitor fixes that field.
	const recheck = (e) => {
		const field = e.target.closest('.field');
		const test = rules[e.target.name];
		if (field && test && field.classList.contains('is-invalid') && test(form.elements[e.target.name].value)) {
			field.classList.remove('is-invalid');
			field.querySelectorAll('[aria-invalid]').forEach((input) => input.setAttribute('aria-invalid', 'false'));
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
				.from(success.querySelector('.appointment__success-icon'), { scale: 0, rotation: -90, duration: 0.8, ease: 'back.out(2)' })
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
		label.textContent = 'Sending…';

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
			errorBox.textContent = `Your request has not been sent. Please try again or contact ${form.dataset.email}.`;
			errorBox.hidden = false;
		} finally {
			submit.disabled = false;
			label.textContent = original;
		}
	});
})();
