import 'normalize.css';
import 'vanillelightbox/dist/vanillelightbox.css';
import '../scss/main.scss';
import '../scss/style.scss';
import '../scss/editor-style.scss';

// images used in html
import '../images/logo.svg';

// screenshot for wp theme
import '../screenshot.png';

import SideNav from './sidenav.js';
import VanilleLightbox from 'vanillelightbox';
import ScrollClasses from './scrollclasses.js';

function isPostType(postTypes){
	if(typeof postTypes !== 'string' && ! Array.isArray(postTypes)){
		return false;
	}

	if(typeof postTypes === 'string'){
		postTypes = [postTypes];
	}

	for(let i=0;i<postTypes.length;i++){
		if(document.body.classList.contains('single-' + postTypes[i])){
			return true;
		}
	}

	return false;
}

/**
 *  Load al lightBoxes
 */
window.addEventListener('DOMContentLoaded', function(){

	if(isPostType('photoalbum')){
		new VanilleLightbox(document.querySelectorAll('.photos a.photo'));
	}

	if(isPostType(['post', 'event'])){
		let galleryItems = document.querySelectorAll('.gallery .gallery-item a[href$="jpg"]');
		new VanilleLightbox(galleryItems);

		let imageLinks = document.querySelectorAll('.article a[href$="jpg"]');

		galleryItems = [].slice.call(galleryItems);

		imageLinks = [].slice.call(imageLinks);
		
		if(Array.isArray(imageLinks)){
			imageLinks.forEach((imageLink) => {
				if(galleryItems.indexOf(imageLink) < 0){
					new VanilleLightbox(imageLink);
				}
			});
		}
	}
});

/**
 * Initialize main menu
 */
window.addEventListener('DOMContentLoaded', function(){

	var sideNav = new SideNav(document.getElementById('header-nav-wrapper'), document.getElementById('header-nav'), document.getElementById('header-menu'));

	var downloadbutton = document.getElementById('header-nav-download-button');
	if(downloadbutton){
		document.getElementById('header-nav-download-button').addEventListener('click', function(){
			document.getElementById('header-nav-download-container').classList.toggle('is-active');
		});

		document.addEventListener('click', function(e){
			var target = e.target;
			while(target.getAttribute('id') != 'header-nav-download-button' && target.getAttribute('id') != 'header-nav-download-container' && target != document.body){
				target = target.parentNode;
			}

			if(target == document.body){
				document.getElementById('header-nav-download-container').classList.remove('is-active');
			}
		});
	}
});

window.addEventListener('DOMContentLoaded', function(){
	const scrollClasses = new ScrollClasses(
		document.body,
		[
			{
				'scrolled': 50,
				'class': 'scrolled-down'
			},
			{
				'scrolled': 500,
				'class': 'scrolled-down-content'
			}
		]
	);
});