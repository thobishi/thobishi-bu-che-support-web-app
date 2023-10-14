$(function() {
	var $popupList = $('.popupList')
		.each(function() {
			var $this = $(this);
			var $parent = $this.siblings('.popupButton');

			$this
				.css({position: 'absolute'})
				.position({
					of: $parent,
					my: 'left top',
					at: 'left bottom'
				});
		})
		.hide();

	$('.popupButton').click(function(e){
		e.preventDefault();
	});

	$('.popupWrapper').hoverIntent({
		timeout:250,
		over:function() {
			$(this).find('.popupList')
				.show()
				.css({position: 'absolute'})
				.position({
					of: $(this),
					my: 'left top',
					at: 'left bottom'
				})
				.hide()
				.show('fast');
		},
		out:function() {
			$(this).find('.popupList')
				.hide('fast');
		}
	});
});