<script>
	window.addEventListener('DOMContentLoaded', function(){
		var lightBoxes = new Array();
		var allAnchorElements = new Array();

		var galleryElements = document.querySelectorAll('.gallery');

		for(var i=0;i<galleryElements.length;i++){
			
			var anchorElements = document.querySelectorAll('#' + galleryElements[i].getAttribute('id') + ' a[href$="jpg"]');

			allAnchorElements = allAnchorElements.concat(anchorElements);

			lightBoxes.push(new LightBox(anchorElements));
		}

		var anchorElements = document.querySelectorAll('.article a[href$="jpg"]');

		for(var i=0;i<anchorElements.length;i++){
			console.log(allAnchorElements.indexOf(anchorElements[i]));
			if(!anchorElements[i].parentNode.classList.contains('gallery-icon')){
				lightBoxes.push(new LightBox(new Array(anchorElements[i])));
			}
		}
	});
</script>