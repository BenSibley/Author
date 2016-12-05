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
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php _e( 'Get Started', 'author' ); ?></h3>
				<p><?php _e( "Not sure where to start? The <strong>Author Getting Started Guide</strong> will take you step-by-step through every feature in Author.", "author" ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/help/getting-started-author/"><?php _e( 'View Guide', 'author' ); ?></a>
				</p>
			</div>
			<?php if ( !function_exists( 'ct_author_pro_init' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php _e( 'Author Pro Plugin', 'author' ); ?></h3>
					<p><?php _e( 'Download the Author Pro plugin and unlock custom colors, featured videos, background images, and more', 'author' ); ?>...</p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/author-pro/"><?php _e( 'See Full Feature List', 'author' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php _e( 'Leave a Review', 'author' ); ?></h3>
				<p><?php _e( 'Help others find Author by leaving a review on wordpress.org.', 'author' ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/author/reviews/"><?php _e( 'Leave a Review', 'author' ); ?></a>
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
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }