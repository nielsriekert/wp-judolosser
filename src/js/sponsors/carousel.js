function CarouselSlider(carouselElement, options, slideContainer, indicatorContainer, noAutoPlay){
	//default options
	this.options = {
		'autoplay' : true,
		'autoDelay' : 4000,
		'actionDelay' : 14000,
		'animationType' : 'dissolve',
		'startSlideNo' : 1,
		'indicatorClickable' : true,
		'touchSupport' : false,
		'itemsPerSlide' : 1,
		'breakPoints' : [
			{
				'maxWidth' : 370,
				'itemsPerSlide' : 1
			},
			{
				'maxWidth' : 570,
				'itemsPerSlide' : 2
			},
			{
				'maxWidth' : 670,
				'itemsPerSlide' : 3
			}
		]
	};

	this.state = 'unloaded';

	//voeg input opties samen met default opties
    for (let attrname in options) { this.options[attrname] = options[attrname]; }

	this.carouselElement = carouselElement;
	this.slides = [];
	this.slideContainer = slideContainer;
	this.currentSlide = this.options.startSlideNo;
	this.itemsPerSlide = this.options.itemsPerSlide;

	this.noAutoPlay = noAutoPlay;

	this.timeOutHandler = false;

	//bind
	this.onTouch = this.onTouch.bind(this);
	this.nextSlide = this.nextSlide.bind(this);
	this.prevSlide = this.prevSlide.bind(this);
	this.onResize = this.onResize.bind(this);
	this.init = this.init.bind(this);
	this.destroy = this.destroy.bind(this);

	this.touchStartX = false;
}

CarouselSlider.prototype.init = function() {
	this.updateItemsPerSlide();

	var _this = this;

	for(let i=0;i<this.slideContainer.childNodes.length;i++){
		if(this.slideContainer.childNodes[i].nodeType == 1){
			this.slides.push(this.slideContainer.childNodes[i]);
		}
	}

	if(typeof indicatorContainer == 'object'){
		this.indicatorContainer = indicatorContainer;
		var elementArray = [];
		var exists = false;
		for(let i=0;i<this.indicatorContainer.childNodes.length;i++){
			if(this.indicatorContainer.childNodes[i].nodeType == 1){
				elementArray.push(this.indicatorContainer.childNodes[i]);
			}
		}
		if(elementArray.length != this.slides.length){
			var node = this.indicatorContainer.firstChild;
			while(node){
				node.parentNode.removeChild(node);
			}
		}
		else {
			exists = true;
		}
		for(let i=0;i<this.slides.length;i++){
			var indicatorElement = document.createElement('button');
			if(exists){
				indicatorElement = elementArray[i];
			}
			indicatorElement.setAttribute('class', 'carousel-indicator');
			if(i+1 == this.currentSlide){
				indicatorElement.classList.add('is-active');
			}
			indicatorElement.setAttribute('data-slideno', i+1);
			if(!exists){
				this.indicatorContainer.appendChild(indicatorElement);
			}
			if(this.options.indicatorClickable){
				indicatorElement.addEventListener('click', function(e){_this.changeSlide(e);}, false); // jshint ignore:line
			}
		}
	}
	this.state = 'loaded';
	this.updateSlide();

	if(this.options.animationType == 'horizontal'){
		for(var i=0;i<this.slides.length;i++){
			this.slides[i].setAttribute('style', 'width: ' + 100 / this.slides.length + '%;');
		}
	}

	this.carouselElement.classList.remove('unloaded');
	this.carouselElement.classList.add('is-loaded');


	if(this.options.autoplay && !this.noAutoPlay){
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.options.autoDelay);
	}

	//touch
	if(this.options.touchSupport){
		if(document.addEventListener){
			this.slideContainer.addEventListener('touchstart', this.onTouch, false);
			document.addEventListener('touchend', this.onTouch, false);
		}
	}

	window.addEventListener('resize', this.onResize);
}

// TODO: minimal implementation
CarouselSlider.prototype.destroy = function() {
	this.itemsPerSlide = this.options.itemsPerSlide;

	this.carouselElement.classList.remove('is-loaded');
	this.carouselElement.classList.add('unloaded');

	this.slides.forEach(slide => {
		slide.removeAttribute('style');
	});

	this.slideContainer.removeAttribute('style');

	clearTimeout(this.timeOutHandler);

	this.slides = [];
	window.removeEventListener('resize', this.onResize);
	this.state = 'unloaded';
}

CarouselSlider.prototype.updateItemsPerSlide = function() {
	this.itemsPerSlide = false;
	for(var i=0;i<this.options.breakPoints.length;i++){
		if(window.innerWidth < this.options.breakPoints[i].maxWidth && this.options.itemsPerSlide > this.options.breakPoints[i].itemsPerSlide){
			this.itemsPerSlide = this.options.breakPoints[i].itemsPerSlide;
			break;
		}
	}

	if(!this.itemsPerSlide){
		this.itemsPerSlide = this.options.itemsPerSlide;
		this.carouselElement.classList.remove('is-overflown');
	}
	else {
		this.carouselElement.classList.add('is-overflown');
	}
}

CarouselSlider.prototype.onResize = function(e) {
	this.updateItemsPerSlide();

	this.updateSlide();
};

CarouselSlider.prototype.nextSlide = function(e){
	clearTimeout(this.timeOutHandler);
	this.options.autoplay = false;

	if(this.currentSlide >= (this.slides.length - (this.itemsPerSlide-1))){
		this.currentSlide = 1;
	}
	else {
		this.currentSlide ++;
	}

	this.changeSlide();
};


CarouselSlider.prototype.prevSlide = function(e){
	clearTimeout(this.timeOutHandler);
	this.options.autoplay = false;

	if(this.currentSlide <= 1){
		this.currentSlide = this.slides.length - (this.itemsPerSlide-1);
	}
	else {
		this.currentSlide --;
	}

	this.changeSlide();
};

CarouselSlider.prototype.onTouch = function(e){
	if(e.type == 'touchstart'){
		e.preventDefault();
	}

	if(e.targetTouches.length == 1 || e.changedTouches.length == 1) {
		var touch = false;
		if(e.changedTouches.length == 1){
			touch = e.changedTouches[0];
		}
		else if(e.targetTouches.length == 1){
			touch = e.targetTouches[0];
		}

		if(e.type == 'touchstart'){
			this.touchStartX = touch.pageX;
		}
		else if(e.type == 'touchend' && this.touchStartX){
			if(this.touchStartX < touch.pageX){
				this.prevSlide();
			}
			else if(this.touchStartX > touch.pageX){
				this.nextSlide();
			}
			this.touchStartX = false;
		}
	}
};

CarouselSlider.prototype.changeSlide = function(e){
	var target = false;
	if(e && e.target){
		target = e.target;
	}

	while(target && !target.getAttribute('data-slideno')){
		target = target.parentNode;
	}

	if(target && target.getAttribute('data-slideno')){
		clearTimeout(this.timeOutHandler);
		this.options.autoplay = false;
		this.currentSlide = target.getAttribute('data-slideno');
	}

	var _this = this;
	if(this.options.autoplay && !this.noAutoPlay){
		if(this.currentSlide >= (this.slides.length - (this.itemsPerSlide-1))){
			this.currentSlide = 1;
		}
		else {
			this.currentSlide ++;
		}
		clearTimeout(this.timeOutHandler);
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.options.autoDelay);
	}
	else if(!this.noAutoPlay) {
		this.options.autoplay = true;
		clearTimeout(this.timeOutHandler);
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.options.actionDelay);
	}

	if(typeof this.indicatorContainer == 'object'){
		var childNodes = this.indicatorContainer.childNodes;
		for(var i=0;i<childNodes.length;i++){
			if(childNodes[i].nodeType == 1){
				childNodes[i].classList.remove('is-active');
				if(childNodes[i].getAttribute('data-slideno') == this.currentSlide){
					childNodes[i].classList.add('is-active');
				}
			}
		}
	}

	this.updateSlide();
};

CarouselSlider.prototype.updateSlide = function(){
	if( this.state == 'unloaded' ) {
		return;
	}

	if(this.options.animationType == 'vertical'){
		this.slideContainer.setAttribute('style', 'top: -' + (this.currentSlide - 1) * 100 + '%;');
	}
	else if(this.options.animationType == 'horizontal'){
		this.slideContainer.setAttribute('style', 'width: ' + this.slides.length * 100 / this.itemsPerSlide + '%;  transform: translateX(-' + (this.currentSlide - 1) * 100 / this.slides.length + '%); -webkit-transform: translateX(-' + (this.currentSlide - 1) * 100 / this.slides.length + '%);');
	}
	else if(this.options.animationType == 'dissolve'){
		for(var i=0;i<this.slides.length;i++){
			if(this.currentSlide-1 == i){
				this.slides[i].classList.add('active');
			}
			else {
				this.slides[i].classList.remove('active');
			}
		}
	}
};

export default CarouselSlider;