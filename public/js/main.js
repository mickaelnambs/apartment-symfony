$(document).ready(function() {

	$("body").on("click", ".collection-item-delete", e => {
		$(e.currentTarget).closest("div").remove();
	});
	
	$("body").on("click", ".collection-add", e => {
		let collection = $(`#${e.currentTarget.dataset.collection}`);
		let prototype = collection.data('prototype');
		let index = collection.data('index');
		collection.append(prototype.replace(/__name__/g, index));
		collection.data('index', index++);
	})

});
