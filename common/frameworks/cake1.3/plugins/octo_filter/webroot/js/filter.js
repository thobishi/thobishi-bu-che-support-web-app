$(function() {
	var $filterBoxes = $('.filterBox')
		.each(function() {
			var $this = $(this);
			var $parent = $this.closest('th');

			$this
				.css({position: 'absolute', minWidth: '430px'})
				.position({
					of: $parent,
					my: 'left top',
					at: 'left bottom'
				})
		})
		.hide();
		
	$('.filterLink').click(function(e) {
		e.preventDefault();
		var $filterBox = $(this).siblings('.filterBox');
		
		if(!$filterBox.is(':visible')) {
			$filterBoxes.filter(':visible').hide('fast');

			$(this).siblings('.filterBox').show('fast', function() {
				$(this)
					.position({
						of: $(this).siblings('a:first'),
						my: 'left top',
						at: 'left bottom'
					});
			});
		}
	})
	
	$('.clearFilter').click(function(e) {
		$(this).closest('.filterBox').find('input').attr('checked', false);
	});
	
	$('#clearSearch').click(function(e) {
		$(this).siblings(':input').val('');
	});
	
	$('#clearFilters').click(function(e) {		
		$(':checkbox').attr('checked', false);
		$('input:not(:checkbox)').val('');
	});
	
	function _handler ( e ){
		var rEscape = /[\-\[\]{}()*+?.,\\^$|#\s]/g;
		var term = $.trim( $(this).val().toLowerCase() ),

		// speed up lookups
		rows = $(this).closest('.filterBox').find('.filterCheckbox');

		if( !term ){
			rows.show();
		} else {
			rows.hide();

			var regex = new RegExp(term.replace(rEscape, "\\$&"), 'gi');

			rows.each(function() {
				var $this = $(this);
				var value = $this.find('label').text();
				
				if(value.search(regex) !== -1) {
					$this.show();
				}
			});
		}
	}
	
	$('.filterInput').bind({
		keydown: function( e ){
			// prevent the enter key from submitting the form / closing the widget
			if( e.which === 13 ){
				e.preventDefault();
			}
		},
		keyup: _handler,
		click: _handler
	});
	
	$('body').click(function(e) {
		if(!$(e.target).hasClass('filterLink') && $(e.target).closest('.filterBox').length == 0 ) {
			$filterBoxes.hide('fast');
		}
	});
});