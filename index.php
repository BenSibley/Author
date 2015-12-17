<?php get_header();

get_template_part('content/archive-header');

?>

<div id="loop-container" class="loop-container">

    <?php
    // The loop
    if ( have_posts() ) :
        while (have_posts() ) :
            the_post();
            ct_author_get_content_template();
        endwhile;
    endif; ?>

</div>

<?php echo ct_author_loop_pagination(); ?>

<?php get_footer(); ?>