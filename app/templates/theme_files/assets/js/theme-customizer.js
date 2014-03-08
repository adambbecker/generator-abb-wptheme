(function ($, document, body) {

	/* ---- MAIN OPTIONS (colors) ---- */
	// Base Font Color
	wp.customize( '<%= _.slugify(themeTitle) %>_main_options[base_font_color]', function( value ) {
		value.bind( function( newval ) {
			$('body').css('color', newval );
		} );
	} );

})(jQuery, document, document.body);