<?php

$categories = get_the_category( $post->ID );
$separator  = '';
$output     = '';

if ( $categories ) {
	echo '<div class="post-categories">';
		echo '<span>' . __( 'Published in', 'author' ) . '</span>';
		foreach ( $categories as $category ) {
			// output category name linked to the archive
			$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'author' ), $category->name ) ) . '">' . esc_html( $category->cat_name ) . '</a>' . $separator;
		}
		echo trim( $output, $separator );
	echo "</div>";
}