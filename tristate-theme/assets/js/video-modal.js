/**
 * Video pop-up: links with data-video="<YouTube ID>" play in a <dialog>
 * instead of leaving the site. Without JS the link opens YouTube.
 */
(() => {
	const modal = document.querySelector('[data-video-modal]');
	if (!modal || typeof modal.showModal !== 'function') return;

	const frame = modal.querySelector('[data-video-frame]');
	const lenis = () => window.tristateLenis; // smooth-scroll.js, when loaded
	let opener = null;

	const open = (id, link) => {
		opener = link;
		const iframe = document.createElement('iframe');
		// youtube-nocookie: no YouTube cookies until the visitor plays the video.
		iframe.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}?autoplay=1&rel=0&modestbranding=1`;
		iframe.title = link.textContent.trim() || 'Video';
		iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
		iframe.allowFullscreen = true;
		frame.replaceChildren(iframe);

		modal.showModal();
		document.documentElement.classList.add('has-video-modal');
		if (lenis()) lenis().stop();
	};

	document.addEventListener('click', (e) => {
		const link = e.target.closest('[data-video]');
		if (!link || e.metaKey || e.ctrlKey || e.shiftKey) return; // let modified clicks open YouTube
		e.preventDefault();
		open(link.dataset.video, link);
	});

	modal.querySelector('[data-video-close]').addEventListener('click', () => modal.close());

	// A click on the dimmed backdrop (the dialog itself, outside the inner box) closes it.
	modal.addEventListener('click', (e) => {
		if (e.target === modal) modal.close();
	});

	// Esc and the close button both end here: stop playback by removing the player.
	modal.addEventListener('close', () => {
		frame.replaceChildren();
		document.documentElement.classList.remove('has-video-modal');
		if (lenis()) lenis().start();
		if (opener) opener.focus({ preventScroll: true });
	});
})();
