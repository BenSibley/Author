<!DOCTYPE html>

<!--[if IE 9 ]><html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>

	<title><?php wp_title( ' | ' ); ?></title>
    <?php wp_head(); ?>

</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>

<!--skip to content link-->
<a class="skip-content" href="#main"><?php _e('Skip to content', 'unlimited'); ?></a>

<header class="site-header" id="site-header" role="banner">

	<?php get_template_part('content/social-icons'); ?>
	<?php get_template_part('content/search-bar'); ?>

	<div id="title-container" class="title-container">
		<?php get_template_part('logo')  ?>
		<p class="site-description"><?php bloginfo('description'); ?></p>
	</div>
	
	<?php get_template_part( 'menu', 'primary' ); ?>

	<button id="toggle-navigation" class="toggle-navigation">
		<i class="fa fa-bars"></i>
	</button>

</header>
<section id="main" class="main" role="main">