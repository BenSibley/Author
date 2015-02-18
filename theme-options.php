<?php

/* create theme options page */
function ct_author_register_theme_page(){
add_theme_page( 'Author Dashboard', 'Author Dashboard', 'edit_theme_options', 'author-options', 'ct_author_options_content', 'ct_author_options_content');
}
add_action( 'admin_menu', 'ct_author_register_theme_page' );

/* callback used to add content to options page */
function ct_author_options_content(){
    ?>
    <div id="author-dashboard-wrap" class="wrap">
        <h2><?php _e('Author Dashboard', 'author'); ?></h2>
        <?php hybrid_do_atomic( 'theme_options_before' ); ?>
        <div class="content content-customization">
            <h3><?php _e('Customization', 'author'); ?></h3>
            <p><?php _e('Click the "Customize" link in your menu, or use the button below to get started customizing Author', 'author'); ?>.</p>
            <p>
                <a class="button-primary" href="<?php echo admin_url('customize.php'); ?>"><?php _e('Use Customizer', 'author') ?></a>
            </p>
        </div>
        <div class="content content-support">
	        <h3><?php _e('Support', 'author'); ?></h3>
            <p><?php _e("You can find the knowledgebase, changelog, support forum, and more in the Author Support Center", "author"); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/documentation/author-support-center/"><?php _e('Visit Support Center', 'author'); ?></a>
            </p>
        </div>
        <div class="content content-premium-upgrade">
            <h3><?php _e('Upgrade to Author Pro', 'author'); ?></h3>
            <p><?php _e('Author Pro is the premium upgrade for Author. It has custom colors, new layouts, background images, and more', 'author'); ?>...</p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/author-pro/"><?php _e('See Full Feature List', 'author'); ?></a>
            </p>
        </div>
        <div class="content content-resources">
            <h3><?php _e('WordPress Resources', 'author'); ?></h3>
            <p><?php _e('Save time and money searching for WordPress products by following our recommendations', 'author'); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/wordpress-resources/"><?php _e('View Resources', 'author'); ?></a>
            </p>
        </div>
        <div class="content content-delete-settings">
            <h3><?php _e('Reset Customizer Settings', 'author'); ?></h3>
            <p>
                <?php
                $url = admin_url('customize.php');
                $text = sprintf( __( '<strong>Warning:</strong> Clicking this button will erase your current settings in the <a href="%s">Customizer</a>', 'author' ), esc_url( $url ) );
                echo $text . ".";
                ?>
            </p>
            <form method="post">
                <input type="hidden" name="author_reset_customizer" value="author_reset_customizer_settings" />
                <p>
                    <?php wp_nonce_field( 'author_reset_customizer_nonce', 'author_reset_customizer_nonce' ); ?>
                    <?php submit_button( __( 'Reset Customizer Settings', 'author' ), 'delete', 'delete', false ); ?>
                </p>
            </form>
        </div>
        <?php hybrid_do_atomic( 'theme_options_after' ); ?>
    </div>
<?php } ?>
