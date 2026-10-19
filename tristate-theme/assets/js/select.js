/**
 * Styled dropdowns for the booking and message forms.
 *
 * Each <select class="field__control"> inside a .field--select gets a button
 * and a listbox in the site's style (option groups become headings). The real
 * <select> stays in the form, hidden, so submission, validation and the
 * no-JS fallback are unchanged: choosing an option sets its value and fires
 * the usual input/change events.
 *
 * Keyboard: Enter, Space or the arrow keys open; arrows, Home and End move;
 * typing jumps to a matching option; Enter or Space chooses; Escape closes.
 */
(() => {
	let uid = 0;

	const enhance = (select) => {
		const field = select.closest('.field--select');
		const label = field.querySelector(`label[for="${select.id}"]`);
		const id = `dd-${++uid}`;

		const button = document.createElement('button');
		button.type = 'button';
		button.className = 'field__control select__button';
		button.id = `${id}-button`;
		button.setAttribute('aria-haspopup', 'listbox');
		button.setAttribute('aria-expanded', 'false');
		button.setAttribute('aria-controls', `${id}-list`);
		if (label) {
			if (!label.id) label.id = `${id}-label`;
			button.setAttribute('aria-labelledby', `${label.id} ${button.id}`);
			label.htmlFor = button.id; // clicking the label opens the dropdown
		}

		const list = document.createElement('ul');
		list.className = 'select__list';
		list.id = `${id}-list`;
		list.setAttribute('role', 'listbox');
		list.tabIndex = -1;
		list.hidden = true;
		list.dataset.lenisPrevent = '';
		if (label) list.setAttribute('aria-labelledby', label.id);

		// Build the options (skipping the disabled placeholder), with group headings.
		const items = [];
		const addOption = (option) => {
			if (option.disabled && option.value === '') return;
			const li = document.createElement('li');
			li.className = 'select__option';
			li.id = `${id}-opt-${items.length}`;
			li.setAttribute('role', 'option');
			li.dataset.value = option.value;
			li.textContent = option.textContent;
			list.appendChild(li);
			items.push(li);
		};
		[...select.children].forEach((child) => {
			if (child.tagName === 'OPTGROUP') {
				const head = document.createElement('li');
				head.className = 'select__group';
				head.setAttribute('role', 'presentation');
				head.textContent = child.label;
				list.appendChild(head);
				[...child.children].forEach(addOption);
			} else {
				addOption(child);
			}
		});

		select.classList.add('select__native');
		select.tabIndex = -1;
		select.setAttribute('aria-hidden', 'true');
		select.after(button, list);
		field.classList.add('is-enhanced');

		let active = -1;

		const sync = () => {
			const option = select.options[select.selectedIndex];
			const empty = !option || option.value === '';
			button.textContent = option ? option.textContent : '';
			button.classList.toggle('is-placeholder', empty);
			items.forEach((li) => li.setAttribute('aria-selected', String(li.dataset.value === select.value && !empty)));
		};

		const setActive = (index) => {
			if (!items.length) return;
			active = Math.max(0, Math.min(items.length - 1, index));
			items.forEach((li, i) => li.classList.toggle('is-active', i === active));
			list.setAttribute('aria-activedescendant', items[active].id);
			const li = items[active];
			if (li.offsetTop < list.scrollTop) list.scrollTop = li.offsetTop - 8;
			else if (li.offsetTop + li.offsetHeight > list.scrollTop + list.clientHeight) list.scrollTop = li.offsetTop + li.offsetHeight - list.clientHeight + 8;
		};

		const open = () => {
			if (!list.hidden) return;
			list.hidden = false;
			// Open upwards when there is not enough room below.
			const below = window.innerHeight - button.getBoundingClientRect().bottom;
			field.classList.toggle('opens-up', below < Math.min(list.scrollHeight, 320) + 16);
			field.classList.add('is-open');
			button.setAttribute('aria-expanded', 'true');
			const current = items.findIndex((li) => li.dataset.value === select.value);
			setActive(current >= 0 ? current : 0);
			list.focus({ preventScroll: true });
		};

		const close = (refocus) => {
			if (list.hidden) return;
			list.hidden = true;
			field.classList.remove('is-open', 'opens-up');
			button.setAttribute('aria-expanded', 'false');
			if (refocus) button.focus({ preventScroll: true });
		};

		const choose = (index) => {
			const li = items[index];
			if (!li) return;
			if (select.value !== li.dataset.value) {
				select.value = li.dataset.value;
				select.dispatchEvent(new Event('input', { bubbles: true }));
				select.dispatchEvent(new Event('change', { bubbles: true }));
			}
			sync();
			close(true);
		};

		button.addEventListener('click', () => (list.hidden ? open() : close(true)));
		button.addEventListener('keydown', (e) => {
			if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) {
				e.preventDefault();
				open();
			}
		});

		let typed = '';
		let typedTimer = 0;
		list.addEventListener('keydown', (e) => {
			switch (e.key) {
				case 'ArrowDown': e.preventDefault(); setActive(active + 1); break;
				case 'ArrowUp': e.preventDefault(); setActive(active - 1); break;
				case 'Home': e.preventDefault(); setActive(0); break;
				case 'End': e.preventDefault(); setActive(items.length - 1); break;
				case 'Enter':
				case ' ': e.preventDefault(); choose(active); break;
				case 'Escape': e.preventDefault(); close(true); break;
				case 'Tab': choose(active); break;
				default:
					if (e.key.length === 1) {
						clearTimeout(typedTimer);
						typed += e.key.toLowerCase();
						typedTimer = setTimeout(() => { typed = ''; }, 600);
						const match = items.findIndex((li) => li.textContent.toLowerCase().startsWith(typed));
						if (match >= 0) setActive(match);
					}
			}
		});

		list.addEventListener('click', (e) => {
			const li = e.target.closest('.select__option');
			if (li) choose(items.indexOf(li));
		});
		list.addEventListener('mousemove', (e) => {
			const li = e.target.closest('.select__option');
			if (li && items.indexOf(li) !== active) setActive(items.indexOf(li));
		});
		list.addEventListener('focusout', (e) => {
			if (!field.contains(e.relatedTarget)) close(false);
		});
		document.addEventListener('pointerdown', (e) => {
			if (!field.contains(e.target)) close(false);
		});

		// Keep the button in step with the real select: preselection, form.reset(),
		// and the form scripts focusing or flagging the select as invalid.
		select.addEventListener('change', sync);
		select.addEventListener('focus', () => button.focus());
		if (select.form) select.form.addEventListener('reset', () => setTimeout(sync));
		new MutationObserver(() => {
			const invalid = select.getAttribute('aria-invalid');
			if (invalid) button.setAttribute('aria-invalid', invalid);
		}).observe(select, { attributes: true, attributeFilter: ['aria-invalid'] });

		sync();
	};

	document.querySelectorAll('.field--select > select.field__control').forEach(enhance);
})();
