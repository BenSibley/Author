<?php get_header(); ?>

    <div class="archive-header">
        <div class="post-header">
            <h1 class="post-title">
                <?php
                global $wp_query;
                $total_results = $wp_query->found_posts;
                $s = htmlentities($s);
                if($total_results) {
                    printf( _n('%d search result for "%s"', '%d search results for "%s"', $total_results, 'author'), $total_results, $s );
                } else {
                    printf( __('No search results for "%s"', 'author'), $s );
                }
                ?>
            </h1>
            <?php get_search_form(); ?>
        </div>
    </div>

<div id="loop-container" class="loop-container">
    <?php
    // The loop
    if ( have_posts() ) :
        while (have_posts() ) :
            the_post();
            get_template_part( 'content', 'archive' );
        endwhile;
    endif;
    ?>
</div>

<?php echo ct_author_loop_pagination(); ?>

    <?php
    // only display bottom search bar if there are search results
    $total_results = $wp_query->found_posts;
    if($total_results) {
        ?>
        <div class="search-bottom archive-header">
            <p><?php _e("Can't find what you're looking for?  Try refining your search:", "author"); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php } ?>
<?php get_footer(); ?>