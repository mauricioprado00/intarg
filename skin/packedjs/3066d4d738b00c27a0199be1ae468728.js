;

;;

;/*
 * jQuery UI Accordion 1.6
 * 
 * Copyright (c) 2007 Jörn Zaefferer
 *
 * http://docs.jquery.com/UI/Accordion
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.accordion.js 4876 2008-03-08 11:49:04Z joern.zaefferer $
 *
 */

;(function($) {
	
// If the UI scope is not available, add it
$.ui = $.ui || {};

$.fn.extend({
	accordion: function(options, data) {
		var args = Array.prototype.slice.call(arguments, 1);

		return this.each(function() {
			if (typeof options == "string") {
				var accordion = $.data(this, "ui-accordion");
				accordion[options].apply(accordion, args);
			// INIT with optional options
			} else if (!$(this).is(".ui-accordion"))
				$.data(this, "ui-accordion", new $.ui.accordion(this, options));
		});
	},
	// deprecated, use accordion("activate", index) instead
	activate: function(index) {
		return this.accordion("activate", index);
	}
});

$.ui.accordion = function(container, options) {
	
	// setup configuration
	this.options = options = $.extend({}, $.ui.accordion.defaults, options);
	this.element = container;
	
	$(container).addClass("ui-accordion");
	
	if ( options.navigation ) {
		var current = $(container).find("a").filter(options.navigationFilter);
		if ( current.length ) {
			if ( current.filter(options.header).length ) {
				options.active = current;
			} else {
				options.active = current.parent().parent().prev();
				current.addClass("current");
			}
		}
	}
	
	// calculate active if not specified, using the first header
	options.headers = $(container).find(options.header);
	options.active = findActive(options.headers, options.active);

	if ( options.fillSpace ) {
		var maxHeight = $(container).parent().height();
		options.headers.each(function() {
			maxHeight -= $(this).outerHeight();
		});
		var maxPadding = 0;
		options.headers.next().each(function() {
			maxPadding = Math.max(maxPadding, $(this).innerHeight() - $(this).height());
		}).height(maxHeight - maxPadding);
	} else if ( options.autoheight ) {
		var maxHeight = 0;
		options.headers.next().each(function() {
			maxHeight = Math.max(maxHeight, $(this).outerHeight());
		}).height(maxHeight);
	}

	options.headers
		.not(options.active || "")
		.next()
		.hide();
	options.active.parent().andSelf().addClass(options.selectedClass);
	
	if (options.event)
		$(container).bind((options.event) + ".ui-accordion", clickHandler);
};

$.ui.accordion.prototype = {
	activate: function(index) {
		// call clickHandler with custom event
		clickHandler.call(this.element, {
			target: findActive( this.options.headers, index )[0]
		});
	},
	
	enable: function() {
		this.options.disabled = false;
	},
	disable: function() {
		this.options.disabled = true;
	},
	destroy: function() {
		this.options.headers.next().css("display", "");
		if ( this.options.fillSpace || this.options.autoheight ) {
			this.options.headers.next().css("height", "");
		}
		$.removeData(this.element, "ui-accordion");
		$(this.element).removeClass("ui-accordion").unbind(".ui-accordion");
	}
}

function scopeCallback(callback, scope) {
	return function() {
		return callback.apply(scope, arguments);
	};
}

function completed(cancel) {
	// if removed while animated data can be empty
	if (!$.data(this, "ui-accordion"))
		return;
	var instance = $.data(this, "ui-accordion");
	var options = instance.options;
	options.running = cancel ? 0 : --options.running;
	if ( options.running )
		return;
	if ( options.clearStyle ) {
		options.toShow.add(options.toHide).css({
			height: "",
			overflow: ""
		});
	}
	$(this).triggerHandler("change.ui-accordion", [options.data], options.change);
}

function toggle(toShow, toHide, data, clickedActive, down) {
	var options = $.data(this, "ui-accordion").options;
	options.toShow = toShow;
	options.toHide = toHide;
	options.data = data;
	var complete = scopeCallback(completed, this);
	
	// count elements to animate
	options.running = toHide.size() == 0 ? toShow.size() : toHide.size();
	
	if ( options.animated ) {
		if ( !options.alwaysOpen && clickedActive ) {
			$.ui.accordion.animations[options.animated]({
				toShow: jQuery([]),
				toHide: toHide,
				complete: complete,
				down: down,
				autoheight: options.autoheight
			});
		} else {
			$.ui.accordion.animations[options.animated]({
				toShow: toShow,
				toHide: toHide,
				complete: complete,
				down: down,
				autoheight: options.autoheight
			});
		}
	} else {
		if ( !options.alwaysOpen && clickedActive ) {
			toShow.toggle();
		} else {
			toHide.hide();
			toShow.show();
		}
		complete(true);
	}
}

function clickHandler(event) {
	var options = $.data(this, "ui-accordion").options;
	if (options.disabled)
		return false;
	// called only when using activate(false) to close all parts programmatically
	if ( !event.target && !options.alwaysOpen ) {
		options.active.parent().andSelf().toggleClass(options.selectedClass);
		var toHide = options.active.next(),
			data = {
				instance: this,
				options: options,
				newHeader: jQuery([]),
				oldHeader: options.active,
				newContent: jQuery([]),
				oldContent: toHide
			},
			toShow = options.active = $([]);
		toggle.call(this, toShow, toHide, data );
		return false;
	}
	// get the click target
	var clicked = $(event.target);
	
	// due to the event delegation model, we have to check if one
	// of the parent elements is our actual header, and find that
	if ( clicked.parents(options.header).length )
		while ( !clicked.is(options.header) )
			clicked = clicked.parent();
	
	var clickedActive = clicked[0] == options.active[0];
	// if animations are still active, or the active header is the target, ignore click
	if (options.running || (options.alwaysOpen && clickedActive))
		return false;
	if (!clicked.is(options.header))
		return;

	// switch classes
	options.active.parent().andSelf().toggleClass(options.selectedClass);
	if ( !clickedActive ) {
		clicked.parent().andSelf().addClass(options.selectedClass);
	}

	// find elements to show and hide
	var toShow = clicked.next(),
		toHide = options.active.next(),
		//data = [clicked, options.active, toShow, toHide],
		data = {
			instance: this,
			options: options,
			newHeader: clicked,
			oldHeader: options.active,
			newContent: toShow,
			oldContent: toHide
		},
		down = options.headers.index( options.active[0] ) > options.headers.index( clicked[0] );
	options.active = clickedActive ? $([]) : clicked;
	toggle.call(this, toShow, toHide, data, clickedActive, down );


	return false;
};

function findActive(headers, selector) {
	return selector != undefined
		? typeof selector == "number"
			? headers.filter(":eq(" + selector + ")")
			: headers.not(headers.not(selector))
		: selector === false
			? $([])
			: headers.filter(":eq(0)");
}

$.extend($.ui.accordion, {
	defaults: {
		selectedClass: "selected",
		alwaysOpen: true,
		animated: 'slide',
		event: "click",
		header: "a",
		autoheight: true,
		running: 0,
		navigationFilter: function() {
			return this.href.toLowerCase() == location.href.toLowerCase();
		}
	},
	animations: {
		try_anim: function(anim, options, additions){
			try{
				anim(options, additions);
			}
			catch(e){
				this.crapy(options);
			}
		},
		slide: function(options, additions){
			this.try_anim(this.do_slide, options, additions);
		},
		do_slide: function(options, additions) {
			options = $.extend({
				easing: "swing",
				duration: 300
			}, options, additions);
			if ( !options.toHide.size() ) {
				options.toShow.animate({height: "show"}, options);
				return;
			}
			var hideHeight = options.toHide.height(),
				showHeight = options.toShow.height(),
				difference = showHeight / hideHeight;
			options.toShow.css({ height: 0, overflow: 'hidden' }).show();
			options.toHide.filter(":hidden").each(options.complete).end().filter(":visible").animate({height:"hide"},{
				step: function(now) {
					var current = (hideHeight - now) * difference;
					if ($.browser.msie || $.browser.opera) {
						current = Math.ceil(current);
					}
					options.toShow.height( current );
				},
				duration: options.duration,
				easing: options.easing,
				complete: function() {
					if ( !options.autoheight ) {
						options.toShow.css("height", "auto");
					}
					options.complete();
				}
			});
		},
		bounceslide: function(options){
			this.try_anim(this.do_bounceslide, options);
		},
		do_bounceslide: function(options) {
			this.slide(options, {
				easing: options.down ? "bounceout" : "swing",
				duration: options.down ? 1000 : 200
			});
		},
		crapy: function(options){
			options.toHide.hide().css('height','');
			options.toShow.show().css('height','');
			options.complete();
		},
		easeslide: function(options){
			this.try_anim(this.do_easeslide, options);
		},
		do_easeslide: function(options) {
			this.slide(options, {
				easing: "easeinout",
				duration: 700
			})
		}
	}
});

})(jQuery);
;

;;

;/**
 * HoverScroll jQuery Plugin
 *
 * Make an unordered list scrollable by hovering the mouse over it
 *
 * @author RasCarlito <carl.ogren@gmail.com>
 * @version 0.2.2
 * @revision 14
 *
 * 
 *
 * FREE BEER LICENSE VERSION 1.02
 *
 * The free beer license is a license to give free software to you and free
 * beer (in)to the author(s).
 * 
 *
 * Released: 27-06-2009 6:00pm
 *
 * Changelog
 * ----------------------------------------------------
 * 0.2.2	Bug fixes
 *      	- Backward compatibility with jQuery 1.1.x
 *      	- Added test file to the archive
 *      	- Bug fix: The right arrow appeared when it wasn't necessary (thanks to <admin at unix dot am)
 * 
 * 0.2.1	Bug fixes
 *      	- Backward compatibility with jQuery 1.2.x (thanks to Andy Mull for compatibility with jQuery >= 1.2.4)
 *      	- Added information to the debug log
 * 
 * 0.2.0	Added some new features
 *      	- Direction indicator arrows
 *      	- Permanent override of default parameters
 * 
 * 0.1.1	Minor bug fix
 *      	- Hover zones did not cover the complete container
 *
 *      	note: The css file has not changed therefore it is still versioned 0.1.0
 *
 * 0.1.0	First release of the plugin. Supports:
 *      	- Horizontal and vertical lists
 *      	- Width and height control
 *      	- Debug log (doesn't show useful information for the moment)
 */
 
(function($) {

/**
 * @method hoverscroll
 * @param	params {Object}  Parameter list
 * 	params = {
 * 		vertical {Boolean},	// Vertical list or not ?
 * 		width {Integer},	// Width of list container
 * 		height {Integer},	// Height of list container
 *  	arrows {Boolean},	// Show direction indicators or not
 *  	arrowsOpacity {Float},	// Arrows maximum opacity
 * 		debug {Boolean}		// Debug output in firebug console
 * 	};
 */
$.fn.hoverscroll = function(params) {
	if (!params) { params = {}; }
	
	// Extend default parameters
	// note: empty object to prevent params object from overriding default params object
	params = $.extend({}, $.fn.hoverscroll.params, params);
	
	// Loop through all the elements
	this.each(function() {
		var $this = $(this);
		
		if (params.debug) {
			$.log('[HoverScroll] Trying to create hoverscroll on element ' + this.tagName + '#' + this.id);
		}
		
		// wrap ul list with a div.listcontainer
		$this.wrap('<div class="listcontainer"></div>');
		
		$this.addClass('list');
		//.addClass('ui-helper-clearfix');
		
		// store handle to listcontainer
		var listctnr = $this.parent();
		
		// wrap listcontainer with a div.hoverscroll
		listctnr.wrap('<div class="ui-widget-content hoverscroll"></div>');
		//listctnr.wrap('<div class="hoverscroll"></div>');
		
		// store hoverscroll container
		var ctnr = listctnr.parent();
		
		// Add arrow containers
		if (params.arrows) {
			if (!params.vertical) {
				listctnr.append('<div class="arrow left"></div>').append('<div class="arrow right"></div>')
				//.append('<div class="hoverZoneLeft"></div>').append('<div class="hoverZoneRight"></div>');
			}
			else {
				listctnr.append('<div class="arrow top"></div>').append('<div class="arrow bottom"></div>')
				//.append('<div class="hoverZoneTop"></div>').append('<div class="hoverZoneBottom"></div>');
			}
		}
		
		// Apply parameters width and height
		ctnr.width(params.width).height(params.height);
		listctnr.width(params.width).height(params.height);
		
		var size = 0;
		
		if (!params.vertical) {
			ctnr.addClass('horizontal');
			
			// Determine content width
			$this.children().each(function() {
				$(this).addClass('item');
				
				if ($(this).outerWidth) {
					size += $(this).outerWidth(true);
				}
				else {
					// jQuery < 1.2.x backward compatibility patch
					size += $(this).width() + parseInt($(this).css('padding-left')) + parseInt($(this).css('padding-right'))
						+ parseInt($(this).css('margin-left')) + parseInt($(this).css('margin-right'));
				}
			});
			// Apply computed width to listcontainer
			$this.width(size);
			
			if (params.debug) {
				$.log('[HoverScroll] Computed content width : ' + size + 'px');
			}
			
			// Retrieve container width instead of using the given params.width to include padding
			if (ctnr.outerWidth) {
				size = ctnr.outerWidth();
			}
			else {
				// jQuery < 1.2.x backward compatibility patch
				size = ctnr.width() + parseInt(ctnr.css('padding-left')) + parseInt(ctnr.css('padding-right'))
					+ parseInt(ctnr.css('margin-left')) + parseInt(ctnr.css('margin-right'));
			}
			
			if (params.debug) {
				$.log('[HoverScroll] Computed container width : ' + size + 'px');
			}
		}
		else {
			ctnr.addClass('vertical');
			
			// Determine content height
			$this.children().each(function() {
				$(this).addClass('item')
				
				if ($(this).outerHeight) {
					size += $(this).outerHeight(true);
				}
				else {
					// jQuery < 1.2.x backward compatibility patch
					size += $(this).height() + parseInt($(this).css('padding-top')) + parseInt($(this).css('padding-bottom'))
						+ parseInt($(this).css('margin-bottom')) + parseInt($(this).css('margin-bottom'));
				}
			});
			// Apply computed height to listcontainer
			$this.height(size);
			
			if (params.debug) {
				$.log('[HoverScroll] Computed content height : ' + size + 'px');
			}
			
			// Retrieve container height instead of using the given params.height to include padding
			if (ctnr.outerHeight) {
				size = ctnr.outerHeight();
			}
			else {
				// jQuery < 1.2.x backward compatibility patch
				size = ctnr.height() + parseInt(ctnr.css('padding-top')) + parseInt(ctnr.css('padding-bottom'))
					+ parseInt(ctnr.css('margin-top')) + parseInt(ctnr.css('margin-bottom'));
			}
			
			if (params.debug) {
				$.log('[HoverScroll] Computed container height : ' + size + 'px');
			}
		}
		
		// Define hover zones on container
		var zone = {
			1: { action: 'move', from: 0, to: 0.06 * size, direction: -1 , speed: 16 },
			2: { action: 'move', from: 0.06 * size, to: 0.15 * size, direction: -1 , speed: 8 },
			3: { action: 'move', from: 0.15 * size, to: 0.25 * size, direction: -1 , speed: 4 },
			4: { action: 'move', from: 0.25 * size, to: 0.4 * size, direction: -1 , speed: 2 },
			5: { action: 'stop', from: 0.4 * size, to: 0.6 * size },
			6: { action: 'move', from: 0.6 * size, to: 0.75 * size, direction: 1 , speed: 2 },
			7: { action: 'move', from: 0.75 * size, to: 0.85 * size, direction: 1 , speed: 4 },
			8: { action: 'move', from: 0.85 * size, to: 0.94 * size, direction: 1 , speed: 8 },
			9: { action: 'move', from: 0.94 * size, to: size, direction: 1 , speed: 16 }
		}
		
		// Store default state values in container
		ctnr[0].isChanging = false;
		ctnr[0].direction  = 0;
		ctnr[0].speed      = 1;
		
		
		/**
		 * Check mouse position relative to hoverscroll container
		 * and trigger actions according to the zone table
		 *
		 * @param x {Integer} Mouse X event position
		 * @param y {Integer} Mouse Y event position
		 */
		function checkMouse(x, y) {
			x = x - ctnr.offset().left;
			y = y - ctnr.offset().top;
			
			var pos;
			if (!params.vertical) { pos = x; }
			else { pos = y; }
			
			for (i in zone) {
				if (pos >= zone[i].from && pos < zone[i].to) {
					if (zone[i].action == 'move') { startMoving(zone[i].direction, zone[i].speed); }
					else { stopMoving(); }
				}
			}
		}
		
		
		/**
		 * Sets the opacity of the left|top and right|bottom
		 * arrows according to the scroll position.
		 */
		function setArrowOpacity() {
			if (!params.arrows) { return; }
			
			var maxScroll;
			var scroll;
			
			if (!params.vertical) {
				maxScroll = listctnr[0].scrollWidth - listctnr.width();
				scroll = listctnr[0].scrollLeft;
			}
			else {
				maxScroll = listctnr[0].scrollHeight - listctnr.height();
				scroll = listctnr[0].scrollTop;
			}
			
			var opacity = (scroll / maxScroll);
			var limit = params.arrowsOpacity;
			
			if (isNaN(opacity)) { opacity = 0; }
			
			// Check if the arrows are needed
			// Thanks to <admin at unix dot am> for fixing the bug that displayed the right arrow when it was not needed
			var done = false;
			if (opacity <= 0) { $('div.arrow.left, div.arrow.top', ctnr).hide(); done = true; }
			if (opacity >= limit || maxScroll <= 0) { $('div.arrow.right, div.arrow.bottom', ctnr).hide(); done = true; }
			
			if (!done) {
				$('div.arrow.left, div.arrow.top', ctnr).show().css('opacity', (opacity > limit ? limit : opacity));
				$('div.arrow.right, div.arrow.bottom', ctnr).show().css('opacity', (1 - opacity > limit ? limit : 1 - opacity));
			}
		}
		
		
		/**
		 * Start scrolling the list with a given speed and direction
		 *
		 * @param direction {Integer}	Direction of the displacement, either -1|1
		 * @param speed {Integer}		Speed of the displacement (20 being very fast)
		 */
		function startMoving(direction, speed) {
			if (ctnr[0].direction != direction) {
				if (params.debug) {
					$.log('[HoverScroll] Starting to move. direction: ' + direction + ', speed: ' + speed);
				}
				
				stopMoving();
				ctnr[0].direction  = direction;
				ctnr[0].isChanging = true;
				move();
			}
			if (ctnr[0].speed != speed) {
				if (params.debug) {
					$.log('[HoverScroll] Changed speed: ' + speed);
				}
				
				ctnr[0].speed = speed;
			}
		}
		
		/**
		 * Stop scrolling the list
		 */
		function stopMoving() {
			if (ctnr[0].isChanging) {
				if (params.debug) {
					$.log('[HoverScroll] Stoped moving');
				}
				
				ctnr[0].isChanging = false;
				ctnr[0].direction  = 0;
				ctnr[0].speed      = 1;
				clearTimeout(ctnr[0].timer);
			}
		}
		
		/**
		 * Move the list one step in the given direction and speed
		 */
		function move() {
			if (ctnr[0].isChanging == false) { return; }
			
			setArrowOpacity();
			
			var scrollSide;
			if (!params.vertical) { scrollSide = 'scrollLeft'; }
			else { scrollSide = 'scrollTop'; }
			
			listctnr[0][scrollSide] += ctnr[0].direction * ctnr[0].speed;
			ctnr[0].timer = setTimeout(function() { move(); }, 50);
		}
		
		// Bind actions to the hoverscroll container
		ctnr
		// Bind checkMouse to the mousemove
		.mousemove(function(e) { checkMouse(e.pageX, e.pageY); })
		// Bind stopMoving to the mouseleave
		// jQuery 1.2.x backward compatibility, thanks to Andy Mull!
		// replaced .mouseleave(...) with .bind('mouseleave', ...)
		.bind('mouseleave', function() { stopMoving(); });
		/*<mauricio>*/
		if(params.onBeforeInit!=null){
			try{
				params.onBeforeInit.apply(this);
			}catch(e){}
		}
		/*</mauricio>*/
		if (params.arrows) {
			// Initialise arrow opacity
			setArrowOpacity();
		}
		else {
			// Hide arrows
			$('.arrowleft, .arrowright, .arrowtop, .arrowbottom', ctnr).hide();
		}
	});
	
	return this;
};


// Backward compatibility with jQuery 1.1.x
if (!$.fn.offset) {
	$.fn.offset = function() {
		this.left = this.top = 0;
		
		if (this[0] && this[0].offsetParent) {
			var obj = this[0];
			do {
				this.left += obj.offsetLeft;
				this.top += obj.offsetTop;
			} while (obj = obj.offsetParent);
		}
		
		return this;
	}
}



/**
 * HoverScroll default parameters
 */
$.fn.hoverscroll.params = {
	vertical:	false,
	width:		400,
	height:		50,
	arrows:		true,
	arrowsOpacity:	0.7,
	debug:		false
};



/**
 * log to firebug console if exists
 */
$.log = function(msg) {
	if (console && console.log) {
		console.log(msg);
	}
};


})(jQuery);
;

;;

;/* Copyright (c) 2006 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * $LastChangedDate: 2007-12-20 09:02:08 -0600 (Thu, 20 Dec 2007) $
 * $Rev: 4265 $
 *
 * Version: 3.0
 * 
 * Requires: $ 1.2.2+
 */

(function($) {

$.event.special.mousewheel = {
	setup: function() {
		var handler = $.event.special.mousewheel.handler;
		
		// Fix pageX, pageY, clientX and clientY for mozilla
		if ( $.browser.mozilla )
			$(this).bind('mousemove.mousewheel', function(event) {
				$.data(this, 'mwcursorposdata', {
					pageX: event.pageX,
					pageY: event.pageY,
					clientX: event.clientX,
					clientY: event.clientY
				});
			});
	
		if ( this.addEventListener )
			this.addEventListener( ($.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel'), handler, false);
		else
			this.onmousewheel = handler;
	},
	
	teardown: function() {
		var handler = $.event.special.mousewheel.handler;
		
		$(this).unbind('mousemove.mousewheel');
		
		if ( this.removeEventListener )
			this.removeEventListener( ($.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel'), handler, false);
		else
			this.onmousewheel = function(){};
		
		$.removeData(this, 'mwcursorposdata');
	},
	
	handler: function(event) {
		var args = Array.prototype.slice.call( arguments, 1 );
		
		event = $.event.fix(event || window.event);
		// Get correct pageX, pageY, clientX and clientY for mozilla
		$.extend( event, $.data(this, 'mwcursorposdata') || {} );
		var delta = 0, returnValue = true;
		
		if ( event.wheelDelta ) delta = event.wheelDelta/120;
		if ( event.detail     ) delta = -event.detail/3;
//		if ( $.browser.opera  ) delta = -event.wheelDelta;
		
		event.data  = event.data || {};
		event.type  = "mousewheel";
		
		// Add delta to the front of the arguments
		args.unshift(delta);
		// Add event to the front of the arguments
		args.unshift(event);

		return $.event.handle.apply(this, args);
	}
};

$.fn.extend({
	mousewheel: function(fn) {
		return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
	},
	
	unmousewheel: function(fn) {
		return this.unbind("mousewheel", fn);
	}
});

})(jQuery);;

;;

;
jQuery.ScreenBlock = jQuery.fn.ScreenBlock = function(options){
	var staticmode = this==jQuery;
	if(options!=null){
		if(typeof(options)=='object'){
			for(idx in options){
				if(typeof(options[idx])=='object' && jQuery.fn.ScreenBlock.params[idx]!=null){
					options[idx] = jQuery.extend({}, jQuery.fn.ScreenBlock.params[idx], options[idx]);
				}
			}
		}
		else if(typeof(options)=='string'){
			options = {message:options};
		}
		else if(typeof(options)=='boolean'){
			if(options==false)
				jQuery('.ScreenBlockWrapper').hide();
				jQuery('.ScreenBlockWrapper').each(function(){
					var message = jQuery(this).data('ScreenBlock/Message');
					if(typeof(message)=='object'){
						var postizo = null;
						if(postizo = jQuery(message).data('ScreenBlock/Postizo'))
							postizo.replaceWith(message);
							//jQuery(message).appendTo(parent);
						postizo = null;
					}
				})
				jQuery('.ScreenBlockWrapper').remove();
			return;
		}
	}
	jQuery.ScreenBlock(false);
	if(options==null)
		options = {};
	if(options.message==null){
		if(staticmode==false){
			options.message = this.first();
		}
		else return false;//si no hay mensaje no se muestra nada
	}
	var params = jQuery.extend({}, jQuery.fn.ScreenBlock.params, options);
	var jqwindow = jQuery('<div class="ScreenBlockWrapper"></div>').data('ScreenBlock/Message', params.message);
	if(params.message==null){
		jqwindow = null;
		return;
	}
	if(typeof(params.message)=='object'){
		var postizo = jQuery('<div style="display:none;"></div>');
		jQuery(params.message).after(postizo);
		jQuery(params.message).data('ScreenBlock/Postizo', postizo);
		postizo = null;
	}
	var jqblock = jQuery('<div class="ScreenBlockTile"></div>');
	var jqmessage_container = jQuery('<div></div>').css(params.message_container_css);
	var jqmessage = jQuery('<div class="message"></div>').css(params.message_css).html(params.message).appendTo(jqmessage_container);
	
	jqwindow.css(params.window_css).appendTo('body');
	jqblock.css(params.block_css).appendTo(jqwindow);
	jqmessage_container.appendTo(jqwindow);
	if(params.message_css.width==null && params.autosize){//autowidth
		jqmessage.css('display','inline');
		jqmessage.width(jqmessage.width());
		jqmessage.css('display','block');
	}
	if(params.message_css.height==null && params.autosize){//autowidth
		jqmessage.css('display','inline');
		jqmessage.height(jqmessage.height());
		jqmessage.css('display','block');
	}
	if(params.autocenter==true){
		var tiene_tamano_definido = params.autosize || (params.message_css.height!=null && params.message_css.width!=null && params.message_css.display=='block');
		if(tiene_tamano_definido){
			jqmessage.css({
				position:'absolute',
				margin:'auto',
				top:0,
				left:0,
				bottom:0,
				right:0
			});
		}
		else{
			var whole_height = jqmessage.height() + parseFloat(jqmessage.css('borderTopWidth')) + parseFloat(jqmessage.css('borderBottomWidth')) + parseFloat(jqmessage.css('paddingBottom')) + parseFloat(jqmessage.css('paddingTop'));
			var half_height = whole_height / 2;
			var whole_width = jqmessage.width() + parseFloat(jqmessage.css('borderRightWidth')) + parseFloat(jqmessage.css('borderLeftWidth')) + parseFloat(jqmessage.css('paddingLeft')) + parseFloat(jqmessage.css('paddingRight'));
			var half_width = whole_width / 2;
			
			jqmessage.css({
				position:'absolute',
				top:'50%',
				margin:0,
				'margin-top':'-'+half_height+'px',//(height + padding-top + padding-bottom + border-top + border-bottom)/2 * -1
				left:'50%',
				'margin-left':'-'+half_width+'px',//(width + padding-right + padding-left + border-right + border-left)/2 * -1
			});
		}
	}

	if(params.autocenter_y){
		var height = jqmessage.height();
		var dheight = jqmessage.parent().height();
		var top = (dheight - height)/2;
		//jqmessage_container.css('position','absolute');
		jqmessage_container.css('position','absolute').css('top', top + 'px');
	}
	if(params.close_button){
		var jqbutton = jQuery('<div class="cerrar">X</div>').attr('title','Cerrar').css(params.close_button_css).appendTo(jqmessage);
		jqbutton[0].scrollLeft = 2;
		jqbutton[0].scrollTop = 1;
		jqbutton.click(function(){
			jQuery.ScreenBlock(false);
		});
		jqbutton = null;
	}
	jqblock=null;
	jqmessage_container=null;
	jqmessage=null;
	jqwindow=null;
}
jQuery.fn.ScreenBlock.params = {
	message:'Aguarde un momento...',
	window_css:{
		height:'100%',
		left:0,
		position:'fixed',
		top:0,
		width:'100%'
	},
	block_css: {
		opacity: 0.5,
		top: 0,
		left: 0, 
		width: '100%', 
		height: '100%', 
		'background-color': 'white',
		position: 'fixed', 
		'z-index': 50
	},
	message_css:{
		'-moz-border-radius': '11px',
		'webkit-border-radius': '11px',
		'border-radius': '11px',
		display:'block',
		//width:'auto',
		border:'5px solid #333333',
		'background-color':'white',
		margin:'0 auto',
		padding:'15px',
		position:'absolute',
		color:'black'
	},
	//autosize: true,
	autocenter: true,
	//autocenter_y: false,
	close_button: true,
	close_button_css:{
//		border:'2px solid black',
//		'-moz-border-radius':'15px',
//		width:'15px',
//		height:'15px',
//		cursor:'pointer',
		'border': '2px solid black',
		'width': '15px',
		'height': '15px',
		'cursor': 'pointer',
		'-moz-border-radius': '9px',
		'webkit-border-radius': '9px',
		'border-radius': '9px',
		'position': 'absolute',
		'right': '-8px',
		'top':'-8px',
		'color': 'black',
		'background-color': 'white',
		'font-weight': 'bold',
		'font-family': 'arial',
		'font-size': '28px',
		'overflow': 'hidden',
		'line-height': '19px'
	},
	message_container_css: {
		top: 0,
		left: 0, 
		width: '100%', 
		height: '100%', 
		position: 'fixed',
		'z-index': 51,
		'text-align':'center'
	}
}
//jQuery.ScreenBlock({message:'kradkk.com'});
//jQuery.ScreenBlock(false);
;

;;

;function MapaBarrio(contenedor,id_barrio,url_imagen_mapa, tipos_puntos, anuncios){
	this.contenedor = null;
	this.url_imagen_mapa ='';
	this.id_barrio = null;
	this.tipos_puntos = [];
	this.indexed_tipos_puntos = {};
	this.anuncios = [];
	this.preloaded_images = [];
	this.url_contador;
	this.tamano_estado=0;
	this.tamano_estado_maximo = 10;
	this.escala_tamano = {};
	this.escala_opacity = {};
	this.escala_tamano;
	var p1 = 0;
	var p2 = 0;
	for(var i=0; i<=this.tamano_estado_maximo; i++){
		
		fib = p1 + p2;
		this.escala_tamano[i] = (fib = fib<1?1:fib);
		p2 = p1;
		p1 = fib;
		
		this.escala_opacity[i] = 100 - parseInt(((i-1)*100)/this.tamano_estado_maximo);
	}
	//window.console.log(this.escala_tamano);
	if(typeof(contenedor)=='object'){
		if(contenedor.contenedor!=null){
			this.loadFromObject(contenedor);
			return;
		}
	}
	this.load(contenedor, id_barrio, url_imagen_mapa, tipos_puntos, anuncios);
}
MapaBarrio.prototype = {
//	contenedor: null,
//	url_imagen_mapa:'',
//	tipos_puntos: [],
//	indexed_tipos_puntos: {},
//	anuncios: [],
//	preloaded_images: [],
	setUrlContador: function(url_contador){
		this.url_contador = url_contador;
	},
	preloadImage: function(src, full_url){
		if(full_url==null)
			full_url = false;
		if(full_url==false)
			src = this.getResourceUrl(src);
		img = new Image();
		img.src = src;
		//window.console.log('preloading ' + src);
		this.preloaded_images.push(img);
	},
	loadFromObject: function(o){
		this.load(o.contenedor, o.id_barrio, o.url_imagen_mapa, o.tipos_puntos, o.anuncios);
	},
	load: function(contenedor, id_barrio, url_imagen_mapa, tipos_puntos, anuncios){
		if(typeof(contenedor)=='string')
			this.contenedor = jQuery(contenedor);
		else
			this.contenedor = contenedor;
		this.id_barrio = id_barrio;
		this.url_imagen_mapa = url_imagen_mapa;
		this.loadTiposPuntos(tipos_puntos);
		this.loadAnuncios(anuncios);
		this.overloadVisor();
	},
	overloadVisor: function(){
		VisorAnuncio.tellme(this,this.onVisorClick, this.onVisorOver, this.onVisorOut);
	},
	onVisorClick: function(){
		//window.console.log('onVisorClick');
	},
	onVisorOver: function(){
		//window.console.log('onVisorOver');
		this.remarcarSeleccionado();
	},
	onVisorOut: function(){
		//window.console.log('onVisorOut');
		this.desmarcarSeleccionado();
	},
	remarcarSeleccionado: function(){
		//window.console.log(jQuery('.seleccionado', this.contenedor));
		jQuery('.seleccionado', this.contenedor).mouseover();
	},
	desmarcarSeleccionado: function(){
		jQuery('.dohover', this.contenedor).mouseout();
	},
	loadTiposPuntos:function(tipos_puntos){
		for(idx in tipos_puntos){
			this.addTipoPunto( new MapaBarrioTipoPunto(tipos_puntos[idx]) );
		}
	},
	addTipoPunto:function(oTipoPunto){
		this.preloadImage(oTipoPunto.imagen_deseleccionado);
		if(oTipoPunto.imagen_seleccionado)
			this.preloadImage(oTipoPunto.imagen_seleccionado);
		if(oTipoPunto.imagen_over)
			this.preloadImage(oTipoPunto.imagen_over);
		this.tipos_puntos.push(oTipoPunto);
		this.indexed_tipos_puntos['id:'+oTipoPunto.id] = oTipoPunto;
	},
	getTipoPunto: function(id_tipo_punto){
		if(this.indexed_tipos_puntos['id:'+id_tipo_punto]!=null)
			return this.indexed_tipos_puntos['id:'+id_tipo_punto];
		return null;
	},
	loadAnuncios:function(anuncios){
		for(idx in anuncios){
//			window.console.log(anuncios[idx]);
			this.addAnuncio( new MapaAnuncio(anuncios[idx]) );
		}
	},
	addAnuncio:function(oAnuncio){
		//window.console.log(oAnuncio);
		this.preloadImage(oAnuncio.imagen_logo);
		this.anuncios.push(oAnuncio);
	},
	getUrl: function(url){
		return UrlHelper.getUrl(url);
	},
	getResourceUrl: function(url){
		return UrlHelper.getResourceUrl(url);
	},
	getUrlImagenMapa: function(){
		return this.getResourceUrl(this.url_imagen_mapa);
	},
	drawMapa:function(){
		jQuery('<img />').attr('src', this.getUrlImagenMapa()).appendTo(this.contenedor);
	},
	configureContainer: function(){
		this.contenedor.css('position', 'relative').css('float','left');
	},
	contados: {},
	contarClickAnuncio: function(anuncio){
		if(this.contados[this.id_barrio]==null)
			this.contados[this.id_barrio] = {};
		if(this.contados[this.id_barrio][anuncio.id]==null){
			this.contados[this.id_barrio][anuncio.id] = true;
			jQuery.post(this.url_contador,{id_anuncio:anuncio.id});
		}
	},
	updateCirculosEstadoAnuncio: function(tamano, opacity){
		var that = this;
		jQuery('.circulo_estado_anuncio', this.contenedor).each(function(){
			var anuncio = jQuery(this).data('MapaAnuncio');
			that.updateCirculoEstadoAnuncio(jQuery(this), anuncio, tamano, opacity);
		});
	},//( this.tamano_estado );
	updateCirculoEstadoAnuncio: function( capa, anuncio, tamano, opacity){
		//capa.css('border', '1px solid black');
		capa.css('position', 'absolute');
		var top = (anuncio.posicion_y - (tamano + 1));
		capa.css('top', top+'px');
		var left = (anuncio.posicion_x - (tamano + 1));
		capa.css('left', left+'px');
		capa.css('z-index',5);
		capa.css('cursor','pointer');
		capa.width(tamano*2 + 1);
		capa.height(tamano*2 + 1);
		//window.console.log(opacity);
		var o = opacity/100;
		capa.css('opacity', o);
	},
	createCirculoEstadoAnuncio: function( anuncio, tamano ){
//		var capa = anuncio.data('CirculoEstadoAnuncio');
//		if(capa==null){
		var capa = jQuery('<div></div>');
		var capa = jQuery('<img />');
		capa.addClass('circulo_estado_anuncio');
		capa.attr('src', this.getResourceUrl('skin/granguia/default/img/'+(anuncio.abierto?'activo.png':'inactivo.png')));
		capa.data('MapaAnuncio', anuncio);
		capa.appendTo(this.contenedor);
//		}
//		capa.data('MapaAnuncio', anuncio);
//		capa.data('MapaBarrioTipoPunto', tipo_punto);
//		capa.data('MapaBarrio', this);
//		capa.attr('title', anuncio.nombre);
//		//window.console.log(this.getUrl(tipo_punto.getImagenDeseleccionado()));
		
		this.updateCirculoEstadoAnuncio(capa, anuncio, tamano, 100);
		capa = null;
		
	},
	createPuntoAnuncio: function(anuncio, tipo_punto){
		var capa = jQuery('<div></div>');
		capa.addClass('punto_anuncio')
		capa.data('MapaAnuncio', anuncio);
		capa.data('MapaBarrioTipoPunto', tipo_punto);
		capa.data('MapaBarrio', this);
		capa.attr('title', anuncio.nombre);
		//window.console.log(this.getUrl(tipo_punto.getImagenDeseleccionado()));
		
		
		capa.css('background-repeat', 'no-repeat');
		capa.css('position', 'absolute');
		capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_deseleccionado'))+'px');
		capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_deseleccionado'))+'px');
		//capa.css('z-index',10);
		capa.css('cursor','pointer');
//		capa.width(50);
//		capa.height(50);
		capa.width(tipo_punto.width);
		capa.height(tipo_punto.height);
		if(anuncio.destacado==1){
			capa.addClass('seleccionado');
		}
		capa.appendTo(this.contenedor);
		capa
			.mouseover(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
//				window.console.log(
//					tipo_punto.getImagenOver(),
//					that.getUrl(tipo_punto.getImagenOver())
//				);
				capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenOver())+')');
				capa.addClass('dohover');
				//capa.css('z-index',20);
				capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_over'))+'px');
				capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_over'))+'px');
			})
			.mouseout(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
				if(capa.hasClass('seleccionado'))
					capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenSeleccionado())+')');
				else
					capa.css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenDeseleccionado())+')');
				//capa.css('z-index',10);
				capa.removeClass('dohover');
				capa.css('top', (anuncio.posicion_y - tipo_punto.getHotspotY('imagen_deseleccionado'))+'px');
				capa.css('left', (anuncio.posicion_x - tipo_punto.getHotspotX('imagen_deseleccionado'))+'px');
			})
			.click(function(){
				var capa = jQuery(this);
				var that = jQuery(this).data('MapaBarrio');
				jQuery('.punto_anuncio.seleccionado', that.contenedor).each(function(){
					var tipo_punto = jQuery(this).data('MapaBarrioTipoPunto');
					jQuery(this).css('background-image', 'url('+that.getResourceUrl(tipo_punto.getImagenDeseleccionado())+')');
				}).removeClass('seleccionado');
				jQuery(this).addClass('seleccionado');
				VisorAnuncio.displayAnuncio(anuncio);
				that.contarClickAnuncio(anuncio);
			})
			.mouseout()
		;
//		window.console.log(capa.data('MapaAnuncio'));
//		window.console.log(capa.data('MapaBarrioTipoPunto'));
		capa = null;
	},
	drawAnuncios: function(){
		for(idx in this.anuncios){
			var anuncio = this.anuncios[idx];
			var tipo_punto = this.getTipoPunto( anuncio.id_tipo_punto )
			this.createPuntoAnuncio( anuncio, tipo_punto );
		}
	},
	draw:function(){
		this.configureContainer();
		this.drawMapa();
		this.drawAnuncios();
		this.drawCirculosEstado(true);
		this.pollingEstado();
	},
	drawCirculosEstado:function(init){
		init = init==null?false:(init?true:false);
		if(init){
			this.preloadImage('skin/granguia/default/img/activo.png', false);
			this.preloadImage('skin/granguia/default/img/inactivo.png', false);
			for(idx in this.anuncios){
				var anuncio = this.anuncios[idx];
				//var tipo_punto = this.getTipoPunto( anuncio.id_tipo_punto )
				if(init)
					this.createCirculoEstadoAnuncio( anuncio, this.escala_tamano[this.tamano_estado] );
			}
		}
		else{
			//jQuery('.circulo_estado_anuncio', this.contenedor).remove();
			if(this.tamano_estado > this.tamano_estado_maximo){
				this.tamano_estado = 0;
			}
			this.updateCirculosEstadoAnuncio( this.escala_tamano[this.tamano_estado], this.escala_opacity[this.tamano_estado] );
			this.tamano_estado += 1;
		}
	},
	pollingEstado: function(){
		if(this.contenedor==null){
			return;
		}
		if(!jQuery(this.contenedor).parents('body').length){
			this.contenedor = null;
			return;
		}
		this.drawCirculosEstado();
		var that = this;
		setTimeout(function(){that.pollingEstado()},60);
	}
};




function MapaBarrioTipoPunto(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y){
	if(typeof(id)=='object'){
		this.loadFromObject(id);
		return;
	}
	this.load(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y);
}
MapaBarrioTipoPunto.prototype = {
	id: null,
	imagen_deseleccionado: null,
	imagen_over: null,
	imagen_seleccionado: null,
	nombre: null,
	imagen_deseleccionado_hotspot_x: null,
	imagen_deseleccionado_hotspot_y: null,
	imagen_seleccionado_hotspot_x: null,
	imagen_seleccionado_hotspot_y: null,
	imagen_over_hotspot_x: null,
	imagen_over_hotspot_y: null,
	loadFromObject: function(o){
		this.load(o.id, o.imagen_deseleccionado, o.imagen_over, o.imagen_seleccionado, o.nombre, o.width, o.height, o.imagen_deseleccionado_hotspot_x, o.imagen_deseleccionado_hotspot_y, o.imagen_seleccionado_hotspot_x, o.imagen_seleccionado_hotspot_y, o.imagen_over_hotspot_x, o.imagen_over_hotspot_y);
	},
	load: function(id, imagen_deseleccionado, imagen_over, imagen_seleccionado, nombre, width, height, imagen_deseleccionado_hotspot_x, imagen_deseleccionado_hotspot_y, imagen_seleccionado_hotspot_x, imagen_seleccionado_hotspot_y, imagen_over_hotspot_x, imagen_over_hotspot_y){
		this.id = id;
		this.imagen_deseleccionado = imagen_deseleccionado!=''?imagen_deseleccionado:null;
		this.imagen_over = imagen_over!=''?imagen_over:null;
		this.imagen_seleccionado = imagen_seleccionado!=''?imagen_seleccionado:null;

		this.imagen_seleccionado_hotspot_x = this.imagen_seleccionado!=null?imagen_seleccionado_hotspot_x:imagen_deseleccionado_hotspot_x;
		this.imagen_seleccionado_hotspot_y = this.imagen_seleccionado!=null?imagen_seleccionado_hotspot_y:imagen_deseleccionado_hotspot_y;
		this.imagen_over_hotspot_x = this.imagen_over!=null?imagen_over_hotspot_x:imagen_seleccionado_hotspot_x;
		this.imagen_over_hotspot_y = this.imagen_over!=null?imagen_over_hotspot_y:imagen_seleccionado_hotspot_y;
		
		this.imagen_deseleccionado_hotspot_x = parseInt(imagen_deseleccionado_hotspot_x); 
		this.imagen_deseleccionado_hotspot_y = parseInt(imagen_deseleccionado_hotspot_y); 
		this.imagen_seleccionado_hotspot_x = parseInt(this.imagen_seleccionado_hotspot_x); 
		this.imagen_seleccionado_hotspot_y = parseInt(this.imagen_seleccionado_hotspot_y); 
		this.imagen_over_hotspot_x = parseInt(this.imagen_over_hotspot_x); 
		this.imagen_over_hotspot_y = parseInt(this.imagen_over_hotspot_y);
		
		//window.console.log(this);
		this.imagen_seleccionado = this.imagen_seleccionado!=null?this.imagen_seleccionado:this.imagen_deseleccionado;
		this.imagen_over = this.imagen_over!=null?this.imagen_over:this.imagen_seleccionado;
		//window.console.log(this);

		this.nombre = nombre;
		this.width = width;
		this.height = height;
	},
	getImagenDeseleccionado: function(){
		return this.imagen_deseleccionado;
	},
	getImagenSeleccionado: function(){
		return this.imagen_seleccionado!=null?this.imagen_seleccionado:this.getImagenDeseleccionado();
	},
	getImagenOver: function(){
		return this.imagen_over!=null?this.imagen_over:this.getImagenSeleccionado();
	},
	getHotspotX: function(image_name){
		return this[image_name+'_hotspot_x']!=null?this[image_name+'_hotspot_x']:0;
	},
	getHotspotY: function(image_name){
		return this[image_name+'_hotspot_y']!=null?this[image_name+'_hotspot_y']:0;
	}
};


function MapaAnuncio(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto){
	if(typeof(nombre)=='object'){
		this.loadFromObject(nombre);
		return;
	}
	this.load(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto);
}
MapaAnuncio.prototype = {
	nombre: null,
	imagen_logo: null,
	id_tipo_punto: null,
	posicion_x: null,
	posicion_y: null,
	tiene_minisitio: null,
	url_minisitio: null,
	destacado:null,
	id_tipo_contacto: null,
	url_contacto: null,
	label_contacto: null,
	id: null,
	texto_minisitio:null,
	loadFromObject: function(o){
		this.load(o.nombre, o.imagen_logo, o.id_tipo_punto, o.posicion_x, o.posicion_y, o.tiene_minisitio, o.url_minisitio, o.destacado, o.id_tipo_contacto, o.url_contacto, o.label_contacto, o.id, o.texto_minisitio, o.abierto);
	},
	load: function(nombre, imagen_logo, id_tipo_punto, posicion_x, posicion_y, tiene_minisitio, url_minisitio, destacado, id_tipo_contacto, url_contacto, label_contacto, id, texto_minisitio, abierto){
		this.nombre = nombre;
		this.imagen_logo = imagen_logo;
		this.id_tipo_punto = id_tipo_punto;
		this.posicion_x = posicion_x;
		this.posicion_y = posicion_y;
		this.tiene_minisitio = tiene_minisitio;
		this.url_minisitio = url_minisitio;
		this.destacado = destacado;
		this.id_tipo_contacto = id_tipo_contacto;
		this.url_contacto = url_contacto;
		this.label_contacto = label_contacto;
		this.id = id;
		this.texto_minisitio = texto_minisitio;
		this.abierto = abierto;
	}
};
jQuery(document).ready(function(){
	ComponentHandler.loadComponent('contenedor_mapa.marcadores.zindexes',"<style type=\"text/css\">\n"+
			".contenedor_mapa .punto_anuncio{z-index:10;}\n"+
			".contenedor_mapa .seleccionado{z-index:15;}\n"+
			".contenedor_mapa .dohover{z-index:20;}\n"+
			"</style>");
	return;
//	var newrule = jQuery(
//			"<style>\n"+
//			".contenedor_mapa .punto_anuncio{z-index:10;}\n"+
//			".contenedor_mapa .seleccionado{z-index:15;}\n"+
//			".contenedor_mapa .dohover{z-index:20;}\n"+
//			"</style>"
//	);
//	newrule.appendTo('head');
});;

;;

;function VisorAnuncio(contenedor, anuncio){
	if(typeof(nombre)=='object'){
		if(contenedor.contenedor!=null){
			this.loadFromObject(contenedor);
			return;
		}
	}
	this.load(contenedor, anuncio);
}
VisorAnuncio.prototype = {
	contenedor: null,
	anuncio: null,
	textos: {contacto_normal:'Contacto',contacto_boliche:'Arma tu Lista',link_minisitio:'Ver Minisitio'},
	setTextos: function(t){
		for(idx in t){
			if(this.textos[idx]!=null)
				this.textos[idx] = t[idx]; 
		}
		return this;
	},
	loadFromObject: function(o){
		this.load(o.contenedor, o.anuncio);
		return this;
	},
	load: function(contenedor, anuncio){
		this.setContenedor(contenedor);
		this.setAnuncio(anuncio);
		this.prepareTriggers();
		return this;
	},
	removeTriggers: function(){
		if(this.contenedor==null)
			return this;
		jQuery('img', this.contenedor).unbind('click');
		jQuery('img', this.contenedor).unbind('mouseover');
		jQuery('img', this.contenedor).unbind('mousedown');
		return this;
	},
	prepareTriggers: function(){
		if(this.contenedor==null)
			return this;
		jQuery('img', this.contenedor).click(function(){
			VisorAnuncio.getInstance().triggerClick();
		});
		jQuery('img', this.contenedor).mouseover(function(){
			VisorAnuncio.getInstance().triggerMouseOver();
		});
		jQuery('img', this.contenedor).mouseout(function(){
			VisorAnuncio.getInstance().triggerMouseOut();
		});
		return this;
	},
	tellto: null,
	tellme: function(o, onclick, onmouseover, onmouseout){
		this.tellto = {o: o, onclick: onclick, onmouseover: onmouseover, onmouseout:onmouseout};
		return this;
	},
	triggerClick: function(){
		if(this.tellto!=null)
			if(this.tellto.onclick!=null)
				this.tellto.onclick.apply(this.tellto.o, arguments);
//		window.console.log('triggerClick');
		return this;
	},
	triggerMouseOver: function(){
		if(this.tellto!=null)
			if(this.tellto.onmouseover!=null)
				this.tellto.onmouseover.apply(this.tellto.o, arguments);
//		window.console.log('triggerMouseOver');
		return this;
	},
	triggerMouseOut: function(){
		if(this.tellto!=null)
			if(this.tellto.onmouseout!=null)
				this.tellto.onmouseout.apply(this.tellto.o, arguments);
//		window.console.log('triggerMouseOut');
		return this;
	},
	setContenedor: function(contenedor){
		this.removeTriggers();
		if(typeof(contenedor)=='string')
			this.contenedor = jQuery(contenedor);
		else this.contenedor = contenedor;
		this.prepareTriggers();
		return this;
	},
	setAnuncio: function(anuncio){
		this.anuncio = anuncio;
		return this;
	},
	getUrl: function(url){
		return UrlHelper.getUrl(url);
	},
	getResourceUrl: function(url){
		return UrlHelper.getResourceUrl(url);
	},
	getUrlAnuncio: function(anuncio){
		if(anuncio.tiene_minisitio == 0)
			return false;
		else if(anuncio.tiene_minisitio == 1)
			return this.getResourceUrl(anuncio.url_minisitio)
		//else if(anuncio.tiene_minisitio == 2)
		return anuncio.url_minisitio;
	},
	displayAnuncio: function(anuncio){
		if(anuncio!=null)
			this.setAnuncio(anuncio);
		anuncio = this.anuncio;
		this.contenedor.html('');
		jQuery('<img />')
			.attr('alt', anuncio.nombre)
			.attr('title', anuncio.nombre)
			.attr('src', this.getResourceUrl(anuncio.imagen_logo))
			.width(191)
			.height(187)
			.appendTo(this.contenedor)
		;
		var jqbottombox = jQuery('<div class="bottombox"></div>').appendTo(this.contenedor);
		var url = this.getUrlAnuncio(anuncio);
		if(url!=false){
			var texto_minisitio = this.textos.link_minisitio;
			if(anuncio.texto_minisitio!=null && anuncio.texto_minisitio!=''){
				texto_minisitio = anuncio.texto_minisitio;
			}
			jQuery('<a></a>')
				.attr('href', url)
				.attr('target','_blank')
				.text(texto_minisitio)
				.appendTo(jqbottombox)
			;
		}
		if(anuncio.id_tipo_contacto>1){//tieneContacto()//1 es sin contacto
			var tipo_contacto = anuncio.id_tipo_contacto==2?'normal':'boliche';
			var texto = anuncio.label_contacto;
			if(texto==null||texto=='')
				texto = anuncio.id_tipo_contacto==2?this.textos.contacto_normal:this.textos.contacto_boliche;
			var url = this.getUrl(anuncio.url_contacto);
			jQuery('<a></a>')
				.attr('class','link_contacto link_contacto_'+tipo_contacto)
				.attr('href', url)
				.text(texto)
				.appendTo(jqbottombox)
			;
		}
		jqbottombox = null;
		this.prepareTriggers();
//		window.console.log('mostrar anuncio');
//		window.console.log(anuncio);
		return this;
	}
};

/*<para crear un singleton>*/
VisorAnuncio.instancia = new VisorAnuncio();
VisorAnuncio.getInstance = function(){
	if(VisorAnuncio.instancia == null)
		VisorAnuncio.instancia = new VisorAnuncio();
	return VisorAnuncio.instancia; 
}
for(idx in VisorAnuncio.getInstance()){
	var instancia = VisorAnuncio.getInstance();
	if(typeof(instancia[idx])=='function'){
		(function(funcion){
			VisorAnuncio[idx] = function(){
				return funcion.apply(VisorAnuncio.getInstance(), arguments);
			}
		})(instancia[idx]);
	}
	instancia = null;
	//window.console.log(idx, );
}
/*</para crear un singleton>*/



//VisorAnuncio.doAlgo('kradkk');
//window.console.log(VisorAnuncio.getInstance() == VisorAnuncio.getInstance());
//window.console.log(VisorAnuncio.instancia);

