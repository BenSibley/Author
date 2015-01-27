<?php get_header(); ?>

    <div class="post-header">
        <h1 class="post-title">
            <?php
            global $wp_query;
            $total_results = $wp_query->found_posts;
            if($total_results) {
                printf(__('%d search results for','unlimited'), $total_results);
            } else {
                _e("No search results for ", 'unlimited');
            }
            ?>
            <span>"<?php echo $s ?>"</span>
        </h1>
        <?php get_search_form(); ?>
    </div>

    <?php
    // The loop
    if ( have_posts() ) :
        while (have_posts() ) :
            the_post();
            get_template_part( 'content', 'archive' );
        endwhile;
    endif;
    ?>

    <?php if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination(); ?>

    <?php
    // only display bottom search bar if there are search results
    $total_results = $wp_query->found_posts;
    if($total_results) {
        ?>
        <div class="search-bottom">
            <p><?php _e("Can't find what you're looking for?  Try refining your search:", "unlimited"); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php } ?>
<?php get_footer(); ?>