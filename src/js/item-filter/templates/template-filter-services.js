import {html} from 'lit-html';

const { __, _x, _n, _nx } = wp.i18n;

const template = (data) => html`
<div class="item-filter-container content">
	<label class="item-filter-input-label">${__('Filter by specialty', 'conseiller')}</label>
	<div class="item-filter-input-container" data-is-loading="${data.state.loadingServices}">
		<button class="item-filter-input-dropdown-button" data-is-open="${data.state.filterDropDownOpen}">
			${(data => {
				if(data.getSelectedService()) {
					return html`${data.getSelectedService().name}`
				}
				else {
					return html`${__('Select a service', 'conseiller')}`
				}
			})(data)}
		</button>
		${(data => {
			if(data.services.length > 0) {
				return html`
				<ul class="item-filter-dropdown-container" data-is-open="${data.state.filterDropDownOpen}">
					${data.services.map((service) => html`
						<li class="items-filter-dropdown-item" data-is-selected="${data.isSelectedService(service)}"><button data-service-id="${service.id}">${service.name}</button></li>
						`
					)}
				</ul>
				`
			}
		})(data)}
	</div>
	${(data => {
		if(data.getSelectedService()) {
			return html`
			<button class="item-filter-dropdown-reset-button button" data-is-reset="true">${__('Reset filter', 'conseiller')}</button>
			`
		}
	})(data)}
</div>
`
export default template