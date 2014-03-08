<!DOCTYPE html>
<!--[if lte IE 9]>      <html <?php language_attributes(); ?> class="no-js ie lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<?php if (is_search()) : ?>
	<meta name="robots" content="noindex, nofollow" /> 
	<?php endif; ?>
	
	<title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo( 'description' ); ?>">
	
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>