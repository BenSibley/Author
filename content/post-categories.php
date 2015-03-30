<?php

// get the post categories
$categories = get_the_category($post->ID);

// comma-separate posts
$separator = '';

// create output variable
$output = '';

// if there are categories for the post
if($categories){

	echo '<div class="post-categories">';
		echo '<span>' . __('Published in', 'author') . '</span>';
		foreach($categories as $category) {
			// output category name linked to the archive
			$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'author' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
		echo trim($output, $separator);
	echo "</div>";
}