function CarouselSlider(carouselObject, slideContainer, indicatorContainer, descriptionContainer, startSlideNo, autoDelay, actionDelay, animationType, touchSupport, wrapperElement, noAutoPlay, indicatorNotClickable){
	this.carousel = carouselObject;
	this.slides = new Array();
	this.slideContainer = slideContainer;
	this.currentSlide = startSlideNo;
	this.autoplay = true;
	this.noAutoPlay = noAutoPlay;
	this.indicatorNotClickable = indicatorNotClickable;

	this.autoDelay = (!isNaN(autoDelay)) ? autoDelay: 4000;
	this.actionDelay = (!isNaN(actionDelay)) ? actionDelay: 14000;
	this.timeOutHandler;
	this.animationType = (animationType) ? animationType: 'horizontal';
	if(wrapperElement){
		this.wrapperElement = wrapperElement;
	}
	
	this.touchStartX = false;
	
	for(var i=0;i<this.slideContainer.childNodes.length;i++){
		if(this.slideContainer.childNodes[i].nodeType == 1){
			this.slides.push(this.slideContainer.childNodes[i]);
		}
	}
	
	if(typeof descriptionContainer == 'object'){
		this.descriptionContainer = descriptionContainer;
		
		this.descriptionElements = new Array();
		
		for(var i=0;i<this.descriptionContainer.childNodes.length;i++){
			if(this.descriptionContainer.childNodes[i].nodeType == 1){
				this.descriptionElements.push(this.descriptionContainer.childNodes[i]);
			}
		}
		for(var i=0;i<this.descriptionElements.length;i++){
			if(i+1 == this.currentSlide){
				this.descriptionElements[i].setAttribute('id', 'd_active');
			}
		}
	}
	
	if(typeof indicatorContainer == 'object'){
		this.indicatorContainer = indicatorContainer;
		var elementArray = new Array(); 
		for(var i=0;i<this.indicatorContainer.childNodes.length;i++){
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
			var exists = true;
		}
		for(var i=0;i<this.slides.length;i++){
			if(!exists){
				var indicatorElement = document.createElement('div');
			}
			else {
				indicatorElement = elementArray[i];
			}
			if(i+1 == this.currentSlide){
				indicatorElement.setAttribute('id', 's_active');
			}
			indicatorElement.setAttribute('data-slideno', i+1);
			if(!exists){
				this.indicatorContainer.appendChild(indicatorElement);
			}
			var _this = this;
			if(!this.indicatorNotClickable){
				addEventHandler(indicatorElement, 'click', function(e){_this.changeSlide(e);}, false);
			}
		}
	}
	
	this.updateSlide();
	
	if(this.animationType == 'horizontal'){
		for(var i=0;i<this.slides.length;i++){
			this.slides[i].setAttribute('style', 'width: ' + 100 / this.slides.length + '%;');
		}
	}
	
	var unloadedClass = this.carousel.getAttribute('class');
	this.carousel.setAttribute('class', unloadedClass.replace(/unloaded/, '').trim());
	
	var _this = this;
	if(this.autoplay && !this.noAutoPlay){
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.autoDelay);
	}
	
	//touch
	if(this.touchSupport){
		if(document.addEventListener){
			slideContainer.addEventListener('touchstart', function(e){ _this.onTouch(e);}, false);
			document.addEventListener('touchend', function(e){ _this.onTouch(e);}, false);
		}
	}
}

CarouselSlider.prototype.nextSlide = function(e){
	if(e.type == 'keydown' && e.keyCode != 39){return;}
	else if(e && e.type != 'keydown' && !checkMouseButton(e)){
		return;
	}

	clearTimeout(this.timeOutHandler);
	this.autoplay = false;
	
	if(this.currentSlide >= this.slides.length){
		this.currentSlide = 1;
	}
	else {
		this.currentSlide ++;
	}
	
	this.changeSlide();
}


CarouselSlider.prototype.prevSlide = function(e){
	if(e.type == 'keydown' && e.keyCode != 37){return;}
	else if(e && e.type != 'keydown' && !checkMouseButton(e)){
		return;
	}
	
	clearTimeout(this.timeOutHandler);
	this.autoplay = false;
	
	if(this.currentSlide <= 1){
		this.currentSlide = this.slides.length;
	}
	else {
		this.currentSlide --;
	}
	
	this.changeSlide();
}

CarouselSlider.prototype.onTouch = function(e){
	if(e.type == 'touchstart'){
		e.preventDefault();
	}
	
	if(e.targetTouches.length == 1 || e.changedTouches.length == 1) {
		if(e.changedTouches.length == 1){
			var touch = e.changedTouches[0];
		}
		else if(e.targetTouches.length == 1){
			var touch = e.targetTouches[0];
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
}

CarouselSlider.prototype.changeSlide = function(e){
	//this.slideContainer.removeAttribute('class');//slordig

	if(e && e.target){
		var target = e.target;
	}
	else if(e && e.srcElement){
		var target = e.srcElement;
	}
	
	while(target && !target.getAttribute('data-slideno')){
		target = target.parentNode;
	}

	if(target && target.getAttribute('data-slideno')){
		clearTimeout(this.timeOutHandler);
		this.autoplay = false;
		this.currentSlide = target.getAttribute('data-slideno');
	}
	
	var _this = this;
	if(this.autoplay && !this.noAutoPlay){
		if(this.currentSlide >= this.slides.length){
			this.currentSlide = 1;
		}
		else {
			this.currentSlide ++;
		}
		clearTimeout(this.timeOutHandler);
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.autoDelay);		
	}
	else if(!this.noAutoPlay) {
		this.autoplay = true;
		clearTimeout(this.timeOutHandler);
		this.timeOutHandler = setTimeout(function(){ _this.changeSlide();}, this.actionDelay);
	}
	
	if(typeof this.indicatorContainer == 'object'){
		var childNodes = this.indicatorContainer.childNodes;
		for(var i=0;i<childNodes.length;i++){
			if(childNodes[i].nodeType == 1){
				childNodes[i].removeAttribute('id');
				if(childNodes[i].getAttribute('data-slideno') == this.currentSlide){
					childNodes[i].setAttribute('id', 's_active');
				}
			}
		}
	}
	
	if(this.descriptionElements){
		this.changeDescription();
	}
	
	this.updateSlide();
	//this.slideContainer.setAttribute('style', 'width: ' + this.slides.length * 100 + '%; ' + this.animationDirection + ': -' + (this.currentSlide - 1) * 100 + '%;');
}

CarouselSlider.prototype.changeDescription = function(){	
	for(var i=0;i<this.descriptionElements.length;i++){
		this.descriptionElements[i].removeAttribute('id');
		if(i+1 == this.currentSlide){
			this.descriptionElements[i].setAttribute('id', 'd_active');
		}
	}
}

CarouselSlider.prototype.updateSlide = function(){
	if(this.animationType == 'vertical'){
		this.slideContainer.setAttribute('style', 'top: -' + (this.currentSlide - 1) * 100 + '%;');
	}
	else if(this.animationType == 'horizontal'){
		//this.slideContainer.setAttribute('style', 'width: ' + this.slides.length * 100 + '%; left: -' + (this.currentSlide - 1) * 100 + '%;');
		this.slideContainer.setAttribute('style', 'width: ' + this.slides.length * 100 + '%; transform: translateX(-' + ((this.currentSlide - 1) * 100) / this.slides.length + '%); -webkit-transform: translateX(-' + ((this.currentSlide - 1) * 100) / this.slides.length + '%);');
	}
	else if(this.animationType == 'dissolve'){
		for(var i=0;i<this.slides.length;i++){
			if(this.currentSlide-1 == i){
				this.slides[i].addClassName('active');
			}
			else {
				this.slides[i].removeClassName('active');
			}
		}
	}
}