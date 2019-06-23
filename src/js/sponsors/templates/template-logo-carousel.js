import {html} from 'lit-html';

//const { __, _x, _n, _nx } = wp.i18n;

const template = (data) => html`
	<div class="logo-carousel-wrapper">
		<div class="logo-carousel-container content">
			<h3 class="logo-carousel-title">Zilveren Band Sponsors</h3>
			${(data => {
				if(data.length > 0) {
					return html`
					<div class="logo-carousel-items-wrapper">
						<ul class="logo-carousel-items-container">
						${data.map(sponsor => {
							return html`
							<li class="logo-carousel-item-container"><a href="${sponsor.websiteUrl}" target="_blank"><img src="${sponsor.logoSrc}"></a><li></li>
							`
						})}
						</ul>
					</div>`
				}
			})(data)}
		</div>
	</div>
`

export default template