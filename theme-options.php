<?php

function ct_author_register_theme_page() {
	add_theme_page( __( 'Author Dashboard', 'author' ), __( 'Author Dashboard', 'author' ), 'edit_theme_options', 'author-options', 'ct_author_options_content', 'ct_author_options_content' );
}
add_action( 'admin_menu', 'ct_author_register_theme_page' );

function ct_author_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => site_url(),
			'return' => add_query_arg( 'page', 'author-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	$support_url = 'https://www.competethemes.com/documentation/author-support-center/';
	?>
	<div id="author-dashboard-wrap" class="wrap">
		<h2><?php _e( 'Author Dashboard', 'author' ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="welcome">
			<h3><?php _e( 'Thanks for Choosing Author!', 'author' ); ?></h3>
			<p>
				<?php printf( __( 'If you need help getting started, there are detailed tutorials in the <a href="%s">Author Support Center</a>.', 'author' ), $support_url ); ?>
				<?php printf( __( 'Otherwise, you can dive right in with the <a href="%s">Customizer</a>.', 'author' ), esc_url($customizer_url) ); ?>
			</p>
		</div>
		<div class="content content-customization">
			<h3><?php _e( 'Customize', 'author' ); ?></h3>
			<p><?php _e( 'Click the "Customize" link in your menu, or use the button below to get started customizing Author', 'author' ); ?>.</p>
			<p>
				<a class="button-primary"
				   href="<?php echo esc_url( $customizer_url ); ?>"><?php _e( 'Use Customizer', 'author' ) ?></a>
			</p>
		</div>
		<div class="content content-support">
			<h3><?php _e( 'Get Help', 'author' ); ?></h3>
			<p><?php _e( "You can find the knowledgebase, changelog, support forum, and more in the Author Support Center", "author" ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/documentation/author-support-center/"><?php _e( 'Visit Support Center', 'author' ); ?></a>
			</p>
		</div>
		<div class="content content-premium-upgrade">
			<h3><?php _e( 'Author Pro Plugin', 'author' ); ?></h3>
			<p><?php _e( 'Download the Author Pro plugin and unlock custom colors, featured videos, background images, and more', 'author' ); ?>...</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/author-pro/"><?php _e( 'See Full Feature List', 'author' ); ?></a>
			</p>
		</div>
		<div class="content content-resources">
			<h3><?php _e( 'WordPress Resources', 'author' ); ?></h3>
			<p><?php _e( 'Save time and money searching for WordPress products by following our recommendations', 'author' ); ?>.</p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/wordpress-resources/"><?php _e( 'View Resources', 'author' ); ?></a>
			</p>
		</div>
		<div class="content content-review">
			<h3><?php _e( 'Leave a Review', 'author' ); ?></h3>
			<p><?php _e( 'Help others find Author by leaving a review on wordpress.org.', 'author' ); ?></p>
			<a target="_blank" class="button-primary" href="https://wordpress.org/support/view/theme-reviews/author"><?php _e( 'Leave a Review', 'author' ); ?></a>
		</div>
		<div class="content content-delete-settings">
			<h3><?php _e( 'Reset Customizer Settings', 'author' ); ?></h3>
			<p>
				<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the Author theme's current settings in the <a href='%s'>Customizer</a>.", 'author' ), esc_url( $customizer_url ) ); ?>
			</p>
			<form method="post">
				<input type="hidden" name="author_reset_customizer" value="author_reset_customizer_settings"/>
				<p>
					<?php wp_nonce_field( 'author_reset_customizer_nonce', 'author_reset_customizer_nonce' ); ?>
					<?php submit_button( __( 'Reset Customizer Settings', 'author' ), 'delete', 'delete', false ); ?>
				</p>
			</form>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }