/*
 * SimpleModal Basic Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: basic.js 254 2010-07-23 05:14:44Z emartin24 $
 */
Jpopup = jQuery.noConflict();
jQuery(function (Jpopup) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	Jpopup('#basic-modal .basic').click(function (e) {
		Jpopup('#basic-modal-content').modal();

		return false;
	});
});