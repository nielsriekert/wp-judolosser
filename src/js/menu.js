/*addEventListener('load', initMenu, false);

function initMenu(){
	document.getElementById('header-menu').addEventListener('click', menuOnClick, false);
}

function menuOnClick(e){
	document.body.classList.toggle('nav-active');
	document.body.classList.toggle('no-scroll');
}*/

window.addEventListener('DOMContentLoaded', function(){

	var sideNav = new SideNav(document.getElementById('header-nav-wrapper'), document.getElementById('header-nav'), document.getElementById('header-menu'));

}, false);

function SideNav(headerNavigationElement, headerNavElement, buttonElement){
	this.headerNavigation = headerNavigationElement;
	this.headerNav = headerNavElement;
	this.button = buttonElement;

	this.toggleSideNav = this.toggleSideNav.bind(this);
	this.blockClicks = this.blockClicks.bind(this);
	this.onTouchMove = this.onTouchMove.bind(this);
	this.onTouchStart = this.onTouchStart.bind(this);
	this.onTouchEnd = this.onTouchEnd.bind(this);
	this.onTransitionEnd = this.onTransitionEnd.bind(this);
	this.update = this.update.bind(this);

	this.startX = 0;
    this.currentX = 0;
    this.touchingSideNav = false;

	this.button.addEventListener('click', this.toggleSideNav);
	this.headerNav.addEventListener('click', this.blockClicks);
	this.headerNavigation.addEventListener('touchstart', this.onTouchStart);
	this.headerNavigation.addEventListener('touchmove', this.onTouchMove);
	this.headerNavigation.addEventListener('touchend', this.onTouchEnd);
	this.headerNavigation.addEventListener('click', this.toggleSideNav);
}

SideNav.prototype.toggleSideNav = function(e){
	this.headerNavigation.classList.add('header-nav-wrapper-animatable');
	this.headerNavigation.classList.toggle('header-nav-wrapper-visible');
	document.body.classList.toggle('nav-active');

	this.headerNavigation.addEventListener('transitionend', this.onTransitionEnd);
}

SideNav.prototype.onTransitionEnd = function() {
	this.headerNavigation.classList.remove('header-nav-wrapper-animatable');
	this.headerNavigation.removeEventListener('transitionend', this.onTransitionEnd);
}

SideNav.prototype.onTouchStart = function(e) {
	if(!this.headerNavigation.classList.contains('header-nav-wrapper-visible'))
		return;

	this.startX = e.touches[0].pageX;
	this.currentX = this.startX;

	this.touchingSideNav = true;
	requestAnimationFrame(this.update);
}

SideNav.prototype.onTouchMove = function(e) {
	if(!this.touchingSideNav)
		return;

	this.currentX = e.touches[0].pageX;
	var translateX = Math.min(0, this.currentX - this.startX);

	if(translateX > 0) {
		e.preventDefault();
	}
}

SideNav.prototype.onTouchEnd = function(e) {
	if(!this.touchingSideNav)
		return;

	this.touchingSideNav = false;

	var translateX = Math.min(0, this.currentX - this.startX);
	this.headerNav.style.transform = '';

	if(translateX < 20) {
		this.toggleSideNav();
	}
}

SideNav.prototype.update = function() {
	if(!this.touchingSideNav)
		return;

	requestAnimationFrame(this.update);

	var translateX = Math.min(0, this.currentX - this.startX);
	this.headerNav.style.transform = 'translateX(' + translateX + 'px)';
}


SideNav.prototype.blockClicks = function(e) {
	e.stopPropagation();
}