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
        <h2><?php _e('author Dashboard', 'author'); ?></h2>
        <div class="content content-customization">
            <h3><?php _e('Customization', 'author'); ?></h3>
            <p><?php _e('Click the "Customize" link in your menu, or use the button below to get started customizing author', 'author'); ?>.</p>
            <p>
                <a class="button-primary" href="customize.php"><?php _e('Use Customizer', 'author') ?></a>
            </p>
        </div>
        <div class="content content-support">
	        <h3><?php _e('Support', 'author'); ?></h3>
            <p><?php _e("You can find the knowledgebase, changelog, support forum, and more in the author Support Center", "author"); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="http://www.competethemes.com/documentation/author-support-center/"><?php _e('Visit Support Center', 'author'); ?></a>
            </p>
        </div>
        <div class="content content-premium-upgrade">
            <h3><?php _e('Upgrade to author Plus ($29)', 'author'); ?></h3>
            <p><?php _e('author Plus is the premium version of author. By upgrading to author Plus, you get:', 'author'); ?></p>
            <ul>
                <li><?php _e('Custom colors', 'author'); ?></li>
                <li><?php _e('Background images & textures', 'author'); ?></li>
                <li><?php _e('New layouts', 'author'); ?></li>
                <li><?php _e('and much more&#8230;', 'author'); ?></li>
            </ul>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/author-plus/"><?php _e('See Full Feature List', 'author'); ?></a>
            </p>
        </div>
    </div>
<?php } ?>
