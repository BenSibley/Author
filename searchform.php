<div class='search-form-container'>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
        <label class="screen-reader-text" for="search-field"><?php _e('Search', 'author'); ?></label>
        <input id="search-field" type="search" class="search-field" value="" name="s" title="<?php _e('Search for:', 'author'); ?>" />
        <input type="submit" class="search-submit" value='<?php _e('Go', 'author'); ?>' />
    </form>
</div>