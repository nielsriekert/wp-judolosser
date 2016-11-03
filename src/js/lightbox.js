function LightBox(aElements, options){
	this.aElements = [].slice.call(aElements);

	//default options
	this.options = {
		'temp' : ''
	};

	//voeg input opties samen met default opties
	for(var attrname in options){ this.options[attrname] = options[attrname];}

	var _this = this;

	//setup viewer
	this.bgElement = document.createElement('div');
	this.bgElement.setAttribute('class', 'lightbox-background');
	this.bgElement.addEventListener('click', function(e){ _this.deactivateViewer();})

	document.body.appendChild(this.bgElement);

	for(var i=0;i<this.aElements.length;i++){
		this.aElements[i].addEventListener('click', function(e){ _this.openViewer(e);});
	}
};

LightBox.prototype.openViewer = function(e){
	e.preventDefault();

	var target = e.target;
	while(target.nodeName != 'A' && target.nodeName != 'BODY'){
		target = target.parentNode;
	}

	if(this.aElements.find(function(aElement){ return aElement == target;})){
		this.activateViewer();
	}
};

LightBox.prototype.activateViewer = function(){
	this.bgElement.classList.add('js-active');
};

LightBox.prototype.deactivateViewer = function(){
	this.bgElement.classList.remove('js-active');
};