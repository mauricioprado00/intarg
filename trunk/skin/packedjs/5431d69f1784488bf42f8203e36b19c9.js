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
