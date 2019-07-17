import {render} from 'lit-html'
import sanityClient from '@sanity/client'
import template from './templates/template-logo-carousel'
import CarouselSlider from './carousel'
import Flickity from 'flickity'
import 'flickity/dist/flickity.css'

function LogoCarousel(containerElement) {
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

	const sponsors = []
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
					logoSrc: sponsorLogos[i].url
				})
			})
			render(template(sponsors), this.containerElement)
			this.setupCarousel()
		})
	}, error => console.log(error))
}

LogoCarousel.prototype.setupCarousel = function() {
	this.flickity = new Flickity(
		this.containerElement.querySelector('.logo-carousel-items-container'),
		{
			wrapAround : true,
			pageDots: false,
			groupCells: '100%',
			autoPlay: true,
			pauseAutoPlayOnHover: false,
			prevNextButtons: false
		}
	);
}

export default LogoCarousel