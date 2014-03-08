<?php

/*==================================================================================

	------- Ajax Calls for Frontend Display -------

	1.	Contact Widget Form
		
==================================================================================*/


/*--------------------------------------------------------------------------------*/
/*	1.	Contact Form
/*--------------------------------------------------------------------------------*/
add_action('wp_ajax_<%= _.slugify(fnPrefix) %>_contact', '<%= _.slugify(fnPrefix) %>_contact');
add_action('wp_ajax_nopriv_<%= _.slugify(fnPrefix) %>_contact', '<%= _.slugify(fnPrefix) %>_contact');

function <%= _.slugify(fnPrefix) %>_contact() {

	// Check wp_nonce, for science!
	check_ajax_referer( '<%= _.slugify(fnPrefix) %>_ajax_cont_ref' );
	
	// Check for form data, otherwise die
	if ( !isset( $_POST['<%= _.slugify(fnPrefix) %>_ajax_cont_data'] ) ) die('-1');
	
	// Parse data
	parse_str( $_POST['<%= _.slugify(fnPrefix) %>_ajax_cont_data'], $cont_data );
	$name = sanitize_text_field( $cont_data['name'] );
	$email = sanitize_email( $cont_data['email'] );
	$message = sanitize_text_field( $cont_data['message'] );
	$error_response = $success_response = new WP_Ajax_Response();
	$errors = new WP_Error();
	
	// Validate data
	if ( empty( $email ) ) $errors->add( 'contact-email', __('Please enter a valid email address.', '<%= _.slugify(textDomain) %>') );
	if ( empty( $message ) ) $errors->add( 'contact-message', __('Please enter a message.', '<%= _.slugify(textDomain) %>') );
	if ( count ( $errors->get_error_codes() ) > 0 ) {
		$error_response->add(array(
			'what' => 'errors',
			'id' => $errors
		));
		$error_response->send();
		exit;
	}
	
	// Send email
	$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
	$cform_options = get_option('<%= _.slugify(themeTitle) %>_cform_options');
	$recipient = isset($cform_options['rec_email']) ? $cform_options['rec_email'] : get_bloginfo('admin_email');
	$subject = get_bloginfo('name') . __(' - Contact Form', '<%= _.slugify(textDomain) %>');
	$mail = wp_mail($recipient, $subject, $message, $headers);
	
	// Check if email was successful
	if ($mail) {
		$success_response->add(array(
			'what' => 'object',
			'data' => __('Thank you, we\'ll be in touch!', '<%= _.slugify(textDomain) %>')
		));
		$success_response->send();
	} else {
		$errors->add( 'mail', __('Sorry, but there was an error with our server, please try again later.', '<%= _.slugify(textDomain) %>') );
		$error_response->add(array(
			'what' => 'errors',
			'id' => $errors
		));
		$error_response->send();
	}
	exit;
	
}