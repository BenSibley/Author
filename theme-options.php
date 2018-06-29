<?php

function ct_author_register_theme_page() {
	add_theme_page( sprintf( esc_html__( '%s Dashboard', 'author' ), wp_get_theme( get_template() ) ), sprintf( esc_html__( '%s Dashboard', 'author' ), wp_get_theme( get_template() ) ), 'edit_theme_options', 'author-options', 'ct_author_options_content', 'ct_author_options_content' );
}
add_action( 'admin_menu', 'ct_author_register_theme_page' );

function ct_author_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => get_home_url(),
			'return' => add_query_arg( 'page', 'author-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	$support_url = 'https://www.competethemes.com/documentation/author-support-center/';
	?>
	<div id="author-dashboard-wrap" class="wrap">
		<h2><?php printf( esc_html__( '%s Dashboard', 'author' ), wp_get_theme( get_template() ) ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php esc_html_e( 'Get Started', 'author' ); ?></h3>
				<p><?php printf( __( 'Not sure where to start? The <strong>%1$s Getting Started Guide</strong> will take you step-by-step through every feature in %1$s.', 'author' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/help/getting-started-author/"><?php esc_html_e( 'View Guide', 'author' ); ?></a>
				</p>
			</div>
			<?php if ( !function_exists( 'ct_author_pro_init' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php printf( esc_html__( '%s Pro Plugin', 'author' ), wp_get_theme( get_template() ) ); ?></h3>
					<p><?php printf( esc_html__( 'Download the %s Pro plugin and unlock custom colors, featured videos, background images, and more', 'author' ), wp_get_theme( get_template() ) ); ?>...</p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/author-pro/"><?php _e( 'See Full Feature List', 'author' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php esc_html_e( 'Leave a Review', 'author' ); ?></h3>
				<p><?php printf( esc_html__( 'Help others find %s by leaving a review on wordpress.org.', 'author' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/author/reviews/"><?php _e( 'Leave a Review', 'author' ); ?></a>
			</div>
			<div class="content content-presspad">
				<h3><?php esc_html_e( 'Turn Author into a Mobile App', 'author' ); ?></h3>
				<p><?php printf( esc_html__( '%s can be converted into a mobile app and listed on the App Store and Google Play Store with the help of PressPad News. Read our tutorial to learn more.', 'author' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://www.competethemes.com/help/convert-mobile-app-author/"><?php esc_html_e( 'Read Tutorial', 'author' ); ?></a>
			</div>
			<div class="content content-delete-settings">
				<h3><?php esc_html_e( 'Reset Customizer Settings', 'author' ); ?></h3>
				<p>
					<?php printf( __( '<strong>Warning:</strong> Clicking this button will erase the %2$s theme\'s current settings in the <a href="%1$s">Customizer</a>.', 'author' ), esc_url( $customizer_url ), wp_get_theme( get_template() ) ); ?>
				</p>
				<form method="post">
					<input type="hidden" name="author_reset_customizer" value="author_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'author_reset_customizer_nonce', 'author_reset_customizer_nonce' ); ?>
						<?php submit_button( esc_html__( 'Reset Customizer Settings', 'author' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }