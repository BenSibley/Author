<?php get_header(); ?>

<?php

// The loop
if ( have_posts() ) :
    while (have_posts() ) :
        the_post();

        /* Blog */
        if( is_home() ) {
            get_template_part( 'content', 'archive' );
        }
        /* Post */
        elseif( is_singular( 'post' ) ) {
            get_template_part( 'content' );
        }
        /* Page */
        elseif( is_page() ) {
            get_template_part( 'content', 'page' );
        }
        /* Attachment */
        elseif( is_attachment() ) {
            get_template_part( 'content', 'attachment' );
        }
        /* Archive */
        elseif( is_archive() ) {
            get_template_part( 'content', 'archive' );
        }
        /* Custom Post Type */
        else {
            get_template_part( 'content' );
        }
    endwhile;
endif; ?>

<?php ct_unlimited_post_navigation(); ?>

<?php get_footer(); ?>