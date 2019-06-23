import {html} from 'lit-html';

const template = (data) => html`
	${(data => {
		if(data.templates.filterTemplate) {
			return html`${data.templates.filterTemplate(data)}`
		}
	})(data)}
	${data.templates.itemsTemplate(data)}
`

export default template