(function($) {
	$.widget("ui.combobox", {
		_create: function() {
			
			var self = this;
			var select = this.element.hide();
			var input = $("<input>")
				.val(jQuery(this.element).find('option[selected]').text())
				.insertAfter(select)
				.autocomplete({
					source: function(request, response) {
						select[0].selectedIndex = -1;
						var matcher = new RegExp(request.term, "i");
						var els = (select.children("option").map(function() {
							var text = $(this).text();
							if (!request.term || matcher.test(text))
								return {
									id: $(this).val(),
									label: text.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + request.term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>"),
									value: text,
									selected:$(this).val()=="2"
								};
						}));
						if(self.options.source){
							//window.console.log(self.options.source);
							try{
								var more = self.options.source(request, response, els);
								if(more!=null&&typeof(more)=='object')
									for(name in more){
										els.push(more[name]);
									}
							}catch(e){
								
							}
						}

						response(els);
					},
					delay: 0,
					result:function(e, ui){
						window.console.log('result');
					},
					select: function(e, ui) {
						if (!ui.item) {
							// remove invalid value, as it didn't match anything
							select.val('');
							//$(this).val("");
							return false;
						}
						$(this).focus();
						select.val(ui.item.id);
						select.change();
						self._trigger("selected", null, {
							item: select.find("[value='" + ui.item.id + "']")
						});
					},
					minLength: 0
				})
				.blur(function(){
					if(select.val()==null){
						jQuery(this).val('');
					}
					else{
						jQuery(this).val(select.find('option[selected]').text());
					}
					select.change();
				})
				.addClass("ui-widget ui-widget-content ui-corner-left");
			$("<button>&nbsp;</button>")
			.insertAfter(input)
			.button({
				icons: {
					primary: "ui-icon-triangle-1-s"
				},
				text: false
			}).removeClass("ui-corner-all")
			.addClass("ui-corner-right ui-button-icon")
			.position({
				my: "left center",
				at: "right center",
				of: input,
				offset: "-1 0"
			}).css("top", "")
			.click(function() {
				// close if already visible
				if (input.autocomplete("widget").is(":visible")) {
					input.autocomplete("close");
					return false;
				}
				// pass empty string as value to search for, displaying all results
				input.autocomplete("search", "");
				input.focus();
				return false;
			});
		}
	});

})(jQuery);
