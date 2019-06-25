import template from './templates/template-container'
import {render} from 'lit-html'

function ItemFilter(containerElement, ajaxUrls, templates) {
	this.containerElement = typeof containerElement == 'string' ? document.querySelector(containerElement) : containerElement.nodeType == 1 ? containerElement : null
	this.ajaxUrls = typeof ajaxUrls == 'object' ? ajaxUrls : []
	this.templates = typeof templates == 'object' ? templates : []
	this.items = []
	this.displayedItems = []
	this.services = []

	this.props = {
		itemsPerView: 6
	}

	this.state = {
		loadingItems: false,
		loadingServices: false,
		filterDropDownOpen: false,
		selectedService: false,
		maxItems: this.props.itemsPerView
	}

	if (!this.containerElement || !this.ajaxUrls || !this.templates) return

	// binds
	this.onItemsLoaded = this.onItemsLoaded.bind(this)
	this.render = this.render.bind(this)
	this.handleEvent = this.handleEvent.bind(this)
	this.getItems = this.getItems.bind(this)
	this.parseItemsRequest = this.parseItemsRequest.bind(this)
	this.parseServicesRequest = this.parseServicesRequest.bind(this)

	this.itemsXmlHttpRequest = new XMLHttpRequest()
	this.servicesXmlHttpRequest = new XMLHttpRequest()

	this.state.loadingItems = true
	this.state.loadingServices = true

	this.render()

	this.getItems(this.parseItemsRequest, this.render)
	if (this.ajaxUrls.services) {
		this.getServices(this.parseServicesRequest, this.render)
	}

	document.addEventListener('click', this.handleEvent)
}

ItemFilter.prototype.render = function() {
	render(template(this), this.containerElement)
}

ItemFilter.prototype.isSelectedService = function(service) {
	return this.state.selectedService && service.id == this.state.selectedService.id
}

ItemFilter.prototype.getSelectedService = function() {
	return this.state.selectedService
}

ItemFilter.prototype.setSelectedService = function(id) {
	const selectedServices = this.services.filter(service => service.id == id)

	if(selectedServices.length == 1) {
		this.state.selectedService = selectedServices[0]
	}
	else {
		this.state.selectedService = false
	}
}

ItemFilter.prototype.updateDisplayedItems = function() {
	this.displayedItems = this.items.filter((item, i) => {
		return i < this.state.maxItems
	})
}

ItemFilter.prototype.updateDisplayedItemsByService = function(serviceId) {
	this.displayedItems = this.items.filter(item => {
		return item.data.services.filter(service => service.id == serviceId).length > 0
	}).filter((item, i) => {
		return i < this.state.maxItems
	})
}

ItemFilter.prototype.getMaxDisplayedItemsCount = function() {
	if (this.getSelectedService()) {
		return this.items.filter(item => {
			return item.data.services.filter(service => {
				return service.id == this.getSelectedService().id

			}).length > 0
		}).length
	}
	else {
		return this.items.length
	}
}

ItemFilter.prototype.handleEvent = function(e) {
	if (e.type == 'click' && e.target.classList.contains('item-filter-input-dropdown-button')) {
		this.state.filterDropDownOpen = this.state.filterDropDownOpen ? false : true
		this.render()
		return
	}

	if (e.type == 'click' && e.target.hasAttribute('data-is-reset')) {
		this.state.maxItems = this.props.itemsPerView
		this.setSelectedService(false)
		this.updateDisplayedItems();
		this.setupFadeInItems()
	}

	if (e.type == 'load' && e.target.classList.contains('item-main-image')) {
		const item = this.getItemByImage(e.target)
		if(item) {
			item.loaded = true
		}
		this.render()
		return
	}

	if (e.type == 'click' && e.target.classList.contains('items-more-button')) {
		this.state.maxItems = this.state.maxItems + this.props.itemsPerView
		this.getSelectedService() ? this.updateDisplayedItemsByService(this.getSelectedService()): this.updateDisplayedItems()
	}

	this.state.filterDropDownOpen = false

	if (e.type == 'click' && e.target.hasAttribute('data-service-id')) {
		this.state.maxItems = this.props.itemsPerView
		this.setSelectedService(e.target.getAttribute('data-service-id'))
		this.updateDisplayedItemsByService(e.target.getAttribute('data-service-id'))
		this.setupFadeInItems()
	}

	this.render()
}

ItemFilter.prototype.setupFadeInItems = function() {
	this.items.forEach(item => item.visible = false)

	setTimeout(() => {
		this.items.forEach(item => item.visible = true)
		this.render()
	}, 1)
}

ItemFilter.prototype.getItemByImage = function(imgElement) {
	let node = imgElement
	while(node && !node.hasAttribute('data-item-id')) {
		node = node.parentNode
	}

	if(node.hasAttribute('data-item-id')) {
		return this.getItemById(node.getAttribute('data-item-id'))
	}

	return false
}

ItemFilter.prototype.getItemById = function(id) {
	const matchedItems = this.items.filter((item) => item.data.id == id)
	if (matchedItems.length == 1) {
		return matchedItems[0]
	}

	return false
}

ItemFilter.prototype.onItemsLoaded = function(items) {
	this.items = items.map(item => {
		return {
			data: item,
			loaded: item.featuredImage ? false : true,
			visible: true
		}
	})
	this.displayedItems = this.items
	this.updateDisplayedItems()

	this.render()
}

ItemFilter.prototype.onServicesLoaded = function(services) {
	this.services = services
	this.render()
}

ItemFilter.prototype.setFaqLoadedCallback = function(callback) {
	this.callbacks.onFaqViewed.push(callback)
}

ItemFilter.prototype.setFaqSearchCallback = function(callback) {
	this.callbacks.onFaqSearch.push(callback)
}

ItemFilter.prototype.getItems = function(responseCallback, callback) {
	this.itemsXmlHttpRequest.abort()

	this.itemsXmlHttpRequest.addEventListener('readystatechange', () => {
		if(this.itemsXmlHttpRequest.readyState != 4) return
		if(this.itemsXmlHttpRequest.status != 200 && this.itemsXmlHttpRequest.status != 304) {
			console.log('HTTP error ' + this.itemsXmlHttpRequest.status)
			return
		}

		responseCallback(JSON.parse(this.itemsXmlHttpRequest.responseText), callback)
	})

	this.itemsXmlHttpRequest.open('POST', this.ajaxUrls.items)
	this.itemsXmlHttpRequest.setRequestHeader('Content-Type', 'application/json')
	this.itemsXmlHttpRequest.send()
}

ItemFilter.prototype.parseItemsRequest = function(response) {
	const items = Array.isArray(response) ? response : []
	this.state.loadingItems = false
	this.onItemsLoaded(items)
}

ItemFilter.prototype.getServices = function(responseCallback, callback) {
	this.servicesXmlHttpRequest.abort()

	this.servicesXmlHttpRequest.addEventListener('readystatechange', () => {
		if(this.servicesXmlHttpRequest.readyState != 4) return
		if(this.servicesXmlHttpRequest.status != 200 && this.servicesXmlHttpRequest.status != 304) {
			console.log('HTTP error ' + this.servicesXmlHttpRequest.status)
			return
		}

		responseCallback(JSON.parse(this.servicesXmlHttpRequest.responseText), callback)
	})

	this.servicesXmlHttpRequest.open('POST', this.ajaxUrls.services)
	this.servicesXmlHttpRequest.setRequestHeader('Content-Type', 'application/json')
	this.servicesXmlHttpRequest.send()
}

ItemFilter.prototype.parseServicesRequest = function(response) {
	const services = Array.isArray(response) ? response : []
	this.state.loadingServices = false
	this.onServicesLoaded(services)
}

export default ItemFilter