<?php
$tags = get_the_tags( $post->ID );
$output = '';
if($tags){
	echo '<div class="post-tags"><ul>';
	foreach($tags as $tag) {
		$output .= '<li><a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'author' ), $tag->name ) ) . '">'.$tag->name.'</a></li>';
	}
	echo $output;
	echo '</ul></div>';
} 
?>