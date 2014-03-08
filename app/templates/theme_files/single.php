<?php
/**
 * Single Template
 *
 * Acts as the template for showing a full post
 *
 * @package WordPress
 * @subpackage <%= themeTitle %> theme
 * @since 1.0.0
 */

get_header(); ?>

<!-- GO AHEAD, SWEAT THE SMALL STUFF -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php endwhile; ?>
<?php else : ?>
	<!-- Show Empty Post (with link to create post if admin) -->
<?php endif; ?>

<?php get_footer(); ?>