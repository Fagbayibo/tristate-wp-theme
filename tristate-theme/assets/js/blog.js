/**
 * Single post: "Copy link" in the share row. Uses the system share sheet on
 * phones that have one, otherwise copies the address to the clipboard.
 */
(() => {
	const button = document.querySelector('[data-copy-link]');
	if (!button) return;

	const status = button.querySelector('.post-share__copied');
	let timer = 0;

	const say = (text) => {
		status.textContent = text;
		clearTimeout(timer);
		timer = setTimeout(() => { status.textContent = ''; }, 2000);
	};

	button.addEventListener('click', async () => {
		const url = button.dataset.copyLink;
		try {
			if (navigator.share && window.matchMedia('(pointer: coarse)').matches) {
				await navigator.share({ title: document.title, url });
				return;
			}
			await navigator.clipboard.writeText(url);
			say('Link copied');
		} catch (err) {
			if (err && err.name === 'AbortError') return; // share sheet dismissed
			say('Copy failed');
		}
	});
})();
