class NavSticky {
	constructor(header) {
		this.header = header;
		this.sticky = header.offsetTop;

		window.addEventListener('scroll', () => {
			this.stickyHeader();
		});
		this.stickyHeader();
	}

	stickyHeader() {
		if (window.pageYOffset > this.sticky) {
			this.header.classList.add('sticky');
		} else {
			this.header.classList.remove('sticky');
		}
	}
}

document.querySelectorAll('header').forEach(el => {
	new NavSticky(el);
});
