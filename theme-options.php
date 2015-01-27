<?php

/* create theme options page */
function ct_unlimited_register_theme_page(){
add_theme_page( 'unlimited Dashboard', 'Unlimited Dashboard', 'edit_theme_options', 'unlimited-options', 'ct_unlimited_options_content', 'ct_unlimited_options_content');
}
add_action( 'admin_menu', 'ct_unlimited_register_theme_page' );

/* callback used to add content to options page */
function ct_unlimited_options_content(){
    ?>
    <div id="unlimited-dashboard-wrap" class="wrap">
        <h2><?php _e('unlimited Dashboard', 'unlimited'); ?></h2>
        <div class="content content-customization">
            <h3><?php _e('Customization', 'unlimited'); ?></h3>
            <p><?php _e('Click the "Customize" link in your menu, or use the button below to get started customizing unlimited', 'unlimited'); ?>.</p>
            <p>
                <a class="button-primary" href="customize.php"><?php _e('Use Customizer', 'unlimited') ?></a>
            </p>
        </div>
        <div class="content content-support">
	        <h3><?php _e('Support', 'unlimited'); ?></h3>
            <p><?php _e("You can find the knowledgebase, changelog, support forum, and more in the unlimited Support Center", "unlimited"); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="http://www.competethemes.com/documentation/unlimited-support-center/"><?php _e('Visit Support Center', 'unlimited'); ?></a>
            </p>
        </div>
        <div class="content content-premium-upgrade">
            <h3><?php _e('Upgrade to unlimited Plus ($29)', 'unlimited'); ?></h3>
            <p><?php _e('unlimited Plus is the premium version of unlimited. By upgrading to unlimited Plus, you get:', 'unlimited'); ?></p>
            <ul>
                <li><?php _e('Custom colors', 'unlimited'); ?></li>
                <li><?php _e('Background images & textures', 'unlimited'); ?></li>
                <li><?php _e('New layouts', 'unlimited'); ?></li>
                <li><?php _e('and much more&#8230;', 'unlimited'); ?></li>
            </ul>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/unlimited-plus/"><?php _e('See Full Feature List', 'unlimited'); ?></a>
            </p>
        </div>
    </div>
<?php } ?>
