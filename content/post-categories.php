<?php

// get the post categories
$categories = get_the_category($post->ID);

// comma-separate posts
$separator = ', ';

// create output variable
$output = '';

// if there are categories for the post
if($categories){

	echo '<p class="post-categories">';
		foreach($categories as $category) {
			// if it's the last and not the first (only) category, pre-prend with "and"
			if( $category === end($categories) && $category !== reset($categories) ) {
				$output .= 'and ';
			}
			// output category name linked to the archive
			$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'unlimited' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
		echo trim($output, $separator);
	echo "</p>";
}