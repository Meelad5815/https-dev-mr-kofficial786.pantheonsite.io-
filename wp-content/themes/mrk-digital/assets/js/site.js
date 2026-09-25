(() => {
	const button = document.querySelector('.menu-toggle');
	const nav = document.querySelector('.primary-nav');
	const event = (name) => {
		if (window.gtag) window.gtag('event', name, { event_category: 'engagement' });
	};
	const setMenuState = (open) => {
		if (!button || !nav) return;
		nav.classList.toggle('open', open);
		button.setAttribute('aria-expanded', String(open));
	};
	if (button && nav) {
		button.addEventListener('click', () => {
			setMenuState(!nav.classList.contains('open'));
		});
		nav.querySelectorAll('a').forEach((link) => {
			link.addEventListener('click', () => setMenuState(false));
		});
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && nav.classList.contains('open')) {
				setMenuState(false);
				button.focus();
			}
		});
	}
	document.querySelectorAll('.js-whatsapp').forEach((link) => link.addEventListener('click', () => event('whatsapp_click')));
	document.querySelectorAll('.quote-form').forEach((form) => form.addEventListener('submit', () => event('quote_submit')));
	document.querySelectorAll('[data-event="service_cta"], [data-event="project_cta"]').forEach((link) => link.addEventListener('click', () => event(link.dataset.event)));
})();