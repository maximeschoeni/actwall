// function registerSticky(element, windowY) {
// 	var manager = {
// 		active: true,
// 		windowY: windowY || 0,
// 		stickOffset: 0,
// 		offsetY: 0,
// 		update: function() {
// 			if (this.active) {
// 				if (window.scrollY > this.y + element.clientHeight - this.windowY) {
// 					this.y = window.scrollY + this.windowY - element.clientHeight;
// 				} else if (window.scrollY < this.y - this.windowY) {
// 					this.y = window.scrollY + this.windowY;
// 				}
// 			} else {
// 				this.y = window.scrollY;
// 			}
// 			this.y = Math.max(this.y, this.stickOffset);
// 			if (this.y < window.scrollY + this.windowY) {
// 				element.style.position = "absolute";
// 				element.style.top = this.y + "px";
// 			} else {
// 				element.style.position = "fixed";
// 				element.style.top = this.windowY + "px";
// 			}
// 		}
// 	};
// 	window.addEventListener("scroll", function(event) {
// 		manager.update();
// 	});
// 	return manager;
// }

// history.scrollRestoration = "manual";


class Sticky {

	constructor(element, getHeight, getOffsetY) {

		this.element = element;
		this.active = true;
		this.getHeight = getHeight || (() => element.clientHeight);
		this.getOffsetY = getOffsetY || (() => 0);
		// this.windowY = windowY;
		// this.stickyOffset = stickyOffset;
		// this.y = element.offsetTop;
		this.y = this.getOffsetY();
		this.lastScrollY = 0;


		// console.log(element.offsetTop, element);

		window.addEventListener("scroll", event => {
			this.update();
		});


		this.update();

	}

	// getHeight() {
	//
	// 	return this.element.clientHeight;
	//
	// }
	//
	//
	//
	// getOffsetY() {
	//
	// 	return this.windowY || 0;
	//
	// }

	getStickyOffset() {

		return 0;
	}

	getUnstickyOffset() {
		return 0;
	}

	updateY() {

		const scrollY = Math.max(0, window.scrollY);

		if (this.active) {

			if (scrollY > this.y + this.getHeight() - this.getOffsetY()) {

				this.y = scrollY + this.getOffsetY() - this.getHeight();

			} else if (scrollY < this.y - this.getOffsetY()) {

				this.y = scrollY + this.getOffsetY();

			}

		} else {

			this.y = scrollY;

		}

		// this.y = Math.max(this.y, this.getStickyOffset());

	}

	update() {

		// if (this.active) {
		//
		// 	if (window.scrollY > this.y + this.getHeight() - this.getOffsetY()) {
		//
		// 		this.y = window.scrollY + this.getOffsetY() - this.getHeight();
		//
		// 	} else if (window.scrollY < this.y - this.getOffsetY()) {
		//
		// 		this.y = window.scrollY + this.getOffsetY();
		//
		// 	}
		//
		// } else {
		//
		// 	this.y = window.scrollY;
		//
		// }

		this.updateY();

		// console.log(this.y);

		const scrollY = Math.max(0, window.scrollY);

		if (scrollY - this.lastScrollY > 0) { // -> scroll down

			if (this.y <= scrollY + this.getOffsetY() - this.getHeight()) {

				this.element.style.position = "fixed";
				this.element.style.top = (this.getStickyOffset() + this.getOffsetY() - this.getHeight()) + "px";

			} else {

				this.element.style.position = "absolute";
				this.element.style.top = (this.getUnstickyOffset() + this.y) + "px";

			}

		} else { // -> scroll up

			if (this.y < scrollY + this.getOffsetY()) {

				this.element.style.position = "absolute";
				this.element.style.top = (this.getUnstickyOffset() + this.y) + "px";


			} else {

				this.element.style.position = "fixed";
				this.element.style.top = (this.getStickyOffset() + this.getOffsetY()) + "px";

			}


		}





		this.lastScrollY = window.scrollY;

	}

}



class Sticky2 {

	constructor(element, container, getHeight, getOffsetY) {

		this.container = container || window;
		this.element = element;
		this.active = true;
		this.getHeight = getHeight || (() => element.clientHeight);
		this.getOffsetY = getOffsetY || (() => 0);
		// this.y = element.offsetTop;

		this.y = this.getOffsetY();
		this.lastScrollY = 0;


		this.container.addEventListener("scroll", event => {
			this.update();
		});

		window.addEventListener("resize", event => {
			this.update();
		});

		this.update();

	}

	getScrollY() {
		if (this.container === window) {
			return Math.max(0, window.scrollY);
		} else {
			return Math.max(0, this.container.scrollTop);
		}
	}

	updateY() {

		const scrollY = this.getScrollY();

		if (this.active) {

			if (scrollY > this.y + this.getHeight() - this.getOffsetY()) {

				this.y = scrollY + this.getOffsetY() - this.getHeight();

			} else if (scrollY < this.y - this.getOffsetY()) {

				this.y = scrollY + this.getOffsetY();

			}

		} else {

			this.y = scrollY;

		}

	}

	update() {

		const scrollY = this.getScrollY();

		this.updateY();

		if (scrollY - this.lastScrollY > 0) { // -> scroll down



			if (this.y <= scrollY + this.getOffsetY() - this.getHeight()) {

				this.element.style.position = "fixed";
				this.element.style.top = (this.getOffsetY() - this.getHeight()) + "px";

			} else {

				this.element.style.position = "absolute";
				this.element.style.top = this.y + "px";

			}

		} else { // -> scroll up


			if (this.y < scrollY + this.getOffsetY()) {

				this.element.style.position = "absolute";
				this.element.style.top = this.y + "px";


			} else {

				this.element.style.position = "fixed";
				this.element.style.top = this.getOffsetY() + "px";

			}


		}





		this.lastScrollY = scrollY;

	}

}
