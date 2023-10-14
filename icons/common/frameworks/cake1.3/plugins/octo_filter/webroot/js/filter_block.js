(function($) {
	var self = null;
	
	$.widget('ui.filterBlock', {
		options: {
			addFilter: function() {},
			removeFilter: function(){},
			updateFilter: function(){},
			changeOption: function(){}
		},
		_filters: {
			form: null,
			active: null,
			available: null,
			select: null,
			options: null
		},
		_create: function() {
			self = this;
			
			self._configureParameters();
			self._configureUi();
			self._configureEvents();
			
			console.log(self);
		},
		_configureParameters: function() {
			self._filters.form = self.element.find('#filterForm');
			self._filters.active = self.element.find('#activeFilters');
			self._filters.available = self.element.find('#availableFilters').detach();
			self._filters.select = self.element.find('#filterSelect');
			self._filters.options = self.element.find('#filterOptions');
		},
		_configureUi: function() {
			self._filters.available
				.find('li')
					.each(function() {
						var $this = $(this);
						
						$('<option />', {value: '#' + this.id})
							.text($this.attr('title'))
							.appendTo(self._filters.select);
					})
				.end();
		},
		_configureEvents: function() {
		}
	});
})(jQuery);



/*$(function() {
	var filters = {
		filters: {
			form: null,
			active: null,
			available: null,
			select: null,
			filterOptions: null
		},
		configureParameters: function() {
			this.filters.available = $('#availableFilters').detach();
			this.filters.active = $('#activeFilters');
			this.filters.select = $('#filterSelect');
			this.filters.form = $('#filterForm');
			this.filters.actions = $('#filterActions');
			this.filters.yAxis = $('#yAxis');
			this.filters.series = $('#series');	
		},
		init: function() {
		
		}
	};
	
	filters.init();
});*/

$.fn.addFilter = function(report, presetValues) {
	var $this = $(this);
	
	report.filters.select.find('option[value="#'+$this.attr('id')+'"]').attr('disabled', true);
	
	var $newFilter = $this
			.clone()
			.prepend('<a href="#" class="removeFilter ui-state-default ui-corner-all" title="Remove filter"><span class="ui-icon ui-icon-circle-close"></span></a>')
			.appendTo(report.filters.active)
			.addClass('filter')
			.find('select[multiple=multiple]')
				.multiselect({
					selectedList: 2,
					minWidth: 400,
					maxWidth: 500,
					noneSelectedText: 'No options selected',
					click: function() {
						report.delayedLoad();
					},
					checkAll: function() {
						report.delayedLoad();
					},
					uncheckAll: function() {
						report.delayedLoad();
					},
					optgrouptoggle: function() {
						report.delayedLoad();
					}
				})
				.multiselectfilter()
			.end()
			.find('select')
				.change(function() {
					report.delayedLoad();
				})
			.end();
	
	if($this.attr('id') == 'date') {
		var $dateStart = {
			div: $newFilter.find('#DateRange0').hide(),
			year: $newFilter.find('#date_range0Year'),
			month: $newFilter.find('#date_range0Month'),
			day: $newFilter.find('#date_range0Day')
		};
		var $dateEnd = {
			div: $newFilter.find('#DateRange1').hide(),
			year: $newFilter.find('#date_range1Year'),
			month: $newFilter.find('#date_range1Month'),
			day: $newFilter.find('#date_range1Day')
		};	
		
		if(typeof presetValues !== 'undefined') {
			if($.isPlainObject(presetValues[0])){
				$dateStart.year.val(presetValues[0].year);
				$dateStart.month.val(presetValues[0].month);
				$dateStart.day.val(presetValues[0].day);
			}

			if($.isPlainObject(presetValues[1])){
				$dateEnd.year.val(presetValues[1].year);
				$dateEnd.month.val(presetValues[1].month);
				$dateEnd.day.val(presetValues[1].day);
			}	
		}
		
		var dateStart = $dateStart.month.val() + '-' + $dateStart.day.val() + '-' + $dateStart.year.val();
		var dateEnd = $dateEnd.month.val() + '-' + $dateEnd.day.val() + '-' + $dateEnd.year.val();
		var value = dateStart + ' to ' + dateEnd;

		$('<input>')
			.val(value)
			.appendTo($newFilter)
			.daterangepicker({
				onChange: function(startDate, endDate) {
					if(startDate == null) {
						$dateStart.div.find(':input').val('');
					}
					else {
						$dateStart.year.val(startDate.format('yyyy'));
						$dateStart.month.val(startDate.format('mm'));
						$dateStart.day.val(startDate.format('dd'));
					}
					if(endDate == null) {
						$dateEnd.div.find(':input').val('');
					}
					else {
						$dateEnd.year.val(endDate.format('yyyy'));
						$dateEnd.month.val(endDate.format('mm'));
						$dateEnd.day.val(endDate.format('dd'));
					}
					
					report.delayedLoad();
				},
				presetRanges: [
					{
						text: 'Today',
						dateStart: 'today',
						dateEnd: 'today'
					},
					{
						text: 'Last 7 days',
						dateStart: 'today-7days',
						dateEnd: 'today'
					},
					{
						text: 'Month to date',
						dateStart: function(){ return Date.parse('today').moveToFirstDayOfMonth();  },
						dateEnd: 'today'
					},
					{
						text: 'Previous month',
						dateStart: function(){ return Date.parse('1 month ago').moveToFirstDayOfMonth();  }, 
						dateEnd: function(){ return Date.parse('1 month ago').moveToLastDayOfMonth();  }
					},
					{
						text: 'Until end of previous month',
						dateStart: false,
						dateEnd: function(){ return Date.parse('1 month ago').moveToLastDayOfMonth();  }
					},
					{
						text: 'Until today',
						dateStart: false,
						dateEnd: function(){ return Date.parse('today');  }
					}					
				],
				rangeSplitter: 'to',
				latestDate: new Date(),
				dateFormat: 'd MM yy',
				arrows: true
			})
			.closest('div');
			
			report.updateData();
	}
	
	return $newFilter.hide().fadeIn('fast');
}