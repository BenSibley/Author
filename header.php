<!DOCTYPE html>

<!--[if IE 9 ]><html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	<title><?php wp_title( ' | ' ); ?></title>
    <?php wp_head(); ?>
</head>
<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
<a class="skip-content" href="#main"><?php _e('Skip to content', 'author'); ?></a><!--skip to content link-->

<section id="main-sidebar" class="main-sidebar">
	<header class="site-header" id="site-header" role="banner">
		<div id="title-container" class="title-container">
			<?php ct_author_output_avatar(); ?>
			<div class="container">
				<?php get_template_part('logo')  ?>
				<p class="tagline"><?php bloginfo('description'); ?></p>
			</div>
		</div>
		<button id="toggle-navigation" class="toggle-navigation">
			<i class="fa fa-bars"></i>
		</button>
		<?php ct_author_social_icons_output('header'); ?>
		<?php get_template_part( 'menu', 'primary' ); ?>
	</header>
	<?php get_sidebar( 'primary' ); ?>
</section>

<section id="main" class="main" role="main">