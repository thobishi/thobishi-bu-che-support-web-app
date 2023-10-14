(function($) {
	var self = null
	$.widget('ui.nestedSelect', {
		options: {
			source: null,
			initialSource: null,
			autocompleteSource: null,
			processingText: 'Processing',
			heading: 'Items',
			select: null
		},
		numberOptions: 0,
		_create: function() {
			self = this;
			var options = self.options;

			self.parent = self.element.closest('div');

			self.element.detach().hide();

			self.visibleElement = self.element
							.clone()
							.attr('id', self.element.attr('id') + 'Select')
							.attr('name', '')
							.show();

			self.selectedItems = $('ul.ui-selected-items', self.parent);

			if(self.selectedItems.length == 0) {
				self.selectedItems = $('<ul class="ui-selected-items" />')
										.appendTo(self.parent);
			}
			else {
				self.numberOptions = self.selectedItems.find('li').length;
			}

			var autocompleteOptions = {
				source: self.options.autocompleteSource,
				minLength: 2,
				focus: function( event, ui ) {
					self.visibleElement.val( ui.item.label );
					return false;
				},
				select: function(event, ui) {
							var item = {id: ui.item.value, name: ui.item.label};
							self._selectItem(item);

							return false;
						}
			};
			
			if(typeof self.options.autocompleteSource == 'string') {
				autocompleteOptions.source = function(request, response) {
						var url = self.options.autocompleteSource;
						$.getJSON(url, request, function(data) {
							response(data[data.index]);
						});
					}
			}

			
			self.visibleElement
				.autocomplete(autocompleteOptions)
				.appendTo(self.parent);

			self.divBlock = $('<div />')
				.addClass('ui-widget ui-widget-content ui-corner-all ui-helper-clearfix ui-select-container')
				.appendTo(self.parent);

			self._resetBreadcrumbs();
			self._loadList(options.initialSource);

			self._bindLinks();
		},
		_resetBreadcrumbs: function() {
			self.breadcrumbs = [{id: null, name: self.options.heading}];
		},
		_selectItem: function(item) {
			if(self.parent.find('input[value='+item.id+']').length == 0) {
				var $field = self.element
					.clone()
					.val(item.id)
					.attr({
						id: self.element.attr('id')+self.numberOptions++,
						name: self.element.attr('name')+'[]'
					});

				self.visibleElement.val('');

				$('<li />')
					.text(item.name)
					.append('<a href="#" class="ui-remove-item ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-close"></span></a>')
					.append($field)
					.hide()
					.appendTo(self.selectedItems)
					.fadeIn('fast');
					
				self._trigger('change');

				self._resetBreadcrumbs();
				self._loadList(self.options.initialSource);
			}
		},
		_loadList: function(url) {
			var blockTimeOut = setTimeout(function() {
				self.divBlock.block({
					message: '<h1>'+self.options.processingText + '</h1>',
					css: { border: '3px solid #a00' }
				});
			}, 250);
			
			$.getJSON(url, function(data) {
				self.divBlock.empty()
				
				var $list = $('<ul />')
					.addClass('ui-menu ui-select-list');

				$.each(data[data.index], function(index, value) {
					$('<li><a href="#" class="ui-corner-all">'+(self.breadcrumbs.length > 1 ? '> ' : '')+value[data.key].name+'</a></li>')
						.data('item', value[data.key])
						.addClass('ui-menu-item ui-state-default ui-corner-all')
						.appendTo($list);
				});

				var lastItem = self.breadcrumbs.slice(-1)[0];
				
				self._generateBreadcrumb().appendTo(self.divBlock);

				self.header = $('<div><span class="ui-widget-header-text">'+lastItem.name+'</span></div>')
					.addClass('ui-widget-header ui-corner-all')
					.appendTo(self.divBlock);

				self.listContainer = $('<div class="ui-widget ui-select" />')
					.appendTo(self.divBlock);

				$list.appendTo(self.listContainer);

				clearTimeout(blockTimeOut);
				self.divBlock.unblock();
			});
		},
		_bindLinks: function() {			
			self.divBlock.delegate('a:not(.ui-breadcrumb)', 'click', function(e) {
				e.preventDefault();
				
				var $list = $(this).closest('li');
				var item = $list.data('item');
				
				if(parseInt(item.rght) != parseInt(item.lft)+1) {
					self.breadcrumbs.push(item);
					self._loadList(self.options.source.replace('#id#', item.id));
				}
				else {
					self._selectItem(item);
				}
			})
			.delegate('a', 'hover', function(e) {
				$(this).toggleClass('ui-state-hover');
			})
			.delegate('a.ui-breadcrumb', 'click', function(e) {
				e.preventDefault();

				var $this = $(this);
				var $list = $this.closest('li');
				var item = $list.data('item');

				self.breadcrumbs = self.breadcrumbs.slice(0, parseInt($this.attr('rel'))+1);
				
				self._loadList(self.options.source.replace('#id#', item.id));
			});

			self.visibleElement.click(function() {
				$(this).select();
			});

			self.selectedItems
				.delegate('a.ui-remove-item', 'click', function(e) {
					e.preventDefault();
					var $parent = $(this).closest('li');

					$parent.fadeOut('fast', function(){$(this).remove()});
					self._trigger('change');
				});
		},
		_generateBreadcrumb: function() {
			if(self.breadcrumbs.length > 1) {
				var $crumbs = $('<div class="ui-widget ui-widget-header ui-corner-all ui-helper-clearfix ui-breadcrumbs "><ul /></div>')
				$.each(self.breadcrumbs.slice(0, self.breadcrumbs.length-1), function(index, value) {
					var $item = $('<li class="ui-breadcrumb-item"><a href="#" rel="'+index+'" class="ui-breadcrumb ui-corner-all">'+value.name+'</a></li>')
						.data('item', value);

					var $parent = $crumbs.find('ul:last');
					if($parent.children('li').length == 0) {
						$item
							.appendTo($parent);
					}
					else {
						$('<ul />')
							.append($item)
							.appendTo($parent.children('li'));
					}
				});
				
				return $crumbs;
			}
			else {
				return $('<span />');
			}
		}
	});
}(jQuery));