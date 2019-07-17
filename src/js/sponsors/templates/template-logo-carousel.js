import {html} from 'lit-html';

//const { __, _x, _n, _nx } = wp.i18n;

const template = (data) => html`
	<div class="logo-carousel-wrapper">
		<div class="logo-carousel-container content">
			<h2 class="logo-carousel-title">Band Sponsoren</h2>
			${(data => {
				if(data.length > 0) {
					return html`
					<div class="logo-carousel-items-wrapper">
						<div class="logo-carousel-items-container">
						${data.map(sponsor => {
							return html`
							<div class="logo-carousel-item-container"><a href="${sponsor.websiteUrl}" target="_blank"><img src="${sponsor.logoSrc}"></a></div>
							`
						})}
						</div>
					</div>`
				}
			})(data)}
		</div>
	</div>
`

export default template