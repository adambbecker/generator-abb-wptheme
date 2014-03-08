<?php

/*==================================================================================

	------- Custom Post Types & Associated Structures -------

	1.	Post Type Definitions & Initialization
	2.	Meta Boxes
	3.	Taxonomies
		
==================================================================================*/


/* The following is a example for a post type called "Portfolio" */


/*-----------------------------------------------------------------------------------*/
/*	1.	Post Type Definitions & Initialization
/*-----------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_custom_posttypes() {
	
	/* Portfolio Definitions */
	$labels_portfolio = array(
		'name' => _x('Portfolio', 'post type general name', '<%= _.slugify(textDomain) %>'),
		'singular_name' => _x('Portfolio Item', 'post type singular name', '<%= _.slugify(textDomain) %>'),
		'add_new' => _x('Add New', 'portfolio', '<%= _.slugify(textDomain) %>'),
		'add_new_item' => __('Add New Portfolio Item', '<%= _.slugify(textDomain) %>'),
		'edit_item' => __('Edit Portfolio Item', '<%= _.slugify(textDomain) %>'),
		'new_item' => __('New Portfolio Item', '<%= _.slugify(textDomain) %>'),
		'view_item' => __('View Portfolio Item', '<%= _.slugify(textDomain) %>'),
		'search_items' => __('Search Portfolio', '<%= _.slugify(textDomain) %>'),
		'not_found' =>  __('No Portfolio Items Found', '<%= _.slugify(textDomain) %>'),
		'not_found_in_trash' => __('No Portfolio Items Found in Trash', '<%= _.slugify(textDomain) %>'), 
		'parent_item_colon' => '',
		'menu_name' => __('Portfolio', '<%= _.slugify(textDomain) %>')
	);
	$args_portfolio = array(
		'labels' => $labels_portfolio,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail')
	);
	
	/* Post Type Registers */
	register_post_type('portfolio', $args_portfolio);
	
}

add_action('after_setup_theme', '<%= _.slugify(fnPrefix) %>_custom_posttypes');


/*-----------------------------------------------------------------------------------*/
/*	2.	Meta Boxes
/*-----------------------------------------------------------------------------------*/

$meta_boxes = array();
$prefix = '<%= _.slugify(fnPrefix) %>_';

// Portfolio Item
$meta_boxes[] = array(
    'id' => 'portoflio-item-meta-box',
    'title' => __('Portfolio Item Options', '<%= _.slugify(textDomain) %>'),
    'pages' => array('portfolio'),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    	array(
            'name' => __('Client', '<%= _.slugify(textDomain) %>'),
            'desc' => __('What client was this work done for?', '<%= _.slugify(textDomain) %>'),
            'id' => $prefix . 'portfolio_client',
            'type' => 'text',
            'std' => ''
        ),
    	array(
            'name' => __('Exclude Featured Image', '<%= _.slugify(textDomain) %>'),
            'desc' => __('exclude featured image in displayed media (once user has clicked on item)', '<%= _.slugify(textDomain) %>'),
            'id' => $prefix . 'portfolio_item_thumb',
            'type' => 'checkbox'
        ),
        array(
            'name' => __('Video Embed', '<%= _.slugify(textDomain) %>'),
            'desc' => __('embed video code', '<%= _.slugify(textDomain) %>'),
            'id' => $prefix . 'portfolio_item_embed',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Page Sidebar', '<%= _.slugify(textDomain) %>'),
            'desc' => __('Select which custom sidebar to be displayed next to post (default = none).', '<%= _.slugify(textDomain) %>'),
            'id' => $prefix . 'portfolio_sidebar_type',
            'type' => 'select',
            'options' => array( 'none', 'blog', 'page' )
        )
    )
);

// Page Heading
$meta_boxes[] = array(
    'id' => 'page-meta-box',
    'title' => __('Page Options', '<%= _.slugify(textDomain) %>'),
    'pages' => array('page'),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    	array(
            'name' => __('Introduction Heading', '<%= _.slugify(textDomain) %>'),
            'desc' => __('Text to display as top heading of page (if blank, the page title will be used).', '<%= _.slugify(textDomain) %>'),
            'id' => $prefix . 'page_heading',
            'type' => 'text',
            'std' => ''
        )
    )
);

// General class to create and show meta boxes
class <%= _.slugify(fnPrefix) %>_meta_box {

    protected $_meta_box;

    // Create meta box based on given data
    function __construct($meta_box) {
        $this->_meta_box = $meta_box;
        add_action('admin_menu', array(&$this, 'add'));
        add_action('save_post', array(&$this, 'save'));
    }

    /// Add meta box for multiple post types
    function add() {
        foreach ($this->_meta_box['pages'] as $page) {
            add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
        }
    }

    // Callback function to show fields in meta box
    function show() {
        global $post;

        // Use nonce for verification
        echo '<input type="hidden" name="<%= _.slugify(fnPrefix) %>_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
        echo '<table class="form-table">';

        foreach ($this->_meta_box['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
        
            echo '<tr>',
                    '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                    '<td>';
            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? esc_attr($meta) : esc_attr($field['std']), '" size="30" style="width:97%" />',
                        '<br />', $field['desc'];
                    break;
                case 'textarea':
                    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="6" style="width:97%">', $meta ? esc_html($meta) : esc_html($field['std']), '</textarea>',
                        '<br />', $field['desc'];
                    break;
                case 'select':
                    echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>',
                    	'<br />', $field['desc'];
                    break;
                case 'radio':
                    foreach ($field['options'] as $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                    }
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />',
                    	'&nbsp;&nbsp;' . $field['desc'];
                    break;
            }
            echo     '<td>',
                '</tr>';
        }
    
        echo '</table>';
    }

    // Save data from meta box
    function save($post_id) {
        // verify nonce
        if (!wp_verify_nonce($_POST['<%= _.slugify(fnPrefix) %>_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        foreach ($this->_meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
    
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
}

foreach ($meta_boxes as $meta_box) {
    $<%= _.slugify(fnPrefix) %>_box = new <%= _.slugify(fnPrefix) %>_meta_box($meta_box);
}


/*-----------------------------------------------------------------------------------*/
/*	3.	Taxonomies
/*-----------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_custom_taxonomies() {
	
	/* Portfolio Categories */
	$labels_portcat_tax = array(
		'name' => _x( 'Portfolio Categories', 'taxonomy general name', '<%= _.slugify(textDomain) %>' ),
		'singular_name' => _x( 'Portfolio Category', 'taxonomy singular name', '<%= _.slugify(textDomain) %>' ),
		'search_items' =>  __( 'Search Portfolio Categories', '<%= _.slugify(textDomain) %>' ),
		'popular_items' => __( 'Popular Portfolio Categories', '<%= _.slugify(textDomain) %>' ),
		'all_items' => __( 'All Portfolio Categories', '<%= _.slugify(textDomain) %>' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Portfolio Category', '<%= _.slugify(textDomain) %>' ), 
		'update_item' => __( 'Update Portfolio Category', '<%= _.slugify(textDomain) %>' ),
		'add_new_item' => __( 'Add New Portfolio Category', '<%= _.slugify(textDomain) %>' ),
		'new_item_name' => __( 'New Portfolio Category Name', '<%= _.slugify(textDomain) %>' ),
		'menu_name' => __( 'Categories', '<%= _.slugify(textDomain) %>' )
	); 
	register_taxonomy('portfolio_category','portfolio',array(
		'hierarchical' => true,
		'labels' => $labels_portcat_tax,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'portfolio_category' )
	));

}

add_action('init', '<%= _.slugify(fnPrefix) %>_custom_taxonomies');
