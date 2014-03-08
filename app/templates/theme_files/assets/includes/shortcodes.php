<?php

/*================================================================================

	------- Theme Shortcodes -------

	1.  Gallery
	2.	Columns
	3.	Buttons
	4.	Alerts
	5.	Toggle
	6.	Tabs
		
==================================================================================*/

/* The following are some examples of different shortcodes */

/*--------------------------------------------------------------------------------*/
/*	1.	Gallery - replaces default WordPress gallery shortcode
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_gallery($output, $attr) {
	global $post;

	static $instance = 0;
	$instance++;
	
	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'p',
		'columns'    => 1,
		'size'       => '4col',
		'include'    => '',
		'exclude'    => '',
		'link'		 => 'file'
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				width: {$itemwidth}%;
			}
		</style>";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
	
	// get size of thumbnail based on columns
	if ($columns == 1) {
		$thumb_size = '4col';
	} elseif ($columns == 2 || $columns == 3) {
		$thumb_size = '2col';
	} elseif ($columns >= 4) {
		$thumb_size = '1col';
	}

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		
		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "<a href='" . wp_get_attachment_url($id) . "' class='view img' rel='" . $instance . "'>" . wp_get_attachment_image($id, $thumb_size) . "</a>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<div class="clear"></div>';
			
	}

	$output .= "
			<div class='clear'></div>
		</div>\n";

	return $output;
	
}

add_filter('post_gallery', '<%= _.slugify(fnPrefix) %>_gallery', 10, 2);


/*--------------------------------------------------------------------------------*/
/*	2.	Columns
/*--------------------------------------------------------------------------------*/

// Quarter page
function <%= _.slugify(fnPrefix) %>_quarter( $atts, $content = null ) {
   return '<div class="col_quarter">' . do_shortcode($content) . '</div>';
}

add_shortcode('quarter', '<%= _.slugify(fnPrefix) %>_quarter');


/*--------------------------------------------------------------------------------*/
/*	3.	Buttons
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_button( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		'url'     => '#',
		'color'   => ''
    ), $atts));
	
   return '<a class="button ' . $color . '" href="' . $url . '">' . do_shortcode($content) . '</a>';
}

add_shortcode('button', '<%= _.slugify(fnPrefix) %>_button');


/*--------------------------------------------------------------------------------*/
/*	4.	Alerts
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_alert( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		'color'   => ''
    ), $atts));
	
   return '<div class="alert ' . $color . '">' . do_shortcode($content) . '</div>';
}

add_shortcode('alert', '<%= _.slugify(fnPrefix) %>_alert');


/*--------------------------------------------------------------------------------*/
/*	5.	Toggle
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_toggle( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		'title'   => 'Sample Title',
		'status'  => 'closed'
    ), $atts));
	
   	$output = '<div class="accordion';
   	if ($status == 'closed') $output .= ' closed';
   	$output .= '">';
   	$output .= '<h5>' . $title . '</h5>';
   	$output .= '<div';
   	if ($status == 'closed') $output .= ' style="display:none;"';
   	$output .= '>';
   	$theContent = do_shortcode($content);
   	$output .= $theContent;
   	$output .= '</div></div>';
   
   	return $output;
}

add_shortcode('toggle', '<%= _.slugify(fnPrefix) %>_toggle');


/*--------------------------------------------------------------------------------*/
/*	6.	Tabs
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_tabs( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'tabs' => 'notabtitles'
    ), $atts));
    
    if( $tabs == 'notabtitles' ) { return; }
    
    $output = '';
    $output .= '<div class="tabs"><nav><ul class="title">';
    
    $theTabs = explode(',', $tabs);
    foreach($theTabs as $tab) {
        $nospacetab = strtolower(str_replace(' ', '_', trim($tab)));
        $output .= '<li><a href="#' . $nospacetab . '">' . $tab . '</a></li>';
    }
    
    $output .= '</ul></nav>';
    $theContent = do_shortcode($content);
    $output .= $theContent;
    $output .= '</div>';
    
    return $output;
}

add_shortcode('tabs', '<%= _.slugify(fnPrefix) %>_tabs');

function <%= _.slugify(fnPrefix) %>_tabs_panes( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'title' => 'notabletitle'
    ), $atts));
    
    if( $title == 'notabtitle' ) { return; }
    
    $nospacetab = trim(strtolower(str_replace(' ', '_', $title)));
    $output = '<div id="' . $nospacetab . '">' . do_shortcode($content) . '</div>';
    
    return $output;
}

add_shortcode('tab', '<%= _.slugify(fnPrefix) %>_tabs_panes');
