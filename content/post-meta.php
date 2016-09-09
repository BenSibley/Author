<span class="post-meta">
	<?php
	$author = "<a href='" . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . "'>" . esc_html( get_the_author() ) . "</a>";
	$date   = "<a href='" . esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) ) ) . "'>" . date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) ) . "</a>";
	?>
	<?php printf( _x( 'Published by %1$s on %2$s', 'This blog post was published by some author on some date', 'author' ), $author, $date ); ?>
</span>