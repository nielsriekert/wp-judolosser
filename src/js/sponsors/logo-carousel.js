import {render} from 'lit-html'
import sanityClient from '@sanity/client'
import template from './templates/template-logo-carousel'
import Flickity from 'flickity'
import 'flickity/dist/flickity.css'

export default function LogoCarousel(containerElement) {
	this.containerElement = containerElement

	if (!containerElement) {
		return
	}

	const client = sanityClient({
		projectId: 'zffxhfow',
		dataset: 'judolosser',
		//token: 'sanity-auth-token', // or leave blank to be anonymous user
		useCdn: true // `false` if you want to ensure fresh data
	})

	render(template([]), this.containerElement)

	let sponsors = []
	client.fetch('*[_type == "sponsor"]').then(sponsorsData => {
		Promise.all(
			sponsorsData.map(sponsor => {
				return client.getDocument(sponsor.logo.asset._ref)
			})
		).then(sponsorLogos => {
			sponsorsData.map((sponsor, i) => {
				sponsors.push({
					name: sponsor.name,
					websiteUrl: sponsor.website,
					logoSrc: sponsorLogos[i].url + '?w=300'
				})
			})
			sponsors = this.shuffle(sponsors)
			render(template(sponsors), this.containerElement)
			this.setupCarousel()
		})
	}, error => console.log(error))
}

LogoCarousel.prototype.shuffle = function(array) {

	var currentIndex = array.length;
	var temporaryValue, randomIndex;

	// While there remain elements to shuffle...
	while (0 !== currentIndex) {
		// Pick a remaining element...
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex -= 1;

		// And swap it with the current element.
		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	}

	return array;
}

LogoCarousel.prototype.setupCarousel = function() {
	this.flickity = new Flickity(
		this.containerElement.querySelector('.logo-carousel-items-container'),
		{
			selectedAttraction: 0.01,
			friction: 1.5,
			wrapAround : true,
			pageDots: false,
			autoPlay: 2500,
			pauseAutoPlayOnHover: false,
			prevNextButtons: false
		}
	);
}
