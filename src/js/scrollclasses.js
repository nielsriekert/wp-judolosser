function ScrollClasses(element, positions){
	if(!window.requestAnimationFrame){
		return;
	}

	if(element.nodeType !== 1 || !Array.isArray(positions)){
		return;
	}
	this.element = element;
	this.positions = positions;

	this.latestKnownScrollY = 0;
	this.ticking = false;

	this.onScroll = this.onScroll.bind(this);
	this.requestTick = this.requestTick.bind(this);
	this.update = this.update.bind(this);

	window.addEventListener('scroll', this.onScroll);
}

/**
 * Callback for our scroll event - just
 * keeps track of the last scroll value
 */
ScrollClasses.prototype.onScroll = function(){
	this.latestKnownScrollY = window.scrollY;
	this.requestTick();
};

/**
 * Calls rAF if it's not already
 * been done already
 */
ScrollClasses.prototype.requestTick = function(){
	if(!this.ticking) {
		requestAnimationFrame(this.update);
	}
	this.ticking = true;
};

/**
 * Update the element status
 */
ScrollClasses.prototype.update = function(time){

	this.positions.forEach((position) => {
		if(this.latestKnownScrollY > position.scrolled){
			this.element.classList.add(position.class);
		}
		else {
			this.element.classList.remove(position.class);
		}
	});

	this.ticking = false;
};

export default ScrollClasses;