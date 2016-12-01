<?php if ( is_active_sidebar( 'primary' ) ) : ?>
	<aside class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
		<h1 class="screen-reader-text"><?php _e( 'Sidebar', 'author' ); ?></h1>
		<?php dynamic_sidebar( 'primary' ); ?>
	</aside>
<?php endif;