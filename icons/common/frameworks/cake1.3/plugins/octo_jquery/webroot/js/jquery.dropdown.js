(function($){$.expr[":"].match=function(a,i,m){var r=/^\/((?:\\\/|[^\/])+)\/([mig]{0,3})$/,e=r.exec(m[3]);return RegExp(e[1],e[2]).test($.trim(a.innerHTML))}})(jQuery);

(function($) {
	$.widget('octoplus.dropdown', {
		options: {
			imagePath: null
		},
		_arrow: '<span class="ui-icon ui-icon-triangle-1-s"></span>',
		_create: function() {
			var self = this;
			
			self.element.hide();
			
			self._createDropDown();
			self._setupEvents();
		},
		_setupEvents: function() {
			var self = this;

			self.selector.click(function(e) {
				e.preventDefault();
				self._currentValue = $(this).html();
				
				$(".ui-dropdown dd ul").hide();
				$(document).unbind('keydown.dropdown');
				$(this).toggleClass('active');
				self.list.toggle(0, function() {
					self._scrollToSelected();
				});

				$(document).bind('keydown.dropdown', function(event) {
					var keyCode = event.keyCode;

					if(keyCode==39 || keyCode==40) {
						//move to next
						event.preventDefault(); event.stopPropagation();
						self._select('next');
						self._setValue();
					}
					else if(keyCode==37 || keyCode==38) {
						event.preventDefault(); event.stopPropagation();
						//move to previous
						self._select('prev');
						self._setValue();
					}
					else if(keyCode == 13) {
						event.preventDefault(); event.stopPropagation();

						self._setAndClose();

					}
					else if(keyCode == 27) {
						event.preventDefault(); event.stopPropagation();

						self._cancel();
					}
					else {
						var key = String.fromCharCode(keyCode);

						self.list.find("a").removeClass('selected');
						var selected = self.list.find('span.text:match(/^'+key+'/i):first').parent('a').addClass('selected');
						self._scrollToSelected(selected);
						self._setValue();
					}
				});
			});
			
			self.list.find("a").click(function(e) {
				e.preventDefault();

				self.list.find("a").removeClass('selected');
				$(this).addClass('selected')

				self._setAndClose();
			}).hover(function() {self.list.find("a").removeClass('selected'); $(this).toggleClass('selected')});

			$(document).bind('click', function(e) {
				var $clicked = $(e.target);
				if (! $clicked.parents().hasClass("ui-dropdown")) {
					$(".ui-dropdown dd ul").hide();
					self._cancel();
				}
			});
		},
		_scrollToSelected: function(selected) {
			if(typeof selected == 'undefined') {
				selected = this.list.find('.selected');
			}

			if(selected.length == 0) {
				var value = this.selector.find('.value').text();
				if (value == '') {
					var selector = 'span.value:empty';
				}
				else {
					var selector = 'span.value:match("/^'+this.selector.find('.value').text()+'$/")';
				}
				selected = this.list.find(selector).parent('a').addClass('selected');
			}

			this.list.scrollTo(selected, 10, {offset: {top: -50, left: 0}});
		},
		_cancel: function() {
			if(this._currentValue !== null) {
				this.selector.html(this._currentValue);
				this.selector.removeClass('active');
				this.element.val(this.selector.find("span.value").text());
			}

			this._close();
		},
		_setAndClose: function() {
			this._setValue();

			this.element.change();

			this._close();
		},
		_close: function() {
			this.list.hide();

			this._currentValue = null;

			this.list.find("a").removeClass('selected');

			$(document).unbind('keydown.dropdown');
		},
		_setValue: function() {
			var selected = this.list.find('.selected');

			this.selector.html(selected.html()+this._arrow);
			this.selector.removeClass('active');
			this.element.val(selected.find("span.value").text());
		},
		_select: function(direction) {
			var selected = this.list
							.find('.selected');

			if(selected.length == 0) {
				selected = this.list.find('a:'+(direction=='next'?'first':'last')).addClass('selected');
			}
			else {
				var closest = selected.removeClass('selected')
						.closest('li')[direction]();

				if(closest.length == 0) {
					selected = this.list.find('a:'+(direction=='next'?'first':'last')).addClass('selected');
				}
				else {
					selected = closest.find('a')
							.addClass('selected');
				}
			}

			this._scrollToSelected(selected);
		},
		_createDropDown: function() {
			var self = this;

			var source = self.element;
			var selected = source.find("option:selected");  // get selected <option>
			if(selected.length == 0) {
				selected = source.find('option:first');
			}
			
			var options = $("option", source);  // get all <option> elements
			// create <dl> and <dt> with selected value inside it
			self.target = $('<dl class="ui-widget ui-reset ui-clearfix ui-dropdown"></dl>');
			self.selector = $('<a href="#"><span class="text">' + selected.text() +
				'</span><span class="value">' + selected.val() +
				'</span>'+self._arrow+'</a>')
			if(selected.val() !== '') {
				self.selector.prepend('<img src="' + self.options.imagePath + '/' + selected.val().toLowerCase() + '.png" alt="" />');
			}
			self.list = $('<ul />');

			$('<dt />').append(self.selector).appendTo(self.target);
			$('<dd />').append(self.list).appendTo(self.target);

		// iterate through all the <option> elements and create UL
			options.each(function(){
				var $this = $(this);
				var listElement = $('<li><a href="#"><span class="text">'+$this.text()+'</span><span class="value">'+$this.val()+'</span></a></li>');

				if(self.options.imagePath !== null && $this.val() !== '') {
					listElement
						.find('a')
						.prepend('<img src="' + self.options.imagePath + '/' + $this.val().toLowerCase() + '.png" alt="" />');
				}

				self.list.append(listElement);
			});

			self.target.insertAfter(self.element);

			self.target.find('dt a').width(self.list.width());
			self.list.hide();
		},
		resetWidth: function() {
			this.list.show();
			this.target.find('dt a').width(this.list.width());
			this.list.hide();
		}
	});
}(jQuery));
