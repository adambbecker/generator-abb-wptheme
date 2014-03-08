<?php

/*================================================================================

	------- Sample Custom Example Widget -------

	1.	Registration & Initialization
	2.	Settings & Creation
	3.	Output
	4.	Update
	5.	Admin
		
==================================================================================*/


/* The following is a example for a custom widget that allows for varying column span
 * and optionally clears floats after it's output
 */


/*--------------------------------------------------------------------------------*/
/*	1.	Registration & Initialization
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_custom_example_widget() {
	register_widget( '<%= _.slugify(fnPrefix) %>_custom_example' );
}

add_action( 'widgets_init', '<%= _.slugify(fnPrefix) %>_custom_example_widget' );

class <%= _.slugify(fnPrefix) %>_custom_example extends WP_Widget {


/*--------------------------------------------------------------------------------*/
/*	2.	Settings & Creation
/*--------------------------------------------------------------------------------*/

function <%= _.slugify(fnPrefix) %>_custom_example() {
	/* Widget settings. */
	$widget_ops = array( 'description' => __('Sample custom widget for demonstration purposes.', '<%= _.slugify(textDomain) %>') );

	/* Widget control settings. */
	$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => '<%= _.slugify(fnPrefix) %>_custom_example' );

	/* Create the widget. */
	$this->WP_Widget( '<%= _.slugify(fnPrefix) %>_custom_example', __('sb - Custom Example', '<%= _.slugify(textDomain) %>'), $widget_ops, $control_ops );
}


/*--------------------------------------------------------------------------------*/
/*	3.	Output
/*--------------------------------------------------------------------------------*/

function widget( $args, $instance ) {
	extract( $args );

	/* Variables. */
	$title = apply_filters('widget_title', $instance['title'] );
	$col_span = $instance['col_span'];
	$text = $instance['text'];

	echo $before_widget;
	echo $before_title . $title . $after_title;
	?>
	
	<div class="col<?php echo $col_span; ?>">
		<p><?php echo $text; ?></p>
	</div>
	
	<?php
	echo $after_widget;
	if( $instance['clear_floats'] ) echo '<div class="clear"></div>';
}


/*--------------------------------------------------------------------------------*/
/*	4.	Update
/*--------------------------------------------------------------------------------*/

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['col_span'] = $new_instance['col_span'];
	$instance['text'] = $new_instance['text'];
	$instance['clear_floats'] = !empty($new_instance['clear_floats']) ? true : false;

	return $instance;
}


/*--------------------------------------------------------------------------------*/
/*	5.	Admin
/*--------------------------------------------------------------------------------*/

function form( $instance ) {

	/* Set up some default widget settings. */
	$defaults = array(
		'title' => '',
		'text' => '',
		'col_span' => 1,
		'clear_floats' => false
	);
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<!-- Widget Title: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', '<%= _.slugify(textDomain) %>'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>
	
	<!-- Column Span: Select Box -->
	<p>
		<label for="<?php echo $this->get_field_id( 'col_span' ); ?>"><?php _e('Column Span:', '<%= _.slugify(textDomain) %>'); ?></label> 
		<select id="<?php echo $this->get_field_id( 'col_span' ); ?>" name="<?php echo $this->get_field_name( 'col_span' ); ?>" class="widefat">
			<option value="1" <?php if ( 1 == $instance['col_span'] ) echo 'selected'; ?>>1 (quarter page width)</option>
			<option value="2" <?php if ( 2 == $instance['col_span'] ) echo 'selected'; ?>>2 (half page width)</option>
			<option value="3" <?php if ( 3 == $instance['col_span'] ) echo 'selected'; ?>>3 (three-quarter page width)</option>
			<option value="4" <?php if ( 4 == $instance['col_span'] ) echo 'selected'; ?>>4 (full page width)</option>
		</select>
	</p>
	
	<!-- Text: Textarea -->
	<textarea class="widefat" rows="12" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" style="margin-bottom:12px;"><?php echo format_to_edit($instance['text']); ?></textarea>
	
	<!-- Clear Floats: Checkbox -->
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'clear_floats' ); ?>" name="<?php echo $this->get_field_name( 'clear_floats' ); ?>" <?php checked( $instance['clear_floats'] ); ?> />
		<label for="<?php echo $this->get_field_id( 'clear_floats' ); ?>"><?php _e('Clear Floats After Widget', '<%= _.slugify(textDomain) %>_'); ?></label>
	</p>

<?php
}

/* END WIDGET */
}