<?php
/*================================================================================

	------- Theme Customizer -------

	1.	Register Settings
	2.	Generate New CSS
	3.	Live Preview js Enqueue
	4.	Textarea Control Definition
	5.	Action Hooks

==================================================================================*/


class <%= _.slugify(fnPrefix) %>_theme_customize {

/*--------------------------------------------------------------------------------*/
/*	1.	Post Type Definitions & Initialization
/*--------------------------------------------------------------------------------*/
public static function register ( $wp_customize ) {

	//1. Define Option Sections
	$wp_customize->add_section( '<%= _.slugify(themeTitle) %>_main_options',
		array (
			'title' => __('General Options', '<%= _.slugify(textDomain) %>'),
			'priority' => 100,
			'capability' => 'edit_theme_options',
			'description' => __('Customize main settings specific to the <%= themeTitle %> theme.', '<%= _.slugify(textDomain) %>')
		)
	);
	$wp_customize->remove_section( 'title_tagline' );
	$wp_customize->remove_section( 'nav' );
	$wp_customize->remove_section( 'static_front_page' );

	//2. Register new settings

	// -- Main Options (general) --
	$wp_customize->add_setting( '<%= _.slugify(themeTitle) %>_main_options[footer_text]',
		array (
			'default' => '&copy; ' . date('Y') . ' ' . get_bloginfo('name'),
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage'
		)
	);

	// -- Main Options (colors) --
	$wp_customize->add_setting( '<%= _.slugify(themeTitle) %>_main_options[base_font_color]',
		array (
			'default' => '#333',
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage'
		)
	);

	// -- Contact Form --
	$wp_customize->add_setting( '<%= _.slugify(themeTitle) %>_cform_options[rec_email]',
		array (
			'default' => get_bloginfo('admin_email'),
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_email'
		)
	);

	function <%= _.slugify(fnPrefix) %>_sanitize_text( $input ) {
	    return wp_kses_post( force_balance_tags( $input ) );
	}

	//3. Controls

	// -- Main Options (general) --
	$wp_customize->add_control(
	    '<%= _.slugify(themeTitle) %>_page_trans',
	    array(
	        'label' => __('Page Transition', '<%= _.slugify(textDomain) %>'),
	        'section' => '<%= _.slugify(themeTitle) %>_main_options',
	        'settings' => '<%= _.slugify(themeTitle) %>_main_options[page_trans]',
	        'type' => 'select',
	        'choices' => array(
	            'slide' => __('Slide', '<%= _.slugify(textDomain) %>'),
	            'fade' => __('Fade', '<%= _.slugify(textDomain) %>'),
	            'none' => __('None', '<%= _.slugify(textDomain) %>')
	        ),
	        'priority' => 10
	    )
	);
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'<%= _.slugify(themeTitle) %>_header_logo',
		array (
			'label' => __( 'Header Logo', '<%= _.slugify(textDomain) %>' ),
			'section' => '<%= _.slugify(themeTitle) %>_main_options',
			'settings' => '<%= _.slugify(themeTitle) %>_main_options[header_logo]',
			'priority' => 10
		)
	) );
	$wp_customize->add_control(
	    '<%= _.slugify(themeTitle) %>_footer_text',
	    array(
	        'label' => __( 'Footer Text', '<%= _.slugify(textDomain) %>' ),
	        'section' => '<%= _.slugify(themeTitle) %>_main_options',
	        'settings' => '<%= _.slugify(themeTitle) %>_main_options[footer_text]',
	        'type' => 'text',
	        'priority' => 10
	    )
	);

	// -- Main Options (colors) --
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'<%= _.slugify(themeTitle) %>_base_font_color',
		array (
			'label' => __( 'Base Font', '<%= _.slugify(textDomain) %>' ),
			'section' => 'colors',
			'settings' => '<%= _.slugify(themeTitle) %>_main_options[base_font_color]',
			'priority' => 25
		)
	) );

	// -- Contact Form --
	$wp_customize->add_control(
	    '<%= _.slugify(themeTitle) %>_cform_rec_email',
	    array(
	        'label' => __( 'Recipient Email Address', '<%= _.slugify(textDomain) %>' ),
	        'section' => '<%= _.slugify(themeTitle) %>_cform_options',
	        'settings' => '<%= _.slugify(themeTitle) %>_cform_options[rec_email]',
	        'type' => 'text',
	        'priority' => 10
	    )
	);

	//4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

}


/*--------------------------------------------------------------------------------*/
/*	2.	Generate New CSS
/*--------------------------------------------------------------------------------*/
public static function header_output() {

	$css_map_arr = array(

		// main options
		'main_options' => array(
			'base_font_color' => array(
				'default' => '#333',
				'changes' => array(
					array(
						'attr'=>'color',
						'selectors'=> array('body')
					)
				)
			)
		)

	);

	$css_options = array(
		'main_options' => get_option('<%= _.slugify(themeTitle) %>_main_options')
	);

	$css_output = '';
	foreach( $css_map_arr as $option_set => $map ) {
		foreach ( $map as $map_key => $css_map ) {
			if (isset($css_options[$option_set][$map_key]) && $css_options[$option_set][$map_key] != $css_map['default']) {
				foreach( $css_map['changes'] as $change ) {
					if ($change['attr'] == 'background-image') {
						$change_output = $change['attr'] . ':url("' . $css_options[$option_set][$map_key] . '");}';
					} else {
						$change_output = $change['attr'] . ':' . $css_options[$option_set][$map_key] . ';}';
					}
					$css_output .= join( ',', $change['selectors'] ) . '{' . $change_output;
				}
			}
		}
	}

	if ($css_output != '') echo '<!--Customizer CSS--><style type="text/css">' . $css_output . '</style><!--/Customizer CSS-->';

}


/*--------------------------------------------------------------------------------*/
/*	3.	Live Preview js Enqueue
/*--------------------------------------------------------------------------------*/
public static function live_preview() {
	wp_enqueue_script('<%= _.slugify(themeTitle) %>-themecustomizer', get_template_directory_uri() . '/assets/js/theme-customizer.js', array('jquery', 'customize-preview', 'modernizr'), '', true);
}

} // END class <%= _.slugify(fnPrefix) %>_theme_customize


/*--------------------------------------------------------------------------------*/
/*	4.	Textarea Control Definition
/*--------------------------------------------------------------------------------*/
if (class_exists('WP_Customize_Control')) {
	class WP_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';

    public function render_content() {
      ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>
      <?php
    }
	}
}


/*--------------------------------------------------------------------------------*/
/*	5.	Action Hooks
/*--------------------------------------------------------------------------------*/

//Setup the Theme Customizer settings and controls...
add_action('customize_register', array('<%= _.slugify(fnPrefix) %>_theme_customize', 'register'));

//Output custom CSS to live site
add_action('wp_head', array('<%= _.slugify(fnPrefix) %>_theme_customize', 'header_output'));

//Enqueue live preview javascript in Theme Customizer admin screen
add_action('customize_preview_init', array('<%= _.slugify(fnPrefix) %>_theme_customize', 'live_preview'));
