import 'normalize.css';
import 'vanillelightbox/dist/vanillelightbox.css';
import '../scss/main.scss';
import '../scss/editor-style.scss';

// images used in html
import '../images/logo.svg';

// screenshot for wp theme
import '../screenshot.png';

import SideNav from './sidenav.js';
import VanilleLightbox from 'vanillelightbox';
import ScrollClasses from './scrollclasses.js';

import ItemFilter from './item-filter';
import itemFilterTemplateArticles from './item-filter/templates/template-articles'
import itemFilterTemplateEvents from './item-filter/templates/template-events'
import itemFilterTemplatePhotoalbums from './item-filter/templates/template-photoalbums'

import LogoCarousel from './sponsors/logo-carousel'

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

window.addEventListener('DOMContentLoaded', function() {
	const articleItemsWrapper = document.querySelector('.article-item-wrapper');

	if (!articleItemsWrapper) {
		return;
	}

	window.itemFilter = new ItemFilter(
		articleItemsWrapper,
		{
			items: ajax_get_articles,
			//services: ajax_get_services,
		},
		{
			itemsTemplate: itemFilterTemplateArticles,
			//filterTemplate: itemFilterTemplateFilterServices
		}
	);
});

window.addEventListener('DOMContentLoaded', function() {
	const eventItemsWrapper = document.querySelector('.event-item-wrapper');

	if (!eventItemsWrapper) {
		return;
	}

	window.itemFilter = new ItemFilter(
		eventItemsWrapper,
		{
			items: ajax_get_events,
			//services: ajax_get_services,
		},
		{
			itemsTemplate: itemFilterTemplateEvents,
			//filterTemplate: itemFilterTemplateFilterServices
		}
	);
});

window.addEventListener('DOMContentLoaded', function() {
	const photoalbumItemsWrapper = document.querySelector('.photoalbum-item-wrapper');

	if (!photoalbumItemsWrapper) {
		return;
	}

	window.itemFilter = new ItemFilter(
		photoalbumItemsWrapper,
		{
			items: ajax_get_photoalbums,
			//services: ajax_get_services,
		},
		{
			itemsTemplate: itemFilterTemplatePhotoalbums,
			//filterTemplate: itemFilterTemplateFilterServices
		}
	);
});

window.addEventListener('DOMContentLoaded', function(){
	const footerWrapper = document.querySelector('.footer-wrapper')

	const sponsorContainer = document.createElement('div');

	document.body.insertBefore(sponsorContainer, footerWrapper);

	new LogoCarousel(sponsorContainer);
});