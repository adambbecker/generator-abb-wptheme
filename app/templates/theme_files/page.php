<?php
/**
 * Page Template
 *
 * Acts as the template for general user created pages
 *
 * @package WordPress
 * @subpackage <%= themeTitle %> theme
 * @since 1.0.0
 */

get_header(); ?>

<!-- INSERT THE AWESOME -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); /* $ptitle = get_post_meta(get_the_id(), '<%= _.slugify(fnPrefix) %>_page_heading', true); */ ?>
  <!-- <h1><?php /* echo $ptitle != '' ? $ptitle : get_the_title( get_the_id() ); */ ?></h1> -->
<?php endwhile; endif; ?>

<?php get_footer(); ?>