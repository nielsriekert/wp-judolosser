import {html} from 'lit-html';

const template = (data) => html`
	<div class="logo-carousel-wrapper">
		<div class="logo-carousel-container content">
			<h2 class="logo-carousel-title">Band Sponsoren</h2>
			${(data => {
				if(data.length > 0) {
					return html`
					<div class="logo-carousel-items-wrapper">
						<div class="logo-carousel-items-container">
							${data.map(sponsor => (
								html`
								<div class="logo-carousel-item-container">
									${sponsor.websiteUrl ?
										html`<a href="${sponsor.websiteUrl}"><img src="${sponsor.logoSrc}"></a>`
											: html`<div><img src="${sponsor.logoSrc}"></div>`}
								</div>`
							))}
						</div>
					</div>`
				}
			})(data)}
		</div>
	</div>
`

export default template;