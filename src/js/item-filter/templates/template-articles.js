import {html} from 'lit-html';

const template = (data) => html`
<div class="items-container content" data-is-loading="${data.state.loadingItems}">
	${(data => {
		if(data.displayedItems.length > 0) {
			return html`
			<ul class="article-items-container items-item-container">
				${data.displayedItems.map((item) => html`
				<li class="article-item items-item" data-is-loaded="${item.loaded}" data-is-visible="${item.visible}">
					<a href="${item.data.url}" data-item-id="${item.data.id}">
						${(item => {
							if(item.data.featuredImage) {
								return html`
								<div class="article-item-thumb">
									<img class="item-main-image" src="${item.data.featuredImage.src}" alt="" @load="${data}">
								</div>
								`
							}
						})(item)}
						<div class="article-item-body">
							<h2 class="article-item-title">${item.data.title}</h2>
							<div class="article-item-date">${item.data.date}</div>
							${(item => {
								if(!item.data.featuredImage) {
									return html`
									<div class="article-item-excerpt">
										<p>${item.data.excerpt}</p>
									</div>
									`
								}
							})(item)}
						</div>
					</a>
				</li>`)}
			</ul>`
		}
		else {
			return html`
				<div class="articles-container-no-content items-container-no-content">Geen items gevonden</div>
			`
		}
	})(data)}
	${(data => {
		if(data.displayedItems.length < data.getMaxDisplayedItemsCount()) {
			return html`
				<button class="articles-more-button items-more-button">Meer items</button>
			`
		}
	})(data)}
</div>
`
export default template