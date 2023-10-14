(function($) {
	var self = null
	$.widget('ui.nestedList', {
		options: {
			source: null,
			initialSource: null,
			autocompleteSource: null,
			processingText: 'Processing',
			heading: 'Items',
			select: function(event, item) {
			}
		},
		numberOptions: 0,
		_create: function() {
			self = this;
			var options = self.options;

			if(self.element.is(':input')) {
				self.parent = self.element.closest('div');

				self.element.detach().hide();
			}
			else {
				self.parent = self.element;
			}

			if(options.autocompleteSource != null) {
				var autocompleteOptions = {
					source: options.autocompleteSource,
					minLength: 2,
					select: self.options.select
				};

				if(typeof self.options.autocompleteSource == 'string') {
					autocompleteOptions.source = function(request, response) {
							var url = self.options.autocompleteSource;
							$.getJSON(url, request, function(data) {
								response(data[data.index]);
							});
						}
				}

				self.visibleElement = self.element
					.clone()
					.attr('id', self.element.attr('id') + 'Select')
					.attr('name', '')
					.show()
					.autocomplete(autocompleteOptions)
					.appendTo(self.parent);
			}

			self.divBlock = $('<div />')
				.addClass('ui-widget ui-widget-content ui-corner-all ui-helper-clearfix ui-select-container')
				.appendTo(self.parent);

			self._resetBreadcrumbs();
			self._loadList();

			self._bindLinks();
		},
		_resetBreadcrumbs: function() {
			self.breadcrumbs = [{value: null, label: self.options.heading}];
		},
		_selectItem: function(event, item) {
			self.options.select(event, {item: item});

			self._resetBreadcrumbs();
			self._loadList();
		},
		_loadList: function(loadItem) {
			if(typeof loadItem == 'undefined') {
				loadItem = {value: null, label: self.options.heading};
			}

			var blockTimeOut = setTimeout(function() {
				self.divBlock.block({
					message: '<h1>'+self.options.processingText + '</h1>',
					css: { border: '3px solid #a00' }
				});
			}, 350);

			$.post(self.options.source, {parent: loadItem.value}, function(data) {
				if(data.results.length == 0) {
					self._selectItem({}, loadItem);
					return;
				}

				self.divBlock.empty()

				var $list = $('<ul />')
					.addClass('ui-menu ui-select-list');

				$.each(data.results, function(index, value) {
					$('<li><a href="#" class="ui-corner-all">'+(self.breadcrumbs.length > 1 ? '> ' : '')+value+'</a></li>')
						.data('item', {value: index, label: value})
						.addClass('ui-menu-item ui-state-default ui-corner-all')
						.appendTo($list);
				});

				var lastItem = self.breadcrumbs.slice(-1)[0];

				self._generateBreadcrumb().appendTo(self.divBlock);

				self.header = $('<div><span class="ui-widget-header-text">'+lastItem.label+'</span></div>')
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

				self.breadcrumbs.push(item);
				self._loadList(item);
			})
			.delegate('a', 'hover', function(e) {
				$(this).closest('li').toggleClass('ui-state-hover');
			})
			.delegate('a.ui-breadcrumb', 'click', function(e) {
				e.preventDefault();

				var $this = $(this);
				var $list = $this.closest('li');
				var item = $list.data('item');

				self.breadcrumbs = self.breadcrumbs.slice(0, parseInt($this.attr('rel'))+1);
				self._loadList(item);
			});

			if(self.visibleElement) {
				self.visibleElement.click(function() {
					$(this).select();
				});
			}
		},
		_generateBreadcrumb: function() {
			if(self.breadcrumbs.length > 1) {
				var $crumbs = $('<div class="ui-widget ui-widget-header ui-corner-all ui-helper-clearfix ui-breadcrumbs "><ul /></div>')
				$.each(self.breadcrumbs.slice(0, self.breadcrumbs.length-1), function(index, value) {
					var $item = $('<li class="ui-breadcrumb-item"><a href="#" rel="'+index+'" class="ui-breadcrumb ui-corner-all">'+value.label+'</a></li>')
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