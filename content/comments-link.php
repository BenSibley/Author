<?php
if ( get_theme_mod( 'comments_link' ) != 'yes' ) {
	return;
}
?>
<span class="comments-link">
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		?><i class="fas fa-comment" title="<?php esc_attr_e( 'comment icon', 'author' ); ?>" aria-hidden="true"></i><?php
		comments_number( esc_html__( 'Comments closed', 'author' ), esc_html__( '1 Comment', 'author' ), esc_html_x( '% Comments', 'noun: 5 comments', 'author' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		?><i class="fas fa-comment" title="<?php esc_attr_e( 'comment icon', 'author' ); ?>" aria-hidden="true"></i><?php
		comments_number( esc_html__( 'Leave a Comment', 'author' ), esc_html__( '1 Comment', 'author' ), esc_html_x( '% Comments', 'noun: 5 comments', 'author' ) );
		echo '</a>';
	endif;
	?>
</span>