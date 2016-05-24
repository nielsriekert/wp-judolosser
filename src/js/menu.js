addEventListener('load', initMenu, false);

function initMenu(){
	document.getElementById('header-menu').addEventListener('click', menuOnClick, false);
}

function menuOnClick(e){
	document.body.classList.toggle('nav-active');
	document.body.classList.toggle('no-scroll');
}