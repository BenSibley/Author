<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
    <?php wp_head(); ?>
</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
	<?php do_action( 'body_top' ); ?>
	<!--skip to content link-->
	<a class="skip-content" href="#main"><?php _e('Skip to content', 'author'); ?></a>
	<div id="overflow-container" class="overflow-container">
		<div class="max-width">
			<div id="main-sidebar" class="main-sidebar">
				<?php do_action( 'before_main_sidebar' ); ?>
				<header class="site-header" id="site-header" role="banner">
					<div id="title-container" class="title-container">
						<?php
						$avatar_method = get_theme_mod('avatar_method');
						if( $avatar_method == 'gravatar' || $avatar_method == 'upload' ) { ?>
							<div id="site-avatar" class="site-avatar" style="background-image: url('<?php echo ct_author_output_avatar(); ?>')" title="<?php echo get_bloginfo('title') . ' avatar'; ?>"></div>
						<?php } ?>
						<div class="container">
							<?php get_template_part('logo')  ?>
							<?php
							if( get_bloginfo('description') ) {
								echo '<p class="tagline">' . get_bloginfo("description") . '</p>';
							} ?>
						</div>
					</div>
					<button id="toggle-navigation" class="toggle-navigation" aria-expanded="false">
						<span class="screen-reader-text"><?php _e('open primary menu', 'author'); ?></span>
						<i class="fa fa-bars"></i>
					</button>
					<?php ct_author_social_icons_output('header'); ?>
					<?php get_template_part( 'menu', 'primary' ); ?>
				</header>
				<?php do_action( 'after_header' ); ?>
				<?php get_sidebar( 'primary' ); ?>
				<?php do_action( 'after_sidebar' ); ?>
			</div>
			<?php do_action( 'before_main' ); ?>
			<section id="main" class="main" role="main">
				<?php do_action( 'main_top' ); ?>