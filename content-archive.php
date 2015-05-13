<div <?php post_class(); ?>>
	<?php hybrid_do_atomic( 'archive_post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'>
				<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
			</h1>
			<span class="post-meta">
				<?php
				$author = "<a href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "'>" . get_the_author() . "</a>";
				$date = "<a href='" . get_month_link( get_the_date('Y'), get_the_date('n') ) . "'>" . date_i18n( get_option( 'date_format' ), strtotime( get_the_date('r') ) ) . "</a>";
				?>
				<?php printf( _x('Published by %1$s on %2$s', 'This blog post was published by some author on some date', 'author'), $author, $date ); ?>
			</span>
		</div>
		<div class="post-content">
			<?php ct_author_excerpt(); ?>
		</div>
	</article>
	<?php hybrid_do_atomic( 'archive_post_after' ); ?>
</div>