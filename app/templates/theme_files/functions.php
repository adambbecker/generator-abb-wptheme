<?php

/*================================================================================

	This is where all custom theme functions and includes are held.  If something
	is broken and you have made edits to this file, the problem most likely is in
	here.
	
==================================================================================

	1.  Set Max Content Width
	2.  Theme Set Up
	3.  Register Sidebars
	4.  Extra Stylesheet and Javascript Enqueues
	5.  Comment Styling
	6.  Get Project Images Function
	n.  Theme Extras
		
==================================================================================*/


/*--------------------------------------------------------------------------------*/
/*	1.  Set Max Content Width
/*--------------------------------------------------------------------------------*/
/* if ( ! isset( $content_width ) ) $content_width = 1346; */


/*--------------------------------------------------------------------------------*/
/*	2.  Theme Set Up - translation domain, custom menus, thumbnail support
/*--------------------------------------------------------------------------------*/
function <%= _.slugify(fnPrefix) %>_theme_setup() {
	
	/* Load translation domain ---------------------------------------------------*/
	load_theme_textdomain('<%= _.slugify(textDomain) %>', get_template_directory() . '/languages');

	$locale = get_locale();
	$locale_file = get_template_directory() . '/languages/' . $locale . '.php';
	if ( is_readable($locale_file) ) {
		require_once($locale_file);
	}
	
	/* Custom Menus --------------------------------------------------------------*/
	register_nav_menu('main-nav', __('Main Nav', '<%= _.slugify(textDomain) %>'));
	
	/* Defult Image Size & Thumbnail Support -------------------------------------*/
	add_theme_support( 'post-thumbnails' );
	/*
set_post_thumbnail_size( 258, '', true ); // Base thumbnail size
	update_option( 'thumbnail_size_w', 135 ); // 1 Column Span
	update_option( 'thumbnail_size_h', null ); // unrestricted
	add_image_size( '1col', 135, '', true ); // 1 Column Span
	update_option( 'medium_size_w', 308 ); // 2 Column Span
	update_option( 'medium_size_h', null ); // unrestricted
	add_image_size( '2col', 308, '', true ); // 2 Column Span
	update_option( 'large_size_w', 654 ); // 4 Column Span
	update_option( 'large_size_h', null ); // unrestricted
	add_image_size( '4col', 654, '', true ); // 4 Column Span
	add_image_size( '6col', 1000, '', true ); // 6 Column Span
	add_image_size( '8col', 1346, '', true ); // 8 Column Span
*/
	
	// Embed Sizes
	/*
update_option( 'embed_size_w', 654 );
	update_option( 'embed_size_h', null );
*/
	
	/* Custom Header / Background ------------------------------------------------*/
	add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );
	
	/* Blogroll Support ----------------------------------------------------------*/
	/*
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
	wp_insert_term(
		'Social',
		'link_category',
		array(
			'description'=> 'Social profile links.',
			'slug' => 'social'
		)
	);
*/
	
}

add_action( 'after_setup_theme', '<%= _.slugify(fnPrefix) %>_theme_setup' );


/*--------------------------------------------------------------------------------*/
/*	3.  Register Sidebars
/*--------------------------------------------------------------------------------*/
function <%= _.slugify(fnPrefix) %>_reg_sidebars() {
	
	// Blog
	register_sidebar(array(
		'id' => 'blog',
		'name' => __('Blog', '<%= _.slugify(textDomain) %>'),
		'before_widget' => '<div id="%1$s" class="%2$s widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	
}

add_action( 'widgets_init', '<%= _.slugify(fnPrefix) %>_reg_sidebars' );


/*--------------------------------------------------------------------------------*/
/*	4.  Extra Stylesheet and Javascript Enqueues
/*--------------------------------------------------------------------------------*/
function <%= _.slugify(fnPrefix) %>_enqueues() {

	/* Stylesheets ---------------------------------------------------------------*/
	wp_register_style('customcss', get_template_directory_uri() . '/assets/css/custom.css');
	wp_enqueue_style('customcss');
	
	/* Javascript - registers ----------------------------------------------------*/
	wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js');
	wp_register_script('lib', get_template_directory_uri() . '/assets/js/lib.js', array(), '1.0.0', true);
	/*
wp_register_script('example', get_template_directory_uri() . '/assets/js/example.js', array('example_dependency'), '0.0.1', true);
	// Last argument puts script in wp_footer() instead of wp_head()
*/
	
	/* Javascript - enqueues -----------------------------------------------------*/
	$site_dep = array('jquery', 'modernizr', 'lib', 'wp-ajax-response');
	wp_enqueue_script('site', get_template_directory_uri() . '/assets/js/site.js', $site_dep, '1.0.0', true);
	wp_enqueue_script( 'comment-reply' ); // required for threaded comments
	
	// localize_script allows for javascript to access variables normally accessed with php
	/*
wp_localize_script( 'site', 'myAjax',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'cform_msg' => array(
				'errors' => __('There are multiple errors on the form blah.', '<%= _.slugify(textDomain) %>'),
				'js_error' => __('Sorry, but an error occurred, please try again.', '<%= _.slugify(textDomain) %>'),
				'btn_error' => __('Error', '<%= _.slugify(textDomain) %>'),
				'btn_succ' => __('Sent', '<%= _.slugify(textDomain) %>'),
				'btn_loading' => __('Sending', '<%= _.slugify(textDomain) %>')
			)
		)
	);
*/

}

add_action('wp_enqueue_scripts', '<%= _.slugify(fnPrefix) %>_enqueues');


/*--------------------------------------------------------------------------------*/
/*	5.  Comment Styling
/*--------------------------------------------------------------------------------*/
function <%= _.slugify(fnPrefix) %>_comment($comment, $args, $depth) {
	
	$isbyAuthor = false;
    if($comment->comment_author_email == get_the_author_meta('email')) $isbyAuthor = true;

    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

        <div id="comment-<?php comment_ID(); ?>">
            <?php echo get_avatar($comment,$size='42'); ?>
            <div class="comment-author vcard">
                <?php printf('%s', get_comment_author_link()); ?> <?php if($isbyAuthor) { ?><span class="author-tag"><?php _e('(Author)', '<%= _.slugify(textDomain) %>'); ?></span><?php } ?>
            </div>
            <div class="comment-meta"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s', '<%= _.slugify(textDomain) %>'), get_comment_date(),  get_comment_time()); ?></a><?php edit_comment_link(__('(Edit)', '<%= _.slugify(textDomain) %>'),'  ',''); ?> &middot; <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="moderation"><?php _e('Your comment is awaiting moderation.', '<%= _.slugify(textDomain) %>'); ?></em>
                <br />
            <?php endif; ?>
            <div class="comment-body">
            <?php comment_text(); ?>
            </div>
        </div>
<?php
}


/*--------------------------------------------------------------------------------*/
/*	6.  Get Attchments Function
/*
/*  Note: $feat_id is used for example:
/*        If you'd like to retrive all images attached to post except the featured
/*--------------------------------------------------------------------------------*/
/*
function <%= _.slugify(fnPrefix) %>_get_attach($post_id, $feat_id) {
	$args = array(
		'post_type' => 'attachment',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => $post_id,
		'numberposts' => -1,
		'exclude' => $feat_id
    );
	$attachments = get_posts($args);
	return $attachments;
}
*/


/*--------------------------------------------------------------------------------*/
/*	n.  Theme Extras - includes for custom post types, widgets, etc.
/*--------------------------------------------------------------------------------*/
$incdir = get_template_directory() . '/assets/includes/';

/*
// Custom Post Types
require_once($incdir . 'custom-posttypes.php');
*/

/*
// Custom Widgets

// Exmaple widgets, require will hard fail (used for widgets that create important pages like home),
// include will soft fail (widgets that aren't absolutely important)
require_once($incdir . 'widgets/widget-home_featured_project.php');
include_once($incdir . 'widgets/widget-project_categories.php');
*/

/*
// Ajax requirements
require_once($incdir . 'ajax-frontend.php');
*/

/*
// Shortcodes
include_once($incdir . 'shortcodes.php');
add_filter('widget_text', 'do_shortcode');
*/

/*
// Theme Customizer
require_once($incdir . 'theme-customizer.php');
*/

?>