<div class='search-form-container'>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
        <label><?php _e('Search', 'unlimited'); ?></label>
        <input type="search" class="search-field" placeholder="<?php _e('Search...', 'unlimited'); ?>" value="" name="s" title="<?php _e('Search for:', 'unlimited'); ?>" />
        <input type="submit" class="search-submit" value='<?php _e('Go', 'unlimited'); ?>' />
    </form>
</div>