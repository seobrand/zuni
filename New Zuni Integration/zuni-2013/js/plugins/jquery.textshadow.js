/* Created by Martin Hintzmann 2008 martin [a] hintzmann.dk
 * MIT (http://www.opensource.org/licenses/mit-license.php) licensed.
 *
 * Version: 0.2
 * Requires: jQuery 1.2+
 * http://plugins.jquery.com/project/textshadow
 *
 */
(function($) {
	$.fn.textShadow = function(option) {
		//if (!$.browser.msie) return;
		//var IE6 = $.browser.version < 7;
		 
	};
	
	$.fn.textShadowParse = function(value) 
	{
		value = String(value)
			.replace(/^\s+|\s+$/gi, '')
			.replace(/\s*!\s*important/i, '')
			.replace(/\(\s*([^,\)]+)\s*,\s*([^,\)]+)\s*,\s*([^,\)]+)\s*,\s*([^\)]+)\s*\)/g, '($1/$2/$3/$4)')
			.replace(/\(\s*([^,\)]+)\s*,\s*([^,\)]+)\s*,\s*([^\)]+)\s*\)/g, '($1/$2/$3)')
	
		var shadow = {
			x      : 0,
			y      : 0,
			radius : 0,
			color  : null
		};

		if (value.length > 1 || value[0].toLowerCase() != 'none') {
			value = value.replace(/\//g, ',');
			var color;
			if ( value.match(/(\#[0-9a-f]{6}|\#[0-9a-f]{3}|(rgb|hsb)a?\([^\)]*\)|\b[a-z]+\b)/i) && (color = RegExp.$1) ) {
				shadow.color = color.replace(/^\s+/, '');
				value = value.replace(shadow.color, '');
			}

			value = value
				.replace(/^\s+|\s+$/g, '')
				.split(/\s+/)
				.map(function(item) {
						return (item || '').replace(/^0[a-z]*$/, '') ? item : 0 ;
					});

			switch (value.length)
			{
				case 1:
					shadow.x = shadow.y = value[0];
					break;
				case 2:
					shadow.x = value[0];
					shadow.y = value[1];
					break;
				case 3:
					shadow.x = value[0];
					shadow.y = value[1];
					shadow.radius = value[2];
					break;
			}
			if ((!shadow.x && !shadow.y && !shadow.radius) || shadow.color == 'transparent') {
				shadow.x = shadow.y = shadow.radius = 0;
				shadow.color = null;
			}
		}

		return shadow;
	};

	$.fn.textShadowRemove = function() {
		if (!$.browser.msie) return;
		return this.each(function() {
			$(this).children("span.jQueryTextShadow").remove();
		});
	};
})(jQuery);

if(typeof Array.prototype.map == 'undefined') {
	Array.prototype.map = function(fnc) {
		var a = new Array(this.length);
		for (var i = 0; i < this.length; i++) {
			a[i] = fnc(this[i]);
		}
		return a;
	}
}
