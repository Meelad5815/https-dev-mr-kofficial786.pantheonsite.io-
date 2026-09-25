(() => {
	const button = document.querySelector('.menu-toggle');
	const nav = document.querySelector('.primary-nav');
	const event = (name) => {
		if (window.gtag) window.gtag('event', name, { event_category: 'engagement' });
	};
	if (button && nav) {
		button.addEventListener('click', () => {
			const open = nav.classList.toggle('open');
			button.setAttribute('aria-expanded', String(open));
		});
	}
	document.querySelectorAll('.js-whatsapp').forEach((link) => link.addEventListener('click', () => event('whatsapp_click')));
	document.querySelectorAll('.quote-form').forEach((form) => form.addEventListener('submit', () => event('quote_submit')));
	document.querySelectorAll('[data-event="service_cta"], [data-event="project_cta"]').forEach((link) => link.addEventListener('click', () => event(link.dataset.event)));
})();
(() => { const button=document.querySelector('.menu-toggle'), nav=document.querySelector('.primary-nav'); if(button&&nav){button.addEventListener('click',()=>{const open=nav.classList.toggle('open');button.setAttribute('aria-expanded',String(open));});} document.querySelectorAll('.js-whatsapp').forEach((link)=>link.addEventListener('click',()=>{if(window.gtag) window.gtag('event','whatsapp_click',{event_category:'engagement'});})); })();
